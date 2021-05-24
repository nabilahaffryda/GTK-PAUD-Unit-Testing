<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-container>
        <div v-for="(schema, s) in schemas['diklat']" :key="s">
          <div class="body-1 font-weight-medium my-4">{{ $titleCase(s.replace('_', ' ')) }}</div>
          <base-form-generator :schema="schema" v-model="form" />
        </div>
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
        kelas: {
          selector: ['k_propinsi', 'k_kota', 'k_kecamatan', 'k_kelurahan'],
          required: ['k_propinsi', 'k_kota', 'k_kecamatan', 'k_kelurahan'],
          label: ['Provinsi', 'Kota/Kabupaten', 'Kecamatan', 'Kelurahan'],
          options: [M_PROPINSI, M_KOTA, M_KECAMATAN, M_KELURAHAN],
          grid: [{ cols: 6 }, { cols: 6 }, { cols: 6 }, { cols: 6 }],
          useSchema: true,
        },
        diklat: {
          selector: ['k_propinsi', 'k_kota'],
          required: ['k_propinsi', 'k_kota'],
          label: ['Provinsi', 'Kota/Kabupaten'],
          options: [M_PROPINSI, M_KOTA],
          grid: [{ cols: 6 }, { cols: 6 }],
        },
      };
    },

    schemas() {
      return {
        diklat: {
          tambah_diklat: [
            {
              type: 'VTextField',
              name: 'nama',
              label: 'Nama Diklat',
              dense: true,
              hint: 'wajib diisi',
              placeholder: 'Isikan Nama Diklat',
              required: true,
              hideDetails: false,
              outlined: true,
              singleLine: true,
              grid: { cols: 12, md: 12 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'nama_singkat',
              label: 'Nama Singkat Diklat',
              hint: '',
              placeholder: 'Isikan Nama Singkat Diklat',
              required: true,
              grid: { cols: 12 },
              outlined: true,
              dense: true,
              singleLine: true,
              labelColor: 'secondary',
            },
            {
              type: 'VTextarea',
              name: 'deskripsi',
              label: 'Deskripsi',
              hint: '',
              placeholder: 'Isi Deskripsi',
              required: true,
              grid: { cols: 12 },
              outlined: true,
              dense: true,
              singleLine: true,
              labelColor: 'secondary',
            },
            {
              type: 'cascade',
              configs: this.configs.diklat,
              grid: { cols: 12 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'tahapan',
              label: 'Tahapan Diklat',
              hint: '',
              placeholder: '',
              required: true,
              grid: { cols: 12 },
              outlined: true,
              dense: true,
              singleLine: true,
              labelColor: 'secondary',
            },
          ],
          pendaftaran_peserta: [
            {
              type: 'VDatePicker',
              name: 'tgl_mulai',
              label: 'Tanggal Mulai',
              dense: true,
              hint: 'wajib diisi',
              placeholder: '',
              required: true,
              hideDetails: false,
              outlined: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
              labelColor: 'secondary',
            },
            {
              type: 'VDatePicker',
              name: 'tgl_selesai',
              label: 'Tanggal Selesai',
              dense: true,
              hint: 'wajib diisi',
              placeholder: '',
              required: true,
              hideDetails: false,
              outlined: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
              labelColor: 'secondary',
            },
          ],
        },
        kelas: {
          tambah_kelas: [
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
              type: 'VSelect',
              name: 'mapel',
              label: 'Mata Pelajaran',
              hint: 'wajib dipilih',
              items: [],
              required: true,
              hideDetails: false,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextarea',
              name: 'deskripsi',
              label: 'Deskripsi',
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
              configs: this.configs.kelas,
              grid: { cols: 12 },
              labelColor: 'secondary',
            },
          ],
        },
      };
    },
  },
  methods: {
    reset() {
      this.form = {};
    },
  },
};
</script>
