<template>
  <v-dialog ref="dialog" v-model="modal" :return-value.sync="date" persistent width="290px">
    <template v-slot:activator="{ on }">
      <v-text-field
        :label="label"
        :prepend-icon="useicon ? 'mdi-calendar' : ''"
        readonly
        :value="formatDate"
        v-on="on"
        :disabled="disabled"
        :dense="dense"
        :single-line="singleLine"
        :outlined="outlined"
        :error-messages="errorMessages"
        :placeholder="placeholder"
        :append-icon="useAppendIcon ? useAppendIcon : ''"
      />
    </template>
    <v-date-picker v-model="date" :type="typeDate || 'date'" scrollable :min="min" :max="max">
      <v-spacer />
      <v-btn text color="primary" @click="modal = false">Cancel</v-btn>
      <v-btn text color="primary" @click="$refs.dialog.save(date)">OK</v-btn>
    </v-date-picker>
  </v-dialog>
</template>

<script>
export default {
  name: 'BaseDatePicker',
  props: [
    'value',
    'label',
    'typeDate',
    'min',
    'max',
    'errors',
    'errorMessages',
    'disabled',
    'useicon',
    'dense',
    'singleLine',
    'outlined',
    'placeholder',
    'useAppendIcon',
  ],
  data() {
    return {
      modal: false,
      date: this.value || '',
    };
  },
  computed: {
    formatDate() {
      return (this.date && this.$localDate(this.date)) || '';
    },
  },
  methods: {
    mutate() {
      this.$emit('input', this.date);
    },
    setForm(value) {
      this.$set(this, 'date', value);
    },
  },
  watch: {
    date: 'mutate',
    value: 'setForm',
  },
};
</script>

<style scoped></style>
