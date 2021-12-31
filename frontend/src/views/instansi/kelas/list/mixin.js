import { mapActions } from 'vuex';
import { today } from '../../../../utils/format';

export default {
  methods: {
    ...mapActions('master', ['getMasters']),
    ...mapActions('petugasKelas', ['fetch', 'getDetail']),

    async onDetail(item) {
      const data = await this.getDetail({ id: item.id }).then(({ data }) => data);
      this.$set(this.formulir, 'title', 'Detail Diklat');
      this.$set(this.formulir, 'form', 'detail-kelas');
      this.$set(this.formulir, 'useSave', false);
      this.$refs.modal.open();

      this.$nextTick(() => {
        this.$refs.formulir.reset();
        this.$refs.formulir.kelas = data || {};
      });
    },

    isEndDiklat(item) {
      const now = new Date(today());
      const wkt_selesai = new Date(this.$getDeepObj(item, 'paud_diklat_luring.data.tgl_selesai'));
      return now >= wkt_selesai;
    },

    onView(item) {
      this.$router.push({ name: 'kelas-luring-peserta', params: { kelas_id: item.id } });
    },

    onLaporan(item) {
      this.$router.push({ name: 'kelas-luring-laporan', params: { kelas_id: item.id } });
    },

    onUploadLaporan(data) {
      this.kelasId = data && data.id;
      this.$set(this.formulir, 'title', 'Unggah Laporan Pelaksanaan');
      this.$set(this.formulir, 'form', 'FormUpload');
      this.$set(this.formulir, 'useSave', true);
      this.$refs.modal.open();

      this.$nextTick(() => {
        this.$refs.formulir.reset();
      });
    },

    onUpload() {
      this.$refs.uploader.open();
    },

    setFile(data) {
      this.$refs.formulir.setFile(data);
      this.$refs.uploader.dialog = false;
    },

    onSave() {
      const file = this.$refs.formulir.file || {};
      this.uploadSave(file);
    },

    uploadSave(data) {
      const formData = new FormData();
      for (let obj in data) {
        formData.append(obj, data[obj]);
      }

      const params = {
        params: formData,
        tipe: this.akses,
      };

      this.upload(params)
        .then(({ data }) => {
          if (data && data.errors) {
            const error = Object.values(data.errors) || [];
            // this.$refs.uploader.step = 1;
            // this.$refs.uploader.errorFile.push(...error);
            if (error.length) {
              this.$error(error.join('<br/>'));
            } else {
              this.$error(
                'Terdapat kesalahan pada Data pada berkas yang diunggah, silakan periksa berkas Anda dan pastikan tidak ada Data yang sama kemudian coba kembali!'
              );
            }
            this.$refs.modal.loading = false;
            return;
          }

          this.$refs.uploader.step = 1;
          this.$refs.modal.close();
          this.fetchData();
        })
        .catch(() => {
          this.$refs.modal.loading = false;
          this.$error(
            'Terdapat kesalahan pada Data pada berkas yang diunggah, silakan periksa berkas Anda dan pastikan tidak ada Data yang sama kemudian coba kembali!'
          );
        });
    },

    unduhTemplate() {
      this.templateUpload({ tipe: this.akses }).then((url) => {
        this.$downloadFile(url);
      });
    },

    onAjuan(item) {
      this.$confirm(`Apakah Anda yakin ingin mengajukan laporan berikut ?`, `Ajuan Laporan`, {
        tipe: 'info',
        data: [
          {
            icon: 'mdi-file',
            iconSize: 30,
            iconColor: 'secondary',
            title: `${this.$getDeepObj(item, 'nama')}`,
            subtitles: [`<span>Deskripsi: ${this.$getDeepObj(item, 'deskripsi') || ''}</span>`],
          },
        ],
      }).then(() => {
        this.action({ id: item.id, diklat_id: this.diklatId, type: 'ajuan/create', name: this.attr.tipe }).then(() => {
          this.$success(`Laporan pelaksanaan berhasil di ajukan`);
          this.onReload();
        });
      });
    },

    onBatalAjuan(item) {
      if ([4, 6].includes(item?.m_verval_paud ?? 1)) {
        this.$error('Laporan pelaksanaan sudah diverval, untuk pembatalan silahkan menghubungi Admin GTK');
        return;
      }

      this.$confirm(`Apakah Anda yakin ingin membatalkan pengajuan laporan berikut ?`, `Batalkan Ajuan Laporan`, {
        tipe: 'warning',
        data: [
          {
            icon: 'mdi-file',
            iconSize: 30,
            iconColor: 'secondary',
            title: `${this.$getDeepObj(item, 'nama')}`,
            subtitles: [`<span>Deskripsi: ${this.$getDeepObj(item, 'deskripsi') || ''}</span>`],
          },
        ],
      }).then(() => {
        this.action({ id: item.id, diklat_id: this.diklatId, type: 'ajuan/delete', name: this.attr.tipe }).then(() => {
          this.$success(`Ajuan Laporan pelaksanaan berhasil dibatalkan`);
          this.onReload();
        });
      });
    },
  },
};
