<template>
  <div>
    <v-card tile flat class="my-5">
      <v-card-text class="pa-0">
        <v-row no-gutters>
          <v-col cols="2">
            <div class="bg-kiri"></div>
          </v-col>
          <v-col cols="10" class="pa-5">
            <h1 class="headline black--text"> {{ $route.meta.title }} </h1>
            <div> </div>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <v-card>
      <v-card-title class="pa-0">
        <base-table-header
          @search="onSearch"
          :btnFilter="false"
          :btnAdd="$allow('diklat-periode.create')"
          @add="onAdd"
          @reload="onReload"
          @filter="onFilter"
        >
          <template v-slot:subtitle>
            <div class="subtitle-1 black--text">
              <b>{{ total }}</b> Jadwal Diklat
            </div>
          </template>
        </base-table-header>
      </v-card-title>
      <v-divider />
      <v-card-text style="margin-bottom: 80px">
        <v-row v-for="(item, i) in data" :key="i">
          <v-col cols="12" md="12" sm="12">
            <v-list-item dense class="px-0">
              <v-list-item-content>
                <v-row>
                  <v-col class="py-0" cols="12" md="6">
                    <v-list-item class="px-0">
                      <v-list-item-avatar color="primary">
                        <v-icon dark>mdi-teach</v-icon>
                      </v-list-item-avatar>
                      <v-list-item-content class="py-0 mt-3">
                        <div class="label--text">Tahap Diklat</div>
                        <div class="body-1 black--text">
                          <strong>{{ $getDeepObj(item, 'nama') || '-' }}</strong>
                        </div>
                      </v-list-item-content>
                    </v-list-item>
                  </v-col>
                  <v-col class="py-0" cols="12" md="4">
                    <v-list-item class="px-0">
                      <v-list-item-content class="py-0 mt-3">
                        <span class="caption">Jadwal Pelaksanaan</span>
                        <div>
                          {{ $durasi($getDeepObj(item, 'tgl_diklat_mulai'), $getDeepObj(item, 'tgl_diklat_selesai')) }}
                        </div>
                      </v-list-item-content>
                    </v-list-item>
                  </v-col>
                </v-row>
              </v-list-item-content>
              <v-list-item-action-text>
                <template v-if="$allow('diklat-periode.update') || $allow('diklat-periode.delete')">
                  <base-list-action :data="item" :actions="actions" :allow="allow" @action="onAction" />
                </template>
                <template v-else>
                  <v-icon class="mr-3" color="white">mdi-dots-vertical</v-icon>
                </template>
              </v-list-item-action-text>
            </v-list-item>
          </v-col>
          <v-col cols="12" md="12" sm="12">
            <v-divider v-if="data.length !== Number(i + 1)" />
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <base-modal-full ref="modal" colorBtn="primary" generalError :title="formulir.title" @save="onSave">
      <component ref="formulir" :is="formulir.form" :initValue="formulir.init" :isEdit="formulir.isEdit"></component>
    </base-modal-full>
  </div>
</template>
<script>
import { mapActions } from 'vuex';
import list from '@mixins/list';
import FormJadwal from '../../formulir/Jadwal';
import actions from './actions';
export default {
  mixins: [list],
  components: { FormJadwal },
  data() {
    return {
      formulir: {},
      actions: actions,
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    ...mapActions('diklatPeriode', ['fetch', 'getDetail', 'create', 'update', 'delete']),

    fetchData: function () {
      return new Promise((resolve) => {
        const params = Object.assign({}, this.params, this.$isObject(this.filters) ? { filter: this.filters } : {});
        const attr = Object.assign({}, this.attr);
        this.fetch({ params, attr }).then(({ data }) => {
          this.data = data || [];
          this.total = data.length || 0;
          resolve(true);
        });
      });
    },

    onAdd() {
      this.$set(this.formulir, 'title', 'Tambah Jadwal');
      this.$set(this.formulir, 'form', 'form-jadwal');
      this.$set(this.formulir, 'isEdit', false);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
        this.$set(this.formulir, 'init', null);
      });
      console.log(this.data);
    },

    async onEdit(item) {
      const resp = await this.getDetail({ id: item.paud_periode_id }).then(({ data }) => data);
      this.$set(this.formulir, 'id', item.paud_periode_id);
      this.$set(this.formulir, 'title', 'Update Jadwal');
      this.$set(this.formulir, 'form', 'form-jadwal');
      this.$set(this.formulir, 'isEdit', true);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
        this.$set(this.formulir, 'init', resp);
      });
      console.log(this.data);
    },

    onSave() {
      const formulir = this.$refs.formulir.getValue();
      const isEdit = this.formulir.isEdit;
      const id = this.formulir.id;
      let params = {};

      if (isEdit) {
        params = formulir.form || {};
      } else {
        params = {
          data: formulir.data || [],
        };
      }

      this[isEdit ? 'update' : 'create']({ id: id, params })
        .then(() => {
          this.$success(`Jadwal berhasil di ${isEdit ? 'perbarui' : 'tambahkan'}`);
          this.$refs.modal.close();
          this.onReload();
        })
        .catch(() => {
          this.$refs.modal.loading = false;
        });
    },

    onDelete(item) {
      this.$confirm(`Apakan anda ingin menghapus jadwal <b>${item.nama}</b> berikut ?`, `Hapus Jadwal`, {
        tipe: 'error',
      }).then(() => {
        this.action({ id: item.paud_periode_id }).then(() => {
          this.$success(`Jadwal berhasil di hapus`);
          this.fetchData();
        });
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
