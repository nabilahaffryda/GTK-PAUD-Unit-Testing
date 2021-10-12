<template>
  <div>
    <v-card>
      <v-card-title class="pa-0">
        <base-table-header @search="onSearch" :btnFilter="true" @reload="onReload" @filter="onFilter">
          <template v-slot:toolbar> </template>
          <template v-slot:subtitle>
            <div class="subtitle-1 black--text">
              <b>{{ total }} </b> {{ title }}
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
              <slot :item="item">
                <v-list-item dense class="px-0">
                  <v-list-item-content>
                    <v-row>
                      <v-col class="py-0" cols="12" md="3">
                        <v-list-item class="px-0" @click="onDetail(item)">
                          <v-list-item-avatar color="primary">
                            <v-icon dark>mdi-account-circle</v-icon>
                          </v-list-item-avatar>
                          <v-list-item-content class="py-0 mt-3">
                            <div class="body-1 black--text">
                              <strong>{{ $getDeepObj(item, 'akun.data.nama') || '-' }}</strong>
                            </div>
                            <p class="caption black--text">
                              <span>Email: {{ $getDeepObj(item, 'akun.data.email') || '-' }}</span>
                            </p>
                            <p class="caption purple--text" v-if="Number(item.is_refreshment) === 1">
                              <v-icon small color="purple">mdi-check-circle</v-icon> Lulus Bimtek
                            </p>
                          </v-list-item-content>
                        </v-list-item>
                      </v-col>
                      <v-col class="py-0" cols="12" md="3">
                        <v-list-item class="px-0">
                          <v-list-item-content class="py-0 mt-3">
                            <span class="caption">Grup</span>
                            <p>
                              <span>{{ $getDeepObj(item, 'm_petugas_paud.data.keterangan') || '-' }}</span>
                            </p>
                            <template v-if="$getDeepObj(item, 'instansi.data.nama')">
                              <span class="caption">Instansi</span>
                              <p>
                                <span>{{ $getDeepObj(item, 'instansi.data.nama') || '-' }}</span>
                              </p>
                            </template>
                          </v-list-item-content>
                        </v-list-item>
                      </v-col>
                      <v-col class="py-0" cols="12" md="2">
                        <v-list-item class="px-0">
                          <v-list-item-content class="py-0 mt-3">
                            <span class="caption">No HP/WA</span>
                            <p>
                              <span>{{ $getDeepObj(item, 'akun.data.no_hp') || '-' }}</span>
                            </p>
                          </v-list-item-content>
                        </v-list-item>
                      </v-col>
                      <v-col class="py-0" cols="12" md="2">
                        <v-list-item class="px-0">
                          <v-list-item-content>
                            <span class="caption">Status</span>
                            <div>
                              <v-chip
                                small
                                dark
                                :color="Number($getDeepObj(item, 'akun.data.is_aktif')) === 1 ? 'success' : 'red'"
                              >
                                {{ Number($getDeepObj(item, 'akun.data.is_aktif')) === 1 ? 'Aktif' : 'Tidak Aktif' }}
                              </v-chip>
                            </div>
                          </v-list-item-content>
                        </v-list-item>
                      </v-col>
                      <v-col class="py-0" cols="12" md="2">
                        <v-list-item class="px-0">
                          <v-list-item-content>
                            <span class="caption">Peran</span>
                            <div>
                              <v-chip small dark color="pink"> {{ title }} Inti </v-chip>
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
              </slot>
            </td>
          </template>
        </base-list-table>
      </v-card-text>
      <v-card-actions>
        <base-table-footer :pageTotal="pageTotal" @changePage="onChangePage"></base-table-footer>
      </v-card-actions>
    </v-card>
    <base-modal-full ref="modal" colorBtn="primary" generalError :title="formulir.title" :useSave="false">
      <detail-view :detail="formulir.detail" :jenis="jenis" :masters="masters"></detail-view>
    </base-modal-full>
  </div>
</template>
<script>
import { mapState } from 'vuex';
import DetailView from './Detail';
import mixin from './mixin';
import list from '@mixins/list';
import { M_AKTIF } from '@utils/master';
export default {
  name: 'ListAdmin',
  props: {
    title: {
      type: String,
      required: true,
    },
    jenis: {
      type: String,
      required: true,
    },
    desc: {
      type: String,
      default: '',
    },
    actions: {
      type: Array,
      required: true,
    },
    paramsTipe: {
      type: Object,
      default: () => {},
    },
  },
  mixins: [list, mixin],
  components: { DetailView },
  data() {
    return {
      formulir: {},
      selector: {},
      selected: [],
      akun: {},
      groups: {},
      instansis: {},
      menu: false,
    };
  },
  mounted() {
    Object.assign(this.attr, {
      tipe: this.$route.meta && this.$route.meta.tipe,
    });

    Object.assign(this.params, this.paramsTipe);
  },
  computed: {
    ...mapState('master', ['masters']),

    mAktif() {
      let temp = {};
      for (const item of M_AKTIF) {
        temp[item.value] = item.text;
      }
      return temp;
    },

    formFilter() {
      return [
        {
          label: 'Pilih Status',
          default: true,
          type: 'radio',
          model: 'is_aktif',
          master: M_AKTIF,
        },
      ];
    },

    filtersMaster() {
      return {
        is_aktif: this.mAktif,
      };
    },

    kGroup() {
      return this.$route.meta.k_group;
    },
  },
  created() {
    // this.getGroups();
    this.getMasters({
      name: ['m_propinsi', 'm_kota', 'm_kualifikasi', 'm_golongan'].join(';'),
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
        default:
          disabled = this.$allow(action.akses, data.policies || false);
          break;
      }
      return disabled;
    },
  },
};
</script>
