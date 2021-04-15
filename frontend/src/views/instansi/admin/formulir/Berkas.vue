<template>
  <v-list-item class="pa-0">
    <v-list-item-content class="px-0">
      <v-row>
        <v-col cols="12" md="1" class="py-1">
          <v-avatar color="blue-grey lighten-5">
            <v-icon>mdi-attachment</v-icon>
          </v-avatar>
        </v-col>
        <v-col cols="12" md="6" class="px-0">
          <span class="subtitle-1">
            <b>{{ berkas.title }}</b>
          </span>
          <p class="body-2 grey--text text--darken-1" v-html="berkas.pesan" />
          <div v-if="berkas.url_template">
            <v-btn text small depressed elevation="0" color="info" class="text-capitalize pa-0">
              <v-icon x-small left class="mr-0">mdi-download</v-icon> Unduh template
            </v-btn>
          </div>
        </v-col>
        <v-col cols="12" md="5" class="pa-0">
          <v-container class="py-0">
            <v-row>
              <v-col cols="12" md="4" class="pb-0">
                <div>
                  <v-label color="caption"><small>Keterangan</small></v-label>
                </div>
                {{ valid ? 'Sudah Diunggah' : 'Belum Diunggah' }}
              </v-col>
              <v-col cols="12" md="4" class="mt-4">
                <v-btn depressed small @click="onView(type)" color="blue-grey lighten-5">
                  <v-icon>mdi-eye</v-icon>
                </v-btn>
                <v-btn class="ml-md-1" depressed small @click="onView(type)" color="blue-grey lighten-5">
                  <v-icon>mdi-download</v-icon>
                </v-btn>
              </v-col>
              <v-col cols="12" md="4" class="mt-4" v-if="withAction">
                <v-btn depressed small color="blue mr-2" dark @click="onUpload">
                  unggah file
                </v-btn>
              </v-col>
            </v-row>
          </v-container>
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
    onUpload(type) {
      this.$emit('upload', { type: type });
    },
    onView() {
      this.$downloadFile(this.value && this.value.url_berkas);
    },
  },
};
</script>

<style scoped></style>
