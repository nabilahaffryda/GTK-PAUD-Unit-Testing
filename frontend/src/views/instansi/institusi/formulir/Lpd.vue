<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-container>
        <v-stepper v-model="step" class="elevation-0">
          <v-stepper-header class="elevation-0" style="border: 1px solid rgba(0, 0, 0, 0.12); padding: 0 15%">
            <v-stepper-step :color="step > 1 ? 'success' : 'primary'" :complete="step > 1" step="1">
              Data Institusi LPD
            </v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="2"> Konfirmasi Data </v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-3">
                  <h1 class="title mb-3">Data Institusi LPD</h1>
                  <base-form-generator :schema="schema.dasar" v-model="form" />
                  <h1 class="title my-3">Pengaturan Petugas Diklat</h1>
                  <base-form-generator :schema="schema.petugas" v-model="form" />
                  <v-divider class="my-4" />
                </v-card-text>
                <v-card-actions class="pa-0">
                  <span class="secondary--text font-italic">Form dengan tanda (*) wajib di isi</span>
                  <v-spacer></v-spacer>
                  <v-btn class="text-md-right" right color="primary" @click="$emit('onValidate')"> Selanjutnya </v-btn>
                </v-card-actions>
              </v-card>
            </v-stepper-content>
            <v-stepper-content step="2" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-7">
                  <h2 class="primary--text">Data Institusi LPD</h2>
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
                      Selamat, <b>Institusi LPD Baru Berhasil Ditambahkan.</b> Anda dapat mencetak data akun sebagai
                      bukti pembuatan akun dengan menekan tombol dibawah ini.
                    </v-col>
                  </v-row>
                </v-card-text>
                <v-card-actions class="pa-0">
                  <v-btn right color="primary" @click="back"> Sebelumnya </v-btn>
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
    isEdit: {
      type: Boolean,
      default: false,
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
    configs() {
      const M_PROPINSI = this.masters.m_propinsi || {};
      const M_KOTA = this.masters.m_kota || {};
      return {
        selector: ['k_propinsi', 'k_kota'],
        required: ['k_propinsi', 'k_kota'],
        label: ['Provinsi', 'Kota/Kabupaten'],
        options: [M_PROPINSI, M_KOTA],
        grid: [{ cols: 6 }, { cols: 6 }],
        disabled: [this.isEdit || false, this.isEdit || false],
      };
    },
    schema() {
      const mPengajar = [];

      for (let i = 2; i <= 8; i++) {
        mPengajar.push({ value: i, text: i });
      }

      return {
        dasar: [
          {
            type: 'VTextField',
            name: 'nama',
            label: 'Nama Institusi',
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
            singleLine: true,
          },
          {
            type: 'VTextarea',
            name: 'alamat',
            label: 'Alamat',
            hint: 'wajib diisi',
            labelColor: 'secondary',
            required: true,
            grid: { cols: 12 },
            outlined: true,
            dense: true,
            singleLine: true,
            disabled: this.isEdit || false,
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
            disabled: this.isEdit || false,
          },
          {
            type: 'VTextField',
            name: 'nama_penanggung_jawab',
            label: 'Penanggung Jawab',
            dense: true,
            hint: 'wajib diisi',
            required: true,
            hideDetails: false,
            outlined: true,
            singleLine: true,
            grid: { cols: 12, md: 6 },
            labelColor: 'secondary',
            disabled: this.isEdit || false,
          },
          {
            type: 'VTextField',
            name: `telp_penanggung_jawab`,
            label: `No. Telpon (Penanggung Jawab)`,
            labelColor: 'secondary',
            hideDetails: false,
            placeholder: 'Nomor Telpon Penanggung Jawab',
            hint: 'wajib diisi',
            grid: { cols: 12, md: 6 },
            required: true,
            dense: true,
            outlined: true,
            singleLine: true,
            mask: '##############',
            counter: 14,
            disabled: this.isEdit || false,
          },
          {
            type: 'VSelect',
            name: 'k_lpd_paud',
            label: 'Level LPD',
            hint: 'wajib diisi',
            required: true,
            hideDetails: false,
            outlined: true,
            dense: true,
            singleLine: true,
            items: this.$mapForMaster(this.$getDeepObj(this, 'masters.m_lpd_paud') || {}),
            grid: { cols: 12, md: 6 },
            labelColor: 'secondary',
          },
        ],
        petugas: [
          {
            type: 'VTextField',
            name: 'ratio_pengajar_tambahan',
            label: 'Persentase Pengajar Tambahan',
            hint: 'wajib diisi',
            required: true,
            hideDetails: false,
            outlined: true,
            dense: true,
            singleLine: true,
            mask: '###',
            grid: { cols: 12, md: 6 },
            labelColor: 'secondary',
            suffix: '%',
          },
          // {
          //   type: 'VSelect',
          //   name: 'jml_pembimbing',
          //   label: 'Jumlah Pembimbing Praktik',
          //   hint: 'wajib diisi',
          //   required: true,
          //   hideDetails: false,
          //   outlined: true,
          //   dense: true,
          //   singleLine: true,
          //   items: mPengajar,
          //   grid: { cols: 12, md: 6 },
          //   labelColor: 'secondary',
          // },
        ],
      };
    },
  },
  methods: {
    reset() {
      this.$set(this, 'id', null);
      this.$set(this, 'form', {});
      this.step = 1;
      this.info = [];
      this.id = null;

      // Set default nilai pengaturan petugas diklat
      this.$set(this.form, 'ratio_pengajar_tambahan', 40);
      // this.$set(this.form, 'jml_pembimbing', 2);
    },

    initForm(value) {
      const formulir = [
        ...(this.schema.dasar || []),
        ...(this.schema.petugas || []),
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
      let keys = [...this.schema.dasar, ...this.schema.petugas].map((item) => {
        if (!item.disabled) {
          return item.name;
        }
      });

      if (!this.isEdit) {
        keys = [...keys, 'k_propinsi', 'k_kota'];
      }

      let params = {};
      for (const id of keys) {
        params[id] = this.form[id];
      }

      return params;
    },

    back() {
      this.step--;
      this.$emit('onBack', this.step);
    },

    next() {
      this.info = [
        [
          {
            key: 'nama',
            label: 'Nama',
            value: this.$getDeepObj(this.form, 'nama') || '-',
          },
          {
            key: 'email',
            label: 'Surel (untuk Kontak)',
            value: this.$getDeepObj(this.form, 'email') || '-',
          },
        ],
        [
          {
            key: 'alamat',
            label: 'Alamat',
            value: this.$fAlamat([
              this.$getDeepObj(this.form, 'alamat') || '-',
              false,
              false,
              false,
              false,
              this.$getDeepObj(this.masters, `m_kota.${this.$getDeepObj(this.form, 'k_kota')}`) || '-',
              this.$getDeepObj(this.masters, `m_propinsi.${this.$getDeepObj(this.form, 'k_propinsi')}`) || '-',
            ]),
            size: 12,
          },
        ],
        [
          {
            key: 'kodepos',
            label: 'Kode Pos ',
            value: this.$getDeepObj(this.form, 'kodepos') || '-',
          },
          {
            key: 'level',
            label: 'Level LPD',
            value: this.$getDeepObj(this, `masters.m_lpd_paud.${this.$getDeepObj(this.form, 'k_lpd_paud')}`),
          },
        ],
        [
          {
            key: 'nama_penanggung_jawab',
            label: 'Penanggung Jawab',
            value: this.$getDeepObj(this.form, 'nama_penanggung_jawab') || '-',
          },
          {
            key: 'ratio_pengajar_tambahan',
            label: 'Persentase Pengajar Tambahan',
            value: this.$getDeepObj(this.form, 'ratio_pengajar_tambahan')
              ? `${this.$getDeepObj(this.form, 'ratio_pengajar_tambahan')}%`
              : '-',
          },
        ],
        [
          {
            key: 'telp_penanggung_jawab',
            label: 'No. Telpon (Penanggung Jawab)',
            value: this.$getDeepObj(this.form, 'telp_penanggung_jawab') || '-',
          },
          // {
          //   key: 'jml_pembimbing',
          //   label: 'Jumlah Pembimbing Praktik',
          //   value: this.$getDeepObj(this.form, 'jml_pembimbing') || '-',
          // },
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
