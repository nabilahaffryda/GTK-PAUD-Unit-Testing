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
                <div class="text-h6">
                  Daftar Peserta Diklat | {{ $getDeepObj(detail, 'paud_diklat_luring.data.nama') }} -
                  {{ $getDeepObj(detail, 'nama') }}
                </div>
                <div class="body-2">
                  Dibawah ini merupakan daftar peserta yang mengikuti diklat, Silakan dapat dilakukan penilaian pada
                  tahap pendalaman materi bagi peserta
                </div>
              </div>
              <div>
                <v-chip v-if="is_ppm" class="pa-4" color="black" dark>PPM</v-chip>
                <v-chip v-if="is_pptm" class="pa-4" color="black" dark>PPTM</v-chip>
              </div>
            </div>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <v-card>
      <v-card-title class="pa-0">
        <base-table-header @search="onSearch" :btnFilter="false" @reload="onReload" @filter="onFilter">
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
                    <v-col class="py-0" cols="12" :md="use_action ? 2 : 4">
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
                    <v-col class="py-0" cols="12" :md="use_action ? 2 : 4">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0 mt-3">
                          <div class="label--text">Status Nilai</div>
                          <div>
                            {{ getNilai(item) ? 'Sudah dinilai' : 'Belum Dinilai' }}
                          </div>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <template v-if="use_action">
                      <v-col class="py-0" cols="12" md="2">
                        <v-list-item class="px-0">
                          <v-list-item-content class="py-0 mt-3">
                            <div class="label--text">Hasil Nilai</div>
                            <div>
                              {{ getNilai(item) || '-' }}
                            </div>
                          </v-list-item-content>
                        </v-list-item>
                      </v-col>
                      <v-col class="py-0" cols="12" md="2">
                        <v-list-item class="px-0">
                          <v-list-item-content class="py-0 mt-3">
                            <div class="label--text">Aksi Selanjutnya</div>

                            <v-btn
                              v-if="!getNilai(item) && $allow('petugas-luring-nilai.save')"
                              small
                              color="success"
                              @click="onNilai(item)"
                              >Mulai Nilai</v-btn
                            >
                            <v-btn v-else dark small color="grey" @click="onNilai(item)"> Lihat Detail </v-btn>
                          </v-list-item-content>
                        </v-list-item>
                      </v-col>
                    </template>
                  </v-row>
                </v-list-item-content>
                <v-list-item-action-text>
                  <template v-if="getNilai(item) && $allow('petugas-luring-nilai.delete') && use_action">
                    <base-list-action :data="item" :actions="actions" :allow="allow" @action="onAction" />
                  </template>
                  <template v-else>
                    <v-icon class="ml-2" color="white">mdi-dots-vertical</v-icon>
                  </template>
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
      :use-save="formulir.isEdit"
      :title="formulir.title"
      :autoClose="false"
      @close="onClose"
      @save="onSave"
    >
      <component
        ref="formulir"
        :is="'FormNilai'"
        :kelas="detail"
        :isEdit="formulir.isEdit"
        :initValue="formulir.initValue"
      />
    </base-modal-full>
  </div>
</template>
<script>
import { mapActions, mapState } from 'vuex';
import BaseBreadcrumbs from '@components/base/BaseBreadcrumbs';
import FormNilai from './formulir/Form';
import list from '@mixins/list';
export default {
  mixins: [list],
  components: { BaseBreadcrumbs, FormNilai },
  data() {
    return {
      formulir: {},
      detail: {},
      is_ppm: false,
      is_pptm: false,
      use_action: false,
      actions: [{ icon: 'mdi-close', title: 'Batal Nilai', event: 'onBatalNilai', akses: true }],
    };
  },
  computed: {
    ...mapState('master', {
      masters: (state) => Object.assign({}, state.masters),
    }),

    breadcrumbs() {
      return [
        { text: 'Kelas Diklat', to: `kelas-luring` },
        {
          text: `${this.$getDeepObj(this, 'detail.paud_diklat_luring.data.nama')} - ${this.$getDeepObj(
            this,
            'detail.nama'
          )}`,
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
    Object.assign(this.attr, { id: this.id });
  },
  created() {
    this.getKelasDiklat();
  },
  methods: {
    ...mapActions('master', ['getMasters']),
    ...mapActions('petugasKelas', {
      fetch: 'fetchPeserta',
      getDetail: 'getDetailLuring',
      getPeserta: 'getDetailPesertaNilai',
      action: 'action',
    }),

    fetchData: function () {
      return new Promise((resolve) => {
        const params = Object.assign({}, this.params, this.$isObject(this.filters) ? { filter: this.filters } : {});
        const attr = Object.assign({}, this.attr);
        this.fetch({ params, attr }).then(({ data, meta }) => {
          this.data = data || [];
          this.total = meta?.total || data.length || 0;
          this.pageTotal = meta?.last_page || 1;
          this.is_ppm = meta?.is_ppm ?? false;
          this.is_pptm = meta?.is_pptm ?? false;
          this.use_action = this.is_ppm || this.is_pptm;
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
      return this.use_action
        ? this.$getDeepObj(item, `${this.is_ppm ? 'n_pendalaman_materi' : 'n_tugas_mandiri'}`) || null
        : this.$getDeepObj(item, 'n_pendalaman_materi') || this.$getDeepObj(item, 'n_tugas_mandiri') || null;
    },

    async onNilai(item) {
      const resp = await this.getPeserta({ kelas_id: this.id, id: item.id }).then(({ data }) => data);
      this.$set(this.formulir, 'title', 'Penilaian Peserta');
      this.$set(this.formulir, 'isEdit', !this.getNilai(item) && this.$allow('petugas-luring-nilai.save'));
      this.$refs.modal.open();

      this.$nextTick(() => {
        // this.$refs.formulir.reset();
        this.$set(
          this.formulir,
          'initValue',
          Object.assign({
            peserta: Object.assign(item, { is_nilai: this.getNilai(item) }),
            instruments: resp,
            is_pptm: this.is_pptm,
          })
        );
      });
    },

    onSave() {
      const formulir = this.$refs.formulir.getValue();

      this.action({
        kelas_id: this.id,
        id: this.$getDeepObj(this.formulir, 'initValue.peserta.id'),
        name: 'save',
        params: { nilai: formulir },
      })
        .then(() => {
          this.$success('Penilaian berhasil dijalankan');
          this.onReload();
          this.$refs.modal.dialog = false;
        })
        .catch(() => {
          this.$refs.modal.loading = false;
        });
    },

    onBatalNilai(item) {
      this.$confirm(`Apakan anda ingin membatalkan penilaian berikut ?`, `Batalkan Penilaian`, {
        tipe: 'warning',
      }).then(() => {
        this.action({
          kelas_id: this.id,
          id: item.id,
          name: 'delete',
        }).then(() => {
          this.$success(`Penilaian berhasil dibatalkan`);
          this.onReload();
          this.$refs.modal.dialog = false;
        });
      });
    },

    onClose() {
      if (this.formulir.isEdit) {
        const msg = [
          '<h3>Apakah Anda yakin ingin keluar dari halaman Penilaian Peserta ?</h3>',
          'data yang tidak disimpan akan hilang jika Anda keluar dari Halaman ini',
        ].join(' ');
        this.$confirm(msg, 'Peringatan', { tipe: 'warning', lblConfirmColor: 'red', lblCancelColor: 'grey' }).then(
          () => {
            this.$refs.modal.dialog = false;
          }
        );
      } else {
        this.$refs.modal.dialog = false;
      }
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
