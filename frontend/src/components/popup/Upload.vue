<template>
  <v-dialog v-model="dialog" scrollable :persistent="true" width="750px">
    <v-card flat>
      <v-card-title class="pa-0">
        <v-toolbar color="secondary" dark flat>
          <v-toolbar-title>Unggah {{ title || '' }}</v-toolbar-title>
        </v-toolbar>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text style="min-height: 150px;">
        <v-container class="py-5">
          <template v-if="step === 0">
            <form-unggah
              ref="formulir"
              :title="`Formulir ${title}`"
              :format="format"
              uimodel="row"
              :max="max"
              :min="min"
              :rules="rules"
            ></form-unggah>
          </template>
          <template v-else>
            <v-flex xs12>
              <template v-if="errorFile && errorFile.length">
                <v-subheader class="green--text">Pengecekan File Unggah</v-subheader>
                <v-divider></v-divider>
                <p class="px-2 body-1 mt-1 grey--text">
                  Unggah Data {{ title }} <b class="red--text">GAGAL</b> dilakukan dikarenakan terdapat kesalahan pada
                  data. Unduh file pada <b class="blue--text">Hasil Unggah Data</b>, kemudian perbaiki kesalahan -
                  kesalahan berikut ini
                </p>
                <v-list outlined>
                  <v-list-item>
                    <v-list-item-content>
                      <v-simple-table fixed-header height="230px">
                        <template v-slot:default>
                          <thead>
                            <tr>
                              <th class="text-left">
                                No.
                              </th>
                              <th class="text-left">
                                Kesalahan
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(item, index) in errorFile" :key="index">
                              <td>{{ index + 1 }}</td>
                              <td class="red--text text--lighten-1">{{ item }}</td>
                            </tr>
                          </tbody>
                        </template>
                      </v-simple-table>
                    </v-list-item-content>
                  </v-list-item>
                </v-list>
              </template>
              <template v-else>
                <div class="text-center">
                  <v-icon color="green" size="80">mdi-check-decagram</v-icon>
                  <h2 class="py-2 black--text">Selamat!! Unggah Data {{ title }} Berhasil</h2>

                  <template v-if="!(errorFile && errorFile.length)">
                    <v-btn color="info" flat @click="unduhTokenTemplate"> Daftar Aktivasi {{ title }} </v-btn>
                  </template>

                  <p class="py-5 body-2">
                    Unggah Data {{ title }} <b class="green--text">Berhasil</b> disimpan. Anda dapat mengunduh
                    <b class="blue--text">Daftar Aktivasi {{ title }}</b> untuk melihat kode aktivasi (token) masing -
                    masing {{ title }}.
                  </p>
                </div>
              </template>
            </v-flex>
          </template>
        </v-container>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn v-if="step === 0" text right @click.native="dialog = false"> Batal</v-btn>
        <v-btn v-if="step === 0" color="info" depressed right @click="submit"> {{ labelOk || 'Upload' }}</v-btn>
        <template v-if="step === 1">
          <v-btn v-if="!(errorFile && errorFile.length)" color="info" right text @click="tutup"> OK</v-btn>
          <v-btn v-else color="red darken-1" right text @click="back"> Unggah Ulang</v-btn>
        </template>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import FormUnggah from '@/components/form/Unggah';
export default {
  name: 'PopupUpload',
  components: { FormUnggah },
  props: {
    title: {
      type: String,
      required: true,
    },
    min: {
      type: Number,
      // dalam KB
      default: 20,
    },
    max: {
      type: Number,
      // dalam KB
      default: 1500,
    },
    format: {
      type: String,
      default: '',
    },
    rules: {
      type: Object,
      default: () => {},
    },
    labelOk: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      step: 0,
      errorFile: [],
      dialog: false,
    };
  },
  methods: {
    open() {
      // reset
      this.reset();
      this.dialog = true;
    },

    reset() {
      this.step = 0;
      this.errorFile = [];
      this.$refs.formulir && this.$refs.formulir.reset();
    },

    close() {
      this.dialog = false;
    },

    tutup() {
      this.close();
    },

    unduhTemplate() {
      this.$emit('unduhTemplate', 'template');
    },

    unduhTokenTemplate() {
      this.$emit('unduhTokenTemplate', 'token');
    },

    openFile(file) {
      window.open(file);
    },

    getFile() {
      const params = this.$refs.formulir.form || {};
      return params;
    },

    validate() {
      let params = this.getFile();
      let valid = true;
      if (!(params.file && params.file.size)) valid = false;
      return valid;
    },

    submit() {
      const isValid = this.validate();

      if (!isValid) {
        let msg = `File Data Unggah ${this.title} belum ada!`;
        this.$error(msg);
        return;
      }
      this.$emit('save', Object.assign({}, this.getFile()));
    },

    back() {
      this.reset();
    },
  },
};
</script>
