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
            <h1 class="headline black--text" v-html="`Peserta Non Dapodik`"></h1>
            <p>
              <b>Peserta Non Dapodik adalah</b> peserta yang ditambahkan daru unsur guru yang tidak terdata dari Dapodik
              untuk kebutuhan diklat moda luring
            </p>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <v-card>
      <v-card-title class="pa-0">
        <base-table-header
          @search="onSearch"
          :btnAdd="$allow('lpd-peserta-non-ptk.create')"
          @add="onAdd"
          @reload="onReload"
          @download="onDownload"
        >
          <template v-slot:subtitle>
            <div class="subtitle-1 black--text">
              Daftar Peserta Luring <b>{{ total }} </b>
            </div>
          </template>
        </base-table-header>
      </v-card-title>
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
                        <v-list-item class="px-0">
                          <v-list-item-avatar color="primary">
                            <v-icon dark>mdi-account-circle</v-icon>
                          </v-list-item-avatar>
                          <v-list-item-content class="py-0 mt-3">
                            <span class="caption">Nama</span>
                            <div class="body-1 black--text">
                              <strong>{{ $getDeepObj(item, 'nama') || '-' }}</strong>
                            </div>
                          </v-list-item-content>
                        </v-list-item>
                      </v-col>
                      <v-col class="py-0" cols="12" md="3">
                        <v-list-item class="px-0">
                          <v-list-item-content class="py-0 mt-3">
                            <span class="caption">NIK</span>
                            <div class="body-1">
                              {{ $getDeepObj(item, 'nik') || '-' }}
                            </div>
                          </v-list-item-content>
                        </v-list-item>
                      </v-col>
                      <v-col class="py-0" cols="12" md="3">
                        <v-list-item class="px-0">
                          <v-list-item-content class="py-0 mt-3">
                            <span class="caption">Jenis Diklat</span>
                            <div class="body-1">
                              {{ item['k_diklat_paud'] ? masters['m_diklat_paud'][item['k_diklat_paud']] : '' }}
                            </div>
                          </v-list-item-content>
                        </v-list-item>
                      </v-col>
                      <v-col class="py-0" cols="12" md="3">
                        <v-list-item class="px-0">
                          <v-list-item-content>
                            <span class="caption">Jenjang Diklat</span>
                            <div class="body-1">
                              {{
                                item['k_jenjang_diklat_paud']
                                  ? masters['m_jenjang_diklat_paud'][item['k_jenjang_diklat_paud']]
                                  : ''
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
      generalError
      :use-save="formulir.isValid"
      :title="formulir.title"
      @save="onSave"
    >
      <component
        ref="formulir"
        :is="formulir.component || 'FormPeserta'"
        :isEdit="formulir.isEdit"
        :initValue="formulir.initValue"
        :masters="masters"
        @validate="onValidate"
        @onStep="
          () => {
            formulir.isValid = false;
          }
        "
      />
    </base-modal-full>
  </div>
</template>
<script>
import list from '@mixins/list';
import FormPeserta from './formulir/Peserta';
import { mapActions, mapState } from 'vuex';
import actions from './actions';

export default {
  mixins: [list],
  components: { FormPeserta },
  data() {
    return {
      formulir: {},
      actions: actions,
    };
  },
  created() {
    this.getMasters({
      name: ['m_propinsi', 'm_kota', 'm_kualifikasi', 'm_golongan', 'm_diklat_paud', 'm_jenjang_diklat_paud'].join(';'),
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
  computed: {
    ...mapState('master', ['masters']),
  },
  methods: {
    ...mapActions('master', ['getMasters']),
    ...mapActions('peserta', ['fetch', 'getDetail', 'create', 'update', 'delete']),

    onAdd() {
      this.$set(this.formulir, 'title', 'Tambah Peserta Non Dapodik');
      this.$set(this.formulir, 'isEdit', false);
      this.$set(this.formulir, 'isValid', false);
      this.$set(this.formulir, 'id', null);

      this.$set(this.formulir, 'initValue', null);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
      });
    },

    async onEdit(data) {
      const resp = await this.getDetail({ id: this.$getDeepObj(data, 'paud_peserta_nonptk_id') }).then(
        ({ data }) => data
      );
      this.$set(this.formulir, 'title', 'Ubah Peserta Non Dapodik');
      this.$set(this.formulir, 'isEdit', true);
      this.$set(this.formulir, 'isValid', false);
      this.$set(this.formulir, 'id', this.$getDeepObj(data, 'paud_peserta_nonptk_id'));
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
        this.$set(this.formulir, 'initValue', resp);
      });
    },

    onValidate() {
      this.$refs.modal.onValidate().then((valid) => {
        this.$set(this.formulir, 'isValid', valid);
        if (valid) this.$refs.formulir.next(valid);
      });
    },

    onSave() {
      const form = this.$refs.formulir.form || {};
      const formData = new FormData();
      const id = this.formulir.id || null;

      Object.keys(form).forEach((key) => {
        if (form[key]) {
          formData.append(key, form[key]);
        }
      });

      this[id ? 'update' : 'create']({ id: id, params: formData }).then(() => {
        this.$success(`Data peserta berhasil ${id ? 'Di ubah' : 'Ditambahkan'}`);
        this.$refs.modal.close();
        this.onReload();
      });
    },

    onDelete(data) {
      this.$confirm(`Apakan anda ingin menghapus peserta ${data.nama} berikut ?`, `Hapus Peserta`, {
        tipe: 'error',
        data: [],
      }).then(() => {
        this.delete({ id: this.$getDeepObj(data, 'paud_peserta_nonptk_id') }).then(() => {
          this.$success(`Peserta berhasil dihapus`);
          this.fetchData();
        });
      });
    },
  },
};
</script>
