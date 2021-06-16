<template>
  <div>
    <v-card>
      <v-card-text>
        <div class="text-h4 font-weight-bold">{{ title }}</div>
        Lengkapi CV dibawah ini sesuai dengan form yang tersedia
        <v-row class="my-5">
          <v-col cols="12" md="2" sm="12">
            <base-photo-profil
              :photo="
                photo ||
                this.$getDeepObj(detail, 'akun.data.foto_url') ||
                this.$getDeepObj(detail, 'instansi.data.foto_url') ||
                ''
              "
              :photodef="jenis === 'lpd' ? 'default_foto_lpd.png' : 'default_foto_gp.png'"
              :useBase64="true"
              :use-trigger="false"
              is-edit
              @upload="onChangePhoto"
            >
              <template v-slot:render>
                <v-btn class="mt-2" id="edit-profpic" depressed block color="secondary"> UNGGAH FOTO </v-btn>
              </template>
            </base-photo-profil>
          </v-col>
          <v-col cols="12" md="10" sm="12">
            <div v-for="(item, i) in schema" :key="i">
              <div v-if="i !== 'dasar'" class="text-h6 my-3 font-weight-bold"> Data {{ $titleCase(i) }} </div>
              <base-form-generator :schema="item" v-model="form" />
            </div>

            <template v-if="jenis === 'lpd'">
              <v-alert type="info" class="mt-2"
                >Tambahkan data diklat minimal <b>1</b> dan maksimal <b>5 diklat</b></v-alert
              >
              <div>
                <v-row v-for="(diklat, i) in diklats" :key="i">
                  <v-col cols="12" md="5" sm="12">
                    <span class="text-caption secondary--text">Nama Diklat</span>
                    <v-text-field label="Nama Diklat" v-model="diklats[i]['nama']" outlined dense single-line />
                  </v-col>
                  <v-col cols="12" md="5" sm="12">
                    <span class="text-caption secondary--text">Tahun Diklat</span>
                    <v-text-field label="Tahun Diklat" v-model="diklats[i]['tahun']" outlined dense single-line />
                  </v-col>
                  <v-col cols="12" md="2" sm="12" class="my-auto">
                    <template v-if="i === diklats.length - 1">
                      <v-btn class="mb-1" depressed @click="onAdd(i)">
                        <v-icon>mdi-plus</v-icon>
                      </v-btn>
                    </template>
                    <template v-else>
                      <v-btn color="red" dark class="mb-1" depressed @click="onRemove(i)">
                        <v-icon>mdi-delete</v-icon>
                      </v-btn>
                    </template>
                  </v-col>
                </v-row>
              </div>
            </template>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </div>
</template>
<script>
import BaseFormGenerator from '@components/base/BaseFormGenerator';
import BasePhotoProfil from '@components/base/BasePhotoProfil';
export default {
  props: {
    masters: {
      type: Object,
      default: () => {},
    },
    initValue: {
      type: Object,
      default: () => null,
    },
    detail: {
      type: Object,
      default: () => {},
    },
    title: {
      type: String,
      default: 'pengajar',
    },
    jenis: {
      type: String,
      default: 'pengajar',
    },
  },
  components: { BaseFormGenerator, BasePhotoProfil },
  data() {
    return {
      form: {},
      diklats: [],
      photo: '',
      objPhoto: null,
    };
  },
  computed: {
    mKualifikasi() {
      return this.$mapForMaster(this.masters.m_kualifikasi || {}).filter((s) => [9, 10, 11].includes(s.value)) || [];
    },

    configs() {
      const M_PROPINSI = this.masters.m_propinsi || {};
      const M_KOTA = this.masters.m_kota || {};
      const config = {
        selector: ['k_propinsi', 'k_kota'],
        required: ['k_propinsi', 'k_kota'],
        label: ['Provinsi', 'Kota/Kabupaten'],
        options: [M_PROPINSI, M_KOTA],
        grid: [{ cols: 6 }, { cols: 6 }],
      };
      return {
        rumah: Object.assign({}, config),
        instansi: Object.assign({}, config, {
          selector: ['instansi_k_propinsi', 'instansi_k_kota'],
          required: ['instansi_k_propinsi', 'instansi_k_kota'],
          label: ['Provinsi Instansi Asal', 'Kota/Kabupaten Instansi Asal'],
        }),
      };
    },

    schema() {
      const forms = {
        pengajar: {
          dasar: [
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
              rules: { required: true, nik: true },
              mask: '################',
              counter: 16,
              grid: { cols: 12, md: 6 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'nip',
              label: 'NIP/NUPTK',
              hint: '',
              required: false,
              hideDetails: false,
              outlined: true,
              dense: true,
              singleLine: true,
              mask: '##############################',
              counter: 30,
              grid: { cols: 12, md: 6 },
              labelColor: 'secondary',
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
              type: 'VSelect',
              name: 'kelamin',
              label: `Jenis Kelamin`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Jenis Kelamin',
              hint: '',
              grid: { cols: 12, md: 6 },
              items: [
                { value: 'L', text: 'Laki-Laki' },
                { value: 'P', text: 'Perempuan' },
              ],
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
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
              disabled: true,
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VTextField',
              name: 'lulusan',
              label: `Institusi Pendidikan Terakhir`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Institusi Pendidikan Terakhir',
              grid: { cols: 12, md: 4, sm: 12 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VTextField',
              name: 'prodi',
              label: `Prodi`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'prodi',
              grid: { cols: 12, md: 4, sm: 12 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_kualifikasi',
              label: 'Jenjang',
              hint: 'wajib diisi',
              items: this.mKualifikasi,
              value: 'value',
              text: 'text',
              required: true,
              hideDetails: false,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 4 },
              labelColor: 'secondary',
            },
            {
              type: 'cascade',
              configs: this.configs.rumah,
              grid: { cols: 8 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'kodepos',
              label: 'Kode Pos',
              hint: '',
              required: false,
              hideDetails: false,
              outlined: true,
              dense: true,
              singleLine: true,
              labelClass: 'mt-n3 secondary--text px-0 body-2',
              mask: '######',
              grid: { cols: 12, md: 4 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextarea',
              name: 'alamat',
              label: `Alamat Sesuai KTP`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Alamat KTP',
              grid: { cols: 12, md: 12 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
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
          ],
          instansi: [
            {
              type: 'VTextField',
              name: 'instansi_nama',
              label: 'Nama Instansi',
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
              name: 'instansi_jabatan',
              label: 'Jabatan',
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
              type: 'cascade',
              configs: this.configs.instansi,
              grid: { cols: 8 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'instansi_kodepos',
              label: 'Kode Pos',
              hint: '',
              required: false,
              hideDetails: false,
              outlined: true,
              dense: true,
              labelClass: 'mt-n3 secondary--text px-0 body-2',
              singleLine: true,
              mask: '######',
              grid: { cols: 12, md: 4 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextarea',
              name: 'instansi_alamat',
              label: `Alamat Instansi`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Alamat Instansi',
              grid: { cols: 12, md: 12 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
          ],
        },
        pembimbing: {
          dasar: [
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
              rules: { required: true, nik: true },
              mask: '################',
              counter: 16,
              grid: { cols: 12, md: 6 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'nip',
              label: 'NIP/NUPTK',
              hint: '',
              required: false,
              hideDetails: false,
              outlined: true,
              dense: true,
              singleLine: true,
              mask: '##############################',
              counter: 30,
              grid: { cols: 12, md: 6 },
              labelColor: 'secondary',
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
              type: 'VSelect',
              name: 'kelamin',
              label: `Jenis Kelamin`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Jenis Kelamin',
              hint: '',
              grid: { cols: 12, md: 6 },
              items: [
                { value: 'L', text: 'Laki-Laki' },
                { value: 'P', text: 'Perempuan' },
              ],
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
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
              disabled: true,
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VTextField',
              name: 'lulusan',
              label: `Institusi Pendidikan Terakhir`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Institusi Pendidikan Terakhir',
              grid: { cols: 12, md: 4, sm: 12 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VTextField',
              name: 'prodi',
              label: `Prodi`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'prodi',
              grid: { cols: 12, md: 4, sm: 12 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_kualifikasi',
              label: 'Jenjang',
              hint: 'wajib diisi',
              items: this.mKualifikasi,
              value: 'value',
              text: 'text',
              required: true,
              hideDetails: false,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 4 },
              labelColor: 'secondary',
            },
            {
              type: 'cascade',
              configs: this.configs.rumah,
              grid: { cols: 8 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'kodepos',
              label: 'Kode Pos',
              hint: '',
              required: false,
              hideDetails: false,
              outlined: true,
              dense: true,
              singleLine: true,
              labelClass: 'mt-n3 secondary--text px-0 body-2',
              mask: '######',
              grid: { cols: 12, md: 4 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextarea',
              name: 'alamat',
              label: `Alamat Sesuai KTP`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Alamat KTP',
              grid: { cols: 12, md: 12 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
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
          ],
          instansi: [
            {
              type: 'VTextField',
              name: 'instansi_nama',
              label: 'Nama Instansi',
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
              name: 'instansi_jabatan',
              label: 'Jabatan',
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
              type: 'cascade',
              configs: this.configs.instansi,
              grid: { cols: 8 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'instansi_kodepos',
              label: 'Kode Pos',
              hint: '',
              required: false,
              hideDetails: false,
              outlined: true,
              dense: true,
              labelClass: 'mt-n3 secondary--text px-0 body-2',
              singleLine: true,
              mask: '######',
              grid: { cols: 12, md: 4 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextarea',
              name: 'instansi_alamat',
              label: `Alamat Instansi`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Alamat Instansi',
              grid: { cols: 12, md: 12 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
          ],
        },
        'admin-kelas': {
          dasar: [
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
              rules: { required: true, nik: true },
              mask: '################',
              counter: 16,
              grid: { cols: 12, md: 6 },
              labelColor: 'secondary',
            },
            {
              type: 'VSelect',
              name: 'kelamin',
              label: `Jenis Kelamin`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Jenis Kelamin',
              hint: '',
              grid: { cols: 12, md: 6 },
              items: [
                { value: 'L', text: 'Laki-Laki' },
                { value: 'P', text: 'Perempuan' },
              ],
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
              type: 'VTextField',
              name: 'email',
              label: `Alamat Surel`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Alamat Surel',
              hint: '',
              grid: { cols: 12, md: 6 },
              disabled: true,
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
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
            {
              type: 'VTextarea',
              name: 'alamat',
              label: `Alamat Sesuai KTP`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Alamat KTP',
              grid: { cols: 12, md: 12 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'cascade',
              configs: this.configs.rumah,
              grid: { cols: 8 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'kodepos',
              label: 'Kode Pos',
              hint: '',
              required: false,
              hideDetails: false,
              outlined: true,
              dense: true,
              singleLine: true,
              labelClass: 'mt-n3 secondary--text px-0 body-2',
              mask: '######',
              grid: { cols: 12, md: 4 },
              labelColor: 'secondary',
            },
          ],
        },
        lpd: {
          dasar: [
            {
              type: 'VTextField',
              name: 'nama',
              label: 'Nama Lembaga',
              dense: true,
              hint: 'wajib diisi',
              required: true,
              hideDetails: false,
              outlined: true,
              disabled: true,
              singleLine: true,
              grid: { cols: 12, md: 12, sm: 12 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextarea',
              name: 'alamat',
              label: `Alamat`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Alamat',
              grid: { cols: 12, md: 12, sm: 12 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'cascade',
              configs: this.configs.rumah,
              grid: { cols: 12 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'kodepos',
              label: 'Kode Pos',
              hint: '',
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
              name: 'email',
              label: `Alamat Surel`,
              labelColor: 'secondary',
              hideDetails: false,
              placeholder: 'Alamat Surel',
              hint: '',
              grid: { cols: 12, md: 6 },
              disabled: true,
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VTextField',
              name: `no_telpon`,
              label: `Nomor Telepon`,
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
          tambahan: [
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
              grid: { cols: 12, md: 6, sm: 12 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'telp_penanggung_jawab',
              label: 'Telepon Penanggung Jawab',
              dense: true,
              hint: 'wajib diisi',
              required: true,
              hideDetails: false,
              outlined: true,
              singleLine: true,
              grid: { cols: 12, md: 6, sm: 12 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'nama_sekretaris',
              label: 'Nama Sekertaris',
              dense: true,
              hint: 'wajib diisi',
              required: true,
              hideDetails: false,
              outlined: true,
              singleLine: true,
              grid: { cols: 12, md: 6, sm: 12 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'telp_sekretaris',
              label: 'Telpon Sekertaris',
              dense: true,
              hint: 'wajib diisi',
              required: true,
              hideDetails: false,
              outlined: true,
              singleLine: true,
              grid: { cols: 12, md: 6, sm: 12 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'nama_bendahara',
              label: 'Nama Bendahara',
              dense: true,
              hint: 'wajib diisi',
              required: true,
              hideDetails: false,
              outlined: true,
              singleLine: true,
              grid: { cols: 12, md: 6, sm: 12 },
              labelColor: 'secondary',
            },
            {
              type: 'VTextField',
              name: 'telp_bendahara',
              label: 'Telpon Bendahara',
              dense: true,
              hint: 'wajib diisi',
              required: true,
              hideDetails: false,
              outlined: true,
              singleLine: true,
              grid: { cols: 12, md: 6, sm: 12 },
              labelColor: 'secondary',
            },
          ],
        },
      };
      return forms[this.jenis];
    },
  },
  methods: {
    reset() {
      this.$set(this, 'diklats', []);
      this.$set(this, 'form', {});
      this.$set(this, 'photo', '');
      this.$set(this, 'objFoto', null);
    },

    initForm(value) {
      const formulir = [
        ...(this.schema.dasar || []),
        ...(this.schema.instansi || []),
        ...(this.schema.tambahan || []),
        { name: 'k_propinsi' },
        { name: 'k_kota' },
        { name: 'instansi_k_propinsi' },
        { name: 'instansi_k_kota' },
        { name: 'kcp_paud_lain' },
      ];

      for (const item of formulir) {
        if (item.name) {
          this.$set(this.form, item.name, this.$getDeepObj(value, item.name) || '');
        }
      }

      this.id = (value && value.paud_admin_id) || '';
      if (value?.pengalaman?.length || value?.diklat?.length) {
        const pengalaman = value.pengalaman || value.diklat;
        pengalaman.forEach((key) => {
          this.diklats.push({ nama: key.nama, tahun: key.tahun });
        });
      } else {
        this.$set(this, 'diklats', [{ nama: '', tahun: '' }]);
      }
    },

    onAdd(index) {
      if (this.diklats.length > 4) {
        this.$error('Diklat tidak boleh lebih dari 5');
        return;
      } else if (!this.diklats[index]['nama']) {
        this.$error('Harap isikan nama Diklat');
        return;
      } else if (!this.diklats[index]['tahun']) {
        this.$error('Harap isikan tahun Diklat');
        return;
      }

      this.diklats.push({ nama: '', tahun: '' });
    },

    onRemove(index) {
      this.diklats.splice(index, 1);
    },

    getValue() {
      return { form: this.form, diklats: this.diklats, photo: this.objPhoto };
    },

    onChangePhoto(data) {
      let photo = [];
      for (const item of data) {
        photo.push(item);
      }
      this.objPhoto = data;
      this.photo = photo[0][1] || '';
    },
  },

  watch: {
    initValue: 'initForm',
  },
};
</script>
