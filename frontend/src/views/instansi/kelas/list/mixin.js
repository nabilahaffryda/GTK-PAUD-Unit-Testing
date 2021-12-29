import { mapActions } from 'vuex';

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
  },
};
