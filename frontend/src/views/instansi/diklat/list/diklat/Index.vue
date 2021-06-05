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
            <h1 class="headline black--text--text"> Diklatku </h1>
            <div> </div>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <v-card>
      <v-card-title class="pa-0">
        <base-table-header
          @search="onSearch"
          :btnFilter="true"
          :btnAdd="$allow('lpd-diklat.create')"
          @add="onAddDiklat"
          @reload="onReload"
          @filter="onFilter"
        >
          <template v-slot:subtitle>
            <div class="subtitle-1 black--text">
              <b>{{ total }}</b> Diklat
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
                        <v-list-item-avatar color="primary">
                          <v-icon dark>mdi-account</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content class="py-0 mt-3">
                          <div class="label--text">Nama Diklat</div>
                          <div class="body-1 black--text">
                            <strong>{{ $getDeepObj(item, 'nama') || '-' }}</strong>
                          </div>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col class="py-0" cols="12" md="3">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0 mt-3">
                          <div class="label--text">Tahapan Diklat</div>
                          <div class="body-2">Periode {{ $getDeepObj(item, 'paud_periode.data.angkatan') || '1' }}</div>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col class="py-0" cols="12" md="3">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0 mt-3">
                          <span class="caption">Tanggal Pendaftaran</span>
                          <p>
                            <span>{{
                              $durasi(
                                $getDeepObj(item, 'paud_periode.data.tgl_daftar_mulai'),
                                $getDeepObj(item, 'paud_periode.data.tgl_daftar_selesai')
                              )
                            }}</span>
                          </p>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col v-if="false" class="py-0" cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content>
                          <span class="caption">Alamat</span>
                          <div>
                            {{ $getDeepObj(item, 'instansi.data.alamat') || '-' }}
                            <br />
                            {{
                              [
                                $getDeepObj(item, 'instansi.data.m_kota.data.keterangan') || '-',
                                $getDeepObj(item, 'instansi.data.m_propinsi.data.keterangan') || '-',
                              ].join(' - ')
                            }}
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
        :masters="masters"
        :type="formulir.type"
        :periodes="periodes"
        :initValue="formulir.init"
      ></component>
    </base-modal-full>
  </div>
</template>
<script>
import { mapActions, mapState } from 'vuex';
import FormDiklat from '../../formulir/FormDiklat';
import DetilKelas from '../../formulir/Detail';
import mixin from '../base/mixin';
import list from '@mixins/list';
import actions from './actions';
export default {
  mixins: [list, mixin],
  components: { FormDiklat, DetilKelas },
  data() {
    return {
      formulir: {},
      actions: actions,
      akun: {},
      groups: {},
      periodes: [],
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
      ];
    },

    filtersMaster() {
      return {
        k_propinsi: this.masters && this.masters.m_propinsi,
        k_kota: this.masters && this.masters.m_kota,
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

    this.listPeriodes();
  },
  methods: {
    ...mapActions('diklat', [
      'fetch',
      'create',
      'update',
      'listGroups',
      'getPeriode',
      'action',
      'lookup',
      'getDetail',
      'downloadList',
    ]),

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
