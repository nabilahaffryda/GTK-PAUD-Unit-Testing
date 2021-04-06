<template>
  <v-dialog v-model="dialog" persistent width="300">
    <v-card color="primary" dark>
      <v-card-text class="pt-3">
        <span class="pt-2">Sedang dalam proses, silakan tunggu...</span>
        <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'loader',
  props: {
    loading: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      dialog: this.loading,
    };
  },
  methods: {
    setDialog(value) {
      this.dialog = value;
    },
  },
  watch: {
    loading: 'setDialog',
  },
};
</script>

<style scoped></style>
