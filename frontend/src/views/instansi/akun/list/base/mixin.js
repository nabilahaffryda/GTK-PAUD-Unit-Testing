import { mapActions } from 'vuex';

export default {
  methods: {
    ...mapActions('akun', [
      'fetch',
      'create',
      'update',
      'listGroups',
      'listInstansis',
      'action',
      'lookup',
      'getDetail',
      'downloadList',
      'templateUpload',
      'upload',
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
      this.$set(this.formulir, 'isEdit', false);
      this.$set(this.formulir, 'isValid', false);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
      });
    },

    async onEdit(item) {
      this.$set(this.formulir, 'isEdit', true);
      this.$set(this.formulir, 'isValid', false);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
        this.$set(this.formulir, 'title', 'Ubah Data');
        // langsung ke step 2
        this.$refs.formulir.step = 2;
        this.$set(
          this.formulir,
          'init',
          Object.assign({}, item?.akun?.data ?? {}, {
            paud_admin_id: item.paud_admin_id,
            k_group: this.$getDeepObj(item, 'akun.data.k_golongan'),
            instansi_id: item.instansi_id,
          })
        );
      });
    },

    onCheck(email) {
      this.$set(this.formulir, 'errorEmail', null);
      this.lookup(email)
        .then((resp) => {
          this.$set(this.formulir, 'init', this.$isObject(resp) ? resp : { email });
          this.$set(this.formulir, 'isExist', this.$isObject(resp));
          this.$refs.formulir.step++;
        })
        .catch((resp) => {
          this.$set(this.formulir, 'init', {});
          this.$set(this.formulir, 'isExist', false);
          this.$set(this.formulir, 'errorEmail', resp.error || 'Pastikan anda memasukan data email yang Valid');
        });
    },

    onValidate() {
      this.$refs.modal.onValidate().then((valid) => {
        this.$set(this.formulir, 'isValid', valid);
        if (valid) this.$refs.formulir.next(valid);
      });
    },

    onSave() {
      const isEdit = this.formulir.isEdit;
      const id = this.$refs.formulir.id;
      const params = Object.assign({}, this.$refs.formulir.getValue(), { k_group: +this.kGroup });
      const name = this.$getDeepObj(this, 'attr.tipe');
      const tipe = this.$refs.formulir.isPilih || '';
      const file = this.$refs.formulir.file || {};

      if (tipe === 'excel') {
        this.uploadSave(file);
        return;
      }

      this[isEdit ? 'update' : 'create']({ params, id, name })
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

    resetAkun(id) {
      this.action({ id, type: 'reset', name: this.$getDeepObj(this, 'attr.tipe') }).then(({ data, included }) => {
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

    onDelete(item) {
      this.$confirm(`Apakan anda ingin menghapus ${this.title} berikut ?`, `Hapus ${this.title}`, {
        tipe: 'error',
        data: this.confirmHtml(item),
      }).then(() => {
        this.action({ id: item.paud_admin_id, type: 'delete', name: this.attr.tipe }).then(() => {
          this.$success(`Akun ${this.title} berhasil dihapus`);
          this.fetchData();
        });
      });
    },

    onReset(item) {
      const id = item.paud_admin_id;
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
          acl: this.$allow(`akun-${this.akses}.download`),
        },
        {
          key: 'download-aktivasi',
          label: `Daftar Aktivasi Admin`,
          acl: this.$allow(`akun-${this.akses}.download-aktivasi`),
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
        this.downloadList({ params, url: url.dokumen, tipe: this.akses }).then((url) => {
          this.$downloadFile(url);
        });
      });
    },

    getInstansi() {
      let mInstansi = {};
      if ([171].includes(Number(this.kGroup)) && !Object.keys(this.instansis).length) {
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
      }
    },

    confirmHtml(data) {
      return [
        {
          icon: 'mdi-account-circle',
          iconSize: 60,
          iconColor: 'secondary',
          title: `${this.$getDeepObj(data, 'akun.data.nama')}`,
          subtitles: [`<span>Email: ${this.$getDeepObj(data, 'akun.data.email')}</span>`],
        },
      ];
    },

    onUpload() {
      this.$refs.uploader.open();
    },

    setFile(data) {
      this.$refs.formulir.setFile(data);
      this.$refs.uploader.dialog = false;
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
            this.$refs.uploader.step = 1;
            this.$refs.uploader.errorFile.push(...error);
            return;
          }

          this.$refs.uploader.step = 1;
          this.$refs.modal.close();
          this.fetchData();
        })
        .catch(() => {
          this.$refs.modal.loading = false;
          this.$error('Terdapat kesalahan saat mengunggah berkas, silakan periksa berkas Anda kemudian coba kembali!');
        });
    },

    unduhTemplate() {
      this.templateUpload({ tipe: this.akses }).then((url) => {
        this.$downloadFile(url);
      });
    },
  },
};
