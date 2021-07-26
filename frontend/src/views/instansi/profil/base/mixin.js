import { mapActions, mapState } from 'vuex';
import { roundDecimal } from '@/utils/format';

export default {
  data() {
    return {
      formulir: {},
      detail: {},
      lengkap: {},
      diklat: [],
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
      return this.jenis === 'admin-kelas'
        ? [
            {
              label: `${this.$route.meta.title}`,
              value: this.lengkap?.profil ? 'passed' : 'not_complete',
            },
          ]
        : this.jenis === 'lpd'
        ? [
            {
              label: `${this.$route.meta.title}`,
              value: this.lengkap?.profil ? 'passed' : 'not_complete',
            },
            { label: 'Berkas Persyaratan Lainnya', value: this.lengkap?.berkas ? 'passed' : 'not_complete' },
          ]
        : [
            {
              label: `${this.$route.meta.title}`,
              value: this.lengkap?.profil ? 'passed' : 'not_complete',
            },
            {
              label: `Diklat`,
              value: this.lengkap?.diklat ? 'passed' : 'not_complete',
            },
            { label: 'Berkas Persyaratan Lainnya', value: this.lengkap?.berkas ? 'passed' : 'not_complete' },
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
      return Object.assign(
        {},
        this.jenis === 'admin-kelas'
          ? {
              profil: {
                component: 'Profil',
                form: 'FormProfil',
                title: this.$route.meta.title,
                deskripsi: '',
                max: 10,
                optional: true,
              },
            }
          : this.jenis === 'lpd'
          ? {
              profil: {
                component: 'Profil',
                form: 'FormProfil',
                title: this.$route.meta.title,
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
            }
          : {
              profil: {
                component: 'Profil',
                form: 'FormProfil',
                title: this.$route.meta.title,
                deskripsi: '',
                max: 10,
                optional: true,
              },
              diklat: {
                component: 'Daftar',
                form: 'FormCollection',
                title: 'Data Pengalaman Diklat',
                status: 'diklat',
                deskripsi: 'Tuliskan pelatihan yang relevan dengan profesi Anda yang pernah anda ikuti',
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
            }
      );
    },

    berkases() {
      const mBerkas = this.$arrToObj(
        this.berkas,
        `k_berkas_${['lpd', 'admin-kelas'].includes(this.jenis) ? this.jenis : 'petugas'}_paud`
      );
      const withAction = this.$allow(
        `${['lpd', 'admin-kelas'].includes(this.jenis) ? this.jenis : 'petugas'}-profil-berkas.create`
      );
      return {
        pengajar: [
          {
            title: 'Pakta Integritas',
            pesan: `* Silakan unduh template pakta integritas terlebih dahulu. <b><a href="https://files1.simpkb.id/berkas/paud/PAKTA_INTEGRITAS_PENGAJAR.docx" target="_blank">UNDUH DISINI</a> </b>`,
            valid: !!mBerkas['1'],
            type: 'integritas',
            withAction: withAction,
            kBerkas: 1,
            value: mBerkas['1'] || {},
          },
          {
            title: 'Ijazah Terakhir',
            pesan: ``,
            valid: !!mBerkas['3'],
            type: 'ijasah',
            withAction: withAction,
            kBerkas: 3,
            value: mBerkas['3'] || {},
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
        ],
        bimtek: [
          {
            title: 'Pakta Integritas',
            pesan: `* Silakan unduh template pakta integritas terlebih dahulu. <b><a href="https://files1.simpkb.id/berkas/paud/PAKTA_INTEGRITAS_PEMBIMBING_PRAKTIK.docx" target="_blank">UNDUH DISINI</a> </b>`,
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
            title: 'Ijazah Terakhir',
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
            valid: !!mBerkas['1'],
            type: 'pendirian',
            withAction: withAction,
            value: mBerkas['1'] || {},
            kBerkas: 1,
          },
          {
            title: 'Profil Lembaga Pelatihan',
            pesan: ``,
            valid: !!mBerkas['2'],
            type: 'profillembaga',
            withAction: withAction,
            value: mBerkas['2'] || {},
            kBerkas: 2,
            optional: true,
          },
          {
            title: 'Foto Kartu NPWP atas nama Lembaga',
            pesan: ``,
            valid: !!mBerkas['3'],
            type: 'npwp',
            withAction: withAction,
            value: mBerkas['3'] || {},
            kBerkas: 3,
          },
          {
            title: 'SK Lembaga Pelatihan Yang Terakreditasi',
            pesan: `<i>* Khusus Perguruan Tinggi</i>`,
            valid: !!mBerkas['4'],
            type: 'skpelatihan',
            withAction: withAction,
            value: mBerkas['4'] || {},
            kBerkas: 4,
            optional: true,
          },
          {
            title: 'SK Kepengurusan Masih Berlaku',
            pesan: ``,
            valid: !!mBerkas['5'],
            type: 'skpengurusan',
            withAction: withAction,
            value: mBerkas['5'] || {},
            kBerkas: 5,
          },
          {
            title: 'Pakta Integritas',
            pesan: `* Silakan unduh template pakta integritas terlebih dahulu. <b><a href="https://files1.simpkb.id/berkas/paud/PAKTA_INTEGRITAS_LPD.docx" target="_blank">UNDUH DISINI</a> </b>`,
            valid: !!mBerkas['6'],
            type: 'integritas',
            withAction: withAction,
            value: mBerkas['6'] || {},
            kBerkas: 6,
          },
          {
            title: 'Foto Rekening Atas Nama Lembaga Pada Halaman Nomor Rekening',
            pesan: ``,
            valid: !!mBerkas['7'],
            type: 'bukurekening',
            withAction: withAction,
            value: mBerkas['7'] || {},
            kBerkas: 7,
            optional: true,
          },
        ],
        pembimbing: [
          {
            title: 'Pakta Integritas',
            pesan: `* Silakan unduh template pakta integritas terlebih dahulu. <b><a href="https://files1.simpkb.id/berkas/paud/PAKTA_INTEGRITAS_PEMBIMBING_PRAKTIK.docx" target="_blank">UNDUH DISINI</a> </b>`,
            valid: !!mBerkas['1'],
            type: 'integritas',
            withAction: withAction,
            kBerkas: 1,
            value: mBerkas['1'] || {},
          },
          {
            title: 'Kartu Tanda Penduduk (KTP)',
            pesan: ``,
            type: 'ktp',
            valid: !!mBerkas['2'],
            withAction: withAction,
            kBerkas: 2,
            value: mBerkas['2'] || {},
          },
          {
            title: 'Nomor Pokok Wajib Pajak (NPWP)',
            pesan: ``,
            type: 'npwp',
            valid: !!mBerkas['3'],
            withAction: withAction,
            kBerkas: 3,
            value: mBerkas['3'] || {},
          },
          {
            title: 'Ijazah Terakhir',
            pesan: ``,
            valid: !!mBerkas['4'],
            type: 'ijasah',
            withAction: withAction,
            kBerkas: 4,
            value: mBerkas['4'] || {},
          },
        ],
      };
    },

    kVerval() {
      return ['lpd', 'admin-kelas'].includes(this.jenis)
        ? this.$getDeepObj(this.detail, 'k_verval_paud') || 1
        : this.$getDeepObj(this.detail, 'paud_petugas_perans.data.0.k_verval_paud') || 1;
    },

    isAjuan() {
      return ['lpd', 'pengajar'].includes(this.jenis) && ![1, 4, 5].includes(this.kVerval);
    },

    catatan() {
      const catatan =
        this.jenis === 'lpd'
          ? this.$getDeepObj(this.detail, 'alasan')
          : this.$getDeepObj(this.detail, 'paud_petugas_perans.data.0.alasan');
      return catatan;
    },
  },
  methods: {
    ...mapActions('master', ['getMasters']),
    ...mapActions('profil', [
      'fetch',
      'update',
      'getDiklat',
      'getBerkas',
      'setBerkas',
      'dropBerkas',
      'ajuan',
      'batalAjuan',
    ]),

    fetchProfil() {
      this.fetch({ jenis: this.jenis })
        .then(({ data, meta }) => {
          this.detail = Object.assign({}, data);
          this.lengkap = Object.assign({}, (meta && meta.status_lengkap) || {});
          this.id = this.$getDeepObj(data, 'id');
        })
        .then(() => {
          if (this.jenis !== 'admin-kelas') {
            this.fetchDokumen();
            if (this.jenis !== 'lpd') this.fetchDiklat();
          }
        });
    },

    fetchDiklat() {
      this.getDiklat({ jenis: this.jenis, id: this.id }).then(({ data }) => {
        this.diklat = data || [];
      });
    },

    fetchDokumen() {
      this.getBerkas({ jenis: this.jenis, id: this.id }).then(({ data }) => {
        this.berkas = data || [];
      });
    },

    upload(type) {
      if (this.isAjuan) {
        const msg = `<p class="title mb-2">Mohon maaf! Anda sudah mengajukan Berkas untuk diperiksa Tim Verval`;
        this.$info(msg, `Perubahan data tidak diperbolehkan`, {
          tipe: 'warning',
          data: '',
        });
        return;
      }

      const defRules = 'PDF/JPEG/JPG/PNG';

      const rules = {
        integritas: { format: defRules, required: true },
        fungsi: { format: defRules, required: true },
        pelatihan: { format: defRules, required: true },
        ktp: { format: defRules, required: true },
        npwp: { format: defRules, required: true },
        ijasah: { format: defRules, required: true },
        sertifikat: { format: defRules, required: true },
        pendirian: { format: defRules, required: true },
        profillembaga: { format: defRules, required: true },
        skpelatihan: { format: defRules, required: true },
        skpengurusan: { format: defRules, required: true },
        bukurekening: { format: defRules, required: true },
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

    deleteBerkas(type) {
      const mBerkas = this.$arrToObj(this.berkases[this.jenis], 'type');
      this.dropBerkas({
        jenis: this.jenis,
        id: mBerkas[type]['value']['id'] || '',
      })
        .then(() => {
          this.onReload();
          this.$success(`Berkas berhasil dihapus`);
          this.$refs.modal.close();
        })
        .catch(() => {
          this.$refs.modal.loading = false;
        });
    },

    edit(type) {
      if (this.isAjuan) {
        const msg = `<p class="title mb-2">Mohon maaf! Anda sudah mengajukan Berkas untuk diperiksa Tim Verval`;
        this.$info(msg, `Perubahan data tidak diperbolehkan`, {
          tipe: 'warning',
          data: '',
        });
        return;
      }

      const initValue =
        type === 'diklat'
          ? this.diklat || []
          : Object.assign(
              {},
              this.$getDeepObj(this.detail, 'akun.data') || {},
              this.$getDeepObj(this.detail, 'instansi.data') || {},
              this.detail
            );

      this.$set(this.formulir, 'form', this.contents[type]['form']);
      this.$set(this.formulir, 'title', this.contents[type]['title']);
      this.$set(this.formulir, 'max', Number(this.$getDeepObj(this.contents, `${type}.max`)) || '');
      this.$set(this.formulir, 'items', this.diklat || []);
      this.$set(this.formulir, 'mode', 'form');
      this.$set(this.formulir, 'type', type);
      this.$set(this.formulir, 'init', null);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
        this.$set(this.formulir, 'init', initValue);
      });
    },

    onSave() {
      if (this.formulir.mode === 'upload') {
        this.uploadData();
        return;
      }

      const data = this.$refs.formulir.getValue();
      const type = this.formulir.type;
      let formData = new FormData();

      if (type !== 'diklat') {
        const { form, diklats, photo } = data;

        Object.keys(form).forEach((key) => {
          formData.append(key, form[key] || '');
        });

        if (this.jenis === 'lpd') {
          if (!diklats.length) {
            this.$error('Mohon isikan data diklat minimal 1 (satu)');
            this.$refs.modal.loading = false;
            return;
          } else {
            for (let i = 0; i < diklats.length; i++) {
              if (
                !diklats[i]['nama'] ||
                diklats[i]['nama'].trim() === '' ||
                !diklats[i]['tahun'] ||
                diklats[i]['tahun'].trim() === ''
              ) {
                this.$error('Mohon lengkapi data Diklat Anda');
                this.$refs.modal.loading = false;
                return;
              }
              formData.append((this.jenis === 'lpd' ? 'diklat' : 'pengalaman') + `[${i}][nama]`, diklats[i]['nama']);
              formData.append((this.jenis === 'lpd' ? 'diklat' : 'pengalaman') + `[${i}][tahun]`, diklats[i]['tahun']);
            }
          }
        }

        if (photo) {
          for (let item of photo.entries()) {
            formData.append(item[0], item[1]);
          }
        }
      } else {
        const hasData = data.filter((item) => item && item.penyelenggara);
        if (!hasData.length) {
          this.$error('Mohon isikan data diklat minimal 1 (satu)');
          this.$refs.modal.loading = false;
          return;
        } else {
          // const dasar = hasData.findIndex((item) => Number(item.k_diklat_paud) === 1) > -1;
          // const pcp = hasData.findIndex((item) => Number(item.k_diklat_paud) === 2) > -1;
          // const mot = hasData.findIndex((item) => Number(item.k_diklat_paud) === 3) > -1;
          const lainnya = hasData.findIndex((item) => Number(item.k_diklat_paud) === 4) > -1;
          const tambahan = Number(this.detail && this.detail.k_petugas_paud) === 2;

          if (tambahan && !lainnya) {
            this.$error('Mohon isikan data diklat lainnya');
            this.$refs.modal.loading = false;
            return;
          }

          for (let i = 0; i < hasData.length; i++) {
            if (!hasData[i]['penyelenggara'] || !hasData[i]['tahun_diklat'] || !hasData[i]['file']) {
              this.$error('Mohon lengkapi data Diklat Anda');
              this.$refs.modal.loading = false;
              return;
            }
            formData.append(`data[${i}][nama]`, hasData[i]['nama']);
            formData.append(`data[${i}][k_diklat_paud]`, hasData[i]['k_diklat_paud']);
            formData.append(`data[${i}][penyelenggara]`, hasData[i]['penyelenggara']);
            formData.append(`data[${i}][tahun_diklat]`, hasData[i]['tahun_diklat']);

            if (hasData[i]['k_tingkat_diklat_paud'])
              formData.append(`data[${i}][k_tingkat_diklat_paud]`, hasData[i]['k_tingkat_diklat_paud']);
            if (hasData[i]['tingkatan']) formData.append(`data[${i}][tingkatan]`, hasData[i]['tingkatan']);
            if (hasData[i]['paud_petugas_diklat_id'])
              formData.append(`data[${i}][paud_petugas_diklat_id]`, hasData[i]['paud_petugas_diklat_id']);

            if (typeof hasData[i]['file'] !== 'string') {
              const size = hasData[i]['file']['size'] || 0;

              if (size > roundDecimal(1500 * 1000)) {
                this.$error('Berkas yang Anda upload melebihi kapasitas maksimum!');
                this.$refs.modal.loading = false;
                return;
              }
              formData.append(`data[${i}][file]`, hasData[i]['file']);
            }
          }
        }
      }

      const payload = {
        jenis: this.jenis,
        tipe: this.formulir.type,
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

    onAjuan() {
      this.$confirm('<p>Anda yakin ingin ajukan verval diatas?</p>', 'Ajuan Verval', {
        tipe: 'info',
        data: '',
      }).then(() => {
        this.ajuan({ jenis: this.jenis, id: this.id }).then(() => {
          this.$success('Berkas Berhasil Diajukan');
          this.fetchProfil();
        });
      });
    },

    onBatalAjuan() {
      this.$confirm(`<h3>Anda yakin ingin membatalkan Ajuan Verval?</h3>`, `Batal Ajuan Verval`, {
        tipe: 'error',
      }).then(() => {
        this.batalAjuan({ jenis: this.jenis, id: this.id }).then(() => {
          this.$success('Ajuan Berhasil Dibatalkan');
          this.fetchProfil();
        });
      });
    },

    onResetForm() {
      this.$refs.modal.reset();
    },
  },
};
