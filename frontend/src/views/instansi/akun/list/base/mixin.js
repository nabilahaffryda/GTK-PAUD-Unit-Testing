import { mapActions } from 'vuex';

export default {
  data() {
    return {
      paramsInstasi: {},
      cacheInstansi: {},
    };
  },
  methods: {
    ...mapActions('akun', [
      'fetch',
      'create',
      'update',
      'listGroups',
      'action',
      'lookup',
      'getDetail',
      'downloadList',
      'templateUpload',
      'upload',
      'setInti',
    ]),

    ...mapActions('institusi', { listInstansis: 'fetch' }),

    ...mapActions('master', ['getMasters']),

    async getGroups() {
      const groups = await this.listGroups();
      const temp = {};
      for (const item of groups) {
        temp[item.k_group] = item.keterangan;
      }
      this.groups = temp;
    },

    onDetail(data) {
      this.$set(this.formulir, 'title', `Detail ${this.title}`);
      this.$set(this.formulir, 'detail', data);
      this.$set(this.formulir, 'component', 'DetailView');
      this.$set(this.formulir, 'isValid', false);
      this.$refs.modal.open();
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
            instansi: item.instansi,
          })
        );
      });
    },

    onCheck(email) {
      this.$set(this.formulir, 'errorEmail', null);
      if (email && !this.validateEmail(email)) {
        this.$set(this.formulir, 'errorEmail', 'Pastikan Anda memasukan data email yang Valid');
        return;
      }

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

    validateEmail(email) {
      const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    },

    onValidate() {
      this.$refs.modal.onValidate().then((valid) => {
        this.$set(this.formulir, 'isValid', valid);
        this.$set(
          this.formulir,
          'lblBtn',
          this.$refs.formulir.isPilih === 'excel' ? 'Unggah Berkas' : 'Simpan & Cetak'
        );
        if (valid) this.$refs.formulir.next(valid);
      });
    },

    onSave() {
      const isEdit = this.formulir.isEdit;
      const id = this.$refs.formulir.id;
      const params = Object.assign({}, this.$refs.formulir.getValue(), { k_group: +this.kGroup });
      const name = this.$getDeepObj(this.attr, 'tipe');
      const tipe = this.$refs.formulir.isPilih || '';
      const file = this.$refs.formulir.file || {};

      if (name === 'pengajar-tambahan') {
        Object.assign(params, { k_unsur_pengajar_paud: this.params['k_unsur_pengajar_paud'] });
        Object.assign(file, { k_unsur_pengajar_paud: this.params['k_unsur_pengajar_paud'] });
      }

      if (tipe === 'excel') {
        this.uploadSave(file);
        return;
      }

      this[isEdit ? 'update' : 'create']({ params, id, name })
        .then(({ data }) => {
          this.$success(`Data ${this.title} berhasil di ${isEdit ? 'diubah' : 'ditambahkan'}`);
          this.$refs.modal.close();
          this.fetchData();

          if (!isEdit) {
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
        this.$set(this.akun, 'password', !this.formulir.isExist ? this.$getAttr(ptk, 'passwd') : '*********');
        this.$set(this.akun, 'peran', (this.$getAttr(group, 'keterangan') || '-').toUpperCase());
        this.$nextTick(() => {
          this.$refs.akun.print();
        });
      });
    },

    onNonAktif(item) {
      this.$confirm(`Apakan anda ingin menon-aktifkan ${this.title} berikut ?`, `Non-Aktifkan ${this.title}`, {
        tipe: 'error',
        data: this.confirmHtml(item),
      }).then(() => {
        this.action({ id: item.paud_admin_id, type: 'non-aktif', name: this.attr.tipe }).then(() => {
          this.$success(`Akun ${this.title} berhasil dinon-aktifkan`);
          this.fetchData();
        });
      });
    },

    onAktif(item) {
      this.$confirm(`Apakan anda ingin mengaktifkan ${this.title} berikut ?`, `Aktifkan ${this.title}`, {
        tipe: 'error',
        data: this.confirmHtml(item),
      }).then(() => {
        this.action({ id: item.paud_admin_id, type: 'aktif', name: this.attr.tipe }).then(() => {
          this.$success(`Akun ${this.title} berhasil diaktifkan`);
          this.fetchData();
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
          label: `Daftar ${this.$titleCase(this.jenis)}`,
          acl: this.$allow(`akun-${this.akses}.download`),
        },
        {
          key: 'download-aktivasi',
          label: `Daftar Aktivasi ${this.$titleCase(this.jenis)}`,
          acl: this.$allow(`akun-${this.akses}.download-aktivasi`),
        },
      ];

      let url = {};
      this.$confirm('Pilih jenis Berkas yang ingin di Unduh?', 'Unduh Berkas', {
        tipe: 'secondary',
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
                  input: function (event) {
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

    getInstansi(val) {
      let mInstansi = {};
      if (this.cacheInstansi && this.cacheInstansi[val] && Object.keys(this.cacheInstansi[val] || {}).length) {
        this.$set(this, 'instansis', this.cacheInstansi[val]);
      } else {
        this.paramsInstasi = Object.assign({}, { page: 1 }, { filter: { keyword: val } });
        this.listInstansis({ params: this.paramsInstasi })
          .then(({ data }) => {
            const instansi = data || [];
            instansi.forEach((item) => {
              if (+item.is_aktif === 1) {
                this.$set(
                  mInstansi,
                  this.$getDeepObj(item, 'instansi_id'),
                  this.$getDeepObj(item, 'instansi.data.nama')
                );
              }
            });
          })
          .then(() => {
            const keyword = this.$getDeepObj(this.paramsInstasi, 'filter.keyword');
            this.cacheInstansi[keyword] = mInstansi;
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

    setAkunInti(item) {
      const id = item.akun_id;
      const params = {
        akun_ids: [id],
      };

      this.$confirm(
        `Anda yakin ingin menjadikan Pembimbing Praktik Inti atas nama <strong>${
          item.akun?.data?.nama ?? ''
        }</strong> ?`,
        'Set Pembimbing Praktik Inti',
        {
          tipe: 'warning',
        }
      ).then(() => {
        this.setInti({ name: this.attr.tipe, params }).then(() => {
          this.$success(`${this.title} berhasil`);
          this.fetchData();
        });
      });
    },

    setMultiInti() {
      this.$set(this.selector, 'title', 'Set Peran Pembimbing Praktik');
      this.$set(this.selector, 'valueId', 'akun_id');
      this.$set(this.selector, 'fetch', this.fetch);
      this.$set(this.selector, 'filters', {
        is_inti: 0,
        is_aktif: 1,
      });
      this.$set(this.selector, 'attr', this.attr);

      this.$nextTick(() => {
        this.$refs.selector.open();
      });
    },

    onSaveInti(akunIds) {
      const params = {
        akun_ids: akunIds,
      };
      this.$confirm(`Anda yakin ingin menjadikan Pembimbing Praktik Inti?`, 'Set Pembimbing Praktik Inti', {
        tipe: 'warning',
      }).then(() => {
        this.setInti({ name: this.attr.tipe, params }).then(() => {
          this.$refs.selector.close();
          this.$success(`${this.title} berhasil`);
          this.fetchData();
        });
      });
    },

    onResetInti(item) {
      const id = item.paud_admin_id;
      this.$confirm(
        `Anda yakin ingin membatalkan Pembimbing Praktik Inti atas nama <strong>${
          item.akun?.data?.nama ?? ''
        }</strong> ?`,
        'Reset Pembimbing Praktik Inti',
        {
          tipe: 'error',
        }
      ).then(() => {
        this.action({ id, type: 'reset-inti', name: this.attr.tipe }).then(() => {
          this.$success(`${this.title} berhasil`);
          this.fetchData();
        });
      });
    },
  },
};
