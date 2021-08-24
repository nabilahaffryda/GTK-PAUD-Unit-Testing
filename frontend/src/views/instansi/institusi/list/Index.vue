<template>
  <div>
    <!--notif jadwal-->
    <v-card tile flat class="my-5">
      <v-card-text class="pa-0">
        <v-row no-gutters>
          <v-col cols="2">
            <div class="bg-kiri"></div>
          </v-col>
          <v-col cols="10" class="pa-5">
            <h1 class="headline black--text"> <strong>Daftar</strong> Institusi LPD </h1>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <v-card>
      <v-card-title class="pa-0">
        <base-table-header
          @search="onSearch"
          :btnFilter="true"
          :btnAdd="$allow('akun-admin-program-lpd.create')"
          :btnDownload="$allow('lpd.download')"
          @add="onAdd"
          @reload="onReload"
          @filter="onFilter"
          @download="onDownload"
        >
          <template v-slot:subtitle>
            <div class="subtitle-1 black--text">
              <b>{{ total }}</b> Institusi LPD</div
            >
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
        @save="filterStatus"
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
                    <v-col class="py-0" cols="12" md="4">
                      <v-list-item class="px-0">
                        <v-list-item-avatar color="blue-grey darken-3">
                          <v-icon dark>mdi-office-building-outline</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content class="py-0 mt-3">
                          <div class="body-1 black--text">
                            <strong>{{ $getDeepObj(item, 'instansi.data.nama') || '-' }}</strong>
                          </div>
                          <p class="caption black--text">
                            <span>ID Institusi: {{ $getDeepObj(item, 'instansi.data.instansi_id') || '-' }}</span>
                            <br />
                            <span>
                              Alamat: {{ $getDeepObj(item, 'instansi.data.alamat') || '-' }}
                              <br />
                              {{
                                [
                                  $getDeepObj(item, 'instansi.data.m_kota.data.keterangan') || '-',
                                  $getDeepObj(item, 'instansi.data.m_propinsi.data.keterangan') || '-',
                                ].join(' - ')
                              }}
                            </span>
                          </p>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col class="py-0" cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0 mt-3">
                          <span class="caption">Penanggung Jawab</span>
                          <p>
                            <span>{{ $getDeepObj(item, 'nama_penanggung_jawab') || '-' }}</span
                            ><br />
                            <span>{{ $getDeepObj(item, 'telp_penanggung_jawab') || '-' }}</span>
                          </p>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col class="py-0" cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0 mt-3">
                          <span class="caption">Alamat Email</span>
                          <p>
                            <span>{{ $getDeepObj(item, 'instansi.data.email') || '-' }}</span>
                          </p>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col class="py-0" cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content>
                          <span class="caption">Petugas Diklat</span>
                          <p class="caption black--text">
                            <span
                              >Pengajar:
                              {{
                                $getDeepObj(item, 'ratio_pengajar_tambahan')
                                  ? `${100 - Number($getDeepObj(item, 'ratio_pengajar_tambahan'))}%`
                                  : '-'
                              }}</span
                            >
                            <br />
                            <span
                              >Pengajar Tambahan:
                              {{
                                $getDeepObj(item, 'ratio_pengajar_tambahan')
                                  ? `${$getDeepObj(item, 'ratio_pengajar_tambahan')}%`
                                  : '-'
                              }}</span
                            >
                            <br />
                            <span> Pembimbing Praktik: {{ $getDeepObj(item, 'jml_pembimbing') || '-' }} </span>
                          </p>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col class="py-0" cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0 mt-3">
                          <span class="caption">Status</span>
                          <div>
                            <v-chip :color="+item.is_aktif === 1 ? 'success' : 'red'" dark small>
                              {{ +item.is_aktif === 1 ? 'Aktif' : 'Tidak Aktif' }}
                            </v-chip>
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
      <form-lpd
        ref="formulir"
        :isEdit="formulir.isEdit"
        :initValue="formulir.init"
        :errorEmail="formulir.errorEmail"
        :masters="masters"
        @onValidate="onValidate"
        @onBack="onBack"
      />
    </base-modal-full>
    <Akun ref="akun" :akun="akun" />
  </div>
</template>
<script>
import { mapState } from 'vuex';
import FormLpd from '../formulir/Lpd';
import Akun from '@components/cetak/Akun';
import mixin from './mixin';
import list from '@mixins/list';
import actions from './actions';
import { M_AKTIF } from '@utils/master';
export default {
  mixins: [list, mixin],
  components: { FormLpd, Akun },
  data() {
    return {
      formulir: {},
      actions: actions,
      akun: {},
      groups: {},
    };
  },
  computed: {
    ...mapState('master', {
      masters: (state) => Object.assign({}, state.masters),
    }),

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
        {
          label: 'Pilih Status',
          default: true,
          type: 'checkbox',
          model: 'is_aktif',
          master: M_AKTIF,
        },
      ];
    },

    filtersMaster() {
      const mAktif = {};
      M_AKTIF.forEach((item) => {
        this.$set(mAktif, item.value, item.text);
      });

      return {
        k_propinsi: this.masters && this.masters.m_propinsi,
        k_kota: this.masters && this.masters.m_kota,
        is_aktif: mAktif,
      };
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
  },
  methods: {
    allow(action, data) {
      let disabled = false;
      switch (action.event) {
        case 'onAktif':
          disabled = !Number(this.$getDeepObj(data, 'is_aktif') || 0);
          break;
        case 'onNonAktif':
          disabled = Number(this.$getDeepObj(data, 'is_aktif') || 0);
          break;
        default:
          disabled = this.$allow(action.akses, data.policies);
          break;
      }
      return disabled;
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
