<template>
  <div>
    <!--notif jadwal-->
    <v-card tile flat class="my-5">
      <v-card-text class="pa-0">
        <v-row no-gutters>
          <v-col cols="2">
            <div class="bg-kiri"></div>
          </v-col>
          <v-col cols="10" class="pa-5 black--text">
            <div class="headline">Kelas Diklat {{ $titleCase(jenis) }}</div>
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
                  <v-row dense>
                    <v-col cols="12" :md="jenis === 'luring' ? 5 : 6">
                      <v-list-item class="px-0">
                        <v-list-item-avatar color="primary">
                          <v-icon dark>mdi-teach</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content class="py-0 mt-3">
                          <div class="label--text">Nama Kelas</div>
                          <div class="body-1 black--text">
                            <strong>{{ $getDeepObj(item, 'nama') || '-' }}</strong>
                          </div>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col cols="12" md="3">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0 mt-3">
                          <div class="label--text">Jadwal Pelaksanaan</div>
                          <div class="pt-1">
                            <span v-if="jenis === 'daring'">
                              {{
                                $durasi(
                                  $getDeepObj(item, 'paud_diklat.data.paud_periode.data.tgl_diklat_mulai'),
                                  $getDeepObj(item, 'paud_diklat.data.paud_periode.data.tgl_diklat_selesai')
                                )
                              }}
                            </span>
                            <span v-else>
                              {{
                                $durasi(
                                  $getDeepObj(item, 'paud_diklat_luring.data.tgl_mulai'),
                                  $getDeepObj(item, 'paud_diklat_luring.data.tgl_selesai')
                                )
                              }}<br />
                              <span :class="['caption', isEndDiklat(item) ? 'success--text' : 'grey--text']">
                                <v-icon left small :color="isEndDiklat(item) ? 'success' : 'grey'">
                                  {{ isEndDiklat(item) ? 'mdi-check-circle' : 'mdi-timer-sand' }}
                                </v-icon>
                                <i>{{ isEndDiklat(item) ? 'Selesai' : 'Sedang Berjalan' }}</i>
                              </span>
                            </span>
                          </div>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col cols="12" md="2" v-if="jenis === 'luring' && item && item.is_admin === 1">
                      <v-list-item>
                        <v-list-item-content class="py-0 mt-3">
                          <div class="label--text">Status Laporan</div>
                          <div>
                            <template v-if="!item.laporan_k_verval_paud">
                              <span class="caption grey--text">Silakan Unggah Laporan</span>
                            </template>
                            <template v-else>
                              <v-chip color="primary" small> Belum dilengkapi </v-chip>
                            </template>
                          </div>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col cols="12" md="2">
                      <v-list-item>
                        <v-list-item-content class="py-0 mt-3">
                          <template v-if="jenis === 'daring'">
                            <v-btn
                              :disabled="
                                !$getDeepObj(item, 'lms_url') ||
                                Number(item.k_verval_paud) < 6 ||
                                [1].includes(Number($getDeepObj(item, 'paud_diklat.data.paud_periode_id') || 0))
                              "
                              color="primary"
                              depressed
                              small
                              block
                              @click="toLms($getDeepObj(item, 'lms_url'))"
                            >
                              <v-icon left> mdi-link </v-icon>
                              Tautan LMS
                            </v-btn>
                          </template>
                          <template v-if="jenis === 'luring'">
                            <div
                              v-if="
                                item.is_admin === 1 || (isEndDiklat(item) && (item.is_ppm === 1 || item.is_pptm === 1))
                              "
                              class="label--text"
                              >Aksi</div
                            >
                            <v-btn
                              v-if="item.is_admin === 1"
                              color="primary"
                              depressed
                              small
                              block
                              @click="onUploadLaporan(item)"
                            >
                              <v-icon left> mdi-upload </v-icon>
                              Laporan
                            </v-btn>
                            <v-btn
                              v-else-if="isEndDiklat(item) && (item.is_ppm === 1 || item.is_pptm === 1)"
                              depressed
                              small
                              color="success"
                              @click="onView(item)"
                            >
                              <v-icon left>mdi-file-edit</v-icon>
                              Penilaian
                            </v-btn>
                          </template>
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
    <base-modal-full ref="modal" colorBtn="primary" generalError :title="formulir.title" :useSave="formulir.useSave">
      <component
        ref="formulir"
        :is="formulir.form"
        :type="formulir.type"
        :mapels="mapels"
        :detail="detail"
        :masters="masters"
        :initValue="formulir.init"
        :jenis="jenis"
        @upload="onUpload"
        @unduhTemplate="unduhTemplate"
      ></component>
    </base-modal-full>
    <popup-upload
      ref="uploader"
      :rules="{ format: 'xls', required: true }"
      label-ok="pilih"
      format="XLXS/XLS"
      title="Laporan Pelaksanaan Kelas Luring"
      @save="setFile"
      @unduhTemplate="unduhTemplate"
    ></popup-upload>
  </div>
</template>
<script>
import { mapState } from 'vuex';
import DetailKelas from '../formulir/Detail';
import FormUpload from '../formulir/UploadLaporan';
import PopupUpload from '@components/popup/Upload';
import BaseBreadcrumbs from '@components/base/BaseBreadcrumbs';
import list from '@mixins/list';
import mixin from './mixin';
import actions from './actions';
export default {
  mixins: [list, mixin],
  components: { DetailKelas, FormUpload, PopupUpload, BaseBreadcrumbs },
  data() {
    return {
      formulir: {},
      actions: actions,
      mapels: [],
      detail: {},
    };
  },
  computed: {
    ...mapState('master', {
      masters: (state) => Object.assign({}, state.masters),
    }),

    jenis() {
      return this.$route.meta.jenis;
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

    objMapel() {
      return this.$arrToObj(this.$getDeepObj(this, 'mapels') || [], 'id');
    },
  },
  created() {
    this.getMasters({
      name: ['m_propinsi', 'm_kota'].join(';'),
      filter: {
        0: {
          k_propinsi: {
            op: '<',
            val: 90,
          },
        },
      },
    });
    Object.assign(this.attr, { jenis: this.jenis });
  },
  methods: {
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

    toLms(url) {
      window.open(url, '_blank');
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
