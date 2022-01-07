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
                <template v-if="peserta && peserta.is_nilai">
                  <v-chip color="success" dark>Sudah Dinilai</v-chip>
                </template>
                <template v-else>
                  <v-chip color="red" dark>Belum Dinilai</v-chip>
                </template>
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
                            <div class="body-2 black--text" id="nama"
                              >:
                              {{
                                $getDeepObj(peserta, 'ptk.data.nama') ||
                                $getDeepObj(peserta, 'paud_peserta_nonptk.data.nama') ||
                                ''
                              }}
                            </div>
                            <div class="body-2 black--text" id="email"
                              >:
                              {{
                                $getDeepObj(peserta, 'ptk.data.email') ||
                                $getDeepObj(peserta, 'paud_peserta_nonptk.data.email') ||
                                ''
                              }}
                            </div>
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
              <span class="body-2" id="tahap">
                Penilaian pada tahap <b>{{ is_pptm ? 'pelaksanaan tugas mandiri' : 'pendalaman materi' }}</b>
              </span>
            </v-alert>

            <template v-for="(ins, i) in instruments">
              <validation-provider
                mode="passive"
                :name="$getDeepObj(ins, 'm_instrumen_nilai_luring_paud.data.keterangan')"
                :rules="{ required: true, min: 0, max: 100 }"
                v-slot="{ errors }"
                :key="i"
              >
                <div class="d-flex px-2">
                  <div class="pa-2 body-2 black--text">
                    {{ i + 1 }}. <span v-html="$getDeepObj(ins, 'm_instrumen_nilai_luring_paud.data.keterangan')" />
                    <div v-if="errors.length" class="red--text caption">{{ errors && errors[0] }}</div>
                  </div>
                  <div class="pa-2 ml-auto body-2 black--text">
                    <v-text-field
                      dense
                      outlined
                      min="0"
                      max="100"
                      :readonly="!isEdit"
                      v-model="form[ins.k_instrumen_nilai_luring_paud]"
                      @keypress="checkFormat"
                      @input="checkNumber(form[ins.k_instrumen_nilai_luring_paud], ins.k_instrumen_nilai_luring_paud)"
                    />
                  </div>
                </div>
              </validation-provider>
            </template>
          </v-col>
          <v-col cols="12" md="12" sm="12">
            <v-divider></v-divider>
            <div class="d-flex px-2">
              <div class="pa-2 font-weight-bold body-2 black--text">Total</div>
              <div class="pa-2 ml-auto font-weight-bold body-2 black--text" id="total">{{ total }}</div>
            </div>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </div>
</template>
<script>
import { ValidationProvider } from 'vee-validate';

export default {
  name: 'FormPenilaianPesertaLuring',
  components: { ValidationProvider },
  props: {
    kelas: {
      type: Object,
      default: () => {},
    },
    initValue: {
      type: Object,
      default: () => null,
    },
    isEdit: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      instruments: [],
      peserta: {},
      form: {},
      is_pptm: false,
    };
  },
  computed: {
    total() {
      let result = 0;
      Object.keys(this.form).forEach((key) => {
        const nilai = this.instruments.find((s) => Number(s.k_instrumen_nilai_luring_paud) === Number(key)) || {};
        const bobot = nilai?.m_instrumen_nilai_luring_paud?.data?.n_bobot ?? 0;
        result += Number(this.form[key]) * (bobot / 100);
      });
      return (result || 0).toFixed(2);
    },
  },
  methods: {
    initForm(value) {
      if (!value) return;
      const { instruments, peserta, is_pptm } = value;
      this.$set(this, 'instruments', instruments || []);
      this.$set(this, 'peserta', peserta);
      this.$set(this, 'is_pptm', is_pptm);
      this.form = {};

      instruments.forEach((item) => {
        this.$set(this.form, this.$getDeepObj(item, 'k_instrumen_nilai_luring_paud'), (item && item.nilai) || '');
      });
    },

    getValue() {
      return this.form;
    },

    checkFormat(evt) {
      var theEvent = evt || window.event;

      // Handle paste
      if (theEvent.type === 'paste') {
        key = event.clipboardData.getData('text/plain');
      } else {
        // Handle key press
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
      }
      var regex = /[0-9]|\./;
      if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
      }

      key = String.fromCharCode(theEvent.keyCode || theEvent.which);
    },

    checkNumber(value, id) {
      if (Number(value) < 0 || Number(value) > 100) {
        this.form[id] = '';
      }
    },
  },
  watch: {
    initValue: 'initForm',
  },
};
</script>
