import { mapActions, mapState } from 'vuex';

export default {
  data() {
    return {
      formulir: {},
      detail: {},
      lengkap: {},
      berkas: [],
      id: '',
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
          value: this.lengkap?.profil ? 'passed' : 'not_finish',
        },
        { label: 'Berkas Persyaratan Lainnya', value: this.lengkap?.berkas ? 'passed' : 'not_finish' },
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
      const withAction = this.$allow(`${this.jenis}-profil-berkas.create`);
      return {
        pengajar: [
          {
            title: 'Pakta Integritas',
            pesan: `* Silakan unduh template pakta integritas terlebih dahulu. <b><a href="#" target="_blank">UNDUH DISINI</a> </b>`,
            valid: !!mBerkas['1'],
            type: 'integritas',
            withAction: withAction,
            kBerkas: 1,
            value: mBerkas['1'] || {},
          },
          {
            title: 'Surat Keterangan Sudah Menjalankan Fungsi Sebagai Pengajar Diklat PAUD',
            pesan: ``,
            valid: !!mBerkas['4'],
            type: 'fungsi',
            withAction: withAction,
            kBerkas: 4,
            value: mBerkas['4'] || {},
          },
          {
            title: 'Sertifikat Pelatihan Calon Pelatih (PCP)',
            pesan: ``,
            valid: !!mBerkas['5'],
            type: 'pelatihan',
            withAction: withAction,
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
            withAction: withAction,
            kBerkas: 1,
          },
          {
            title: 'Kartu Tanda Penduduk (KTP)',
            pesan: ``,
            valid: true,
            type: 'ktp',
            withAction: withAction,
          },
          {
            title: 'Nomor Pokok Wajib Pajak (NPWP)',
            pesan: ``,
            valid: true,
            type: 'npwp',
            withAction: withAction,
          },
          {
            title: 'Ijasah Terakhir',
            pesan: ``,
            valid: true,
            type: 'ijasah',
            withAction: withAction,
            kBerkas: 3,
          },
          {
            title: 'Sertifikat Diklat Dasar',
            pesan: ``,
            valid: true,
            type: 'diklat',
            withAction: withAction,
          },
          {
            title: 'Sertifikat Diklat PAUD',
            pesan: `<i>*3 Pelatihan PAUD TERAKHIR</i>`,
            valid: true,
            type: 'sertifikat',
            withAction: withAction,
          },
        ],
        lpd: [
          {
            title: 'Akta Pendirian dan atau SK Oleh Pejabat Berwenang',
            pesan: `<i>* Diperioritaskan dari kemenkumham</i>`,
            valid: false,
            type: 'akta-lembaga',
            withAction: withAction,
            kBerkas: 1,
          },
          {
            title: 'Foto Lembaga Pelatihan',
            pesan: ``,
            valid: false,
            type: 'ktp',
            withAction: withAction,
          },
          {
            title: 'Foto Kartu NPWP Atas Nama Lembaga',
            pesan: ``,
            valid: false,
            type: 'npwp',
            withAction: withAction,
          },
          {
            title: 'SK Lembaga Pelatihan Yang Terakreditasi',
            pesan: `<i>* Khusus Perguruan Tinggi</i>`,
            valid: false,
            type: 'ijasah',
            withAction: withAction,
            kBerkas: 3,
          },
          {
            title: 'SK Kepengurusan Masih Berlaku',
            pesan: ``,
            valid: false,
            type: 'diklat',
            withAction: withAction,
          },
          {
            title: 'Pakta Integritas',
            pesan: ``,
            valid: false,
            type: 'sertifikat',
            withAction: withAction,
          },
          {
            title: 'Foto Rekening Atas Nama Lembaga Pada Halaman Nomor Rekening',
            pesan: ``,
            valid: false,
            type: 'sertifikat',
            withAction: withAction,
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
        integritas: { format: 'PDF', required: true },
        fungsi: { format: 'PDF', required: true },
        pelatihan: { format: 'PDF', required: true },
        ktp: { format: 'PDF', required: true },
        npwp: { format: 'PDF', required: true },
        ijasah: { format: 'PDF', required: true },
        sertifikat: { format: 'PDF', required: true },
      };

      this.action = 'upload';

      const mBerkas = this.$arrToObj(this.berkases[this.jenis], 'type');

      this.$set(this.formulir, 'form', 'FormUnggah');
      this.$set(this.formulir, 'title', `${mBerkas[type]['title']}`);
      this.$set(this.formulir, 'type', type);
      this.$set(this.formulir, 'kBerkas', mBerkas[type]['kBerkas']);
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
      this.$set(this.formulir, 'init', null);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
        this.$set(this.formulir, 'init', Object.assign({}, this.detail, this.detail?.akun?.data));
      });
    },

    fetchProfil() {
      this.fetch({ jenis: this.jenis })
        .then(({ data, meta }) => {
          this.detail = Object.assign({}, data);
          this.lengkap = Object.assign({}, (meta && meta.status_lengkap) || {});
          this.id = this.$getDeepObj(data, 'paud_pengajar_id') || this.$getDeepObj(data, 'paud_instansi_id');
        })
        .then(() => {
          this.fetchDokumen();
        });
    },

    fetchDokumen() {
      this.getBerkas({ jenis: this.jenis, id: this.id }).then(({ data }) => {
        this.berkas = data || [];
      });
    },

    onSave() {
      if (this.formulir.mode === 'upload') {
        this.uploadData();
        return;
      }

      const data = this.$refs.formulir.getValue();
      const { form, photo, diklats } = data;

      let formData = new FormData();

      Object.keys(form).forEach((key) => {
        if (form[key]) {
          formData.append(key, form[key]);
        }
      });

      if (!diklats.length) {
        this.$error('Mohon isikan data diklat minimal 1 (satu)');
        this.$refs.modal.loading = false;
        return;
      } else {
        diklats.forEach((key, index) => {
          formData.append('pengalaman' + `[${index}][nama]`, key['nama']);
          formData.append('pengalaman' + `[${index}][tahun]`, key['tahun']);
        });
      }

      if (photo) {
        for (let item of photo.entries()) {
          formData.append(item[0], item[1]);
        }
      }

      const payload = {
        jenis: this.jenis,
        id: this.id,
        params: formData,
      };

      this.update(payload)
        .then(() => {
          this.$success('Profil Berhasil diperbarui');
          this.fetchProfil();
          this.$refs.modal.close();
        })
        .catch(() => {
          this.$refs.modal.loading = false;
        });
    },

    uploadData() {
      const params = this.$refs.formulir.form || {};
      const formData = new FormData();
      let type = this.formulir.kBerkas;

      let mapParams = this.appendData(
        Object.assign(
          {},
          {
            k_berkas: type,
            file: params['file'],
          }
        ),
        false,
        formData
      );

      this.setBerkas({
        jenis: this.jenis,
        id: this.id,
        params: mapParams,
      })
        .then(() => {
          this.onReload();
          this.$success(`Berkas berhasil diunggah`);
          this.$refs.modal.close();
        })
        .catch(() => {
          this.$refs.modal.loading = false;
        });
    },

    appendData(data, obj, FormData) {
      if (data && this.$isObject(data)) {
        for (let key in data) {
          let name = (obj && `${obj}[${key}]`) || key;
          if (data[key] && this.$isObject(data[key])) {
            this.appendData(data[key], name, FormData);
          } else {
            FormData.append(name, data[key]);
          }
        }
        return FormData;
      }
    },

    onReload() {
      this.fetchProfil();
    },
  },
};
