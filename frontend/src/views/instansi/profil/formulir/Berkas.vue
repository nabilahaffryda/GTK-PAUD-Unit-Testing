<template>
  <v-list-item class="pa-0">
    <v-list-item-icon v-if="useIcon">
      <v-avatar color="primary">
        <v-icon color="white">mdi-attachment</v-icon>
      </v-avatar>
    </v-list-item-icon>
    <v-list-item-content class="px-0">
      <v-row>
        <v-col cols="12" md="6" sm="12">
          <span class="subtitle-1">
            <b> {{ (berkas && berkas.title) || '-' }} </b>
          </span>
          <div class="body-2 grey--text text--darken-1" v-html="berkas && berkas.pesan" />
          <div v-if="optional" class="label--text warning--text"><i>* Tidak Wajib</i></div>
          <div v-if="berkas && berkas.url_template">
            <v-btn text small depressed elevation="0" color="info" class="text-capitalize pa-0">
              <v-icon x-small left class="mr-0">mdi-download</v-icon> Unduh template
            </v-btn>
          </div>
        </v-col>
        <v-col cols="12" md="6" sm="12" class="pa-0">
          <v-container class="py-0">
            <v-row>
              <v-col cols="12" md="4" class="pb-0">
                <div class="label--text"> Keterangan </div>
                {{ valid ? 'Sudah Diunggah' : 'Belum Diunggah' }}
              </v-col>
              <v-col cols="12" md="4" class="mt-4">
                <v-btn depressed :disabled="!valid" small @click="onDetil(berkas)" color="info">
                  <v-icon>mdi-eye</v-icon>
                </v-btn>
                <v-btn class="ml-md-1" :disabled="!valid" depressed small @click="onView(type)" color="success">
                  <v-icon>mdi-download</v-icon>
                </v-btn>
                <v-btn
                  v-if="useDelete"
                  class="ml-md-1"
                  :disabled="!valid"
                  depressed
                  small
                  color="error"
                  @click="onDelete(type)"
                >
                  <v-icon>mdi-delete</v-icon>
                </v-btn>
              </v-col>
              <v-col cols="12" md="4" class="mt-4" v-if="withAction">
                <v-btn depressed small color="blue mr-2" id="unggah" dark @click="onUpload"> unggah file </v-btn>
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
    useDelete: {
      type: Boolean,
      default: false,
    },
    useIcon: {
      type: Boolean,
      default: true,
    },
  },
  methods: {
    onUpload(type) {
      this.$emit('upload', { type: type });
    },
    onView() {
      this.$downloadFile(this.value && this.value.url);
    },
    onDetil(berkas) {
      this.$emit('detil', berkas);
    },
    onDelete(type) {
      this.$confirm('Apakah anda yakin ingin menghapus berkas berikut ?', 'Hapus Berkas', {
        tipe: 'error',
        data: [],
      }).then(() => {
        this.$emit('delete', { type: type });
      });
    },
  },
};
</script>

<style scoped></style>
