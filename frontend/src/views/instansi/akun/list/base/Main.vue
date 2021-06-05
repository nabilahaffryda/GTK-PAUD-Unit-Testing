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
            <h1 class="headline black--text" v-html="`Daftar ${title}`"></h1>
            <p v-html="desc"></p>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <v-card>
      <v-card-title class="pa-0">
        <base-table-header
          @search="onSearch"
          :btnFilter="true"
          :btnAdd="$allow(`akun-${this.akses}.create`)"
          :btnDownload="$allow(`akun-${this.akses}.download`)"
          @add="onAdd"
          @reload="onReload"
          @filter="onFilter"
          @download="onDownload"
        >
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
                      <v-col class="py-0" cols="12" md="4">
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
                          </v-list-item-content>
                        </v-list-item>
                      </v-col>
                      <v-col class="py-0" cols="12" md="3">
                        <v-list-item class="px-0">
                          <v-list-item-content class="py-0 mt-3">
                            <span class="caption">Grup</span>
                            <p>
                              <span>{{ $getDeepObj(item, 'm_group.data.keterangan') || '-' }}</span>
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
                      <v-col class="py-0" cols="12" md="3">
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
                                :color="Number($getDeepObj(item, 'is_aktif')) === 1 ? 'success' : 'red'"
                              >
                                {{ Number($getDeepObj(item, 'is_aktif')) === 1 ? 'Aktif' : 'Tidak Aktif' }}
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
              </slot>
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
      :lblBtn="formulir.lblBtn || `Simpan & Cetak`"
      generalError
      :use-save="formulir.isValid"
      :title="formulir.title"
      @save="onSave"
    >
      <component
        ref="formulir"
        :is="formulir.component || 'FormAkun'"
        :title="title"
        :isChecked="formulir.isChecked"
        :isEdit="formulir.isEdit"
        :initValue="formulir.init"
        :detail="formulir.detail"
        :errorEmail="formulir.errorEmail"
        :groups="groups"
        :instansis="instansis"
        :masters="masters"
        :jenis="jenis"
        :useUpload="$allow(`akun-${akses}.upload`)"
        @check="onCheck"
        @onValidate="onValidate"
        @onStep="
          () => {
            $set(formulir, 'isValid', false);
          }
        "
        @upload="onUpload"
        @unduhTemplate="unduhTemplate"
        @getInstansi="getInstansi"
      />
    </base-modal-full>
    <Akun ref="akun" :akun="akun" />
    <popup-upload
      ref="uploader"
      :rules="{ format: 'xls', required: true }"
      label-ok="pilih"
      format="XLXS/XLS"
      title="Data Akun"
      @save="setFile"
      @unduhTemplate="unduhTemplate"
    ></popup-upload>
  </div>
</template>
<script>
import { mapState } from 'vuex';
import FormAkun from '@views/instansi/akun/formulir/Akun';
import DetailView from './Detail';
import Akun from '@components/cetak/Akun';
import PopupUpload from '@components/popup/Upload';
import mixin from './mixin';
import list from '@mixins/list';
import { M_AKTIF } from '@utils/master';
export default {
  name: 'ListAdmin',
  props: {
    akses: {
      type: String,
      required: true,
    },
    jenis: {
      type: String,
      required: true,
    },
    title: {
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
  },
  mixins: [list, mixin],
  components: { DetailView, FormAkun, Akun, PopupUpload },
  data() {
    return {
      formulir: {},
      akun: {},
      groups: {},
      instansis: {},
    };
  },
  mounted() {
    // filter
    Object.assign(this.filters, {
      k_group: this.$route.meta && this.$route.meta.k_group,
    });

    Object.assign(this.attr, {
      tipe: this.$route.meta && this.$route.meta.tipe,
    });
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
          type: 'checkbox',
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
      name: ['m_propinsi', 'm_kota', 'm_golongan'].join(';'),
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
          disabled = this.$allow(action.akses, data.policies || false);
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
