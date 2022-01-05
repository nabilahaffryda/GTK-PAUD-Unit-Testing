<template>
  <div>
    <v-card tile flat class="pa-0">
      <v-card-text class="pa-0">
        <base-breadcrumbs :items="breadcrumbs" />
      </v-card-text>
    </v-card>
    <v-card tile flat class="my-5">
      <v-card-text class="pa-0">
        <v-row no-gutters>
          <v-col cols="2">
            <div class="bg-kiri"></div>
          </v-col>
          <v-col cols="10" class="pa-5 black--text">
            <div class="d-flex px-2">
              <div class="mr-5">
                <h2 class="text-h6">Laporan Pelaksanaan Diklat Luring</h2>
                <div class="body-2 my-2">
                  Silakan unggah Laporan dengan menekan tombol <b>Unggah Laporan</b>. Template laporan dapat diakses
                  pada link di bawah atau dapat diunduh
                  <a>disini</a>
                </div>
              </div>
              <div>
                <v-chip class="pa-4" :color="$getDeepObj(detail, 'url_laporan') ? 'success' : 'grey'">
                  {{ $getDeepObj(detail, 'url_laporan') ? 'Sudah Unggah' : 'Belum Unggah' }}
                </v-chip>
              </div>
            </div>
            <v-divider class="my-4"></v-divider>
            <div class="d-flex px-2" style="justify-content: space-between">
              <div class="mr-5">
                <v-btn color="info" depressed small block @click="onUploadLaporan(detail)">
                  <v-icon left> mdi-upload </v-icon>
                  Unggah Laporan
                </v-btn>
              </div>
              <div>
                <v-icon small color="info">mdi-file</v-icon>
                <a>Template Laporan Kelas</a>
              </div>
            </div>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <v-card>
      <v-card-title class="pa-0">
        <base-table-header
          @search="onSearch"
          :btnFilter="false"
          :btnDownload="true"
          @reload="onReload"
          @filter="onFilter"
          @download="onDownload"
        >
          <template v-slot:subtitle>
            <div class="subtitle-1 black--text">
              <b>{{ total }}</b> Kelas Diklat
            </div>
          </template>
        </base-table-header>
      </v-card-title>
      <v-divider />
      <base-list-filter
        ref="filter"
        title="Pilih Filter Status"
        :filtered="filtered"
        :filters="formFilter"
        :paramsFilter="filters"
        @save="filterSave"
      />
      <v-card-text class="pa-0">
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
              <v-list-item dense class="px-0">
                <v-list-item-content>
                  <v-row>
                    <v-col class="py-2" cols="12" md="4">
                      <v-list-item class="px-0">
                        <v-list-item-avatar color="primary">
                          <v-icon dark>mdi-account</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content class="py-0 mt-3">
                          <div class="label--text">Nama Peserta</div>
                          <div class="body-2 black--text">
                            {{
                              $getDeepObj(item, 'ptk.data.nama') ||
                              $getDeepObj(item, 'paud_peserta_nonptk.data.nama') ||
                              ''
                            }}
                          </div>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col class="py-0" cols="12" md="3">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0 mt-3">
                          <div class="label--text">Surel</div>
                          <div class="black--text">
                            {{
                              $getDeepObj(item, 'ptk.data.email') ||
                              $getDeepObj(item, 'paud_peserta_nonptk.data.email') ||
                              ''
                            }}
                          </div>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col class="py-0" cols="12" md="3">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0 mt-3">
                          <div class="label--text">Status Nilai</div>
                          <div>
                            {{ getNilai(item) ? 'Sudah dinilai' : 'Belum Dinilai' }}
                          </div>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col class="py-0" cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0 mt-3">
                          <div class="label--text">Hasil Nilai</div>
                          <div>
                            {{ item.predikat || '-' }}
                          </div>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                  </v-row>
                </v-list-item-content>
                <v-list-item-action-text>
                  <base-list-action :data="item" :actions="actions" :allow="allow" @action="onAction" />
                </v-list-item-action-text>
              </v-list-item>
            </td>
          </template>
        </base-list-table>
      </v-card-text>
      <v-card-actions>
        <base-table-footer :pageTotal="pageTotal" @changePage="onChangePage"></base-table-footer>
      </v-card-actions>
    </v-card>
    <base-modal-full
      ref="modal"
      colorBtn="primary"
      generalError
      :title="formulir.title"
      :useSave="formulir.useSave"
      @save="onSave"
    >
      <component
        ref="formulir"
        :is="formulir.form"
        :type="formulir.type"
        :detail="detail"
        :masters="masters"
        :initValue="formulir.initValue"
        :jenis="jenis"
        @unduhTemplate="unduhTemplate"
      ></component>
    </base-modal-full>
  </div>
</template>
<script>
import { mapActions, mapState } from 'vuex';
import BaseBreadcrumbs from '@components/base/BaseBreadcrumbs';
import FormUpload from './formulir/UploadLaporan';
import DetailNilai from './formulir/DetailNilai';
import list from '@mixins/list';
import mixin from './list/mixin';
export default {
  mixins: [list, mixin],
  components: { BaseBreadcrumbs, DetailNilai, FormUpload },
  data() {
    return {
      formulir: {},
      detail: {},
      is_ppm: false,
      is_pptm: false,
      use_action: false,
      actions: [{ icon: 'mdi-file-eye', title: 'Lihat Nilai', event: 'onViewNilai', akses: true }],
    };
  },
  computed: {
    ...mapState('master', {
      masters: (state) => Object.assign({}, state.masters),
    }),

    jenis() {
      return this.$route.meta.jenis;
    },

    breadcrumbs() {
      return [
        { text: 'Kelas Diklat', to: `kelas-luring` },
        {
          text: `
           ${this.$getDeepObj(this.detail, 'paud_diklat_luring.data.nama')} -
           ${this.$getDeepObj(this.detail, 'nama')}`,
        },
      ];
    },

    id() {
      return this.$route.params.kelas_id || null;
    },

    configs() {
      const M_PROPINSI = this.masters.m_propinsi || {};
      const M_KOTA = this.masters.m_kota || {};
      return {
        selector: ['k_propinsi', 'k_kota'],
        required: [false, false],
        label: ['Provinsi', 'Kota/Kabupaten'],
        options: [M_PROPINSI, M_KOTA],
        grid: [{ cols: 12 }, { cols: 12 }],
        disabled: [],
      };
    },

    formFilter() {
      return [
        {
          label: 'Lokasi',
          default: true,
          type: 'cascade',
          configs: this.configs,
          labelColor: 'secondary',
          grid: { cols: 12, md: 6 },
        },
      ];
    },

    filtersMaster() {
      return {
        k_propinsi: this.masters && this.masters.m_propinsi,
        k_kota: this.masters && this.masters.m_kota,
      };
    },
  },
  mounted() {
    Object.assign(this.attr, { id: this.id, jenis: this.jenis });
  },
  created() {
    this.getKelasDiklat();
  },
  methods: {
    ...mapActions('master', ['getMasters']),
    ...mapActions('petugasKelas', {
      fetch: 'getListPesertaLaporan',
      getDetail: 'getDetailLuring',
      getPeserta: 'getDetailPesertaNilai',
      action: 'LaporanAction',
    }),

    onDownload() {
      const M_LAPORAN = [
        {
          key: 'download',
          label: `Laporan Pelaksanaan Diklat Luring`,
          acl: this.$getDeepObj(this.detail, 'url_laporan') ? true : false,
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
        this.$downloadFile(this.$getDeepObj(this.detail, 'url_laporan'));
      });
    },

    fetchData: function () {
      return new Promise((resolve) => {
        const params = Object.assign({}, this.params, this.$isObject(this.filters) ? { filter: this.filters } : {});
        const attr = Object.assign({}, this.attr);
        this.fetch({ id: this.id, jenis: this.jenis, params, attr }).then(({ data, meta, is_ppm, is_pptm }) => {
          this.data = data || [];
          this.total = meta?.total || data.length || 0;
          this.pageTotal = meta?.last_page || 1;
          this.is_ppm = is_ppm;
          this.is_pptm = is_pptm;
          this.use_action = is_ppm || is_pptm;
          resolve(true);
        });
      });
    },

    async getKelasDiklat() {
      if (!this.id) return;
      const data = await this.getDetail({ id: this.id }).then(({ data }) => data);
      this.detail = data || {};
    },

    allow(action, data) {
      let disabled = false;
      switch (action.event) {
        case 'onAktif':
          disabled = !Number(this.$getDeepObj(data, 'akun.is_aktif') || 0);
          break;
        case 'onNonAktif':
          disabled = Number(this.$getDeepObj(data, 'akun.is_aktif') || 0);
          break;
        default:
          disabled = this.$allow(action.akses, data.policies);
          break;
      }
      return disabled;
    },

    getNilai(item) {
      return this.$getDeepObj(item, 'n_pendalaman_materi') || this.$getDeepObj(item, 'n_tugas_mandiri') || null;
    },

    async onViewNilai(item) {
      const resp = await this.getPeserta({ kelas_id: this.id, id: item.id, page: 'laporan' }).then(({ data }) => data);
      this.$set(this.formulir, 'title', 'Penilaian Peserta');
      this.$set(this.formulir, 'form', 'DetailNilai');
      this.$set(this.formulir, 'useSave', false);
      this.$refs.modal.open();

      this.$nextTick(() => {
        // this.$refs.formulir.reset();
        this.$set(
          this.formulir,
          'initValue',
          Object.assign({
            peserta: Object.assign(item, { is_nilai: this.getNilai(item) }),
            instruments: resp,
          })
        );
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
