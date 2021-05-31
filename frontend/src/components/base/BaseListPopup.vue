<template>
  <v-dialog v-model="dialog" width="800">
    <v-toolbar color="primary" dark>
      <v-toolbar-title>{{ title }}</v-toolbar-title>
      <v-spacer />
      <v-btn text icon @click="dialog = false"><v-icon>mdi-close</v-icon></v-btn>
    </v-toolbar>
    <v-card flat>
      <v-card-text>
        <base-table-header @search="onSearch" @reload="onReload">
          <template v-slot:subtitle>
            <div class="mt-4 subtitle-1 black--text"> <b>{{ total }}</b> {{ title.split(' ')[1] || '-' }} </div>
          </template>
        </base-table-header>
        <base-list-table :hideHeader="true" :loading="loading" :item="data" :total="total" :usePaging="false">
          <template slot-scope="{ item }">
            <td>
              <v-list-item dense class="px-0" @click="onSelect(item)">
                <v-list-item-content>
                  <v-row>
                    <v-col v-for="(field, f) in fields" :key="f" class="py-0" v-bind="field.grid">
                      <v-list-item class="px-0">
                        <v-list-item-avatar v-if="field.icon" color="secondary">
                          <v-icon dark>{{ field.icon }}</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content class="py-0 mt-3">
                          <div class="caption" v-html="field.title" />
                          <div>{{ $getDeepObj(item, field.key) }}</div>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                  </v-row>
                </v-list-item-content>
              </v-list-item>
            </td>
          </template>
        </base-list-table>
        <v-divider />
      </v-card-text>
      <v-card-actions>
        <base-table-footer :pageTotal="pageTotal" @changePage="onChangePage" />
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import list from '@mixins/list';
export default {
  mixins: [list],
  props: {
    api: {
      type: String,
      default: '',
    },
    title: {
      type: String,
      default: '',
    },
    id: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      dialog: false,
      resolve: null,
      reject: null,
      fields: [],
    };
  },

  methods: {
    fetchData() {
      const params = Object.assign(this.params, this.$isObject(this.filters) ? this.filters : {});
      this.$store.dispatch(this.api, { params: params, id: this.id }).then((data) => {
        this.data = data.data || [];
        this.total = data.total || 0;
        this.pageTotal = data.last_page || 1;
        this.params.limit = data.per_page || this.params.limit;
      });
    },

    open(fields) {
      this.dialog = true;
      this.fields = fields || [];
      this.data = [];
      this.fetchData();

      return new Promise((resolve, reject) => {
        this.resolve = resolve;
        this.reject = reject;
      });
    },

    onSelect(item) {
      this.resolve(item);
      this.dialog = false;
    },
  },
};
</script>
