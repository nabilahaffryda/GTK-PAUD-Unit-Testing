import { mapActions } from 'vuex';

export default {
  methods: {
    ...mapActions('diklat', [
      'fetch',
      'create',
      'update',
      'listGroups',
      'getPeriode',
      'action',
      'lookup',
      'getDetail',
      'downloadList',
    ]),

    ...mapActions('master', ['getMasters']),

    onAdd() {
      this.$set(this.formulir, 'title', 'Tambah Kelas');
      this.$set(this.formulir, 'form', 'form-diklat');
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
      this.$set(this.formulir, 'isEdit', false);
      this.$set(this.formulir, 'useSave', true);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
      });
    },

    onDetailDiklat() {
      this.$set(this.formulir, 'title', 'Info dan Detil Kelas');
      this.$set(this.formulir, 'form', 'detil-kelas');
      this.$set(this.formulir, 'isEdit', false);
      this.$set(this.formulir, 'useSave', false);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
      });
    },

    async onEdit(item) {
      this.$set(this.formulir, 'isEdit', true);
      this.$set(this.formulir, 'useSave', false);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
        this.$set(this.formulir, 'title', 'Ubah Data');
        this.$set(
          this.formulir,
          'init',
          Object.assign({}, item?.instansi?.data ?? {}, {
            nama_penanggung_jawab: item.nama_penanggung_jawab,
            telp_penanggung_jawab: item.telp_penanggung_jawab,
            kodepos: item.kodepos,
          })
        );
      });
    },

    async onEditDiklat(item) {
      this.$set(this.formulir, 'title', 'Update Data Diklat');
      this.$set(this.formulir, 'form', 'form-diklat');
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
      const payload = {
        params: form,
        id: id || null,
      };

      this[isEdit ? 'update' : 'create'](payload)
        .then(() => {
          this.$success(`Data diklat berhasil di ${isEdit ? 'perbarui' : 'tambahkan'}`);
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
        this.action({ id: item.paud_periode_id, type: 'delete', name: this.attr.tipe }).then(() => {
          this.$success(`Data diklat berhasil di hapus`);
          this.fetchData();
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
  },
};
