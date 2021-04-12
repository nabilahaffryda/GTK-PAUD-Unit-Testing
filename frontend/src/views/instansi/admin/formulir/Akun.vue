<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-container>
        <v-stepper v-model="step" class="elevation-0">
          <v-stepper-header class="elevation-0" style="border: 1px solid rgba(0, 0, 0, 0.12)">
            <v-stepper-step :complete="step > 1" step="1">
              Data Admin Program
            </v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="2">
              Konfirmasi Akun
            </v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1" style="padding: 0">
              <template v-if="isChecked">
                <v-card flat>
                  <v-card-text class="pa-0 pt-7">
                    <base-form-generator :schema="schema.biodata.akun" v-model="form" />
                    <v-divider class="my-4" />
                  </v-card-text>
                  <v-card-actions class="pa-0">
                    <span class="grey--text font-italic">Form dengan tanda (*) wajib di isi</span>
                    <v-spacer></v-spacer>
                    <v-btn class="text-md-right" right color="primary" @click="$emit('onValidate')">
                      Selanjutnya
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </template>
              <template v-else>
                <v-row class="mt-2">
                  <v-col cols="12" md="10" sm="12">
                    <base-form-generator :schema="schema.unchecked" v-model="form" />
                  </v-col>
                  <v-col cols="12" md="2" sm="12" class="py-1">
                    <div class="white--text">d</div>
                    <v-btn depressed color="secondary" @click="onCheck"> CEK SUREL </v-btn>
                  </v-col>
                </v-row>
              </template>
            </v-stepper-content>
            <v-stepper-content step="2" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-7">
                  <h2 class="primary--text">Admin Program LPD</h2>
                  <span>
                    <i>{{ instansis[form.instansi_id] }}</i>
                  </span>
                  <v-row class="my-2">
                    <v-col cols="12" md="2" sm="12">
                      <v-avatar color="primary" size="100">
                        <v-icon dark size="80">mdi-account-circle</v-icon>
                      </v-avatar>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" class="px-0">
                      <base-list-info class="px-0" :info="info"></base-list-info>
                    </v-col>
                    <v-col cols="12" md="12" sm="12">
                      Selamat, <b>Admin Berhasil Baru Berhasil Ditambahkan.</b> Anda dapat mencetak data akun sebagai
                      bukti pembuatan akun dengan menekan tombol dibawah ini.
                    </v-col>
                  </v-row>
                </v-card-text>
                <v-card-actions class="pa-0">
                  <v-btn right color="primary" @click="step--">
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
export default {
  components: { BaseListInfo, BaseFormGenerator },
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
      type: Object,
      default: () => {},
    },
    jenis: {
      type: String,
      default: 'akun',
    },
    instansis: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      id: null,
      step: 1,
      form: {},
      info: [],
      isValid: false,
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
              label: `Alamat Surel`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Alamat Surel',
              hint: 'wajib diisi',
              grid: { cols: 12, md: 6 },
              required: true,
              outlined: true,
              dense: true,
              disable: true,
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
              type: 'VSelect',
              name: 'k_golongan',
              label: 'Golongan',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters.m_golongan),
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
              name: 'instansi_id',
              label: 'Instansi',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.instansis),
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
      this.step = 1;
      this.info = [];
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

    next() {
      this.info = [
        [
          {
            key: 'nama',
            label: 'Nama',
            value: this.$getDeepObj(this.form, 'nama') || '-',
          },
        ],
        [
          {
            key: 'lahir',
            label: 'Tempat, Tanggal Lahir',
            value: [
              this.$getDeepObj(this.form, 'tmp_lahir') || '-',
              this.$localDate(this.$getDeepObj(this.form, 'tgl_lahir') || '-'),
            ].join(', '),
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
            key: 'no_wa',
            label: 'Nomor Telepon (terhubung WhatsApp)',
            value: this.$getDeepObj(this.form, 'no_wa') || '-',
          },
          {
            key: 'email',
            label: 'Surel (untuk Kontak)',
            value: this.$getDeepObj(this.form, 'ptk.data.alt_email') || '-',
          },
        ],
      ];
      this.step = 2;
    },
  },
  watch: {
    initValue: 'initForm',
  },
};
</script>
