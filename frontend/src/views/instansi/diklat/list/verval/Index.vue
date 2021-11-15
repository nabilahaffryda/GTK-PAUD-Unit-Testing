<template>
  <div class="verval">
    <!--daftar-->
    <v-card tile flat class="my-5">
      <v-card-text class="pa-0">
        <v-row no-gutters>
          <v-col cols="2">
            <div class="bg-kiri"></div>
          </v-col>
          <v-col cols="10" class="pa-5">
            <h1 class="headline black--text--text"> Verval Kelas Diklat </h1>
            <div> </div>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <v-card flat>
      <v-card-text>
        <base-table-header
          btn-filter
          :btnDownload="$allow(`${jenis}-verval.download`)"
          @download="onDownload"
          @filter="onFilter"
          @search="onSearch"
          @reload="onReload"
        >
          <template v-slot:subtitle>
            <div class="body-1 black--text">
              Daftar Ajuan Verval <b>{{ total }}</b>
            </div>
          </template>
        </base-table-header>
        <v-divider />
        <base-list-filter
          ref="filter"
          title="Pilih Filter Status"
          :filtered="filtered"
          :filters="formFilter"
          :paramsFilter="filters"
          @save="filterSave"
        />
        <base-list-table
          :hideHeader="true"
          :loading="loading"
          :item="data"
          :total="total"
          :usePaging="false"
          @fetch="fetchData"
        >
          <template slot-scope="{ item }">
            <td>
              <v-list-item class="px-0">
                <v-list-item-content>
                  <v-row no-gutters>
                    <v-col cols="12" md="4">
                      <v-list-item class="px-0" @click="onVerval(item)">
                        <v-list-item-icon class="mr-1 my-auto">
                          <v-icon color="primary" size="60">mdi-account-circle</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content class="py-0 px-1">
                          <p class="caption"> Nama Kelas </p>
                          <span class="body-1 black--text">
                            {{ $getDeepObj(item, `paud_mapel_kelas.data.nama`) || '-' }} -
                            {{ $getDeepObj(item, `nama`) || '-' }}
                          </span>
                          <div class="font-italic">
                            {{ $getDeepObj(item, `paud_diklat.data.instansi.data.nama`) || '-' }}
                          </div>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col cols="12" md="3">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0">
                          <v-list-item-title>
                            <div class="label--text">Tanggal Pelaksanaan</div>
                          </v-list-item-title>
                          <v-list-item-subtitle class="link black--text body-2">
                            {{
                              $durasi(
                                $getDeepObj(item, 'paud_diklat.data.paud_periode.data.tgl_diklat_mulai'),
                                $getDeepObj(item, 'paud_diklat.data.paud_periode.data.tgl_diklat_selesai')
                              )
                            }}
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0">
                          <v-list-item-title>
                            <div class="label--text">Status</div>
                          </v-list-item-title>
                          <v-list-item-subtitle class="link black--text body-2">
                            <v-chip :color="getColor($getDeepObj(item, 'k_verval_paud'))" dark small>
                              {{ $getDeepObj(item, 'm_verval_paud.data.keterangan') }}
                            </v-chip>
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0">
                          <v-list-item-title>
                            <div class="label--text">Aksi Selanjutnya</div>
                          </v-list-item-title>
                          <v-list-item-subtitle class="link black--text body-2">
                            <v-btn
                              v-if="[2].includes(item.k_verval_paud) && $allow('kelas-verval.update-verval')"
                              color="primary"
                              small
                              block
                              @click="onVerval(item)"
                            >
                              Verval Ajuan
                            </v-btn>
                            <v-btn v-else small data-testid="btn-detail" block color="primary" @click="onVerval(item)">
                              Detail
                            </v-btn>
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                  </v-row>
                </v-list-item-content>
                <v-list-item-action-text>
                  <base-list-action
                    v-if="$allow('kelas-verval.batal-verval') && getKVerval(item) > 2"
                    :data="item"
                    :actions="actions"
                    :allow="allow"
                    @action="onAction"
                  />
                  <template v-else>
                    <v-icon class="mr-3" color="white">mdi-dots-vertical</v-icon>
                  </template>
                </v-list-item-action-text>
              </v-list-item>
            </td>
          </template>
        </base-list-table>
      </v-card-text>
      <v-card-actions>
        <base-table-footer :pageTotal="pageTotal" :allow="!allow" @changePage="onChangePage"></base-table-footer>
      </v-card-actions>
    </v-card>
    <base-modal-full
      ref="modal"
      colorBtn="primary"
      :title="formulir['title']"
      :autoClose="formulir['autoClose']"
      :useSave="formulir['is_edit']"
      fluid
      @close="onClose"
      @save="onSave"
    >
      <form-verval
        ref="formulir"
        :masters="Object.assign({}, masters)"
        :id="formulir['id']"
        :detail="formulir['detail']"
        :berkas="formulir['berkas']"
        :fungsi="formulir['fungsi']"
        :isEdit="formulir['is_edit']"
        :isDisable="formulir['is_disable']"
        :initValue="formulir['init']"
        :jenis="jenis"
        :obj="obj"
      ></form-verval>
    </base-modal-full>
  </div>
</template>

<script>
import actions from './actions';
import { mapActions, mapState } from 'vuex';
import list from '@mixins/list';
import FormVerval from '../../formulir/Verval';
export default {
  name: 'Index',
  mixins: [list],
  data() {
    return {
      formulir: {},
      listTimVerval: [],
      listLaporan: [],
      actions: actions,
    };
  },
  components: { FormVerval },
  computed: {
    ...mapState('master', {
      masters: (state) => Object.assign({}, state.masters),
    }),

    ...mapState('preferensi', {
      akun: (state) => (state.data && state.data.akun) || {},
    }),

    configs() {
      const M_PROPINSI = (this.masters && this.masters['propinsi']) || {};
      const M_KOTA = (this.masters && this.masters['kota']) || {};
      return {
        selector: ['k_propinsi', 'k_kota'],
        required: [false, false],
        label: ['Provinsi', 'Kota/Kabupaten'],
        options: [M_PROPINSI, M_KOTA],
        grid: [{ cols: 12 }, { cols: 12 }],
        disabled: [],
      };
    },

    mapTimVerval() {
      let temp = {};
      for (const akun of this.listTimVerval) {
        temp[this.$getDeepObj(akun, 'akun.data.akun_id')] = this.$getDeepObj(akun, 'akun.data.nama');
      }
      return temp;
    },

    mapStatusVerval() {
      const masters = (this.masters && this.masters.m_verval_paud) || {};
      let temp = {};

      Object.keys(masters).forEach((item) => {
        if (![1, 3].includes(Number(item))) {
          this.$set(temp, item, masters[item]);
        }
      });

      return temp;
    },

    formFilter() {
      let master = Object.assign({}, this.masters[`m_berkas_${this.keyTipe}_paud`]);
      // delete kandidat
      delete master[1];
      return [
        // {
        //   label: 'Pilih Verifikator',
        //   model: 'akun_id',
        //   type: 'select',
        //   props: ['attach'],
        //   master: this.$mapForMaster(this.mapTimVerval),
        //   labelColor: 'secondary',
        //   grid: { cols: 12 },
        // },
        {
          label: 'Pilih Status Verval',
          default: true,
          type: 'checkbox',
          model: 'k_verval_paud',
          master: this.$mapForMaster(this.mapStatusVerval),
          props: ['attach', 'chips', 'deletable-chips', 'multiple', 'small-chips'],
        },
        // {
        //   label: 'Pilih Lokasi',
        //   default: true,
        //   type: 'cascade',
        //   configs: this.configs,
        //   labelColor: 'secondary',
        //   grid: { cols: 12, md: 6 },
        // },
      ];
    },

    filtersMaster() {
      return {
        k_verval_paud: this.mapStatusVerval,
        k_propinsi: this.masters && this.masters['propinsi'],
        k_kota: this.masters && this.masters['kota'],
        akun_id: this.mapTimVerval,
      };
    },

    obj() {
      return this.$route.meta.atribut;
    },

    keyTipe() {
      return this.$route.meta.paudkey;
    },

    jenis() {
      return this.$route.meta.tipe;
    },
  },
  created() {},
  methods: {
    ...mapActions('diklatVerval', ['fetch', 'getDetail', 'action', 'downloadList', 'getKinerja', 'getTimVerval']),
    ...mapActions('master', ['getMasters']),

    allowMenu(data) {
      const akses = this.actions;
      let status = false;

      akses.forEach((item) => {
        if (this.allow(item, data)) {
          status = true;
          return;
        }
      });
      return status;
    },

    allow(action, data) {
      let allow = false;
      const kVerval = this.getKVerval(data);
      switch (action.event) {
        case 'onBatalVerval':
          allow = this.$allow(action.akses) && Number(kVerval) > 3;
          break;
        default:
          allow = this.$allow(action.akses);
          break;
      }
      return allow;
    },

    allowVerval(item) {
      const kVerval = this.getKVerval(item);
      return this.$allow('kelas-verval.update-verval') && kVerval <= 3;
    },

    getKVerval(item) {
      return Number(item.k_verval_paud);
    },

    getAkunVerval(item) {
      return this.jenis === 'petugas'
        ? this.$getDeepObj(item, 'paud_petugas_perans.data.0.akun_verval')
        : this.$getDeepObj(item, 'akun_verval');
    },

    getAkunIdVerval(item) {
      return this.jenis === 'petugas'
        ? Number(this.$getDeepObj(item, 'paud_petugas_perans.data.0.akun_id_verval'))
        : Number(this.$getDeepObj(item, 'akun_id_verval'));
    },

    onClose() {
      if (!this.formulir['autoClose']) {
        const msg = [
          '<h3>Apakah Anda yakin ingin keluar dari halaman Verifikasi Ajuan Kelas ?</h3>',
          'data yang tidak disimpan akan hilang jika Anda keluar dari Halaman ini',
        ].join(' ');
        this.$confirm(msg, 'Peringatan', { tipe: 'error', lblConfirmColor: 'red', lblCancelColor: 'grey' }).then(() => {
          this.$refs.modal.dialog = false;
        });
      }
    },

    onCall(item) {
      const url = `https://wa.me/62${Number(this.$getDeepObj(item, 'no_wa'))}`;
      window.open(url, '_blank');
    },

    isAllowAkses(policies) {
      let enable = false;
      let akses = ['psp-peserta-berkas.setuju', 'psp-peserta-berkas.tolak', 'psp-peserta-berkas.tolak-revisi'];

      akses.forEach((key) => {
        if (this.$allow(key, policies)) {
          enable = true;
          return;
        }
      });

      return enable;
    },

    async onVerval(item) {
      const kVerval = item.k_verval_paud;
      const status = {
        color: this.getColor(kVerval),
        keterangan: this.$getDeepObj(item, 'm_verval_paud.data.keterangan') || '-',
      };

      let init = {};

      init = {
        pilihan: kVerval <= 3 ? null : kVerval === 4 ? 1 : kVerval === 6 ? 2 : 3,
      };

      this.$set(this.formulir, 'form', 'form-verval');
      this.$set(this.formulir, 'title', 'Verval Ajuan');
      this.$set(this.formulir, 'id', item.id);
      this.$set(this.formulir, 'init', null);

      this.getDetail({ id: item.id }).then(({ data }) => {
        this.$set(this.formulir, 'is_disable', !this.allowVerval(item));
        this.$set(this.formulir, 'is_edit', this.allowVerval(item));
        this.$set(this.formulir, 'autoClose', !this.allowVerval(item));

        this.$refs.modal.open();
        this.$nextTick(() => {
          this.$refs.formulir.reset();
          this.$set(this.formulir, 'init', init);
          this.$set(this.formulir, 'detail', data);
          this.$set(this.formulir.detail, 'status', status);
        });
      });
    },

    onSave() {
      const formulir = Object.assign({}, this.$refs.formulir.getValue());
      const status = formulir.pilihan;
      const alasan = formulir.alasan || '-';
      const id = formulir.id;

      if (status) {
        const aksi = status === 1 ? '4' : status === 2 ? '6' : '5';
        const html = `<span class="subtitle-1">Anda Yakin ingin <strong>${
          status === 1 ? 'menolak' : status === 2 ? 'menerima' : 'menyatakan perbaikan'
        }</strong> ajuan verval berikut ?</span>`;

        this.$confirm(html, 'Konfirmasi', { tipe: status === 1 ? 'error' : status === 2 ? 'success' : 'warning' }).then(
          () => {
            this.$refs.modal.loading = true;
            this.action({ id: id, params: { k_verval_paud: aksi, alasan }, type: 'verval' })
              .then(() => {
                this.$success('Ajuan berhasil di verifikasi');
                this.$refs.modal.dialog = false;
                this.onReload();
              })
              .catch(() => {
                this.$refs.modal.loading = false;
              });
          }
        );
        this.$refs.modal.loading = false;
      } else {
        this.$error('Silakan tentukan hasil verifikasi data sebelum simpan');
        this.$refs.modal.loading = false;
      }
    },

    onKunci(data) {
      this.id = data.id;
      this.$confirm(`Anda yakin ingin mengunci Ajuan di atas agar bisa diverifikasi?`, `Kunci Ajuan`, {
        tipe: 'warning',
        data: this.confirmHtml(data),
      }).then(() => {
        this.action({ id: this.id, jenis: this.jenis, type: 'kunci', method: 'get' }).then(() => {
          this.$success(`Ajuan Peserta berhasil dikunci`);
          this.onReload();
        });
      });
    },

    onBatalKunci(data) {
      this.$confirm(`Anda yakin ingin membatalkan Kunci Verifikasi Ajuan Peserta di atas?`, `Batal Kunci Ajuan`, {
        tipe: 'error',
        data: this.confirmHtml(data),
      }).then(() => {
        this.action({ id: data.id, jenis: this.jenis, type: 'batal-kunci', method: 'get' }).then(() => {
          this.$success(`Batal kunci Ajuan Peserta berhasil`);
          this.onReload();
        });
      });
    },

    onBatalVerval(data) {
      this.$confirm(`Anda yakin ingin membatalkan VerVal Ajuan di atas?`, `Batal Verval`, {
        tipe: 'error',
        data: this.confirmHtml(data),
      }).then(() => {
        this.action({ id: data.id, type: 'batal-verval', method: 'post' }).then(() => {
          this.$success(`Batal Verval Ajuan Kelas Diklat berhasil`);
          this.onReload();
        });
      });
    },

    confirmHtml(data) {
      return [
        {
          icon: 'mdi-account-circle',
          iconSize: 50,
          iconColor: 'primary',
          title: `<b class='primary--text'>${this.$getDeepObj(data, `paud_mapel_kelas.data.nama`) || ''}</b>`,
          subtitles: [`<span class="text--primary">${this.$getDeepObj(data, `nama`)}</span>`],
        },
      ];
    },

    async onKinerja() {
      const params = {
        angkatan: this.params.angkatan,
        count: 100,
      };

      const resp = await this.getKinerja({ params });
      const nilais = (resp && resp.data) || [];
      const statistik = this.statistik || {};
      const limit = Number(this.$getDeepObj(resp, 'meta.total')) || 0;

      const items = nilais.map((key, index) => {
        return {
          no: index + 1,
          nama: key.nama,
          diproses: key.diproses,
          disetujui: key.disetujui,
          revisi: key.revisi,
          ditolak: key.ditolak,
          jumlah: Number(key.diproses) + Number(key.disetujui) + Number(key.revisi) + Number(key.ditolak),
        };
      });

      const headers = [
        {
          text: 'NO',
          align: 'start',
          sortable: false,
          value: 'no',
        },
        { text: 'Tim Verval', value: 'nama' },
        { text: 'Diproses', value: 'diproses' },
        { text: 'Disetujui', value: 'disetujui' },
        { text: 'Revisi', value: 'revisi' },
        { text: 'Ditolak', value: 'ditolak' },
        { text: 'Jumlah', value: 'jumlah' },
      ];

      this.$refs.kinerja.open();

      this.$nextTick(() => {
        this.$refs.formkinerja.reset();
        this.$refs.formkinerja.headers = headers;
        this.$refs.formkinerja.data = (resp && resp.nilais) || [];
        this.$refs.formkinerja.items = items || [];
        this.$refs.formkinerja.kinerja = (resp && resp.kinerja) || null;
        this.$refs.formkinerja.statistik = statistik;
        this.$refs.formkinerja.limit = limit;
      });
    },

    onDownload() {
      const M_LAPORAN = [
        {
          key: 'download',
          label: `Daftar Verval`,
          acl: `${this.jenis}-verval.download`,
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
                h('option', { attrs: { value: '' } }, '-- Pilih Jenis Unduhan --'),
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
        this.downloadList({ params, url: url.dokumen, jenis: this.jenis, tipe: this.akses }).then((url) => {
          this.$downloadFile(url);
        });
      });
    },

    onUnduhKinerja() {
      const params = {};
      this.unduhKinerja(params).then((url) => {
        window.open(url, '_blank');
      });
    },
  },
};
</script>

<style scoped>
.bg-kiri {
  background: #ffab91;
  height: 100%;
}
.sc-notif {
  background-color: #c8e6c9;
}
</style>
