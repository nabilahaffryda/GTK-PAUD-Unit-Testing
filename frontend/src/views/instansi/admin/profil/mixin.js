import { mapActions, mapState } from 'vuex';

export default {
  data() {
    return {
      formulir: {},
    };
  },
  computed: {
    ...mapState('master', ['masters']),

    status() {
      return [
        {
          label: 'Profil Pengajar',
          value: 'passed',
        },
        { label: 'Berkas Persyaratan Lainnya', value: 'passed' },
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
      return {
        pengajar: [
          {
            title: 'Pakta Integritas',
            pesan: `* Silakan unduh template pakta integritas terlebih dahulu. <b><a href="#" target="_blank">UNDUH DISINI</a> </b>`,
            valid: true,
            type: 'integritas',
            withAction: true,
          },
          {
            title: 'Surat Keterangan Sudah Menjalankan Fungsi Sebagai Pengajar Diklat PAUD',
            pesan: ``,
            valid: true,
            type: 'fungsi',
            withAction: true,
          },
          {
            title: 'Sertifikat Pelatihan Calon Pelatih (PCP)',
            pesan: ``,
            valid: true,
            type: 'pelatihan',
            withAction: true,
          },
        ],
        bimtek: [
          {
            title: 'Pakta Integritas',
            pesan: `* Silakan unduh template pakta integritas terlebih dahulu. <b><a href="#" target="_blank">UNDUH DISINI</a> </b>`,
            valid: true,
            type: 'integritas',
            withAction: true,
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
  },
};
