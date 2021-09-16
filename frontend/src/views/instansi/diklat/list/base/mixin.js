import { mapActions } from 'vuex';

export default {
  methods: {
    ...mapActions('master', ['getMasters']),

    fetchDiklat() {
      if (!this.diklatId) return;
      this.getDiklat({ id: this.diklatId }).then(({ data }) => {
        this.detail = data || {};
      });
    },

    onDetailDiklat(item) {
      this.$router.push({ name: 'diklat-kelas', params: { diklat_id: item.id } });
    },

    async onDetail(item, reload = true) {
      const data = await this.getDetail({ diklat_id: this.diklatId, id: item.id || item.paud_kelas_id }).then(
        ({ data }) => data
      );
      this.$set(this.formulir, 'title', 'Detail Diklat');
      this.$set(this.formulir, 'form', 'detail-kelas');
      this.$set(this.formulir, 'useSave', false);
      if (reload) this.$refs.modal.open();

      this.$nextTick(() => {
        this.$refs.formulir.reset();
        this.$refs.formulir.kelas = data || {};
      });
    },

    onAdd() {
      this.$set(this.formulir, 'title', 'Tambah Kelas Diklat');
      this.$set(this.formulir, 'form', 'form-diklat');
      this.$set(this.formulir, 'type', 'kelas');
      this.$set(this.formulir, 'isEdit', false);
      this.$set(this.formulir, 'useSave', true);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
      });
    },

    onAddDiklat() {
      this.$set(this.formulir, 'title', 'Tambah Diklat');
      this.$set(this.formulir, 'form', 'form-diklat');
      this.$set(this.formulir, 'type', 'diklat');
      this.$set(this.formulir, 'isEdit', false);
      this.$set(this.formulir, 'useSave', true);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
      });
    },

    async onEdit(item) {
      this.$set(this.formulir, 'title', 'Update Data Kelas');
      this.$set(this.formulir, 'form', 'form-diklat');
      this.$set(this.formulir, 'type', 'kelas');
      this.$set(this.formulir, 'isEdit', true);
      this.$set(this.formulir, 'id', item.id);
      this.$set(this.formulir, 'useSave', true);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
        this.$set(this.formulir, 'title', 'Ubah Data');
        this.$set(
          this.formulir,
          'init',
          Object.assign({}, item, {
            k_propinsi: this.$getDeepObj(this, 'detail.k_propinsi'),
            k_kota: this.$getDeepObj(this, 'detail.k_kota'),
          })
        );
      });
    },

    async onEditDiklat(item) {
      this.$set(this.formulir, 'title', 'Update Data Diklat');
      this.$set(this.formulir, 'form', 'form-diklat');
      this.$set(this.formulir, 'type', 'diklat');
      this.$set(this.formulir, 'isEdit', true);
      this.$set(this.formulir, 'useSave', true);
      this.$set(this.formulir, 'id', item.id);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
        this.$set(this.formulir, 'title', 'Ubah Data');
        this.$set(this.formulir, 'init', Object.assign({}, item));
      });
    },

    onSave() {
      const formulir = this.$refs.formulir.getValue();
      const form = formulir && formulir.form;
      const isEdit = this.formulir.isEdit;
      const id = this.formulir.id;
      const jenis = this.formulir.type;
      const payload = {
        params: form,
        id: id || null,
        diklat_id: this.diklatId || null,
      };

      this[isEdit ? 'update' : 'create'](payload)
        .then(() => {
          this.$success(`Data ${jenis} berhasil di ${isEdit ? 'perbarui' : 'tambahkan'}`);
          this.onReload();
          this.$refs.modal.close();
        })
        .catch(() => {
          this.$refs.modal.loading = false;
        });
    },

    onDeleteDiklat(item) {
      this.$confirm(`Apakan anda ingin menghapus data diklat berikut ?`, `Hapus Diklat`, {
        tipe: 'error',
        data: [
          {
            icon: 'mdi-teach',
            iconSize: 30,
            iconColor: 'secondary',
            title: `${this.$getDeepObj(item, 'nama')}`,
            subtitles: [`<span>Email: ${this.$getDeepObj(item, 'singkatan')}</span>`],
          },
        ],
      }).then(() => {
        this.action({ id: item.paud_diklat_id, type: 'delete', name: this.attr.tipe }).then(() => {
          this.$success(`Data diklat berhasil di hapus`);
          this.fetchData();
        });
      });
    },

    onAjuan(item) {
      this.$confirm(`Apakan anda ingin melakukan ajuan pada kelas berikut ?`, `Ajuan Diklat`, {
        tipe: 'info',
        data: [
          {
            icon: 'mdi-teach',
            iconSize: 30,
            iconColor: 'secondary',
            title: `${this.$getDeepObj(item, 'nama')}`,
            subtitles: [`<span>Deskripsi: ${this.$getDeepObj(item, 'deskripsi') || ''}</span>`],
          },
        ],
      }).then(() => {
        this.action({ id: item.id, diklat_id: this.diklatId, type: 'ajuan/create', name: this.attr.tipe }).then(() => {
          this.$success(`Diklat kelas berhasil di ajukan`);
          this.onReload();
        });
      });
    },

    onBatalAjuan(item) {
      if ([4, 6].includes(item?.m_verval_paud ?? 1)) {
        this.$error('Kelas sudah diverval, untuk pembatalan silahkan menghubungi Admin GTK');
        return;
      }

      this.$confirm(`Apakan anda ingin membatalkan ajuan pada kelas berikut ?`, `Batalkan Ajuan Diklat`, {
        tipe: 'warning',
        data: [
          {
            icon: 'mdi-teach',
            iconSize: 30,
            iconColor: 'secondary',
            title: `${this.$getDeepObj(item, 'nama')}`,
            subtitles: [`<span>Deskripsi: ${this.$getDeepObj(item, 'deskripsi') || ''}</span>`],
          },
        ],
      }).then(() => {
        this.action({ id: item.id, diklat_id: this.diklatId, type: 'ajuan/delete', name: this.attr.tipe }).then(() => {
          this.$success(`Ajuan diklat kelas berhasil di batalkan`);
          this.onReload();
        });
      });
    },

    filterStatus(filters) {
      // set filter
      this.filters = filters;
      Object.assign(this.params, { page: 1 });
      this.fetchData();
      this.$refs.filter.close();
    },

    listPeriodes() {
      this.getPeriode().then(({ data }) => {
        this.periodes = data || [];
      });
    },

    listMapels() {
      this.getMapels().then(({ data }) => {
        this.mapels = data || [];
      });
    },
  },
};
