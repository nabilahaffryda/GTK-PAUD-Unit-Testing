<template>
  <div>
    <template v-if="filtered.length">
      <div class="yellow lighten-5 px-4 py-2">
        <div class="secondary--text caption">difilter berdasar:</div>
        <template v-for="(item, i) in filtered">
          <template v-if="$isObject(item)">
            <v-chip small close class="grey lighten-2 secondary--text mr-1" :key="i" @click:close="onDelete(item)">
              {{ item && $isObject(item) ? item.text : item }}
            </v-chip>
          </template>
          <template v-else>
            <v-chip small class="grey lighten-2 secondary--text mr-1" :key="i">
              {{ item && $isObject(item) ? item.text : item }}
            </v-chip>
          </template>
        </template>
      </div>
      <v-divider></v-divider>
    </template>
    <v-dialog v-model="dialog" scrollable max-width="750px">
      <v-card>
        <v-card-title class="headline secondary white--text">
          <v-icon class="mr-3 white--text">mdi-filter-variant</v-icon>
          <slot name="title">{{ title }}</slot>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text class="py-0" style="height: 300px">
          <v-row>
            <v-col v-for="(filter, i) in filters" :key="i" v-bind="filter.grid" class="pr-3">
              <v-list class="py-0" dense>
                <div class="secondary--text mb-2" v-html="filter.label"></div>
                <v-divider class="mb-2"></v-divider>
                <template v-if="filter.type === 'checkbox'">
                  <v-list-item v-for="(item, key) in filter.master" :key="key">
                    <v-list-item-action class="mt-2 pt-1" style="min-width: 35px">
                      <v-checkbox v-model="params[filter.model]" :value="item.value"></v-checkbox>
                    </v-list-item-action>
                    <v-list-item-content>
                      <v-list-item-title class="body-2" v-html="item.text"></v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </template>
                <template v-else-if="filter.type === 'radio'">
                  <v-radio-group :row="filter.row" v-model="params[filter.model]" class="mt-0" hide-details>
                    <template v-for="(item, key) in filter.master">
                      <v-radio :value="item.value" :key="key">
                        <template v-slot:label>
                          <div v-html="item.text"></div>
                        </template>
                      </v-radio>
                    </template>
                  </v-radio-group>
                </template>
                <template v-else-if="filter.type === 'date'">
                  <base-date-picker
                    v-model="params[filter.model]"
                    :outlined="filter.outlined"
                    :dense="filter.dense"
                    :label="filter.label"
                  ></base-date-picker>
                </template>
                <template v-else-if="filter.type === 'select'">
                  <div v-if="filter.master.length">
                    <div v-if="filter.default">
                      <v-select
                        v-model="params[filter.model]"
                        :items="filter.master"
                        :label="filter.label || ''"
                        :multiple="filter.props.indexOf('multiple') > -1"
                        :chips="filter.props.indexOf('chips') > -1"
                        :small-chips="filter.props.indexOf('small-chips') > -1"
                        :disabled="filter.disabled"
                        outlined
                        single-line
                        dense
                      ></v-select>
                    </div>
                    <div v-else>
                      <v-autocomplete
                        :label="`Pilihan ${filter.label || ''}`"
                        :items="filter.master"
                        v-model="params[filter.model]"
                        :disabled="filter.disabled"
                        outlined
                        single-line
                        dense
                        no-data-text="-"
                      ></v-autocomplete>
                    </div>
                  </div>
                </template>
                <template v-else-if="filter.type === 'cascade'">
                  <base-cascade
                    ref="cascade"
                    class="ml-3 mr-3 mt-2"
                    v-model="params"
                    :configs="filter.configs"
                    :labelColor="filter.labelColor"
                  >
                  </base-cascade>
                </template>
                <template v-else-if="filter.type === 'components'">
                  <base-render :renderEl="onRender(filter.components)" :ref="filter.ref" :params="params"></base-render>
                </template>
              </v-list>
            </v-col>
          </v-row>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions class="grey lighten-4 pa-3">
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialog = false"> Batal </v-btn>
          <v-btn color="warning darken-1" text @click="reset"> Setel Ulang </v-btn>
          <v-btn color="secondary" @click="save">Terapkan</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import BaseRender from '@/components/base/BaseRender';
import BaseCascade from '@/components/base/BaseCascade';
import BaseDatePicker from '@/components/base/BaseDatePicker';
import { isArray } from '@/utils/format';
export default {
  name: 'BaseListFilter',
  components: { BaseDatePicker, BaseCascade, BaseRender },
  props: {
    title: {
      type: String,
      required: true,
    },
    closeable: {
      type: Boolean,
      default: false,
    },
    paramsFilter: {
      type: Object,
      default: () => {},
    },
    filters: {
      type: Array,
    },
    filtered: {
      type: Array,
      default: function () {
        return [];
      },
    },
    syncMutate: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      params: {},
      dialog: false,
      cache: {},
    };
  },
  methods: {
    open(data = {}) {
      this.reset();
      this.initValue(data);
      this.dialog = true;
    },

    close() {
      this.dialog = false;
    },

    reset() {
      this.$set(this, 'params', {});
    },

    initValue(value) {
      this.cache = value;
      // set params
      if (value) {
        for (let i = 0; i < this.filters.length; i++) {
          if (this.filters[i].type === 'cascade') {
            const cascade = this.$getDeepObj(this.filters[i], 'configs.selector') || [];
            for (let j = 0; j < cascade.length; j++) {
              const isDisabled = this.filters[i].disabled;
              let key = cascade[j];
              let init = (value && value[key]) || (isDisabled ? this.cache && this.cache[key] : '');
              // set
              this.$set(this.params, key, init);
            }
          } else if (this.filters[i].type === 'components') {
            const isDisabled = this.filters[i].disabled;
            const name = this.filters[i] && this.filters[i].ref;
            const data = Object.assign(
              {},
              (value && value[name]) || (isDisabled ? this.cache && this.cache[name] : {}) || {}
            );
            if (this.$refs && this.$refs[name]) this.$refs[name][0].$refs.myComp.setSelected(data || {});
          } else {
            const isDisabled = this.filters[i].disabled;
            let key = this.filters[i].model;
            let init =
              this.filters[i].type === 'select'
                ? !Array.isArray(value && value[key])
                  ? (value && value[key]) || (isDisabled ? this.cache && this.cache[key] : '') || ''
                  : (value && value[key]) || (isDisabled ? this.cache && this.cache[key] : []) || []
                : this.filters[i].type === 'date'
                ? (value && value[key]) || (isDisabled ? this.cache && this.cache[key] : '') || ''
                : this.filters[i].type === 'checkbox'
                ? (value && value[key]) || (isDisabled ? this.cache && this.cache[key] : []) || []
                : (value && value[key]) || (isDisabled ? this.cache && this.cache[key] : '') || '';

            // set
            this.$set(this.params, key, init);
          }
        }
      }
    },

    onDelete(item) {
      let params = Object.assign({}, this.paramsFilter);
      // cek filter key adalah object, jika ya hapus value
      if (params[item.key] && isArray(params[item.key])) {
        const index = params[item.key].indexOf(item.value);
        params[item.key].splice(index, 1);
      } else {
        delete params[item.key];
      }
      this.$emit('save', params);
    },

    save() {
      // get data from component
      const filters = this.filters.filter((item) => item.type === 'components');
      const params = {};
      if (filters.length)
        for (let i = 0; i < filters.length; i++) {
          const name = filters[i] && filters[i].ref;
          params[name] = this.$refs[name][0].$refs.myComp.getSelected();
        }

      // add param from this params
      const tempParams = Object.assign({}, this.params);
      for (const key in tempParams) {
        if (!tempParams[key]) delete tempParams[key];
      }

      this.$emit('save', Object.assign({}, tempParams, params));
    },

    onRender(components) {
      return (h) => {
        return h(components, {
          ref: 'myComp',
        });
      };
    },

    mutate() {
      this.$emit('change', this.params);
    },
  },

  watch: {
    params: {
      handler(value, oldValue) {
        let status = false;
        let keys = this.syncMutate || [];
        keys.forEach((key) => {
          if (value && value[key] && oldValue && oldValue[key]) {
            status = true;
          }
        });
        if (status) this.mutate();
      },
      deep: true,
    },
  },
};
</script>
