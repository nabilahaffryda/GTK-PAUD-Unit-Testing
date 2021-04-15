<template>
  <div>
    <v-card>
      <v-card-text>
        <div class="text-h4 font-weight-bold">Profil Pengajar</div>
        Lengkapi CV dibawah ini sesuai dengan form yang tersedia
        <v-row class="my-5">
          <v-col cols="12" md="2" sm="12"></v-col>
          <v-col cols="12" md="10" sm="12">
            <base-form-generator :schema="schema" v-model="form" />
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </div>
</template>
<script>
import BaseFormGenerator from '@components/base/BaseFormGenerator';
export default {
  props: {
    masters: {
      type: Object,
      default: () => {},
    },
    intiValue: {
      type: Object,
      default: () => null,
    },
  },
  components: { BaseFormGenerator },
  data() {
    return {
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
          label: 'Nama Lembaga',
          dense: true,
          hint: 'wajib diisi',
          required: true,
          hideDetails: false,
          outlined: true,
          singleLine: true,
          grid: { cols: 12, md: 12 },
          labelColor: 'secondary',
        },
        {
          type: 'VTextField',
          name: 'nik',
          label: 'NIK',
          hint: 'wajib diisi',
          required: true,
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
          name: 'email',
          label: `Alamat Surel`,
          labelColor: 'secondary',
          hideDetails: false,
          placeholder: 'Alamat Surel',
          hint: '',
          grid: { cols: 12, md: 6 },
          required: true,
          outlined: true,
          dense: true,
          singleLine: true,
        },
        {
          type: 'VTextField',
          name: 'tmp_lahir',
          label: `Tempat Lahir`,
          labelColor: 'secondary',
          hideDetails: false,
          placeholder: 'Tempat Lahir',
          hint: 'wajib diisi',
          grid: { cols: 12, md: 6 },
          required: true,
          outlined: true,
          dense: true,
          singleLine: true,
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
          grid: { cols: 12, md: 6 },
          labelColor: 'secondary',
        },
        {
          type: 'VTextarea',
          name: 'alamat',
          label: `Alamat`,
          labelColor: 'secondary',
          hideDetails: false,
          placeholder: 'alamat',
          hint: 'wajib diisi',
          grid: { cols: 12, md: 12 },
          required: false,
          outlined: true,
          dense: true,
          singleLine: true,
        },
        {
          type: 'cascade',
          configs: this.configs,
          grid: { cols: 12 },
          labelColor: 'secondary',
        },
        {
          type: 'VTextField',
          name: 'kodepos',
          label: 'Kode Pos',
          hint: 'wajib diisi',
          required: false,
          hideDetails: false,
          outlined: true,
          dense: true,
          singleLine: true,
          mask: '######',
          grid: { cols: 12, md: 6 },
          labelColor: 'secondary',
        },
        {
          type: 'VTextField',
          name: `no_hp`,
          label: `Nomor HP Aktif`,
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
      ];
    },
  },
  methods: {
    reset() {
      this.$set(this, 'reset', {});
    },
    initForm(value) {
      const formulir = [...(this.schema || []), { name: 'k_propinsi' }, { name: 'k_kota' }];
      for (const item of formulir) {
        if (item.name) {
          this.$set(this.form, item.name, this.$getDeepObj(value, item.name) || '');
        }
      }
      this.id = (value && value.paud_admin_id) || '';
    },
  },

  watch: {
    initValue: 'initForm',
  },
};
</script>
