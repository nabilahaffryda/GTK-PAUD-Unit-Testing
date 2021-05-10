<template>
  <v-card class="mx-auto" flat>
    <v-toolbar color="secondary" dark flat>
      <v-toolbar-title>FORM <span v-html="subtitle"></span></v-toolbar-title>
    </v-toolbar>
    <v-container>
      <v-alert v-if="deskripsi" text type="info" dense>
        <div v-html="deskripsi" />
      </v-alert>
      <template v-for="(f, id) in forms">
        <div :key="id" class="mt-3 mb-4">
          <v-row no-gutters dense>
            <v-col cols="12" md="10" sm="12">
              <span class="title orange--text text--darken-3"> Data riwayat ({{ id + 1 }}) </span>
            </v-col>
            <v-col cols="12" md="2" sm="12" class="my-auto">
              <div class="text-right">
                <v-btn title="Hapus" small depressed rounded color="grey" dark class="mt-3 mx-2" @click="remove(id)">
                  <v-icon left small v-text="'mdi-close'"></v-icon> Hapus
                </v-btn>
              </div>
            </v-col>
          </v-row>
          <base-form-generator :schema="schema(role, type, id)" v-model="form[id]" />
        </div>
      </template>
      <v-divider></v-divider>
      <v-btn
        depressed
        :color="this.max === (forms || []).length ? '' : 'primary'"
        :class="this.max === (forms || []).length ? 'grey--text mt-3' : 'mt-3'"
        @click="add"
      >
        Tambah Data Riwayat
      </v-btn>
    </v-container>
  </v-card>
</template>

<script>
import BaseFormGenerator from '@/components/base/BaseFormGenerator';
import { range } from '@/utils/format';
export default {
  name: 'FormMentoring',
  components: { BaseFormGenerator },
  props: {
    subtitle: {
      type: String,
      default: '',
    },
    type: {
      type: String,
      default: '',
    },
    initValue: {
      type: Array,
      default: () => null,
    },
    masters: {
      type: Object,
      default: () => {},
    },
    mFungsi: {
      type: Object,
      default: () => {},
    },
    configs: {
      type: Object,
      default: () => {},
    },
    items: {
      type: Array,
      default: () => [],
    },
    max: {
      type: Number,
      default: null,
    },
    action: {
      type: String,
      default: null,
    },
    role: {
      type: String,
      required: true,
    },
    deskripsi: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      forms: [],
      form: [],
      currTahun: new Date().getFullYear(),
    };
  },
  methods: {
    schema(role, type) {
      const collection = {
        kasek: {
          diklat: [
            {
              type: 'VTextField',
              name: 'pelatihan',
              label: 'Nama Pelatihan',
              labelColor: 'info',
              placeholder: 'Tuliskan Nama pelatihan yang pernah Anda ikuti',
              hint: 'wajib diisi',
              grid: { cols: 12, md: 6 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_diklat_instansi_psp',
              label: 'Penyelenggara',
              labelColor: 'info',
              placeholder: 'Pilih Penyelenggara',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['diklat_instansi'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VSelect',
              name: 'tahun',
              label: 'Tahun',
              labelColor: 'info',
              placeholder: 'Pilih Tahun Diklat',
              hint: 'wajib diisi',
              items: this.$mapForMaster(range(this.currTahun, this.currTahun - 3, 1)),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
          ],
          organisasi: [
            {
              type: 'VTextField',
              name: 'organisasi',
              label: 'Nama Organisasi',
              labelColor: 'info',
              placeholder: 'Tuliskan Nama Organisasi',
              hint: 'wajib diisi',
              grid: { cols: 12, md: 6 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_organisasi_lingkup_psp',
              label: 'Cakupan Organisasi',
              labelColor: 'info',
              placeholder: 'Pilih Cakupan organisasi',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['organisasi_lingkup'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              class: 'pt-0',
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VSelect',
              name: 'k_organisasi_durasi_psp',
              label: 'Lama tergabung dalam Organisasi',
              labelColor: 'info',
              placeholder: 'Pilih Lama tergabung dalam Organisasi',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['organisasi_durasi'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              class: 'pt-0',
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VSelect',
              name: 'k_organisasi_jabatan_psp',
              label: 'Peran/Posisi dalam Organisasi',
              labelColor: 'info',
              placeholder: 'Pilih posisi Anda dalam Organisasi',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['organisasi_jabatan'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              class: 'pt-0',
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VTextarea',
              name: 'peran',
              label: 'Ceritakan peran Anda dalam Organisasi',
              labelColor: 'info',
              placeholder: 'Tuliskan peran anda dalam Organisasi (max 1000 karakter)',
              hint: 'wajib diisi',
              required: true,
              rules: { required: true, maxChar: 1000 },
              counter: 1000,
              grid: { cols: 12 },
              outlined: true,
              dense: true,
              singleLine: true,
            },
          ],
          program: [
            {
              type: 'VTextField',
              name: 'instansi',
              label: 'Nama Organisasi yang bekerja sama dengan Sekolah yang Anda pimpin',
              labelColor: 'info',
              placeholder: 'Tuliskan Nama Organisasi',
              hint: 'wajib diisi',
              grid: { cols: 12, md: 6 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_program_instansi_psp',
              label: 'Jenis Organisasi',
              labelColor: 'info',
              placeholder: 'Pilih Jenis Organisasi',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['program_instansi'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              class: 'pt-0',
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VSelect',
              name: 'tahun_mulai',
              label: 'Tahun Mulai',
              labelColor: 'info',
              placeholder: 'Pilih Tahun Mulai',
              hint: 'wajib diisi',
              items: this.$mapForMaster(range(this.currTahun, this.currTahun - 4, 1)),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VSelect',
              name: 'tahun_selesai',
              label: 'Tahun Berakhir',
              labelColor: 'info',
              placeholder: 'Pilih Tahun Berakhir',
              hint: 'wajib diisi',
              items: this.$mapForMaster(range(this.currTahun, this.currTahun - 4, 1)),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
          ],
          prestasi: [
            {
              type: 'VTextField',
              name: 'prestasi',
              label: 'Nama Prestasi yang Anda raih',
              labelColor: 'info',
              placeholder: 'Tuliskan Nama Prestasi yang pernah Anda raih',
              hint: 'wajib diisi',
              grid: { cols: 12, md: 6 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_prestasi_psp',
              label: 'Level Prestasi',
              labelColor: 'info',
              placeholder: 'Pilih Level Prestasi',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['prestasi'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VSelect',
              name: 'tahun',
              label: 'Tahun',
              labelColor: 'info',
              placeholder: 'Pilih Tahun Prestasi',
              hint: 'wajib diisi',
              items: this.$mapForMaster(range(this.currTahun, this.currTahun - 5, 1)),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
          ],
          komunitas: [
            {
              type: 'VTextField',
              name: 'kegiatan',
              label: 'Nama Kegiatan',
              labelColor: 'info',
              placeholder: 'Tuliskan Nama Kegiatan',
              hint: 'wajib diisi',
              grid: { cols: 12, md: 6 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_komunitas_psp',
              label: 'Cakupan Kegiatan',
              labelColor: 'info',
              placeholder: 'Pilih Cakupan Kegiatan',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['komunitas'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VSelect',
              name: 'tahun',
              label: 'Tahun',
              labelColor: 'info',
              placeholder: 'Pilih Tahun Prestasi',
              hint: 'wajib diisi',
              items: this.$mapForMaster(range(this.currTahun, this.currTahun - 3, 1)),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VTextarea',
              name: 'deskripsi',
              label: 'Jelaskan rincian kegiatan ini',
              labelColor: 'info',
              placeholder: 'Tuliskan rincian kegiatan (max 1000 karakter)',
              hint: 'wajib diisi',
              required: true,
              rules: { required: true, maxChar: 1000 },
              counter: 1000,
              grid: { cols: 12 },
              outlined: true,
              dense: true,
              singleLine: true,
            },
          ],
          hobi: [
            {
              type: 'VTextField',
              name: 'hobi',
              label: 'Hobi yang ditekuni',
              labelColor: 'info',
              placeholder: 'Tuliskan Hobi yang Anda tekuni',
              hint: 'wajib diisi',
              grid: { cols: 12, md: 6 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_hobi_psp',
              label: 'Lama menekuni',
              labelColor: 'info',
              placeholder: 'Pilih Lama menekuni',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['hobi'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
          ],
        },
        instruktur: {
          bimbingan: [
            {
              type: 'VTextField',
              name: 'program',
              label: 'Nama Program pendampingan/pembimbingan',
              labelColor: 'info',
              placeholder: 'Tuliskan Nama Program pendampingan/pembimbingan yang pernah Anda ikuti',
              hint: 'wajib diisi',
              grid: { cols: 12, md: 6 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_bimbingan_instansi_psp',
              label: 'Instansi yang memberikan penugasan',
              labelColor: 'info',
              placeholder: 'Pilih Instansi yang memberikan penugasan',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['bimbingan_instansi'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VDatePicker',
              name: 'tahun_mulai',
              label: 'Tahun Mulai',
              labelColor: 'info',
              hint: 'wajib diisi',
              typeDate: 'month',
              required: true,
              grid: { cols: 12, md: 6 },
              min: '1995-01',
              max: '2021-04',
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VDatePicker',
              name: 'tahun_selesai',
              label: 'Tahun Berakhir',
              labelColor: 'info',
              typeDate: 'month',
              hint: 'wajib diisi',
              required: true,
              grid: { cols: 12, md: 6 },
              min: '1995-01',
              max: '2021-04',
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_bimbingan_lingkup_psp',
              label: 'Lingkup program yang ditangani',
              labelColor: 'info',
              placeholder: 'Pilih Lingkup program yang ditangani',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['bimbingan_lingkup'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
              class: 'pt-0',
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VAutocomplete',
              name: 'k_bimbingan_kota_psp',
              label: 'Lokasi penyelenggaraan',
              labelColor: 'info',
              placeholder: 'Pilih Lokasi penyelenggaraan',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['kota']),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
              class: 'pt-0',
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'Checkbox',
              name: 'jenjang',
              label: 'Jenjang satuan pendidikan yang didampingi/dibimbing',
              labelColor: 'info',
              hint: 'wajib diisi',
              grid: { cols: 12, md: 12 },
              itemGrid: { cols: 6, md: 2 },
              items: this.$mapForMaster(this.masters['bimbingan_jenjang'], 'k_profil_opsi_psp'),
              required: true,
              row: true,
            },
            {
              type: 'VTextarea',
              name: 'dampak',
              label: 'Jelaskan dampak dari pendampingan/pembimbingan yang Anda lakukan',
              labelColor: 'info',
              hint: 'Isian dampak minimal 50 - 250 kata',
              required: true,
              grid: { cols: 12 },
              outlined: true,
              dense: true,
              singleLine: true,
              counter: 250,
              counterValue: (v) => this.$wordCount(v),
            },
          ],
          organisasi_pa: [
            {
              type: 'VTextField',
              name: 'organisasi',
              label: 'Nama Organisasi',
              labelColor: 'info',
              placeholder: 'Tuliskan Nama Organisasi',
              hint: 'wajib diisi',
              grid: { cols: 12, md: 6 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_organisasi_lingkup_psp',
              label: 'Cakupan Organisasi',
              labelColor: 'info',
              placeholder: 'Pilih Cakupan organisasi',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['organisasi_lingkup'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              class: 'pt-0',
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VDatePicker',
              name: 'tahun_mulai',
              label: 'Tahun Mulai',
              labelColor: 'info',
              hint: 'wajib diisi',
              typeDate: 'month',
              required: true,
              grid: { cols: 12, md: 6 },
              min: '2010-01',
              // max: new Date().toISOString().substr(0, 7),
              max: '2021-04',
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VDatePicker',
              name: 'tahun_selesai',
              label: 'Tahun Berakhir',
              labelColor: 'info',
              typeDate: 'month',
              hint: 'wajib diisi',
              required: true,
              grid: { cols: 12, md: 6 },
              min: '2010-01',
              max: '2021-04',
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_organisasi_jabatan_psp',
              label: 'Peran/Posisi dalam Organisasi',
              labelColor: 'info',
              placeholder: 'Pilih posisi Anda dalam Organisasi',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['organisasi_jabatan'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              class: 'pt-0',
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VTextarea',
              name: 'peran',
              label: 'Ceritakan peran Anda dalam Organisasi',
              labelColor: 'info',
              placeholder: 'Tuliskan peran Anda dalam Organisasi (25 - 100 kata)',
              hint: 'Ceritakan peran Anda minimal 25 dan maksimal 100 kata',
              required: true,
              counter: 100,
              counterValue: (v) => this.$wordCount(v),
              grid: { cols: 12 },
              outlined: true,
              dense: true,
              singleLine: true,
            },
          ],
          diklat: [
            {
              type: 'VTextField',
              name: 'pelatihan',
              label: 'Nama pelatihan',
              labelColor: 'info',
              placeholder: 'Tuliskan Nama pelatihan yang diikuti',
              hint: 'wajib diisi',
              grid: { cols: 12, md: 6 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_diklat_instansi_psp',
              label: 'Penyelenggara',
              labelColor: 'info',
              placeholder: 'Pilih Penyelenggara',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['diklat_instansi'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VSelect',
              name: 'tahun',
              label: 'Tahun Pelatihan Diikuti',
              labelColor: 'info',
              placeholder: 'Pilih tahun pelatihan diikuti',
              hint: 'wajib diisi',
              items: this.$mapForMaster(range(this.currTahun, this.currTahun - 6, 1)),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
          ],
          prestasi: [
            {
              type: 'VTextField',
              name: 'prestasi',
              label: 'Nama Prestasi yang Anda raih',
              labelColor: 'info',
              placeholder: 'Tuliskan Nama Prestasi yang pernah Anda raih',
              hint: 'wajib diisi',
              grid: { cols: 12, md: 6 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_prestasi_psp',
              label: 'Level Prestasi',
              labelColor: 'info',
              placeholder: 'Pilih Level Prestasi',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['prestasi'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
            {
              type: 'VSelect',
              name: 'tahun',
              label: 'Tahun',
              labelColor: 'info',
              placeholder: 'Pilih Tahun Prestasi',
              hint: 'wajib diisi',
              items: this.$mapForMaster(range(this.currTahun, this.currTahun - 5, 1)),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
          ],
          hobi: [
            {
              type: 'VTextField',
              name: 'hobi',
              label: 'Hobi yang ditekuni',
              labelColor: 'info',
              placeholder: 'Tuliskan Hobi yang Anda tekuni',
              hint: 'wajib diisi',
              grid: { cols: 12, md: 6 },
              required: true,
              outlined: true,
              dense: true,
              singleLine: true,
            },
            {
              type: 'VSelect',
              name: 'k_hobi_psp',
              label: 'Lama menekuni',
              labelColor: 'info',
              placeholder: 'Pilih Lama menekuni',
              hint: 'wajib diisi',
              items: this.$mapForMaster(this.masters['hobi'], 'k_profil_opsi_psp'),
              itemText: 'text',
              itemValue: 'value',
              required: true,
              hideDetails: true,
              outlined: true,
              dense: true,
              singleLine: true,
              grid: { cols: 12, md: 6 },
            },
          ],
        },
      };

      return collection[role][type];
    },
    add() {
      if (this.max) {
        if (this.forms.length === this.max) {
          this.$error(`Maksimal ${this.max} data ${this.subtitle}.`);
          return;
        }

        this.forms.push({});
        this.form.push({});
      } else {
        this.forms.push({});
        this.form.push({});
      }
    },
    remove(idx) {
      this.$confirm(
        `<span class="black--text">Anda yakin ingin menghapus <strong>Data Riwayat (${idx + 1})</strong> ?</span>`,
        `Hapus Riwayat`,
        {
          tipe: 'error',
          data: '',
        }
      ).then(() => {
        this.form.splice(idx, 1);
        this.forms.splice(idx, 1);
      });
    },
    reset() {
      this.form = [];
      this.forms = [].concat(this.items);

      if (this.action === 'add') {
        this.add();
      }
    },
    getValue() {
      let result = [];
      if (this.action === 'add') {
        for (const i in this.initValue) {
          let param = {};
          const formulir = [...this.schema(this.role, this.type)];
          for (const f of formulir) {
            const item = this.initValue[i] || {};
            if (Object.keys(item).length) {
              if (['pendampingan', 'pengajaran'].indexOf(this.type) > -1) {
                param['is_pendampingan'] = this.type === 'pendampingan' ? 1 : 0;
              } else if (['pendidikan', 'nonpendidikan'].indexOf(this.type) > -1) {
                param['is_pendidikan'] = this.type === 'pendidikan' ? 1 : 0;
                if (+param['k_jabatan_gpm'] !== 99) {
                  param['peran'] = '';
                }
              }
            }
            param[f.name] = this.$getDeepObj(item, f.name) || '';
          }
          result.push(param);
        }
      }

      for (const f in this.form) {
        if (Object.keys(this.form[f] || {}).length) {
          if (['pendampingan', 'pengajaran'].indexOf(this.type) > -1) {
            this.form[f]['is_pendampingan'] = this.type === 'pendampingan' ? 1 : 0;
          } else if (['pendidikan', 'nonpendidikan'].indexOf(this.type) > -1) {
            this.form[f]['is_pendidikan'] = this.type === 'pendidikan' ? 1 : 0;
            if (+this.form[f]['k_jabatan_gpm'] !== 99) {
              this.form[f]['peran'] = '';
            }
          }

          if (this.form[f].pendampingan) {
            this.form[f].pendampingan = this.form[f].pendampingan.join(' dan ');
          }
          result.push(this.form[f]);
        }
      }

      return result;
    },
    initForm(value) {
      const formulir = [...this.schema(this.role, this.type), { name: 'duplikasi_id' }];
      for (const i in value) {
        const item = value[i] || {};
        this.form.push({});
        for (const f of formulir) {
          if (f.name) {
            if (this.form[i]) {
              this.$set(this.form[i], f.name, this.$getDeepObj(item, f.name) || (f && f.type === 'Checkbox' ? [] : ''));
            } else {
              this.$set(this.form, i, {});
              this.$set(this.form[i], f.name, this.$getDeepObj(item, f.name) || (f && f.type === 'Checkbox' ? [] : ''));
            }
          }
        }
      }
    },
  },
  watch: {
    initValue: 'initForm',
  },
};
</script>

<style scoped></style>
