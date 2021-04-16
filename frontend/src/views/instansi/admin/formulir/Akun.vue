<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-container>
        <v-stepper v-model="step" class="elevation-0">
          <v-stepper-header class="elevation-0" style="border: 1px solid rgba(0, 0, 0, 0.12)">
            <v-stepper-step color="secondary" :complete="step > 1" step="1"> Tambah Akun </v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step color="secondary" :complete="step > 2" step="2"> Data {{ title }} </v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step color="secondary" step="3">
              Konfirmasi Akun
            </v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-7">
                  <base-form-generator :schema="schema.unchecked" v-model="form" />
                  <v-divider class="my-4" />
                </v-card-text>
                <v-card-actions class="pa-0">
                  <span class="grey--text font-italic">Form dengan tanda (*) wajib di isi</span>
                  <v-spacer></v-spacer>
                  <v-btn class="text-md-right" right color="secondary" @click="onCheck">
                    Selanjutnya
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-stepper-content>
            <v-stepper-content step="2" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-7">
                  <base-form-generator :schema="schema.biodata[jenis]" v-model="form" />
                  <v-divider class="my-4" />
                </v-card-text>
                <v-card-actions class="pa-0">
                  <span class="grey--text font-italic">Form dengan tanda (*) wajib di isi</span>
                  <v-spacer></v-spacer>
                  <v-btn
                    v-if="!isEdit"
                    right
                    text
                    @click="
                      () => {
                        step--;
                        $emit('onStep');
                      }
                    "
                  >
                    Kembali
                  </v-btn>
                  <v-btn class="text-md-right" right color="secondary" @click="$emit('onValidate')">
                    Selanjutnya
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-stepper-content>
            <v-stepper-content step="3" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-7">
                  <h2 class="secondary--text">Admin {{ title }}</h2>
                  <span>
                    <i>{{ instansis[form.instansi_id] }}</i>
                  </span>
                  <v-row class="my-2">
                    <v-col cols="12" md="2" sm="12">
                      <v-avatar color="secondary" size="100">
                        <v-icon dark size="80">mdi-account-circle</v-icon>
                      </v-avatar>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" class="px-0">
                      <base-list-info class="px-0" :info="info"></base-list-info>
                    </v-col>
                    <v-col cols="12" md="12" sm="12">
                      Silakan mengecek data <b>Akun Baru</b> yang akan <b>Ditambahkan</b>. Jika data sudah sesuai
                      silakan Simpan dan Cetak data Akun sebagai bukti pembuatan Akun dengan menekan tombol pada kanan
                      atas.
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
export default {
  components: { BaseListInfo, BaseFormGenerator },
  props: {
    title: {
      type: String,
      default: 'Akun Admin Program',
    },
    initValue: {
      default: () => null,
    },
    masters: {
      type: Object,
      default: () => {},
    },
    useUpload: {
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
      default: 'program',
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
          program: [
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
              disabled: this.isEdit,
              grid: { cols: 12, md: 6 },
              labelColor: 'secondary',
            },
          ],
          operator: [
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
          ],
          pengajar: [
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
      const formulir = [
        ...(this.schema.biodata[this.jenis] || []),
        { name: 'email' },
        { name: 'k_propinsi' },
        { name: 'k_kota' },
      ];
      for (const item of formulir) {
        if (item.name) {
          this.$set(this.form, item.name, this.$getDeepObj(value, item.name) || '');
        }
      }
      this.id = (value && value.paud_admin_id) || '';
    },

    getValue() {
      let keys = ['email', 'k_propinsi', 'k_kota'];
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

    next() {
      const konfirmasi = {
        biodata: [
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
              value: this.$getDeepObj(this.form, 'no_hp') || '-',
            },
            {
              key: 'email',
              label: 'Surel (untuk Kontak)',
              value: this.$getDeepObj(this.form, 'email') || '-',
            },
          ],
        ],
        program: [
          [
            {
              key: 'nip',
              label: 'NIP',
              value: this.$getDeepObj(this.form, 'nip') || '-',
            },
            {
              key: 'k_golongan',
              label: 'Golongan',
              value: this.masters.m_golongan[this.$getDeepObj(this.form, 'k_golongan')],
            },
          ],
          [
            {
              key: 'instansi_id',
              label: 'Instansi',
              value: this.instansis[this.$getDeepObj(this.form, 'instansi_id')],
            },
          ],
        ],
        operator: [
          [
            {
              key: 'nip',
              label: 'NIP',
              value: this.$getDeepObj(this.form, 'nip') || '-',
            },
            {
              key: 'k_golongan',
              label: 'Golongan',
              value: this.masters.m_golongan[this.$getDeepObj(this.form, 'k_golongan')],
            },
          ],
        ],
        pengajar: [
          [
            {
              key: 'nik',
              label: 'NIK',
              value: this.$getDeepObj(this.form, 'nik') || '-',
            },
            {
              key: 'alamat',
              label: 'Alamat',
              value: [
                this.$getDeepObj(this.form, 'alamat') || '-',
                [
                  this.masters.m_propinsi[this.$getDeepObj(this.form, 'k_propinsi')],
                  this.masters.m_kota[this.$getDeepObj(this.form, 'k_kota')],
                ].join(' - '),
              ].join('<br/>'),
            },
          ],
        ],
      };
      this.info = [...konfirmasi['biodata'], ...konfirmasi[this.jenis]];
      this.step++;
    },
  },
  watch: {
    initValue: 'initForm',
  },
};
</script>
