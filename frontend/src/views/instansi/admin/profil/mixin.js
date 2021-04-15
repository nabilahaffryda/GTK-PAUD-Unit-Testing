import { mapActions, mapState } from 'vuex';

export default {
  data() {
    return {
      formulir: {},
      detail: {},
      lengkap: {},
      berkas: [],
    };
  },
  computed: {
    ...mapState('master', ['masters']),

    isLengkap() {
      return this.lengkap['profil'] && this.lengkap['berkas'];
    },

    status() {
      return [
        {
          label: 'Profil Pengajar',
          value: this.lengkap['profil'] ? 'passed' : 'not_finish',
        },
        { label: 'Berkas Persyaratan Lainnya', value: this.lengkap['berkas'] ? 'passed' : 'not_finish' },
      ];
    },

    statusLabel() {
      return {
        passed: { icon: 'mdi-check-circle', color: 'success' },
        not_finish: { icon: 'mdi-alert-circle', color: 'warning' },
        failed: { icon: 'mdi-close-circle', color: 'red' },
        autosubmit: { icon: 'mdi-account-alert', color: 'grey' },
        tbs_default: { icon: 'mdi-alert-circle', color: 'warning' },
        revisi: { icon: 'mdi-check-circle', color: 'warning' },
        not_complete: { icon: 'mdi-alert-circle', color: 'grey' },
      };
    },

    contents() {
      return {
        profil: {
          component: 'Profil',
          form: 'FormProfil',
          title: 'Profil Pengajar',
          deskripsi: '',
          max: 10,
          optional: true,
        },
        berkas: {
          component: 'Berkas',
          form: 'FormUnggah',
          title: 'Berkas Persyaratan Lainnya',
          deskripsi: '',
          max: 10,
          optional: true,
        },
      };
    },

    berkases() {
      const mBerkas = this.$arrToObj(this.berkas, 'k_berkas_pengajar_paud');
      return {
        pengajar: [
          {
            title: 'Pakta Integritas',
            pesan: `* Silakan unduh template pakta integritas terlebih dahulu. <b><a href="#" target="_blank">UNDUH DISINI</a> </b>`,
            valid: !!mBerkas['1'],
            type: 'integritas',
            withAction: true,
            kBerkas: 1,
            value: mBerkas['1'] || {},
          },
          {
            title: 'Surat Keterangan Sudah Menjalankan Fungsi Sebagai Pengajar Diklat PAUD',
            pesan: ``,
            valid: !!mBerkas['4'],
            type: 'fungsi',
            withAction: true,
            kBerkas: 4,
            value: mBerkas['4'] || {},
          },
          {
            title: 'Sertifikat Pelatihan Calon Pelatih (PCP)',
            pesan: ``,
            valid: !!mBerkas['5'],
            type: 'pelatihan',
            withAction: true,
            kBerkas: 5,
            value: mBerkas['5'] || {},
          },
        ],
        bimtek: [
          {
            title: 'Pakta Integritas',
            pesan: `* Silakan unduh template pakta integritas terlebih dahulu. <b><a href="#" target="_blank">UNDUH DISINI</a> </b>`,
            valid: true,
            type: 'integritas',
            withAction: true,
            kBerkas: 1,
          },
          {
            title: 'Kartu Tanda Penduduk (KTP)',
            pesan: ``,
            valid: true,
            type: 'ktp',
            withAction: true,
          },
          {
            title: 'Nomor Pokok Wajib Pajak (NPWP)',
            pesan: ``,
            valid: true,
            type: 'npwp',
            withAction: true,
          },
          {
            title: 'Ijasah Terakhir',
            pesan: ``,
            valid: true,
            type: 'ijasah',
            withAction: true,
            kBerkas: 3,
          },
          {
            title: 'Sertifikat Diklat Dasar',
            pesan: ``,
            valid: true,
            type: 'diklat',
            withAction: true,
          },
          {
            title: 'Sertifikat Diklat PAUD',
            pesan: `<i>*3 Pelatihan PAUD TERAKHIR</i>`,
            valid: true,
            type: 'sertifikat',
            withAction: true,
          },
        ],
      };
    },
  },
  methods: {
    ...mapActions('master', ['getMasters']),
    ...mapActions('profil', ['fetch', 'update', 'getBerkas', 'setBerkas']),

    upload(type) {
      const rules = {
        integritas: { format: 'JPEG/JPG/PNG', required: true },
        fungsi: { format: 'PDF', required: true },
        pelatihan: { format: 'PDF', required: true },
        ktp: { format: 'PDF', required: true },
        npwp: { format: 'PDF', required: true },
        ijasah: { format: 'PDF', required: true },
        sertifikat: { format: 'PDF', required: true },
      };

      this.id = this.id || null;
      this.action = 'upload';

      this.$set(this.formulir, 'form', 'FormUnggah');
      this.$set(this.formulir, 'title', `Unggah Berkas ${this.$titleCase(type)}`);
      this.$set(this.formulir, 'type', type);
      this.$set(this.formulir, 'format', `harus bertipe ${this.$getDeepObj(rules, `${type}.format`)}`);
      this.$set(this.formulir, 'rules', this.$getDeepObj(rules, `${type}`));
      this.$set(this.formulir, 'mode', 'upload');
      this.$set(this.formulir, 'init', null);
      this.$set(this.formulir, 'max', 1500);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
        // this.$set(this.formulir, 'init', berkas[0])
      });
    },

    edit() {
      this.$set(this.formulir, 'form', 'FormProfil');
      this.$set(this.formulir, 'title', `Ubah Profil`);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
        // this.$set(this.formulir, 'init', berkas[0])
      });
    },

    fetchProfil() {
      this.fetch({ jenis: this.jenis })
        .then(({ data, meta }) => {
          this.detail = Object.assign({}, data);
          this.lengkap = Object.assign({}, (meta && meta.status_lengkap) || {});
        })
        .then(() => {
          this.fetchDokumen();
        });
    },

    fetchDokumen() {
      this.getBerkas({ jenis: this.jenis, id: this.$getDeepObj(this, 'detail.paud_pengajar_id') }).then(({ data }) => {
        this.berkas = data || [];
      });
    },
  },
};
