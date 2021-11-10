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
              <div class="my-5">
                <div class="text-h5 black--text my-4">Data Peserta</div>
                <base-form-generator :schema="schema" v-model="form" />
              </div>
            </v-stepper-content>
            <v-stepper-content step="2" style="padding: 0"> </v-stepper-content>
          </v-stepper-items>
        </v-stepper>
      </v-container>
    </v-card-text>
  </v-card>
</template>
<script>
import BaseFormGenerator from '@components/base/BaseFormGenerator';
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
  components: { BaseFormGenerator },
  data() {
    return {
      step: null,
      form: {},
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

    schema() {
      return [
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
          dense: true,
          disabled: true,
          singleLine: true,
        },
        {
          type: 'VTextField',
          name: `no_hp`,
          label: `Nomor HP/WA`,
          labelColor: 'secondary',
          hideDetails: false,
          placeholder: 'Nomor Handphone',
          hint: 'wajib diisi',
          grid: { cols: 12, md: 6 },
          required: true,
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
      ];
    },
  },
  methods: {
    reset() {
      this.form = {};
    },
  },
};
</script>
