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
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
      });
    },

    async onEdit(item) {
      this.$set(this.formulir, 'isEdit', true);
      this.$set(this.formulir, 'isChecked', true);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
        this.$set(this.formulir, 'title', 'Ubah Data');
        this.$set(
          this.formulir,
          'init',
          Object.assign({}, item?.akun?.data ?? {}, { k_group: item.k_group, akun_instansi_id: item.akun_instansi_id })
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
        .then(({ data }) => {
          this.$success(`Data admin berhasil di ${isEdit ? 'diubah' : 'ditambahkan'}`);
          this.$refs.modal.close();
          this.fetchData();

          if (!isEdit && !this.formulir.isExist) {
            this.resetAkun(this.$getDeepObj(data, 'id'));
          }
        })
        .catch(() => {
          this.$refs.modal.loading = false;
        });
    },

    onReset(item) {
      const id = item.akun_instansi_id;
      this.$confirm(
        `Anda yakin ingin mereset password atas nama <strong>${item.akun?.data?.nama ?? ''}</strong> ?`,
        'Reset Password',
        {
          tipe: 'error',
        }
      ).then(() => {
        this.resetAkun(id);
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
