<template>
  <v-dialog v-model="dialog" width="800" scrollable>
    <v-card flat>
      <v-card-title class="pa-0">
        <v-toolbar color="secondary" dark>
          <v-toolbar-title>{{ title }}</v-toolbar-title>
          <v-spacer />
          <v-btn text icon @click="dialog = false"><v-icon>mdi-close</v-icon></v-btn>
        </v-toolbar>
      </v-card-title>
      <v-card-text class="pt-2">
        <v-tabs v-model="tab" @change="fetchData">
          <v-tabs-slider color="secondary"></v-tabs-slider>

          <v-tab v-for="item in items" :key="item.url">
            {{ item.text }}
          </v-tab>
        </v-tabs>

        <v-alert type="info" text v-if="selected.length">
          <div>
            <span class="red--text">{{ selected.length }} {{ title.split(' ')[1] || '-' }}</span> Terpilih
          </div>
        </v-alert>
        <base-table-header @search="onSearch" @reload="onReload">
          <template v-slot:subtitle>
            <div class="mt-4 subtitle-1 black--text">
              <b>{{ total }}</b> {{ title.split(' ')[1] || '-' }}
            </div>
          </template>
        </base-table-header>
        <base-list-table :hideHeader="true" :loading="loading" :item="data" :total="total" :usePaging="false">
          <template slot-scope="{ item }">
            <td>
              <v-list-item dense class="px-0" @click="onSelect(item)">
                <v-list-item-content>
                  <v-row>
                    <v-col v-if="multiselect" cols="12" md="1" sm="1" class="px-5">
                      <v-checkbox
                        v-model="select[$getDeepObj(item, id)]"
                        :disabled="Number(tab) === 1 ? !item.is_baru : false"
                      ></v-checkbox>
                    </v-col>
                    <v-col v-for="(field, f) in fields" :key="f" v-bind="field.grid">
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
      <v-card-text v-if="multiselect">
        <div class="text-right">
          <v-btn text @click="dialog = false">Batal</v-btn>
          <v-btn color="primary" :disabled="!selected.length" @click="onSaveSelection">Simpan</v-btn>
        </div>
      </v-card-text>
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
      type: [String, Number],
      default: '',
    },
    multiselect: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      dialog: false,
      resolve: null,
      reject: null,
      fields: [],
      select: {},
      tab: null,
      items: [
        {
          url: 'peserta/kandidat',
          text: 'non-simpatika',
        },
        {
          url: 'peserta/kandidat-simpatika',
          text: 'simpatika',
        },
      ],
    };
  },

  computed: {
    selected() {
      let temp = [];
      Object.keys(this.select).forEach((item) => {
        if (this.select[item]) temp.push(item);
      });
      return temp;
    },
  },

  methods: {
    fetchData() {
      this.$set(this.params, 'tipe', this.items[this.tab]['url']);
      this.$delete(this.params.params, 'k_petugas_paud');
      const params = Object.assign(this.params, this.$isObject(this.filters) ? this.filters : {});
      this.$store.dispatch(this.api, params).then((resp) => {
        this.data = (resp && resp.data) || [];
        this.total = resp?.meta?.total || resp.total || 0;
        this.pageTotal = resp?.meta?.last_page || resp.last_page || 1;
        this.params.limit = resp?.meta?.per_page || resp.per_page || this.params.limit;
      });
    },

    open(fields, params = {}) {
      this.dialog = true;
      this.fields = fields || [];
      this.params = params || {};
      this.data = [];
      this.select = {};
      this.tab = 0;
      this.fetchData();

      return new Promise((resolve, reject) => {
        this.resolve = resolve;
        this.reject = reject;
      });
    },

    onSelect(item) {
      if (this.multiselect) return;
      this.resolve(item);
      this.dialog = false;
    },

    onSaveSelection() {
      this.resolve(this.selected);
      this.dialog = false;
    },
  },

  watch: {
    tab: function () {
      this.select = {};
    },
  },
};
</script>
