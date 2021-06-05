<template>
  <v-row v-if="configs.selector">
    <template v-for="(field, idx) in configs.selector">
      <v-col v-bind="configs.label && configs.grid[idx]" :key="idx" class="py-0">
        <validation-provider
          :name="configs.label && configs.label[idx]"
          :rules="configs.required && configs.required[idx] === field ? 'required' : ''"
          v-slot="{ errors }"
        >
          <v-subheader :class="[`px-0 body-2 ${labelColor}--text`]" style="height: 24px">
            {{ configs.label && configs.label[idx]
            }}{{ configs.required && configs.required[idx] === field ? ' *' : '' }}
          </v-subheader>
          <v-autocomplete
            :disabled="(configs.disabled && configs.disabled[idx]) || false"
            :items="items[field]"
            :label="configs.label && configs.label[idx]"
            :error-messages="errors"
            :name="field"
            item-text="text"
            item-value="value"
            @change="onChange(idx)"
            no-data-text="-"
            v-model="formData[field]"
            single-line
            dense
            outlined
          />
        </validation-provider>
      </v-col>
      <slot name="extra" v-if="idx === configs.selector.length - 1" />
    </template>
  </v-row>
</template>

<script>
import { mapActions } from 'vuex';
import { ValidationProvider } from 'vee-validate';
import { isObject } from '@/utils/format';
export default {
  name: 'BaseCascade',
  components: { ValidationProvider },
  props: {
    value: {
      type: Object,
      default: () => {},
    },
    configs: {
      type: Object,
      default: () => {},
    },
    labelColor: {
      type: String,
      default: 'purple',
    },
  },
  data() {
    return {
      formData: {},
      options: {},
      items: {},
    };
  },
  mounted() {
    // reset cascade
    this.resetAll();

    // init value
    this.update();
  },
  methods: {
    ...mapActions('master', ['fetchMasters']),

    isEmpty(obj) {
      return !isObject(obj);
    },

    resetAll() {
      const selector = this.$getDeepObj(this.configs, 'selector').length;
      for (let i = 0; i < selector; i += 1) {
        this.resetField(i);
      }
    },

    resetField(idx) {
      const key = this.$getDeepObj(this.configs, `selector.${idx}`);
      this.$set(this.formData, key, '');
    },

    onChange(idx) {
      this.$nextTick(() => {
        this.rChild(idx);
      });
    },

    update() {
      const selector = this.$getDeepObj(this.configs, 'selector');
      selector.forEach((value, idx) => {
        // set parent opt
        if (idx === 0) {
          const opt = this.$getDeepObj(this.configs, `options.${idx}`) || {};
          this.$set(this.options, value, Object.assign({}, opt));
          // items
          this.setChildOptions(value);
        }
        this.rChild(idx);
      });
    },

    rChild(idx) {
      const i = Number(idx);
      const options = {
        parent: this.$getDeepObj(this.configs, `selector.${i}`),
        parent_key: this.$getDeepObj(this.configs, `keys.${i}`) || '',
        child: this.$getDeepObj(this.configs, `selector.${i + 1}`),
        child_key: this.$getDeepObj(this.configs, `keys.${i + 1}`) || '',
        options: this.$getDeepObj(this.configs, `options.${i + 1}`),
      };

      const selectedOpt = this.$getDeepObj(this.formData, `${options.parent}`) || {};
      const selected =
        (selectedOpt && typeof selectedOpt === 'object' ? selectedOpt && selectedOpt.value : selectedOpt) || '';

      // set label
      if (this.configs.withLabel && selected) {
        this.formData[options.parent.replace('k_', '')] = this.$getDeepObj(
          this.options,
          `${options.parent}.${selected}`
        );
      }

      // return jika child undefined
      if (!options.child || !selected || options.options === 'input') {
        return;
      }

      // jika ada cek apakah config options adalah obj
      const hasOpt = !this.isEmpty(options.options);
      if (options.child) {
        if (hasOpt) {
          // set opt
          this.options[options.child] = this.getMapMaster(options.options, selected);
          if (!(this.options[options.child] && this.options[options.child][this.formData[options.child]])) {
            this.$set(this.formData, options.child, '');
          }
          this.setChildOptions(options.child);

          // run callback
          if (typeof this.$getDeepObj(this.configs, 'callbacks') === 'function')
            this.$getDeepObj(this.configs, 'callbacks')(this.formData);
        } else {
          const key = (options.child_key || options.child).replace('k_', '');
          let params = '';
          if (this.configs && this.configs.useSchema) {
            params = {
              name: key,
              filter: {
                0: {
                  [options.parent_key || options.parent]: selected,
                },
              },
            };
          } else {
            params = `${key}|${options.parent_key || options.parent}:${selected}`;
          }

          // request
          this.fetchMasters(params).then((resp) => {
            this.options[options.child] = resp[key] || {};
            if (!(this.options[options.child] && this.options[options.child][this.formData[options.child]])) {
              this.$set(this.formData, options.child, '');
            }
            // items
            this.setChildOptions(options.child);
            // run callback
            if (typeof this.$getDeepObj(this.configs, 'callbacks') === 'function')
              this.$getDeepObj(this.configs, 'callbacks')(this.formData);
          });
        }
      }
    },

    setChildOptions(child) {
      // add items with options
      let remapOptions = Object.keys(this.options[child]).map((val) => {
        return { text: this.options[child][val], value: Number(val) };
      });
      // sort items
      // eslint-disable-next-line
      remapOptions.sort((a, b) => (a.text > b.text ? 1 : b.text > a.text ? -1 : 0))
      this.$set(this.items, child, remapOptions);
    },

    getMapMaster(options, selected) {
      const result = {};
      Object.keys(options).forEach((key) => {
        if (key.startsWith(selected)) {
          result[key] = options[key];
        }
      });

      return result;
    },

    mutate() {
      this.$emit('input', this.formData);
    },

    initForm(value) {
      if (value) {
        for (const keySelector of this.configs.selector) {
          this.$set(this.formData, keySelector, this.$getDeepObj(value, keySelector) || '');
        }
        this.update();
      }
    },
  },
  watch: {
    formData: {
      handler() {
        this.mutate();
      },
      deep: true,
    },
    value: 'initForm',
  },
};
</script>

<style scoped>
.secondary--text {
  color: #bf360c !important;
}
</style>
