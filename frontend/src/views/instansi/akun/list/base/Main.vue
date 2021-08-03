<template>
  <div>
    <v-card>
      <v-card-title class="pa-0">
        <base-table-header
          @search="onSearch"
          :btnFilter="true"
          :btnAdd="$allow(`akun-${akses}.create`)"
          :btnDownload="
            akses === 'kelas' ? $allow(`akun-${akses}.download`) : $allow(`akun-${akses}.download-aktivasi`)
          "
          @add="onAdd"
          @reload="onReload"
          @filter="onFilter"
          @download="onDownload"
        >
          <template v-slot:toolbar>
            <template v-if="akses === 'pembimbing-praktik'">
              <v-btn small color="info" class="ml-2 py-5" @click="setMultiInti('inti')"> Set Pembimbing Praktik </v-btn>
            </template>
            <template v-if="akses === 'pengajar'">
              <v-menu :close-on-content-click="false" :nudge-width="200" offset-x offset-y>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn v-bind="attrs" v-on="on" small color="info" class="ml-2 py-5"> Set Pengajar </v-btn>
                </template>
                <v-card>
                  <v-list>
                    <v-list-item @click="setMultiInti('inti')">
                      <v-list-item-title>Pengajar Inti </v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="setMultiInti('bimtek')">
                      <v-list-item-title>Lulus Bimtek</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-card>
              </v-menu>
            </template>
          </template>
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
                      <v-col
                        class="py-0"
                        cols="12"
                        :md="['pembimbing-praktik'].includes(akses) ? 3 : ['pengajar'].includes(akses) ? 4 : 4"
                      >
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
                            <p
                              class="caption purple--text"
                              v-if="akses === 'pengajar' && Number(item.is_refreshment) === 1"
                            >
                              <v-icon small color="purple">mdi-check-circle</v-icon> Lulus Bimtek
                            </p>
                          </v-list-item-content>
                        </v-list-item>
                      </v-col>
                      <v-col class="py-0" cols="12" :md="['pembimbing-praktik', 'pengajar'].includes(akses) ? 3 : 4">
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
                      <v-col class="py-0" cols="12" :md="['pembimbing-praktik', 'pengajar'].includes(akses) ? 2 : 2">
                        <v-list-item class="px-0">
                          <v-list-item-content class="py-0 mt-3">
                            <span class="caption">No HP/WA</span>
                            <p>
                              <span>{{ $getDeepObj(item, 'akun.data.no_hp') || '-' }}</span>
                            </p>
                          </v-list-item-content>
                        </v-list-item>
                      </v-col>
                      <v-col
                        class="py-0"
                        cols="12"
                        :md="['pembimbing-praktik'].includes(akses) ? 2 : ['pengajar'].includes(akses) ? 1 : 2"
                      >
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
                      <template
                        v-if="
                          ['pembimbing-praktik', 'pengajar'].includes(akses) &&
                          Number($getDeepObj(item, 'is_inti') || 0) === 1
                        "
                      >
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
                      </template>
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
    <popup-selector
      v-if="selector && selector.fetch"
      ref="selector"
      :title="selector && selector.title"
      :valueId="selector && selector.valueId"
      :fetch="selector && selector.fetch"
      :filterSelect="selector && selector.filters"
      :attr="selector && selector.attr"
      @save="onSaveInti"
    >
      <template slot-scope="{ item }">
        <v-list-item dense class="px-0">
          <v-list-item-content>
            <v-row>
              <v-col class="py-0" cols="12" md="5">
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
                  </v-list-item-content>
                </v-list-item>
              </v-col>
              <v-col class="py-0" cols="12" md="4">
                <v-list-item class="px-0">
                  <v-list-item-content class="py-0 mt-3">
                    <span class="caption">Instansi</span>
                    <p>
                      <span>{{ $getDeepObj(item, 'instansi.data.nama') || '-' }}</span>
                    </p>
                  </v-list-item-content>
                </v-list-item>
              </v-col>
            </v-row>
          </v-list-item-content>
        </v-list-item>
      </template>
    </popup-selector>
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
import PopupSelector from '@components/popup/Selector';
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
    paramsTipe: {
      type: Object,
      default: () => {},
    },
  },
  mixins: [list, mixin],
  components: { PopupSelector, DetailView, FormAkun, Akun, PopupUpload },
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
    // filter
    Object.assign(this.filters, {
      k_group: this.$route.meta && this.$route.meta.k_group,
    });

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
        case 'setAkunInti':
          disabled = !Number(this.$getDeepObj(data, 'is_inti') || 0);
          break;
        case 'onResetInti':
          disabled = Number(this.$getDeepObj(data, 'is_inti') || 0);
          break;
        case 'onResetBimtek':
          disabled = Number(this.$getDeepObj(data, 'is_refreshment') || 0);
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
