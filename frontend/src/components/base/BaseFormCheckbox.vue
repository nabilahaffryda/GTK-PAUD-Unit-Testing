<template>
  <div>
    <v-subheader :class="[`px-0 body-2 ${labelColor}--text`]" style="height: 24px">
      <span :style="{ fontSize: fontSize, fontWeight: weight }">{{ label }}</span> {{ required ? '*' : '' }}
    </v-subheader>
    <template v-if="row">
      <v-row dense>
        <v-col v-for="(item, i) in items" :key="i" v-bind="itemGrid">
          <v-checkbox
            class="mt-0"
            v-model="form"
            :label="item.text"
            :value="item.value"
            :disabled="disabled"
            :error-messages="errorMessages"
          ></v-checkbox>
        </v-col>
      </v-row>
    </template>
    <template v-else>
      <v-checkbox
        class="mt-0"
        v-for="(item, i) in items"
        :key="i"
        v-model="form"
        :label="item.text"
        :value="item.value"
        :disabled="disabled"
        :error-messages="errorMessages"
      ></v-checkbox>
    </template>
  </div>
</template>

<script>
export default {
  name: 'FormRadio',
  props: [
    'label',
    'labelColor',
    'required',
    'items',
    'value',
    'disabled',
    'row',
    'margin',
    'fontSize',
    'weight',
    'errorMessages',
    'itemGrid',
  ],
  data() {
    return {
      form: this.value || [],
    };
  },
  methods: {
    mutate(value) {
      this.$emit('input', value);
    },

    setForm(value) {
      this.$set(this, 'form', value);
    },
  },
  watch: {
    form: 'mutate',
    value: 'setForm',
  },
};
</script>

<style scoped></style>
