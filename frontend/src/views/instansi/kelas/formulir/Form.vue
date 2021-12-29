<template>
  <div class="mx-auto container">
    <v-card flat>
      <v-card-text>
        <v-row>
          <v-col cols="12" md="12" sm="12">
            <div>
              <v-toolbar flat>
                <v-toolbar-title>
                  <div class="body-1 font-weight-bold black--text">
                    {{ $getDeepObj(kelas, 'paud_diklat_luring.data.nama') }} - {{ $getDeepObj(kelas, 'nama') }}
                  </div>
                </v-toolbar-title>
                <v-spacer />
                <v-chip color="red" dark>Belum Dinilai</v-chip>
              </v-toolbar>
            </div>
          </v-col>
          <v-col cols="12" md="12" sm="12">
            <v-list-item dense class="px-0">
              <v-list-item-content>
                <v-row>
                  <v-col class="py-2" cols="12" md="12" sm="12">
                    <v-list-item class="px-0">
                      <v-list-item-avatar color="primary">
                        <v-icon dark>mdi-account</v-icon>
                      </v-list-item-avatar>
                      <v-list-item-content class="py-0">
                        <v-row dense no-gutters>
                          <v-col cols="12" md="2" sm="2">
                            <div class="body-2 label--text"> Nama Peserta </div>
                            <div class="body-2 label--text"> Surel </div>
                          </v-col>
                          <v-col cols="12" md="6" sm="6">
                            <div class="body-2 black--text">: {{ $getDeepObj(peserta, 'ptk.data.nama') || '' }} </div>
                            <div class="body-2 black--text">: {{ $getDeepObj(peserta, 'ptk.data.email') || '' }} </div>
                          </v-col>
                        </v-row>
                      </v-list-item-content>
                    </v-list-item>
                  </v-col>
                </v-row>
              </v-list-item-content>
            </v-list-item>
          </v-col>
          <v-col cols="12" md="12" sm="12">
            <v-divider></v-divider>
            <div class="d-flex px-2">
              <div class="pa-2 font-weight-bold body-2 black--text">Indikator Penilaian</div>
              <div class="pa-2 ml-auto font-weight-bold body-2 black--text">Nilai</div>
            </div>
            <v-divider></v-divider>
          </v-col>
          <v-col cols="12" md="12" sm="12">
            <v-alert type="info" text>
              <span class="body-2"> Penilaian pada tahap <b>Pendalaman Materi</b></span>
            </v-alert>

            <template v-for="(ins, i) in instruments">
              <div class="d-flex px-2" :key="i">
                <div class="pa-2 body-2 black--text">
                  {{ i + 1 }}. <span v-html="$getDeepObj(ins, 'm_instrumen_nilai_luring_paud.data.keterangan')" />
                </div>
                <div class="pa-2 ml-auto body-2 black--text">
                  <v-text-field dense outlined type="number" v-model="form[ins.k_instrumen_nilai_luring_paud]">
                  </v-text-field>
                </div>
              </div>
            </template>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </div>
</template>
<script>
export default {
  props: {
    kelas: {
      type: Object,
      default: () => {},
    },
    initValue: {
      type: Object,
      default: () => null,
    },
  },
  data() {
    return {
      instruments: [],
      peserta: {},
      form: {},
    };
  },
  methods: {
    initForm(value) {
      if (!value) return;
      const { instruments, peserta } = value;
      this.$set(this, 'instruments', instruments || []);
      this.$set(this, 'peserta', peserta);

      instruments.forEach((item) => {
        this.$set(this.form, this.$getDeepObj(item, 'k_instrumen_nilai_luring_paud'), '');
      });
    },

    getValue() {
      // const form = this.form;
      // let result = [];
      // Object.keys(form).forEach((key) => {
      //   result.push({ k_instrumen_nilai_luring_paud: key, nilai: 22 });
      // });

      return this.form;
    },
  },
  watch: {
    initValue: 'initForm',
  },
};
</script>
