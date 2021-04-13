<template>
  <v-dialog v-model="dialog" width="450" scrollable>
    <v-card flat class="pa-2 pt-3">
      <v-card-title class="headline pa-0">
        <base-table-header @search="onSearch" @reload="onReload">
          <template v-slot:title>Pilih Instansi</template>
          <template v-slot:subtitle> Daftar <span class="blue--text" v-text="total" /> </template>
        </base-table-header>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text class="pa-0" style="height: 500px">
        <base-list-table
          :hideHeader="true"
          :loading="false"
          :item="data"
          :total="total"
          :limit="params.limit"
          :usePaging="true"
          @fetch="fetchData"
        >
          <template slot-scope="{ item }">
            <td>
              <base-list-avatar :detail="item" :title="(item && item.nama) || ''" @detail="select">
                <v-icon>mdi-account-arrow-left</v-icon>
              </base-list-avatar>
            </td>
          </template>
        </base-list-table>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn right color="primary" text @click.native="dialog = false">
          Batal
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import { mapActions } from 'vuex';
import mixin from '@mixins/list';
import BaseListTable from '@/components/base/BaseListTable';
import BaseTableHeader from '@/components/base/BaseTableHeader';
import BaseListAvatar from '@/components/base/BaseListAvatar';

export default {
  name: 'PopupInstansi',
  components: { BaseListAvatar, BaseTableHeader, BaseListTable },
  mixins: [mixin],
  data() {
    return {
      dialog: false,
    };
  },
  methods: {
    ...mapActions('preferensi', ['getInstansi']),

    open() {
      this.dialog = true;
    },

    fetchData: function() {
      const params = Object.assign(this.params, this.$isObject(this.filter) ? { filter: this.filter } : {});
      this.getInstansi({ params }).then(({ data, meta, included }) => {
        this.data = data || [];
        this.included = included || [];
        this.total = meta?.total || 0;
        this.pageTotal = meta?.last_page;
        this.params.limit = meta?.per_page ?? this.params.limit;
      });
    },

    select(data) {
      const id = data.id;
      this.dialog = false;
      window.location.href = `/i/${id}/home`;
    },
  },
  // watch: {
  //   // whenever dialog changes, this function will run
  //   dialog: function(val) {
  //     if (!val) return
  //     this.fetchData()
  //   },
  // },
};
</script>
