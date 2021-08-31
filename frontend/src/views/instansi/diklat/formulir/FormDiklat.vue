<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-container>
        <div v-for="(schema, s) in schemas[type]" :key="s">
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
    initValue: {
      type: Object,
      default: () => null,
    },
    periodes: {
      type: Array,
      default: () => [],
    },
    mapels: {
      type: Array,
      default: () => [],
    },
    type: {
      type: String,
      default: 'diklat',
    },
    detail: {
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
    mPeriode() {
      return this.$arrToObj(this.periodes, 'paud_periode_id');
    },

    mMapel() {
      return this.$arrToObj(this.periodes, 'paud_mapel_kelas_id');
    },

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
          disabled: [true, true, false, false],
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
      const mPengajar = [];

      for (let i = 3; i <= 9; i++) {
        mPengajar.push({ value: i, text: i });
      }

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
              type: 'VTextarea',
              name: 'deskripsi',
              label: 'Deskripsi',
              hint: '',
              placeholder: 'Isi Deskripsi',
              required: false,
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
              type: 'VSelect',
              name: 'paud_periode_id',
              label: 'Tahapan Diklat',
              hint: '',
              placeholder: '',
              items: this.periodes,
              itemValue: 'paud_periode_id',
              itemText: 'nama',
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
              type: 'VTextField',
              name: 'tgl_daftar_mulai',
              label: 'Tanggal Mulai',
              dense: true,
              hint: '',
              placeholder: '',
              required: false,
              hideDetails: false,
              outlined: true,
              readonly: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'tgl_daftar_selesai',
              label: 'Tanggal Selesai',
              dense: true,
              hint: '',
              placeholder: '',
              required: false,
              hideDetails: false,
              outlined: true,
              readonly: true,
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
              name: 'paud_mapel_kelas_id',
              label: 'Mata Pelajaran',
              hint: 'wajib dipilih',
              items: this.mapels,
              itemValue: 'paud_mapel_kelas_id',
              itemText: 'nama',
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
              name: 'jml_pengajar',
              label: 'Jumlah Pengajar',
              hint: 'wajib dipilih',
              required: true,
              hideDetails: false,
              outlined: true,
              dense: true,
              singleLine: true,
              items: mPengajar,
              grid: { cols: 12, md: 6 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextarea',
              name: 'deskripsi',
              label: 'Deskripsi',
              hint: '',
              placeholder: 'Isi Deskripsi Kelas',
              required: false,
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
      if (this.type === 'kelas') {
        this.$set(this.form, 'k_propinsi', this.$getDeepObj(this.detail, 'k_propinsi'));
        this.$set(this.form, 'k_kota', this.$getDeepObj(this.detail, 'k_kota'));
      }
    },

    getValue() {
      return { form: this.form };
    },

    initForm(value) {
      const formulir = [
        ...(this.schemas.diklat.tambah_diklat || []),
        ...(this.schemas.diklat.pendaftaran_peserta || []),
        ...(this.schemas.kelas.tambah_kelas || []),
        { name: 'k_propinsi' },
        { name: 'k_kota' },
        { name: 'k_kecamatan' },
        { name: 'k_kelurahan' },
      ];

      for (const item of formulir) {
        if (item.name) {
          this.$set(this.form, item.name, this.$getDeepObj(value, item.name) || '');
        }
      }
    },
  },

  watch: {
    initValue: 'initForm',
    'form.paud_periode_id': {
      handler(value) {
        if (!value) return;
        this.$set(this.form, 'tgl_daftar_mulai', this.mPeriode[value]['tgl_daftar_mulai']);
        this.$set(this.form, 'tgl_daftar_selesai', this.mPeriode[value]['tgl_daftar_selesai']);
      },
      deep: true,
    },
  },
};
</script>
