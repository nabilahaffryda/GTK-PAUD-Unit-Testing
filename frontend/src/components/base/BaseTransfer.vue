<template>
  <v-row>
    <v-col>
      <h1 class="title mb-2 secondary--text">Data Pilihan {{ title }}</h1>
      <v-card flat outlined>
        <v-card-title class="pb-0 mb-0">
          <slot name="filter" />
          <v-toolbar flat>
            <v-toolbar-title>Pilih {{ title }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn small icon @click="fetchData">
              <v-icon color="secondary">mdi-reload</v-icon>
            </v-btn>
            <v-btn small icon @click="pickAll">
              <v-icon color="secondary">mdi-arrow-right-bold</v-icon>
            </v-btn>
          </v-toolbar>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <base-list-table
            :hideHeader="true"
            :loading="dataLoading"
            :item="dataList"
            :total="totalData"
            :usePaging="false"
            @fetch="fetchData"
          >
            <template slot-scope="{ item }">
              <td
                :class="[
                  'lighten-3',
                  {
                    grey: targetList.findIndex((target) => target[keyAttr] === item[keyAttr]) > -1,
                  },
                ]"
              >
                <v-list-item dense class="px-0">
                  <v-list-item-content>
                    <slot :item="item" />
                  </v-list-item-content>
                  <v-list-item-action-text>
                    <template v-if="targetList.findIndex((target) => target[keyAttr] === item[keyAttr]) > -1">
                      <v-icon color="secondary">mdi-check</v-icon>
                    </template>
                    <template v-else>
                      <v-btn icon @click="onSelect(item)">
                        <v-icon color="secondary">mdi-arrow-right-bold</v-icon>
                      </v-btn>
                    </template>
                  </v-list-item-action-text>
                </v-list-item>
              </td>
            </template>
          </base-list-table>
        </v-card-text>
        <v-card-actions>
          <base-table-footer :pageTotal="pageTotalData" @changePage="onChangePageData"></base-table-footer>
        </v-card-actions>
      </v-card>
    </v-col>
    <v-col>
      <h1 class="title mb-2 secondary--text"> Data {{ title }} untuk Paket Baru </h1>
      <v-card flat outlined>
        <v-card-title class="pb-0 mb-0">
          <v-toolbar flat>
            <v-toolbar-title>{{ totalTarget }} {{ title }} Terpilih</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn small icon @click="deleteAll">
              <v-icon color="secondary">mdi-close</v-icon>
            </v-btn>
          </v-toolbar>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <base-list-table
            :hideHeader="true"
            :loading="false"
            :item="targetList"
            :total="totalTarget"
            :usePaging="false"
          >
            <template slot-scope="{ item }">
              <td>
                <v-list-item dense class="px-0">
                  <v-list-item-content>
                    <slot :item="item" />
                  </v-list-item-content>
                  <v-list-item-action-text>
                    <v-btn icon @click="onDelete(item)">
                      <v-icon color="secondary">mdi-close</v-icon>
                    </v-btn>
                  </v-list-item-action-text>
                </v-list-item>
              </td>
            </template>
          </base-list-table>
        </v-card-text>
        <v-card-actions>
          <base-table-footer :pageTotal="pageTotalTarget" @changePage="onChangePageTarget"></base-table-footer>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: 'BaseTransfer',
  props: {
    title: {
      type: String,
      required: true,
    },
    keyAttr: {
      type: String,
      required: true,
    },
    dataLoading: {
      type: Boolean,
      default: true,
    },
    targetLoading: {
      type: Boolean,
      default: true,
    },
    dataList: {
      type: Array,
      default: () => [],
    },
    targetList: {
      type: Array,
      default: () => [],
    },
    totalData: {
      type: Number,
      default: 10,
    },
    totalTarget: {
      type: Number,
      default: 10,
    },
    pageTotalData: {
      type: Number,
      default: 10,
    },
    pageTotalTarget: {
      type: Number,
      default: 10,
    },
  },
  methods: {
    fetchData() {
      this.$emit('fetch');
    },
    pickAll() {
      this.$emit('pickall');
    },
    deleteAll() {
      this.$emit('deleteall');
    },
    onChangePageData(page) {
      this.$emit('changePageData', page);
    },
    onChangePageTarget(page) {
      this.$emit('changePageTarget', page);
    },
    onSelect(data) {
      this.$emit('selected', data);
    },
    onDelete(data) {
      this.$emit('delete', data);
    },
  },
};
</script>

<style scoped></style>
