<template>
  <form @submit.prevent>
    <v-row>
      <template v-for="(field, i) in schema">
        <v-col
          v-bind="field.grid"
          :key="i"
          :class="field.class || 'py-0'"
          v-if="field.exist ? field.exist.status : true"
        >
          <template v-if="field.required || field.rules">
            <validation-provider
              mode="passive"
              :name="field.label"
              :rules="field.rules || 'required'"
              v-slot="{ errors }"
            >
              <template v-if="field.mask">
                <span
                  v-if="field.singleLine"
                  :class="[`px-0 body-2 ${field.labelColor || 'secondary'}--text`]"
                  style="height: 24px"
                >
                  {{ field.label }} {{ field.required ? '*' : '' }}
                </span>
                <component
                  :is="field.type"
                  :value="formData[field.name]"
                  @input="updateForm(field.name, $event)"
                  :label="`${field.label}`"
                  v-bind="field"
                  v-mask="field.mask"
                  :items="field.items"
                  :error-messages="errors"
                />
                <div v-if="field.subHint" class="my-2 mt-n3 caption grey--text" v-html="field.subHint" />
              </template>
              <template v-else>
                <div v-show="field.show ? field.show.status : true">
                  <span
                    v-if="field.singleLine"
                    :class="[
                      field.labelClass ? field.labelClass : `px-0 body-2 ${field.labelColor || 'secondary'}--text`,
                    ]"
                    style="height: 24px"
                  >
                    {{ field.label }}<span :class="field.asterixColor || ''"> {{ field.required ? '*' : '' }}</span>
                  </span>
                  <component
                    :is="field.type"
                    :value="formData[field.name]"
                    @input="updateForm(field.name, $event)"
                    :label="`${field.label}`"
                    v-bind="field"
                    :items="field.items"
                    :error-messages="errors"
                  />
                </div>
                <div v-if="field.subHint" class="my-2 mt-n3 caption grey--text" v-html="field.subHint" />
              </template>
            </validation-provider>
          </template>
          <template v-else-if="field.type === 'cascade'">
            <base-cascade
              :configs="field.configs"
              :value="formData"
              :labelColor="field.labelColor"
              @input="updateFormCascade"
            />
          </template>
          <template v-else>
            <template v-if="field.mask">
              <v-subheader
                v-if="field.singleLine"
                :class="[field.labelClass ? field.labelClass : `px-0 body-2 ${field.labelColor || 'secondary'}--text`]"
                style="height: 24px"
              >
                {{ field.label }}
              </v-subheader>
              <component
                :is="field.type"
                :value="formData[field.name]"
                @input="updateForm(field.name, $event)"
                :label="`${field.label}`"
                v-bind="field"
                v-mask="field.mask"
                :items="field.items"
              />
              <div v-if="field.subHint" class="my-2 mt-n3 caption grey--text" v-html="field.subHint" />
            </template>
            <template v-else>
              <span
                v-if="field.singleLine"
                :class="[field.labelClass ? field.labelClass : `px-0 body-2 ${field.labelColor || 'secondary'}--text`]"
                style="height: 24px"
              >
                {{ field.label }}
              </span>
              <component
                :is="field.type"
                :value="formData[field.name]"
                @input="updateForm(field.name, $event)"
                :label="`${field.label}`"
                v-bind="field"
                :items="field.items"
                :hide-details="!!field.subHint"
              />
              <div v-if="field.subHint" class="my-2 mb-4 caption grey--text" v-html="field.subHint" />
            </template>
          </template>
          <!-- use other-->
          <template
            v-if="
              ['VAutocomplete', 'VSelect'].indexOf(field.type) > -1 &&
              field.useOthers &&
              formData[field.name] === field.othersValue
            "
          >
            <template v-if="field.others.required || field.others.rules">
              <validation-provider
                mode="passive"
                :name="field.others.label"
                :rules="field.others.rules || 'required'"
                v-slot="{ errors }"
              >
                <component
                  class="mt-2"
                  :is="field.others.type"
                  :value="formData[field.others.name]"
                  @input="updateForm(field.others.name, $event)"
                  :label="`${field.others.label}`"
                  v-bind="field.others"
                  :hide-details="!!field.others.subHint"
                  :error-messages="errors"
                />
              </validation-provider>
            </template>
            <template v-else>
              <component
                class="mt-2"
                :is="field.others.type"
                :value="formData[field.others.name]"
                @input="updateForm(field.others.name, $event)"
                :label="`${field.others.label}`"
                v-bind="field.others"
                :hide-details="!!field.others.subHint"
              />
            </template>
          </template>
        </v-col>
      </template>
    </v-row>
  </form>
</template>

<script>
import { ValidationProvider } from 'vee-validate';
import { mask } from 'vue-the-mask';
import { VAutocomplete, VSelect, VTextarea, VTextField, VCheckbox, VFileInput } from 'vuetify/lib';
import VRadio from './BaseFormRadio';
import Checkbox from './BaseFormCheckbox';
import VDatePicker from './BaseDatePicker';
import VTimePicker from './BaseTimePicker';
import BaseCascade from '@/components/base/BaseCascade';
export default {
  name: 'BaseFormGenerator',
  components: {
    BaseCascade,
    ValidationProvider,
    VAutocomplete,
    VSelect,
    VFileInput,
    VTextarea,
    VTextField,
    VRadio,
    Checkbox,
    VCheckbox,
    VDatePicker,
    VTimePicker,
  },
  props: ['schema', 'value'],
  directives: {
    mask,
  },
  data() {
    return {
      formData: this.value || {},
    };
  },
  methods: {
    updateForm(fieldName, value) {
      this.$set(this.formData, fieldName, value);
      this.$emit('input', this.formData);
    },

    updateFormCascade(value) {
      for (const key in value) {
        this.$set(this.formData, key, value[key]);
      }

      this.$emit('input', this.formData);
    },

    validate() {},
  },
  watch: {
    value: function (value) {
      this.formData = value;
    },
  },
};
</script>

<style scoped></style>
