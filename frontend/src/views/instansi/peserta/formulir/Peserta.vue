<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-container>
        <v-stepper v-model="step" class="elevation-0">
          <v-stepper-header class="elevation-0" style="border: 1px solid rgba(0, 0, 0, 0.12); padding: 0 15%">
            <v-stepper-step :color="step > 1 ? 'success' : 'primary'" :complete="step > 1" step="1">
              Data Peserta
            </v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="2"> Konfirmasi Data </v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-7">
                  <div class="my-5">
                    <div class="text-h6 black--text my-4">Data Peserta</div>
                    <base-form-generator :schema="schema.umum" v-model="form" />
                  </div>
                  <div class="my-3">
                    <div class="text-h6 black--text my-4">Data Diklat</div>
                    <base-form-generator :schema="schema.diklat" v-model="form" />
                  </div>
                  <div class="my-3">
                    <div class="text-h6 black--text my-4">Data Unggahan</div>
                    <v-row>
                      <v-col cols="12" md="6" sm="12">
                        <span :class="[`px-0 body-2 secondary--text`]" style="height: 24px">
                          Unggah Sertifikat/Ijazah *
                          <v-icon v-if="false" small right color="orange">mdi-information</v-icon>
                        </span>
                        <template v-if="files && files.sertifikat">
                          <div class="mt-2">
                            <v-btn color="secondary" depressed small @click="onView('sertifikat')">
                              <v-icon left>mdi-eye</v-icon> Preview
                            </v-btn>
                            <v-btn class="mx-2" color="primary" depressed small @click="onDelete('sertifikat')">
                              <v-icon left>mdi-pencil</v-icon> Ubah Berkas
                            </v-btn>
                          </div>
                        </template>
                        <template v-else>
                          <validation-provider name="Sertifikat Diklat" rules="required" v-slot="{ errors }">
                            <v-file-input
                              v-model="form.file_sertifikat"
                              :error-messages="errors"
                              label="Pindaian Berkas Sertifikat (20 KB - 1,5 MB)"
                              append-icon="mdi-paperclip"
                              prepend-icon=""
                              accept="image/*,.pdf"
                              hint=""
                              :rules="[
                                (value) =>
                                  (value && value.size < roundDecimal(1500 * 1000)) ||
                                  'Berkas yang Anda upload melebihi kapasitas maksimum!',
                              ]"
                              persistent-hint
                              show-size
                              outlined
                              dense
                              single-line
                            ></v-file-input>
                          </validation-provider>
                        </template>
                      </v-col>
                      <v-col cols="12" md="6" sm="12">
                        <span :class="[`px-0 body-2 secondary--text`]" style="height: 24px">
                          Unggah Scan KTP
                          <v-icon v-if="false" small right color="orange">mdi-information</v-icon>
                        </span>
                        <template v-if="files && files.ktp">
                          <div class="mt-2">
                            <v-btn color="secondary" depressed small @click="onView('ktp')">
                              <v-icon left>mdi-eye</v-icon> Preview
                            </v-btn>
                            <v-btn class="mx-2" color="primary" depressed small @click="onDelete('ktp')">
                              <v-icon left>mdi-pencil</v-icon> Ubah Berkas
                            </v-btn>
                          </div>
                        </template>
                        <template v-else>
                          <v-file-input
                            v-model="form.file_ktp"
                            label="File scan KTP (20 KB - 1,5 MB)"
                            append-icon="mdi-paperclip"
                            prepend-icon=""
                            accept="image/*,.pdf"
                            hint=""
                            :rules="[
                              (value) =>
                                (value && value.size < roundDecimal(1500 * 1000)) ||
                                'Berkas yang Anda upload melebihi kapasitas maksimum!',
                            ]"
                            persistent-hint
                            show-size
                            outlined
                            dense
                            single-line
                          ></v-file-input>
                        </template>
                      </v-col>
                    </v-row>
                  </div>
                  <v-divider class="my-2" />
                </v-card-text>
                <v-card-actions class="pa-0">
                  <span class="grey--text font-italic">Form dengan tanda (*) wajib di isi</span>
                  <v-spacer></v-spacer>
                  <v-btn v-if="step !== 1" class="text-md-right" right text> Kembali </v-btn>
                  <v-btn class="text-md-right" right color="primary" @click="onValidate(true)"> Selanjutnya </v-btn>
                </v-card-actions>
              </v-card>
            </v-stepper-content>
            <v-stepper-content step="2" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-7">
                  <h2 class="secondary--text">Konfirmasi {{ isEdit ? 'Ubah' : 'Tambah' }} Peserta</h2>
                  <v-row class="my-2">
                    <v-col cols="12" md="2" sm="12">
                      <v-avatar color="primary" size="100">
                        <v-icon dark size="80">mdi-account-circle</v-icon>
                      </v-avatar>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" class="px-0">
                      <base-list-info class="px-0" tipe="row" :info="info"></base-list-info>
                      <div>
                        <span class="caption">File Sertifikat : </span>
                        <v-btn class="mx-2" color="blue" outlined small depressed @click="onView('sertifikat')">
                          Lihat Sertifikat
                        </v-btn>
                      </div>
                    </v-col>
                  </v-row>
                </v-card-text>
                <v-card-actions class="pa-0">
                  <v-spacer></v-spacer>
                  <v-btn
                    right
                    text
                    @click="
                      () => {
                        step--;
                        $emit('onStep');
                      }
                    "
                  >
                    Sebelumnya
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
import BaseFormGenerator from '@components/base/BaseFormGenerator';
import BaseListInfo from '@components/base/BaseListInfo';
import { ValidationProvider } from 'vee-validate';
import { roundDecimal } from '@utils/format';

export default {
  props: {
    masters: {
      type: Object,
      default: () => {},
    },
    initValue: {
      type: Object,
      default: () => null,
    },
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  components: { BaseFormGenerator, BaseListInfo, ValidationProvider },
  data() {
    return {
      step: null,
      form: {},
      files: {},
    };
  },
  computed: {
    configs() {
      const M_PROPINSI = this.masters.m_propinsi || {};
      const M_KOTA = this.masters.m_kota || {};
      return {
        selector: ['k_propinsi', 'k_kota'],
        required: ['k_propinsi', 'k_kota'],
        label: ['Provinsi', 'Kota/Kabupaten'],
        options: [M_PROPINSI, M_KOTA],
        grid: [{ cols: 6 }, { cols: 6 }],
      };
    },

    info() {
      return [
        [
          {
            key: 'nama',
            label: 'Nama',
            value: this.$getDeepObj(this.form, 'nama') || '-',
          },
          {
            key: 'email',
            label: 'Alamat Email',
            value: this.$getDeepObj(this.form, 'email') || '-',
          },
        ],
        [
          {
            key: 'nik',
            label: 'NIK',
            value: this.$getDeepObj(this.form, 'nik') || '-',
          },
          {
            key: 'no_hp',
            label: 'Nomor Telepon',
            value: this.$getDeepObj(this.form, 'no_hp') || '-',
          },
        ],
        [
          {
            key: 'lahir',
            label: 'Tempat, Tanggal Lahir',
            value: this.$getDeepObj(this.form, 'tmp_lahir') || '-',
          },
          {
            key: 'kelamin',
            label: 'Jenis Kelamin',
            value:
              this.$getDeepObj(this.form, 'kelamin') === 'L'
                ? 'Laki - laki'
                : this.$getDeepObj(this.form, 'kelamin') === 'P'
                ? 'Perempuan'
                : '',
          },
        ],
        [
          {
            key: 'tgl_lahir',
            label: 'Tanggal Lahir',
            value: this.$localDate(this.$getDeepObj(this.form, 'tgl_lahir') || '-'),
          },
          {
            key: 'unit_kerja',
            label: 'Unit Kerja',
            value: this.$getDeepObj(this.form, 'unit_kerja') || '-',
          },
        ],
        [
          {
            key: 'alamat',
            label: 'Alamat',
            value: this.$getDeepObj(this.form, 'alamat') || '-',
          },
          {
            key: 'jenjang_diklat',
            label: 'Jenis Diklat',
            value: [
              this.form['k_diklat_paud'] ? this.masters['m_diklat_paud'][Number(this.form['k_diklat_paud'])] : '',
              this.form['k_jenjang_diklat_paud']
                ? this.masters['m_jenjang_diklat_paud'][Number(this.form['k_jenjang_diklat_paud'])]
                : '',
            ].join('<br/>'),
          },
        ],
      ];
    },

    schema() {
      return {
        umum: [
          {
            type: 'VTextField',
            name: 'nama',
            label: 'Nama Lengkap',
            dense: true,
            hint: 'wajib diisi',
            required: true,
            hideDetails: false,
            outlined: true,
            singleLine: true,
            grid: { cols: 12, md: 6 },
            labelColor: 'secondary',
            counter: 100,
          },
          {
            type: 'VTextField',
            name: 'nik',
            label: 'NIK',
            hint: 'wajib diisi',
            hideDetails: false,
            outlined: true,
            dense: true,
            singleLine: true,
            required: true,
            mask: '################',
            counter: 16,
            grid: { cols: 12, md: 6 },
            labelColor: 'secondary',
          },
          {
            type: 'VTextField',
            name: 'tmp_lahir',
            label: 'Tempat Lahir',
            hint: 'wajib diisi',
            required: true,
            hideDetails: false,
            outlined: true,
            dense: true,
            singleLine: true,
            grid: { cols: 12, md: 4 },
            labelColor: 'secondary',
          },
          {
            type: 'VDatePicker',
            name: 'tgl_lahir',
            label: 'Tanggal Lahir',
            hint: 'wajib diisi',
            required: true,
            hideDetails: false,
            outlined: true,
            dense: true,
            singleLine: true,
            grid: { cols: 12, md: 4 },
            labelColor: 'secondary',
          },
          {
            type: 'VRadio',
            name: 'kelamin',
            label: 'Jenis Kelamin',
            hint: 'wajib diisi',
            items: [
              { value: 'L', text: 'Laki-Laki' },
              { value: 'P', text: 'Perempuan' },
            ],
            required: true,
            hideDetails: false,
            outlined: true,
            dense: true,
            row: true,
            singleLine: false,
            grid: { cols: 12, md: 4 },
            labelColor: 'secondary',
          },
          {
            type: 'VTextField',
            name: 'email',
            label: `Alamat Email`,
            labelColor: 'secondary',
            hideDetails: false,
            placeholder: 'Alamat Email',
            hint: 'wajib diisi',
            grid: { cols: 12, md: 6 },
            outlined: true,
            required: true,
            dense: true,
            disabled: false,
            singleLine: true,
          },
          {
            type: 'VTextField',
            name: `no_hp`,
            label: `Nomor HP/WA`,
            labelColor: 'secondary',
            hideDetails: false,
            placeholder: 'Nomor Handphone',
            hint: '',
            grid: { cols: 12, md: 6 },
            required: false,
            dense: true,
            outlined: true,
            singleLine: true,
            mask: '##############',
            counter: 14,
          },
          {
            type: 'VTextarea',
            name: 'alamat',
            label: 'Alamat Sesuai KTP',
            hint: 'wajib diisi',
            grid: { cols: 12 },
            required: true,
            outlined: true,
            dense: true,
            singleLine: true,
            labelColor: 'secondary',
          },
          {
            type: 'cascade',
            configs: this.configs,
            grid: { cols: 12 },
            labelColor: 'secondary',
          },
          {
            type: 'VTextField',
            name: 'unit_kerja',
            label: 'Unit Kerja',
            hint: '',
            grid: { cols: 12, md: 6, sm: 12 },
            required: false,
            outlined: true,
            dense: true,
            singleLine: true,
            labelColor: 'secondary',
          },
        ],
        diklat: [
          {
            type: 'VSelect',
            name: 'k_jenjang_diklat_paud',
            label: 'Jenjang Diklat',
            hint: 'wajib diisi',
            items: this.$mapForMaster(this.masters.m_jenjang_diklat_paud),
            value: 'value',
            text: 'text',
            required: true,
            hideDetails: false,
            outlined: true,
            dense: true,
            singleLine: true,
            grid: { cols: 12, md: 6 },
            labelColor: 'secondary',
          },
          {
            type: 'VSelect',
            name: 'k_diklat_paud',
            label: 'Jenis Diklat',
            hint: 'wajib diisi',
            items: this.$mapForMaster(this.masters.m_diklat_paud),
            value: 'value',
            text: 'text',
            required: true,
            hideDetails: false,
            outlined: true,
            dense: true,
            singleLine: true,
            grid: { cols: 12, md: 6 },
            labelColor: 'secondary',
          },
        ],
      };
    },
  },
  methods: {
    reset() {
      this.form = {};
      this.files = {};
      this.step = 1;
    },

    initForm(value) {
      if (!value) return;
      console.log(value);
      const formulir = [
        ...(this.schema.umum || []),
        ...(this.schema.diklat || []),
        { name: 'k_propinsi' },
        { name: 'k_kota' },
      ];
      for (const item of formulir) {
        if (item.name) {
          this.$set(this.form, item.name, this.$getDeepObj(value, item.name) || '');
        }
      }

      const urls = ['sertifikat', 'ktp'];

      urls.forEach((key) => {
        if (value[`${key}_url`]) {
          this.$set(this.files, key, value[`${key}_url`]);
        }
      });
    },

    onValidate() {
      this.$emit('validate');
    },

    next(status) {
      if (status) this.step = 2;
    },

    resetPilih() {
      this.$emit('reset');
    },

    roundDecimal(value) {
      return roundDecimal(value);
    },

    onDelete(tipe) {
      this.$delete(this.files, tipe);
    },

    onView(tipe) {
      const url = this.$getDeepObj(this.initValue, `${tipe}_url`);
      window.open(url, '_blank');
    },
  },
  watch: {
    initValue: 'initForm',
  },
};
</script>
