<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-container>
        <v-stepper v-model="step">
          <v-stepper-header>
            <v-stepper-step step="1">
              Data Admin Program
            </v-stepper-step>
            <v-stepper-step step="2">
              Konfirmasi Akun
            </v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1">

            </v-stepper-content>
            <v-stepper-content step="2">

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
      step: 1,
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
