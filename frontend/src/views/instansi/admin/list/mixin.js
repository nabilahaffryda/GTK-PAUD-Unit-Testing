import { mapActions } from 'vuex';

export default {
  methods: {
    ...mapActions('admin', [
      'fetch',
      'create',
      'update',
      'listGroups',
      'listInstansis',
      'action',
      'lookup',
      'getDetail',
      'downloadList',
    ]),

    ...mapActions('master', ['getMasters']),

    async getGroups() {
      const groups = await this.listGroups();
      const temp = {};
      for (const item of groups) {
        temp[item.k_group] = item.keterangan;
      }
      this.groups = temp;
    },

    onAdd() {
      this.$set(this.formulir, 'title', 'Tambah Data');
      this.$set(this.formulir, 'isChecked', false);
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

    onCheck(email) {
      this.$set(this.formulir, 'errorEmail', null);
      this.lookup(email)
        .then((resp) => {
          this.$set(this.formulir, 'isChecked', true);
          this.$set(this.formulir, 'init', this.$isObject(resp) ? resp : { email });
          this.$set(this.formulir, 'isExist', this.$isObject(resp));
        })
        .catch((resp) => {
          this.$set(this.formulir, 'isChecked', false);
          this.$set(this.formulir, 'init', {});
          this.$set(this.formulir, 'isExist', false);
          this.$set(this.formulir, 'errorEmail', resp.error || 'Pastikan anda memasukan data email yang Valid');
        });
    },

    onUncheck() {
      this.$set(this.formulir, 'isChecked', false);
      this.$set(this.formulir, 'init', {});
      this.$set(this.formulir, 'isExist', false);
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
      const params = Object.assign({}, this.$refs.formulir.getValue(), { k_group: this.kGroup });

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

    unduhTokenTemplate() {
      this.downloadList({ url: 'download-aktivasi' }).then((url) => {
        this.$downloadFile(url);
      });
    },

    onDownload() {
      const M_LAPORAN = [
        {
          key: 'download',
          label: `Daftar Admin`,
          acl: this.$allow('psp-admin.download'),
        },
        {
          key: 'download-aktivasi',
          label: `Daftar Aktivasi Admin`,
          acl: this.$allow('psp-admin.download-aktivasi'),
        },
      ];

      let url = {};
      this.$confirm('Pilih jenis Berkas yang ingin di Unduh?', 'Unduh Berkas', {
        tipe: 'warning',
        form: {
          desc: 'Laporan Berkas',
          render: (h) => {
            return h(
              'select',
              {
                class: 'custom-select',
                domProps: {
                  value: '',
                },
                on: {
                  input: function(event) {
                    url.dokumen = event.target.value;
                  },
                },
              },
              [
                h('option', { attrs: { value: '' } }, '-- Pilih Laporan Berkas --'),
                M_LAPORAN.filter((laporan) => this.$allow(laporan.acl)).map((item) =>
                  h('option', { attrs: { value: item.key } }, item.label)
                ),
              ]
            );
          },
        },
        lblConfirmButton: 'Unduh',
      }).then(() => {
        if (!url.dokumen) {
          this.$error('Silakan pilih laporan yang ingin diunduh!');
          return;
        }

        const params = Object.assign(this.params, this.$isObject(this.filters) ? this.filters : {});
        this.downloadList({ params, url: url.dokumen }).then((url) => {
          this.$downloadFile(url);
        });
      });
    },

    onValidate() {
      this.$refs.modal.onValidate().then((valid) => {
        if (valid) this.$refs.formulir.next(valid);
      });
    },

    getInstansi() {
      let mInstansi = {};
      this.listInstansis()
        .then((resp) => {
          const instansi = resp || [];
          instansi.forEach((item) => {
            this.$set(mInstansi, this.$getDeepObj(item, 'instansi_id'), this.$getDeepObj(item, 'instansi.data.nama'));
          });
        })
        .then(() => {
          this.$set(this, 'instansis', mInstansi);
        });
    },
  },
};
