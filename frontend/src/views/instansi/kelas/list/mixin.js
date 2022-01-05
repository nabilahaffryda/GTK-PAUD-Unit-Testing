import { mapActions } from 'vuex';
// import { today } from '../../../../utils/format';

export default {
  methods: {
    ...mapActions('master', ['getMasters']),
    ...mapActions('petugasKelas', ['fetch', 'getDetail', 'upload', 'LaporanAction']),

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
      // const now = new Date(today());
      // const wkt_selesai = new Date(this.$getDeepObj(item, 'paud_diklat_luring.data.tgl_selesai'));
      // return now >= wkt_selesai;

      return Number(item?.is_selesai ?? 0) === 1;
    },

    onView(item) {
      this.$router.push({ name: 'kelas-luring-peserta', params: { kelas_id: item.id } });
    },

    onLaporan(item) {
      this.$router.push({ name: 'kelas-luring-laporan', params: { kelas_id: item.id } });
    },

    onUploadLaporan(item) {
      if (![1, 5].includes(item?.laporan_k_verval_paud ?? 1)) {
        const message =
          item.laporan_k_verval_paud === 2
            ? 'Laporan pelaksanaan diklat Luring sudah di ajukan, untuk pembatalan silakan Batal Ajuan Laporan terlebih dahulu'
            : 'Laporan pelaksanaan sudah diverval, untuk pembatalan silahkan menghubungi Admin GTK';
        this.$error(message);
        return;
      }

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
      const file = this.$refs.formulir.$refs.formulir.form || {};
      this.uploadSave(file);
    },

    uploadSave(data) {
      const formData = new FormData();
      for (let obj in data) {
        formData.append(obj, data[obj]);
      }

      const params = {
        params: formData,
        id: this.id,
        jenis: this.jenis,
      };

      this.upload(params)
        .then(() => {
          this.$refs.modal.loading = false;
          this.$refs.modal.close();
          this.getKelasDiklat();
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
        this.LaporanAction({ id: item.id, jenis: this.jenis, aksi: 'kirim' }).then(() => {
          this.$success(`Laporan pelaksanaan berhasil di ajukan`);
          this.onReload();
        });
      });
    },

    onBatalAjuan(item) {
      if ([4, 6].includes(item?.laporan_k_verval_paud ?? 1)) {
        this.$error('Laporan pelaksanaan sudah diverval, untuk pembatalan silahkan menghubungi Admin GTK');
        return;
      }

      this.$confirm(`Apakah Anda yakin ingin membatalkan pengajuan laporan berikut ?`, `Batalkan Ajuan Laporan`, {
        tipe: 'error',
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
        this.LaporanAction({ id: item.id, jenis: this.jenis, aksi: 'batal' }).then(() => {
          this.$success(`Ajuan Laporan pelaksanaan berhasil dibatalkan`);
          this.onReload();
        });
      });
    },
  },
};
