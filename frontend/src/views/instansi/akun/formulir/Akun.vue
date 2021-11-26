<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <template v-if="useUpload && !isPilih && !isEdit">
        <h2 class="">Akun Baru</h2>
        <span class="my-2">
          Untuk menambah data akun baru, terdapat dua pilihan dengan cara unggah data melalui excel atau membuat akun
          persatu melalui sistem
        </span>
        <v-radio-group v-model="pilihan" hide-details class="pa-0 mt-3">
          <v-alert text outlined dense color="secondary">
            <v-radio value="manual">
              <template v-slot:label>
                <div class="pa-4">
                  <div class="font-weight-bold">Tambah {{ title }} Mengunakan Form Input</div>
                  <div class="caption">
                    Anda dapat menambahkan akun baru secara <b>manual satu persatu</b> melalui form input yang di
                    sediakan sistem
                  </div>
                </div>
              </template>
            </v-radio>
          </v-alert>
          <v-alert text outlined dense color="secondary">
            <v-radio value="excel">
              <template v-slot:label>
                <div class="pa-4">
                  <div class="font-weight-bold">Tambah {{ title }} Mengunakan Excel</div>
                  <div class="caption">
                    Anda dapat menambahkan template baru di bawah ini.
                    <ol>
                      <li>Download format template dibawah ini.</li>
                      <li>Silakan isi data sesuai format yang tersedia pada template</li>
                      <li>
                        Unggah Berkas yang sudah Anda isi pada langkah selanjutnya. Silakan tekan
                        <b>tombol selanjutnya</b>
                      </li>
                    </ol>
                    <v-btn
                      depressed
                      class="ma-2"
                      color="secondary"
                      @click="$emit('unduhTemplate')"
                      style="text-transform: none"
                    >
                      <v-icon left>mdi-file</v-icon>Template_akun_baru.xls
                    </v-btn>
                  </div>
                </div>
              </template>
            </v-radio>
          </v-alert>
        </v-radio-group>
        <v-divider class="my-2"></v-divider>
        <div class="text-md-right">
          <v-btn right color="primary" id="next" :disabled="!pilihan" @click="onPilih"> Selanjutnya </v-btn>
        </div>
      </template>
      <v-container v-if="!useUpload || isEdit ? true : isPilih === 'manual'">
        <v-stepper v-model="step" class="elevation-0">
          <v-stepper-header class="elevation-0" style="border: 1px solid rgba(0, 0, 0, 0.12); padding: 0 15%">
            <v-stepper-step :color="step > 1 ? 'success' : 'primary'" :complete="step > 1" step="1">
              Tambah Akun
            </v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step :color="step > 2 ? 'success' : 'primary'" :complete="step > 2" step="2">
              Data {{ title }}
            </v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="3"> Konfirmasi Akun </v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-7">
                  <v-alert class="mb-4" type="error" v-if="errorEmail">
                    {{ errorEmail }}
                  </v-alert>
                  <base-form-generator id="email-input" :schema="(schema && schema.unchecked) || []" v-model="form" />
                  <v-divider class="my-4" />
                </v-card-text>
                <v-card-actions class="pa-0">
                  <span class="grey--text font-italic">Form dengan tanda (*) wajib di isi</span>
                  <v-spacer></v-spacer>
                  <v-btn
                    id="back-step1"
                    v-if="useUpload || step !== 1"
                    class="text-md-right"
                    right
                    text
                    @click="onResetPilih"
                  >
                    Kembali
                  </v-btn>
                  <v-btn id="next-onCheck" class="text-md-right" right color="primary" @click="onCheck">
                    Selanjutnya
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-stepper-content>
            <v-stepper-content step="2" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-7">
                  <base-form-generator
                    id="biodata-input"
                    :schema="(schema.biodata && schema.biodata[jenis]) || []"
                    v-model="form"
                  />
                  <template v-if="jenis === 'program'">
                    <v-row>
                      <v-col cols="12" md="6">
                        <span class="px-0 body-2 secondary--text text--darken-4" style="height: 24px">
                          Instansi<span>*</span>
                        </span>
                        <v-autocomplete
                          v-model="form.instansi_id"
                          :items="this.instansis ? this.$mapForMaster(this.instansis || []) : []"
                          :search-input.sync="search"
                          item-text="text"
                          item-value="value"
                          label="Cari Instansi"
                          placeholder="Cari Instansi"
                          single-line
                          outlined
                          dense
                        ></v-autocomplete>
                      </v-col>
                    </v-row>
                  </template>
                  <v-divider class="my-4" />
                </v-card-text>
                <v-card-actions class="pa-0">
                  <span class="grey--text font-italic">Form dengan tanda (*) wajib di isi</span>
                  <v-spacer></v-spacer>
                  <v-btn
                    v-if="!isEdit"
                    right
                    text
                    id="back-step2"
                    @click="
                      () => {
                        step--;
                        $emit('onStep');
                      }
                    "
                  >
                    Kembali
                  </v-btn>
                  <v-btn id="next-OnValidate" class="text-md-right" right color="primary" @click="$emit('onValidate')">
                    Selanjutnya
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-stepper-content>
            <v-stepper-content step="3" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-7">
                  <h2 class="secondary--text">{{ title }}</h2>
                  <span>
                    <i>{{ instansis && instansis[form.instansi_id] }}</i>
                  </span>
                  <v-row class="my-2">
                    <v-col cols="12" md="2" sm="12">
                      <v-avatar color="primary" size="100">
                        <v-icon dark size="80">mdi-account-circle</v-icon>
                      </v-avatar>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" class="px-0">
                      <base-list-info id="info" class="px-0" :info="info"></base-list-info>
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
      <v-container v-else-if="isPilih === 'excel'">
        <v-stepper v-model="stepUnggah" class="elevation-0">
          <v-stepper-header class="elevation-0" style="border: 1px solid rgba(0, 0, 0, 0.12); padding: 0 15%">
            <v-stepper-step :color="stepUnggah > 1 ? 'success' : 'primary'" :complete="stepUnggah > 1" step="1">
              Isi Data Akun
            </v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step :color="stepUnggah > 2 ? 'success' : 'primary'" :complete="stepUnggah > 2" step="2">
              Konfirmasi Akun
            </v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-7">
                  <v-row class="my-2" dense no-gutters>
                    <v-col cols="12" md="2" sm="12" class="px-0">
                      <v-avatar color="primary" size="100">
                        <v-icon dark size="80">mdi-file-upload</v-icon>
                      </v-avatar>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" class="px-0">
                      <h2>Unggah Akun Secara Kolektif</h2>
                      <span
                        >Silakan unggah data {{ title }} yang sudah di isi sesuai format template yang telah Anda unduh
                        pada langkah sebelumnnya. Pastikan data yang Anda masukan bersifat final</span
                      >
                      <div class="mt-2">
                        <v-btn depressed color="primary" @click="$emit('upload')">
                          <v-icon left>mdi-upload</v-icon>Pilih Berkas
                        </v-btn>
                      </div>
                    </v-col>
                  </v-row>
                  <v-divider class="my-3" />
                </v-card-text>
                <v-card-actions class="pa-0">
                  <v-spacer></v-spacer>
                  <v-btn id="backStep1" class="text-md-right" right text @click="onResetPilih"> Kembali </v-btn>
                  <v-btn
                    id="nextOnvalidate"
                    class="text-md-right"
                    :disabled="!file"
                    right
                    color="primary"
                    @click="
                      () => {
                        $emit('onValidate');
                        stepUnggah++;
                      }
                    "
                  >
                    Selanjutnya
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-stepper-content>
            <v-stepper-content step="2" style="padding: 0">
              <v-card flat>
                <v-card-text class="pa-0 pt-7">
                  <h2 class="secondary--text">Konfirmasi Pembuatan Akun</h2>
                  <i>Tambah Data Menggunakan Excel</i>
                  <v-row class="my-2">
                    <v-col cols="12" md="2" sm="2">
                      <v-avatar color="primary" size="80">
                        <v-icon dark size="60">mdi-file</v-icon>
                      </v-avatar>
                    </v-col>
                    <v-col cols="12" md="10" sm="10" class="my-auto">
                      <div class="ml-n5">
                        <h3>Nama Berkas</h3>
                        {{ $getDeepObj(file, 'file.name') || '' }}
                      </div>
                    </v-col>
                    <v-col cols="12" md="12" sm="12">
                      Silakan periksa kembali akun yang Anda inputkan.
                      <b>Jika terjadi kesalahan input Anda dapat kembali ke langkah sebelumnya</b> dengan menekan tombol
                      dibawah ini
                    </v-col>
                  </v-row>
                </v-card-text>
                <v-card-actions class="pa-0">
                  <v-spacer></v-spacer>
                  <v-btn
                    id="backStep2"
                    right
                    text
                    @click="
                      () => {
                        $emit('onStep');
                        stepUnggah--;
                      }
                    "
                  >
                    Kembali
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
import debounce from 'lodash.debounce';
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
      pilihan: null,
      isPilih: '',
      stepUnggah: 1,
      file: null,
      search: '',
      keyword: '',
    };
  },
  computed: {
    configs() {
      const M_PROPINSI = (this.masters && this.masters.m_propinsi) || {};
      const M_KOTA = (this.masters && this.masters.m_kota) || {};
      return {
        selector: ['k_propinsi', 'k_kota'],
        required: this.jenis === 'kelas' ? [] : ['k_propinsi', 'k_kota'],
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
              counter: 100,
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
              items: this.$mapForMaster((this.masters && this.masters.m_golongan) || []),
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
          operator: [
            {
              type: 'VTextField',
              name: 'nama',
              label: 'Nama Operator',
              dense: true,
              hint: 'wajib diisi',
              required: true,
              hideDetails: false,
              outlined: true,
              singleLine: true,
              grid: { cols: 12, md: 12 },
              labelColor: 'secondary',
              counter: 100,
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
              items: this.$mapForMaster((this.masters && this.masters.m_golongan) || []),
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
              grid: { cols: 12, md: 12 },
              labelColor: 'secondary',
              counter: 100,
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
              grid: { cols: 12, md: 3 },
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
              grid: { cols: 12, md: 3 },
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
          ],
          kelas: [
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
              label: `Alamat Surel`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Alamat Surel',
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
          ],
          pembimbing: [
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
              label: `Alamat Surel`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Alamat Surel',
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
              type: 'cascade',
              configs: this.configs,
              grid: { cols: 12 },
              labelColor: 'secondary',
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
          ],
          admin: [
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
              counter: 100,
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
              items: this.$mapForMaster((this.masters && this.masters.m_golongan) || []),
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
      this.stepUnggah = 1;
      this.info = [];
      this.pilihan = null;
      this.isPilih = '';
      this.search = '';
      this.keyword = '';
      this.file = null;
    },

    initForm(value) {
      const formulir = [
        ...((this.schema && this.schema.biodata && this.schema.biodata[this.jenis]) || []),
        { name: 'email' },
        { name: 'k_propinsi' },
        { name: 'k_kota' },
      ];
      if (this.jenis === 'program') {
        formulir.push({ name: 'instansi_id' });
      }

      for (const item of formulir) {
        if (item.name) {
          this.$set(this.form, item.name, this.$getDeepObj(value, item.name) || '');
        }
      }
      this.id = (value && value.paud_admin_id) || '';

      // if (this.$getDeepObj(value, 'instansi.data.nama')) {
      //   this.$emit('getInstansi', this.$getDeepObj(value, 'instansi.data.nama') || '');
      this.keyword = this.$getDeepObj(value, 'instansi.data.nama') || '';
      // }
    },

    getValue() {
      let keys = ['email', 'k_propinsi', 'k_kota'];
      if (this.jenis === 'program') {
        keys.push('instansi_id');
      }

      keys = keys.concat(
        ((this.schema && this.schema.biodata && this.schema.biodata[this.jenis]) || []).map((item) => {
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
      if (!(this.form && this.form.email)) {
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
        pengajar: [],
        kelas: [
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
        pembimbing: [
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
        admin: [
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
      };
      this.info = [...konfirmasi['biodata'], ...konfirmasi[this.jenis]];
      this.step++;
    },

    onPilih() {
      this.isPilih = this.pilihan;
    },

    onResetPilih() {
      this.pilihan = null;
      this.isPilih = null;
    },

    setFile(file) {
      this.file = file;
    },

    searchInstansi: debounce(function (e) {
      this.$emit('getInstansi', e || this.keyword || '');
    }, 500),
  },
  watch: {
    initValue: 'initForm',
    search: 'searchInstansi',
  },
};
</script>
