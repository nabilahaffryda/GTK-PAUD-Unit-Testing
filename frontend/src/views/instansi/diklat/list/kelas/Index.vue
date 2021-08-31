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
            <base-breadcrumbs :items="breadcrumbs" class="px-0 pb-0" />
            <div class="my-2" />
            <div class="headline">{{ $getDeepObj(detail, 'nama') || '' }}</div>
            <div class="body-1">
              <v-icon small left>mdi-clock</v-icon>
              {{ $localDate($getDeepObj(detail, 'paud_periode.data.tgl_diklat_mulai')) }} s/d
              {{ $localDate($getDeepObj(detail, 'paud_periode.data.tgl_diklat_selesai')) }}
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
          :btnAdd="$allow('lpd-kelas.create')"
          @add="onAdd"
          @reload="onReload"
          @filter="onFilter"
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
                    <v-col class="py-2" cols="12" md="4">
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
                    <v-col class="py-0" cols="12" md="3">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0 mt-3">
                          <div class="label--text">Mata Pelajaran</div>
                          <span>
                            {{
                              (objMapel[$getDeepObj(item, 'paud_mapel_kelas_id')] &&
                                objMapel[$getDeepObj(item, 'paud_mapel_kelas_id')]['nama']) ||
                              '-'
                            }}
                          </span>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col class="py-0" cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0">
                          <v-list-item-title>
                            <div class="label--text">Status</div>
                          </v-list-item-title>
                          <v-list-item-subtitle class="link black--text body-2">
                            <v-chip :color="getColor($getDeepObj(item, 'k_verval_paud'))" dark small>
                              {{ $getDeepObj(item, 'm_verval_paud.data.keterangan') }}
                            </v-chip>
                            <template v-if="[4, 5].includes(Number(item && item.k_verval_paud))">
                              <v-btn class="ml-1" rounded outlined x-small color="orange" @click="onCatatan(item)">
                                <v-icon left>mdi-file-edit</v-icon> Catatan
                              </v-btn>
                            </template>
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col class="py-0" cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content>
                          <div class="label--text">Aksi Selanjutnya</div>
                          <v-btn
                            :disabled="!item.is_siap_ajuan"
                            v-if="+item.k_verval_paud < 2 && $allow('lpd-kelas-ajuan.create')"
                            color="secondary"
                            depressed
                            small
                            @click="onAjuan(item)"
                            >Ajukan</v-btn
                          >
                          <v-btn
                            v-if="+item.k_verval_paud > 1 && $allow('lpd-kelas-ajuan.delete')"
                            color="secondary"
                            outlined
                            depressed
                            small
                            @click="onBatalAjuan(item)"
                            >Batal Ajuan</v-btn
                          >
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col v-if="catatans[item.paud_kelas_id]" cols="12" sm="12" md="12" class="px-0 pb-0">
                      <v-alert text color="orange" class="ml-3">
                        <div class="black--text body-2">
                          <b>Catatan:</b> <br />
                          {{ $getDeepObj(item, 'alasan') }}
                        </div>
                      </v-alert>
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
        :mapels="mapels"
        :detail="detail"
        :masters="masters"
        :initValue="formulir.init"
      ></component>
    </base-modal-full>
  </div>
</template>
<script>
import { mapActions, mapState } from 'vuex';
import DetailKelas from '../../formulir/Detail';
import FormDiklat from '../../formulir/FormDiklat';
import BaseBreadcrumbs from '@components/base/BaseBreadcrumbs';
import mixin from '../base/mixin';
import list from '@mixins/list';
import actions from './actions';
export default {
  mixins: [list, mixin],
  components: { FormDiklat, DetailKelas, BaseBreadcrumbs },
  data() {
    return {
      formulir: {},
      actions: actions,
      mapels: [],
      detail: {},
      catatans: {},
    };
  },
  computed: {
    ...mapState('master', {
      masters: (state) => Object.assign({}, state.masters),
    }),

    breadcrumbs() {
      return [{ text: 'Daftar Diklat', to: 'kelola-diklat' }, { text: this.$getDeepObj(this, 'detail.nama') }];
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

    diklatId() {
      return this.$route.params.diklat_id;
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

    this.listMapels();
    this.fetchDiklat();
  },
  mounted() {
    Object.assign(this.attr, { diklat_id: this.diklatId });
  },
  methods: {
    ...mapActions('diklatKelas', ['fetch', 'create', 'update', 'action', 'getDetail', 'getMapels', 'downloadList']),
    ...mapActions('diklat', {
      getDiklat: 'getDetail',
    }),

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

    onCatatan(data) {
      const id = data.paud_kelas_id;
      if (this.catatans && this.catatans[id]) {
        this.$delete(this.catatans, id);
      } else {
        this.$set(this.catatans, id, true);
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
