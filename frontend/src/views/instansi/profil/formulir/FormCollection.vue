<template>
  <v-card class="mx-auto" flat>
    <v-toolbar color="secondary" dark flat>
      <v-toolbar-title><span v-html="title"></span></v-toolbar-title>
    </v-toolbar>
    <v-container>
      <v-alert v-if="deskripsi" text type="secondary" dense>
        <div v-html="deskripsi" />
      </v-alert>
      <v-tabs v-model="tab" background-color="transparent" color="secondary" data-testid="item-tab" grow>
        <v-tab v-for="item in tabs" :key="item.tab">
          {{ item.tab }}
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tab" ref="tab">
        <v-tab-item v-for="(item, i) in tabs" :key="i">
          <template v-for="(f, id) in forms[item.k_tipe]">
            <div :key="id" class="my-4">
              <v-row no-gutters dense v-if="item.type === 'diklat_lain'">
                <v-col cols="12" md="10" sm="12">
                  <span class="title orange--text text--darken-3"> Data Diklat ({{ id + 1 }}) </span>
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
              <template v-if="tab === i">
                <base-form-generator :schema="schema(item.type)" v-model="form[item.k_tipe][id]" />
                <input type="hidden" v-model="form[item.k_tipe][id]['k_diklat_paud']" />
                <input type="hidden" v-model="form[item.k_tipe][id]['paud_petugas_diklat_id']" />
                <v-row class="mt-9">
                  <v-col cols="12" md="6" class="py-0">
                    <v-subheader :class="[`px-0 body-2 secondary--text text--darken-4`]" style="height: 24px">
                      Unggah Berkas*
                    </v-subheader>
                    <template v-if="form[item.k_tipe][id]['url']">
                      <v-chip
                        color="secondary"
                        dark
                        label
                        close
                        :href="form[item.k_tipe][id]['url']"
                        target="_blank"
                        @click:close="onRemoveFile(item.k_tipe, id)"
                      >
                        {{ form[item.k_tipe][id]['nama_file'] }}
                      </v-chip>
                    </template>
                    <template v-else>
                      <validation-provider name="Sertifikat Diklat" rules="required" v-slot="{ errors }">
                        <v-file-input
                          v-model="form[item.k_tipe][id]['file']"
                          :error-messages="errors"
                          label="Pindaian Berkas Sertifikat (20 KB - 1,5 MB)"
                          append-icon="mdi-paperclip"
                          prepend-icon=""
                          accept="image/*,.pdf"
                          hint="jenis file unggahan JPG/JPEG/PNG/GIF/PDF (20 KB - 1,5 MB). Untuk berkas multi halaman gunakan format PDF"
                          :rules="[
                            (value) =>
                              (value && value.size < roundDecimal(1500 * 1000)) ||
                              'Berkas yang Anda upload melebihi kapasitas maksimum!',
                          ]"
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
              </template>
            </div>
          </template>
          <v-divider></v-divider>
          <v-btn
            v-if="item.type === 'diklat_lain'"
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
import { range, roundDecimal } from '@/utils/format';
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
      default: () => [],
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
        { tab: 'Diklat Berjenjang', type: 'diklat', k_tipe: 1 },
        { tab: 'Diklat PCP', type: 'diklat', k_tipe: 2 },
        { tab: 'Diklat MOT', type: 'diklat', k_tipe: 3 },
        { tab: 'Diklat Lainnya', type: 'diklat_lain', k_tipe: 4 },
      ],
      forms: {},
      form: {},
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
            labelColor: 'secondary',
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
            name: 'k_tingkat_diklat_paud',
            label: 'Tingkatan Diklat',
            labelColor: 'secondary',
            placeholder: 'Pilih Tingkatan Diklat',
            hint: 'wajib diisi',
            items: this.$mapForMaster(this.masters['m_tingkat_diklat_paud']),
            itemText: 'text',
            itemValue: 'value',
            required: true,
            hideDetails: true,
            outlined: true,
            dense: true,
            singleLine: true,
            clearable: true,
            grid: { cols: 12, md: 6 },
          },
          {
            type: 'VSelect',
            name: 'tahun_diklat',
            label: 'Tahun',
            labelColor: 'secondary',
            placeholder: 'Pilih Tahun Diklat',
            hint: 'wajib diisi',
            items: this.$mapForMaster(range(this.currTahun, 2010, 1)),
            itemText: 'text',
            itemValue: 'value',
            required: true,
            hideDetails: true,
            outlined: true,
            dense: true,
            singleLine: true,
            clearable: true,
            grid: { cols: 12, md: 6 },
          },
        ],
        diklat_lain: [
          {
            type: 'VTextField',
            name: 'nama',
            label: 'Nama Diklat',
            labelColor: 'secondary',
            placeholder: 'Tuliskan Nama Diklat yang pernah Anda ikuti',
            hint: 'wajib diisi',
            grid: { cols: 12, md: 6 },
            required: true,
            outlined: true,
            dense: true,
            singleLine: true,
          },
          {
            type: 'VTextField',
            name: 'penyelenggara',
            label: 'Penyelenggara',
            labelColor: 'secondary',
            placeholder: 'Isi Penyelenggara',
            hint: 'wajib diisi',
            required: true,
            outlined: true,
            dense: true,
            singleLine: true,
            grid: { cols: 12, md: 6 },
          },
          {
            type: 'VTextField',
            name: 'tingkatan',
            label: 'Tingkatan Diklat',
            labelColor: 'secondary',
            placeholder: 'Isi Tingkatan Diklat',
            hint: 'wajib diisi',
            required: true,
            outlined: true,
            dense: true,
            singleLine: true,
            grid: { cols: 12, md: 6 },
          },
          {
            type: 'VSelect',
            name: 'tahun_diklat',
            label: 'Tahun',
            labelColor: 'secondary',
            placeholder: 'Pilih Tahun Diklat',
            hint: 'wajib diisi',
            items: this.$mapForMaster(range(this.currTahun, 2010, 1)),
            itemText: 'text',
            itemValue: 'value',
            required: true,
            hideDetails: true,
            outlined: true,
            dense: true,
            singleLine: true,
            clearable: true,
            grid: { cols: 12, md: 6 },
          },
        ],
      };

      return collection[type];
    },
    add() {
      if (this.forms[4].length === this.max) {
        this.$error(`Maksimal ${this.max} data ${this.title}.`);
        return;
      }
      this.forms[4].push({ k_diklat_paud: 4 });
    },
    remove(idx) {
      this.$confirm(
        `<span class="black--text">Anda yakin ingin menghapus <strong>Data Diklat (${idx + 1})</strong> ?</span>`,
        `Hapus Diklat`,
        {
          tipe: 'error',
          data: '',
        }
      ).then(() => {
        this.form[4].splice(idx, 1);
        this.forms[4].splice(idx, 1);
      });
    },
    reset() {
      this.form = {};
      this.forms = {};
    },
    getValue() {
      let result = [];
      for (const item of this.tabs) {
        result.push(...this.form[item.k_tipe]);
      }
      return result;
    },
    onRemoveFile(k_tipe, idx) {
      let form = Object.assign({}, this.form[k_tipe][idx]);
      delete form.url;
      delete form.file;
      this.$set(this.form[k_tipe], idx, form);
    },
    initForm(value) {
      for (const item of this.tabs) {
        const data = (value || []).filter((val) => val.k_diklat_paud === item.k_tipe).length
          ? value.filter((val) => val.k_diklat_paud === item.k_tipe)
          : [{ k_diklat_paud: item.k_tipe }];
        this.$set(this.form, `${item.k_tipe}`, data);
        this.$set(this.forms, `${item.k_tipe}`, data);
      }
    },
    resetValidation() {
      this.$emit('reset');
    },

    roundDecimal(value) {
      return roundDecimal(value);
    },
  },
  watch: {
    initValue: 'initForm',
    tab: 'resetValidation',
  },
};
</script>

<style scoped></style>
