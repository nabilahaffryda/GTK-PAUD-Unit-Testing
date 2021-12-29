<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-container>
        <v-stepper v-model="stepUnggah" class="elevation-0">
          <v-stepper-header class="elevation-0" style="border: 1px solid rgba(0, 0, 0, 0.12); padding: 0 15%">
            <v-stepper-step :color="stepUnggah > 1 ? 'success' : 'primary'" :complete="stepUnggah > 1" step="1">
              Pilih File Laporan
            </v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step :color="stepUnggah > 2 ? 'success' : 'primary'" :complete="stepUnggah > 2" step="2">
              Konfirmasi File Laporan
            </v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-7">
                  <v-alert text outlined dense color="secondary">
                    <div class="pa-4">
                      <div class="font-weight-bold">Unggah {{ title }}</div>
                      <div class="caption">
                        Anda dapat mengggunnakan template lapooran di bawah ini.
                        <ol>
                          <li>Download format template dibawah ini.</li>
                          <li>Silakan isi data sesuai format yang tersedia pada template</li>
                          <li>
                            Unggah Berkas yang sudah Anda isi pada langkah selanjutnya. Silakan tekan
                            <b>tombol selanjutnya</b>
                          </li>
                        </ol>
                        <v-btn
                          depressed
                          class="ma-2"
                          color="secondary"
                          @click="$emit('unduhTemplate')"
                          style="text-transform: none"
                        >
                          <v-icon left>mdi-file</v-icon>Template Laporan
                        </v-btn>
                      </div>
                    </div>
                  </v-alert>
                  <v-divider class="my-2"></v-divider>
                  <v-row class="my-2" dense no-gutters>
                    <v-col cols="12" md="2" sm="12" class="px-0">
                      <v-avatar color="primary" size="100">
                        <v-icon dark size="80">mdi-file-upload</v-icon>
                      </v-avatar>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" class="px-0">
                      <h2>Unggah {{ title }}</h2>
                      <span>
                        Silakan unggah data {{ title }} yang sudah di isi sesuai format template yang telah Anda unduh
                        sebelumnnya. Pastikan data yang Anda masukan bersifat final
                      </span>
                      <div class="mt-2">
                        <v-btn depressed color="primary" @click="$emit('upload')">
                          <v-icon left>mdi-upload</v-icon>Pilih Berkas
                        </v-btn>
                      </div>
                    </v-col>
                  </v-row>
                </v-card-text>
                <v-divider class="my-3" />
                <v-card-actions class="pa-0">
                  <v-spacer></v-spacer>
                  <v-btn class="text-md-right" right text @click="onResetPilih"> Kembali </v-btn>
                  <v-btn
                    class="text-md-right"
                    :disabled="!file"
                    right
                    color="primary"
                    @click="
                      () => {
                        $emit('onValidate');
                        stepUnggah++;
                      }
                    "
                  >
                    Selanjutnya
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-stepper-content>
            <v-stepper-content step="2" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-7">
                  <h2 class="secondary--text">Konfirmasi File Laporan</h2>
                  <v-list-item class="px-0">
                    <v-list-item-avatar color="primary" size="60">
                      <v-icon dark>mdi-file</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content class="py-0 mt-3">
                      <div class="label--text">Nama Berkas</div>
                      <div class="body-1 black--text">
                        <strong>{{ $getDeepObj(file, 'file.name') || '' }}</strong>
                      </div>
                    </v-list-item-content>
                  </v-list-item>
                  <p>
                    Silakan periksa kembali akun yang Anda inputkan.
                    <b>Jika terjadi kesalahan input Anda dapat kembali ke langkah sebelumnya</b> dengan menekan tombol
                    dibawah ini
                  </p>
                </v-card-text>
                <v-divider class="my-3" />
                <v-card-actions class="pa-0">
                  <v-spacer></v-spacer>
                  <v-btn
                    right
                    text
                    @click="
                      () => {
                        $emit('onStep');
                        stepUnggah--;
                      }
                    "
                  >
                    Kembali
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-stepper-content>
          </v-stepper-items>
        </v-stepper>
      </v-container>
    </v-card-text>
  </v-card>
</template>
<script>
export default {
  props: {
    title: {
      type: String,
      default: 'Laporan Pelaksanaan Diklat Luring',
    },
    initValue: {
      default: () => null,
    },
  },
  data() {
    return {
      id: null,
      form: {},
      stepUnggah: 1,
      file: null,
    };
  },
  computed: {},
  methods: {
    reset() {
      this.$set(this, 'id', null);
      this.$set(this, 'form', {});
      this.stepUnggah = 1;
      this.file = null;
    },

    next() {
      this.step++;
    },

    onPilih() {
      this.isPilih = this.pilihan;
    },

    onResetPilih() {
      this.pilihan = null;
      this.isPilih = null;
    },

    setFile(file) {
      console.log(file);
      this.file = file;
    },
  },
  watch: {
    initValue: 'initForm',
  },
};
</script>
