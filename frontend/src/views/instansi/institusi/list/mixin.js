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
      this.id = item.id;
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
            ratio_pengajar_tambahan: item.ratio_pengajar_tambahan,
            jml_pembimbing: item.jml_pembimbing,
          })
        );
      });
    },

    onDelete(item) {
      this.$confirm('Apakan anda ingin menghapus Institusi berikut ?', 'Hapus Institusi', { tipe: 'error' }).then(
        () => {
          this.action({ id: item.akun_instansi_id, type: 'delete' }).then(() => {
            this.$success('Institusi berhasil di hapus');
            this.fetchData();
          });
        }
      );
    },

    onAktif(item) {
      this.$confirm('Apakan anda ingin aktifkan intitusi berikut ?', 'Aktifkan Institusi', { tipe: 'info' }).then(
        () => {
          this.action({ id: `profil/${item.paud_instansi_id}`, type: 'set-aktif', params: { enable: 1 } }).then(() => {
            this.$success('Institusi berhasil di aktifkan');
            this.fetchData();
          });
        }
      );
    },

    onNonAktif(item) {
      this.$confirm('Apakan anda ingin non-aktifkan intitusi berikut ?', 'Non-aktifkan Institusi', {
        tipe: 'warning',
      }).then(() => {
        this.action({ id: `profil/${item.paud_instansi_id}`, type: 'set-aktif', params: { enable: 0 } }).then(() => {
          this.$success('Institusi berhasil di non-aktifkan');
          this.fetchData();
        });
      });
    },

    onDownload() {
      const M_LAPORAN = [
        {
          key: 'download',
          label: `Daftar Instansi LPD`,
          acl: this.$allow(`lpd.download`),
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

    onSave() {
      const isEdit = this.formulir.isEdit;
      const id = this.id;
      const params = Object.assign({}, this.$refs.formulir.getValue());

      this[isEdit ? 'update' : 'create']({ params, id })
        .then(() => {
          this.$success(`Data Institusi berhasil ${isEdit ? 'diubah' : 'ditambahkan'}`);
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
