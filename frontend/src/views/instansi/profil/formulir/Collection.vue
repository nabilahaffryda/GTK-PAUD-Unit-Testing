<template>
  <v-list-item class="pa-0">
    <v-list-item-avatar tile>
      <v-avatar tile color="secondary">
        <span class="white--text">{{ berkas.nomor }}</span>
      </v-avatar>
    </v-list-item-avatar>
    <v-list-item-content class="px-0">
      <v-row>
        <v-col cols="12" md="8" sm="12">
          <span class="body-1">
            <b>{{ berkas.title }}</b>
          </span>
          <p class="body-2 grey--text text--darken-1" v-html="berkas.pesan" />
          <div v-if="berkas.url_template">
            <v-btn text small depressed elevation="0" color="info" class="text-capitalize pa-0">
              <v-icon x-small left class="mr-0">mdi-download</v-icon> Unduh template
            </v-btn>
          </div>
        </v-col>
        <v-col cols="12" md="4" class="mt-4">
          <v-btn depressed :disabled="!valid" small @click="onDetil(berkas)" color="blue-grey lighten-5">
            <v-icon>mdi-eye</v-icon>
          </v-btn>
          <v-btn class="ml-md-1" :disabled="!valid" depressed small @click="onView(type)" color="blue-grey lighten-5">
            <v-icon>mdi-download</v-icon>
          </v-btn>
        </v-col>
      </v-row>
    </v-list-item-content>
  </v-list-item>
</template>

<script>
export default {
  name: 'ViewData',
  props: {
    type: {
      type: String,
      required: true,
    },
    berkas: {
      type: Object,
      default: () => {},
    },
    value: {
      type: Object,
      default: () => {},
    },
    valid: {
      type: Boolean,
      default: false,
    },
    withAction: {
      type: Boolean,
      default: false,
    },
    optional: {
      type: Boolean,
      default: false,
    },
  },
  methods: {
    onView() {
      this.$downloadFile(this.value && this.value.url);
    },
    onDetil(berkas) {
      this.$emit('detil', berkas);
    },
  },
};
</script>

<style scoped></style>
