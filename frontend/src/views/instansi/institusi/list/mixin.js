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
      this.$set(this.formulir, 'title', 'Tambah Data');
      this.$set(this.formulir, 'isEdit', false);
      this.$set(this.formulir, 'useSave', false);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
      });
    },

    onValidate() {
      this.$refs.modal.onValidate().then((valid) => {
        if (valid) {
          this.$refs.formulir.next(valid);
          this.$set(this.formulir, 'useSave', true);
        }
      });
    },

    onBack() {
      this.$set(this.formulir, 'useSave', false);
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

    onDelete(item) {
      this.$confirm('Apakan anda ingin menghapus akun berikut ?', 'Hapus Akun', { tipe: 'error' }).then(() => {
        this.action({ id: item.akun_instansi_id, type: 'delete' }).then(() => {
          this.$success('Akun berhasil di hapus');
          this.fetchData();
        });
      });
    },

    onSave() {
      const isEdit = this.formulir.isEdit;
      const id = this.$refs.formulir.id;
      const params = Object.assign({}, this.$refs.formulir.getValue());

      this[isEdit ? 'update' : 'create']({ params, id })
        .then(() => {
          this.$success(`Data admin berhasil di ${isEdit ? 'diubah' : 'ditambahkan'}`);
          this.$refs.modal.close();
          this.fetchData();
        })
        .catch(() => {
          this.$refs.modal.loading = false;
        });
    },

    resetAkun(id) {
      this.action({ id, type: 'reset' }).then(({ data, included }) => {
        const ptk = this.$getIncluded('akun', this.$getRelasi(data, 'akun')['id'], included);
        const group = this.$getIncluded('m_group', this.$getRelasi(data, 'm_group')['id'], included);
        this.$set(this.akun, 'nama', this.$getAttr(ptk, 'nama') || '-');
        this.$set(this.akun, 'email', this.$getAttr(ptk, 'email') || '-');
        this.$set(this.akun, 'password', this.$getAttr(ptk, 'passwd') || '*********');
        this.$set(this.akun, 'peran', (this.$getAttr(group, 'keterangan') || '-').toUpperCase());
        this.$nextTick(() => {
          this.$refs.akun.print();
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
  },
};
