<template>
  <v-dialog v-model="dialog" scrollable max-width="850px">
    <v-card>
      <v-card-title class="headline secondary white--text">
        <span class="text-capitalize" v-html="title"></span>
      </v-card-title>
      <v-divider />
      <v-card-text>
        <v-row no-gutters class="mx-4 mt-2">
          <v-col cols="12" md="4" class="my-auto">
            <v-checkbox v-model="allSelected" @click="selectAll" />
          </v-col>
          <v-col cols="12" md="8" class="my-auto">
            <v-text-field
              v-model="keyword"
              label="Cari Data"
              dense
              single-line
              outlined
              hide-details
              class="my-auto mr-1"
              append-icon="mdi-magnify"
              @keyup.enter="onSearch"
              @click:append="onSearch"
            >
            </v-text-field>
          </v-col>
        </v-row>
        <v-divider />
        <base-list-table :loading="loading" :item="data" :total="total" :usePaging="false" @fetch="fetchData">
          <template slot-scope="{ item }">
            <template v-if="showSelect">
              <td>
                <v-checkbox v-model="selected" @click="select" :value="Number(item[valueId])" />
              </td>
            </template>
            <td>
              <slot :item="item"></slot>
            </td>
          </template>
        </base-list-table>
        <base-table-footer :pageTotal="pageTotal" @changePage="onChangePage"></base-table-footer>
      </v-card-text>
      <v-divider />
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn class="mr-2" text color="primary" @click.native="close">Tutup</v-btn>
        <v-btn color="primary" @click.native="onSave">Simpan</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import BaseListTable from '../base/BaseListTable';
import BaseTableFooter from '../base/BaseTableFooter';
export default {
  name: 'popupSelector',
  components: { BaseTableFooter, BaseListTable },
  props: {
    title: {
      type: String,
      default: 'Pilih Selector',
    },
    valueId: {
      type: String,
      required: true,
    },
    showSelect: {
      type: Boolean,
      default: true,
    },
    fetch: {
      type: Function,
      required: true,
    },
    filterSelect: {
      type: Object,
      default: () => {},
    },
    attr: {
      type: Object,
      default: () => {},
    },
    value: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      dialog: false,
      allSelected: false,
      selected: this.value || [],
      data: [],
      params: {
        page: Number(this.$route.query.page) || 1,
      },
      filters: Object.assign({}, this.filterSelect || {}),
      loading: false,
      keyword: '',
      page: 1,
      total: 0,
      pageTotal: 0,
    };
  },
  methods: {
    open() {
      this.dialog = true;
    },

    close() {
      this.dialog = false;
    },

    selectAll() {
      this.selected = [];
      if (this.allSelected) {
        for (const item of this.data) {
          this.selected.push(Number(item[this.valueId]));
        }
      }
    },

    select() {
      this.allSelected = false;
    },

    fetchData() {
      return new Promise((resolve) => {
        const params = Object.assign({}, this.params, this.$isObject(this.filters) ? { filter: this.filters } : {});
        const attr = Object.assign({}, this.attr);
        this.fetch({ params, attr }).then(({ data, meta }) => {
          this.data = data || [];
          this.total = meta?.total || 0;
          this.pageTotal = meta?.last_page || 1;
          resolve(true);
        });
      });
    },

    onReload() {
      this.fetchData();
    },

    onSearch() {
      Object.assign(this.filters, { keyword: this.keyword });
      Object.assign(this.params, { page: 1 });
      this.fetchData();
    },

    onChangePage(page) {
      this.$set(this.params, 'page', page);
      this.fetchData();
    },

    onSave() {
      this.$emit('save', this.selected);
    },

    mutate(value) {
      this.$emit('input', value);
    },
  },
  watch: {
    selected: 'mutate',
    value: function (value) {
      this.selected = value;
    },
  },
};
</script>
