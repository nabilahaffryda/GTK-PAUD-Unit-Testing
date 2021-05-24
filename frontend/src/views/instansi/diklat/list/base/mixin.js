import { mapActions } from 'vuex';

export default {
  methods: {
    ...mapActions('institusi', [
      'fetch',
      'create',
      'update',
      'listGroups',
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

    onSave() {},

    filterStatus(filters) {
      // set filter
      this.filters = filters;
      Object.assign(this.params, { page: 1 });
      this.fetchData();
      this.$refs.filter.close();
    },
  },
};
