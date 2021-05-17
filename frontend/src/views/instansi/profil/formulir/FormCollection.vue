<template>
  <v-card class="mx-auto" flat>
    <v-toolbar color="secondary" dark flat>
      <v-toolbar-title><span v-html="title"></span></v-toolbar-title>
    </v-toolbar>
    <v-container>
      <v-alert v-if="deskripsi" text type="info" dense>
        <div v-html="deskripsi" />
      </v-alert>

      <v-tabs v-model="tab" background-color="transparent" color="secondary" grow>
        <v-tab v-for="item in tabs" :key="item.tab">
          {{ item.tab }}
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tab">
        <v-tab-item v-for="(item, i) in tabs" :key="i">
          <template v-for="(f, id) in forms">
            <div :key="id" class="mt-3 mb-4">
              <v-row no-gutters dense>
                <v-col cols="12" md="10" sm="12">
                  <span class="title orange--text text--darken-3"> Data riwayat ({{ id + 1 }}) </span>
                </v-col>
                <v-col cols="12" md="2" sm="12" class="my-auto">
                  <div class="text-right">
                    <v-btn
                      title="Hapus"
                      small
                      depressed
                      rounded
                      color="grey"
                      dark
                      class="mt-3 mx-2"
                      @click="remove(id)"
                    >
                      <v-icon left small v-text="'mdi-close'"></v-icon> Hapus
                    </v-btn>
                  </div>
                </v-col>
              </v-row>
              <base-form-generator :schema="schema(item.type)" v-model="form[id]" />
              <v-row>
                <v-col cols="12" md="6" class="py-0">
                  <v-subheader :class="[`px-0 body-2 secondary--text`]" style="height: 24px"> Unggah Berkas* </v-subheader>
                  <template v-if="form.berkas && form.berkas.length">
                    <v-chip
                      color="secondary"
                      dark
                      label
                      close
                      :href="form.berkas[0] && form.berkas[0]['url_berkas']"
                      target="_blank"
                      @click:close="onRemoveFile"
                    >
                      {{ form.berkas[0] && form.berkas[0]['file_berkas'] }}
                    </v-chip>
                  </template>
                  <template v-else>
                    <validation-provider name="Pindaian Berkas" rules="required" v-slot="{ errors }">
                      <v-file-input
                        v-model="form.file"
                        :error-messages="errors"
                        label="Pindaian Berkas Ijazah (20 KB - 1,5 MB)"
                        append-icon="mdi-paperclip"
                        prepend-icon=""
                        accept="image/*,.pdf"
                        hint="jenis file unggahan JPG/JPEG/PNG/GIF/PDF (20 KB - 1,5 MB). Untuk berkas multi halaman gunakan format PDF"
                        persistent-hint
                        show-size
                        outlined
                        dense
                        single-line
                      ></v-file-input>
                    </validation-provider>
                  </template>
                </v-col>
              </v-row>
            </div>
          </template>
          <v-divider></v-divider>
          <v-btn
            depressed
            :color="max === (forms || []).length ? '' : 'secondary'"
            :class="max === (forms || []).length ? 'grey--text mt-3' : 'mt-3'"
            @click="add"
          >
            Tambah Data Riwayat
          </v-btn>
        </v-tab-item>
      </v-tabs-items>
    </v-container>
  </v-card>
</template>

<script>
import BaseFormGenerator from '@/components/base/BaseFormGenerator';
import { ValidationProvider } from 'vee-validate';
import { range } from '@/utils/format';
export default {
  name: 'FormCollection',
  components: { BaseFormGenerator, ValidationProvider },
  props: {
    title: {
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
    deskripsi: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      tab: null,
      tabs: [
        { tab: 'Diklat Berjenjang', type: 'diklat' },
        { tab: 'Diklat PCP', type: 'diklat' },
        { tab: 'Diklat MOT', type: 'diklat' },
        { tab: 'Diklat Lainnya', type: 'diklat_lain' },
      ],
      forms: [],
      form: [],
      currTahun: new Date().getFullYear(),
    };
  },
  methods: {
    schema(type) {
      const collection = {
        diklat: [
          {
            type: 'VTextField',
            name: 'penyelenggara',
            label: 'Nama Pelatihan',
            labelColor: 'info',
            placeholder: 'Tuliskan Nama pelatihan yang pernah Anda ikuti',
            hint: 'wajib diisi',
            grid: { cols: 12, md: 12 },
            required: true,
            outlined: true,
            dense: true,
            singleLine: true,
          },
          {
            type: 'VSelect',
            name: 'k_jenjang',
            label: 'Tingkatan Diklat',
            labelColor: 'info',
            placeholder: 'Pilih Tingkatan Diklat',
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
        diklat_lain: [
          {
            type: 'VTextField',
            name: 'nama',
            label: 'Nama Diklat',
            labelColor: 'info',
            placeholder: 'Tuliskan Nama Diklat yang pernah Anda ikuti',
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
            name: 'k_jenjang',
            label: 'Tingkatan Diklat',
            labelColor: 'info',
            placeholder: 'Pilih Tingkatan Diklat',
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
      };

      return collection[type];
    },
    add() {
      if (this.max) {
        if (this.forms.length === this.max) {
          this.$error(`Maksimal ${this.max} data ${this.title}.`);
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
          const formulir = [...this.schema(this.type)];
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
      const formulir = [...this.schema(this.type), { name: 'duplikasi_id' }];
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
