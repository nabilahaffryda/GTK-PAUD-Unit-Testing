<template>
  <v-data-table
    :hide-default-footer="!usePaging"
    :items="item || []"
    :options.sync="options"
    :items-per-page="limit"
    :server-items-length="total"
    :loading="loading"
    disable-sort
    :hide-default-header="hideHeader"
    :headers="headers"
    :footer-props="{ itemsPerPageOptions: [limit] }"
  >
    <template v-slot:header="{ props: { headers } }">
      <thead>
        <slot name="header" :headers="headers"></slot>
      </thead>
    </template>
    <template v-slot:body="{ items }">
      <tbody>
        <template v-if="items.length">
          <tr v-for="(item, i) in items" :key="i" class="transparent">
            <slot :item="item" :index="i" />
          </tr>
        </template>
        <template v-else>
          <tr>
            <td :colspan="headers.length">
              {{
                keyword
                  ? `Data Pencarian ${title}${keyword ? ` dengan kata kunci ${keyword}` : ''} tidak ditemukan`
                  : 'Belum ada data yang ditemukan'
              }}
            </td>
          </tr>
        </template>
      </tbody>
    </template>
  </v-data-table>
</template>

<script>
export default {
  name: 'BaseListTable',
  props: {
    usePaging: {
      type: Boolean,
      default: true,
    },
    loading: {
      type: Boolean,
      default: true,
    },
    hideHeader: {
      type: Boolean,
      default: false,
    },
    headers: {
      type: Array,
      default: () => [],
    },
    item: {
      type: Array,
      default: () => [],
    },
    opt: {
      type: Object,
      default: () => {},
    },
    total: {
      type: Number,
      default: 0,
    },
    limit: {
      type: Number,
      default: 10,
    },
    title: {
      type: String,
      default: '',
    },
    keyword: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      options: this.opt || {},
    };
  },
  methods: {
    fetch() {
      this.$emit('fetch', {
        page: this.options.page,
        limit: this.options.itemsPerPage,
      });
    },
  },
  watch: {
    options: {
      handler() {
        this.fetch();
      },
      deep: true,
    },
  },
};
</script>

<style scoped></style>
