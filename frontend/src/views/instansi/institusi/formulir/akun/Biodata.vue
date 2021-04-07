<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-container>
        <h2 class="secondary--text mb-3">Lengkapi Formulir Data Akun dibawah ini</h2>
        <template>
          <v-row v-if="!isChecked">
            <v-col cols="10" md="6">
              <base-form-generator :schema="schema.unchecked" v-model="form" />
            </v-col>
            <v-col cols="2">
              <v-btn class="mt-3" depressed dark color="secondary" @click="onCheck">CEK SUREL</v-btn>
            </v-col>
          </v-row>
          <v-row v-else>
            <v-col cols="10" md="6">
              <base-form-generator :schema="schema.checked" v-model="form" />
            </v-col>
            <v-col cols="2">
              <v-btn class="mt-3" v-if="!isEdit" depressed dark color="secondary" @click="onUncheck">
                GANTI SUREL
              </v-btn>
            </v-col>
          </v-row>
        </template>
        <v-alert class="mt-7 mb-2" type="error" v-show="errorEmail">
          {{ errorEmail }}
        </v-alert>
        <base-form-generator :schema="schema.biodata[`${jenis}`]" v-show="isChecked" v-model="form" />
      </v-container>
    </v-card-text>
  </v-card>
</template>
<script>
import BaseFormGenerator from '@/components/base/BaseFormGenerator';
export default {
  components: { BaseFormGenerator },
  props: {
    initValue: {
      default: () => null,
    },
    masters: {
      type: Object,
      default: () => {},
    },
    isChecked: {
      type: Boolean,
      default: false,
    },
    isEdit: {
      type: Boolean,
      default: false,
    },
    errorEmail: {
      type: String,
      default: null,
    },
    groups: {
      type: Array,
      default: () => [],
    },
    jenis: {
      type: String,
      default: 'akun',
    },
  },
  data() {
    return {
      id: null,
      form: {},
      biodata: {},
    };
  },
  computed: {
    schema() {
      let form = {
        unchecked: [
          {
            type: 'VTextField',
            name: 'email',
            label: `Alamat Surel`,
            labelColor: 'secondary',
            hideDetails: false,
            placeholder: 'Alamat Surel',
            hint: 'wajib diisi',
            grid: { cols: 12 },
            required: true,
            outlined: true,
            dense: true,
            singleLine: true,
          },
        ],
        checked: [
          {
            type: 'VTextField',
            name: 'email',
            label: `Alamat Surel`,
            labelColor: 'secondary',
            hideDetails: false,
            placeholder: 'Alamat Surel',
            hint: 'wajib diisi',
            grid: { cols: 12 },
            disabled: true,
            required: true,
            outlined: true,
            dense: true,
            singleLine: true,
          },
        ],
        biodata: {
          akun: [
            {
              type: 'VTextField',
              name: 'nama',
              label: 'Nama Admin',
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
              name: 'nip',
              label: 'NIP',
              hint: 'wajib diisi',
              required: false,
              hideDetails: false,
              outlined: true,
              dense: true,
              singleLine: true,
              mask: '####################',
              counter: 20,
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
              grid: { cols: 12, md: 6 },
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
              grid: { cols: 12, md: 6 },
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
              grid: { cols: 12, md: 6 },
              labelColor: 'secondary',
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
              type: 'VSelect',
              name: 'k_group',
              label: 'Peran',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.groups),
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
        },
      };

      return form;
    },
  },
  methods: {
    reset() {
      this.$set(this, 'id', null);
      this.$set(this, 'form', {});
      this.biodata = {};
      this.id = null;
    },

    initForm(value) {
      const formulir = [...(this.schema.biodata[this.jenis] || []), { name: 'email' }];
      for (const item of formulir) {
        if (item.name) {
          this.$set(this.form, item.name, this.$getDeepObj(value, item.name) || '');
        }
      }
      this.id = (value && value.akun_instansi_id) || '';
    },

    getValue() {
      let keys = ['email'];
      keys = keys.concat(
        (this.schema.biodata[this.jenis] || []).map((item) => {
          return item.name;
        })
      );

      let params = {};
      for (const id of keys) {
        params[id] = this.form[id];
      }

      return params;
    },

    onCheck() {
      if (!this.form.email) {
        return;
      }

      this.$emit('check', this.form.email);
    },

    onUncheck() {
      this.$emit('unCheck');
    },
  },
  watch: {
    initValue: 'initForm',
  },
};
</script>
