<template>
  <div>
    <v-subheader :class="[`px-0 body-2 ${labelColor}--text`]" style="height: 24px">
      <span :style="{ fontSize: fontSize, fontWeight: weight }">{{ label }}</span> {{ required ? '*' : '' }}
    </v-subheader>
    <v-radio-group
      v-model="form"
      :row="row !== undefined ? row : true"
      :class="margin || 'mt-0'"
      :error-messages="errorMessages"
    >
      <v-radio
        v-for="(item, i) in items"
        :key="i"
        :label="item.text"
        :value="item.value"
        :disabled="disabled"
      ></v-radio>
    </v-radio-group>
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
  ],
  data() {
    return {
      form: this.value,
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
