<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-container>
        {{ form }}
        <base-form-generator :schema="schema" v-model="form" />
      </v-container>
    </v-card-text>
  </v-card>
</template>
<script>
import BaseFormGenerator from '@components/base/BaseFormGenerator';
export default {
  components: { BaseFormGenerator },
  props: {
    masters: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      form: {},
    };
  },
  computed: {
    configs() {
      const M_PROPINSI = this.masters.m_propinsi || {};
      const M_KOTA = this.masters.m_kota || {};
      const M_KECAMATAN = this.masters.m_kecamatan || {};
      const M_KELURAHAN = this.masters.m_kelurahan || {};
      return {
        selector: ['k_propinsi', 'k_kota', 'k_kecamatan', 'k_kelurahan'],
        required: ['k_propinsi', 'k_kota', 'k_kecamatan', 'k_kelurahan'],
        label: ['Provinsi', 'Kota/Kabupaten', 'Kecamatan', 'Kelurahan'],
        options: [M_PROPINSI, M_KOTA, M_KECAMATAN, M_KELURAHAN],
        grid: [{ cols: 6 }, { cols: 6 }, { cols: 6 }, { cols: 6 }],
        useSchema: true,
      };
    },

    schema() {
      return [
        {
          type: 'VTextField',
          name: 'nama',
          label: 'Nama Kelas',
          dense: true,
          hint: 'wajib diisi',
          placeholder: 'Isikan Nama Kelas',
          required: true,
          hideDetails: false,
          outlined: true,
          singleLine: true,
          grid: { cols: 12, md: 12 },
          labelColor: 'secondary',
        },
        {
          type: 'VTextarea',
          name: 'deskripsi',
          label: 'Alamat',
          hint: '',
          placeholder: 'Isi Deskripsi Kelas',
          required: true,
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
