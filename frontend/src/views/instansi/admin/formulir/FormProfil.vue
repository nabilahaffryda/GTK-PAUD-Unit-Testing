<template>
  <div>
    <v-card>
      <v-card-text>
        <div class="text-h4 font-weight-bold">Profil Pengajar</div>
        Lengkapi CV dibawah ini sesuai dengan form yang tersedia
        <v-row class="my-5">
          <v-col cols="12" md="2" sm="12"></v-col>
          <v-col cols="12" md="10" sm="12">
            <base-form-generator :schema="schema.dasar" v-model="form" />
            <div class="text-h6 my-3 font-weight-bold">
              Data Instansi
            </div>
            <base-form-generator :schema="schema.instansi" v-model="form" />
            <div class="text-h6 my-3 font-weight-bold">
              Data Diklat
            </div>
            <v-alert type="info">Tambahkan data diklat minimal <b>1</b> dan maksimal <b>5 diklat</b></v-alert>
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
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </div>
</template>
<script>
import BaseFormGenerator from '@components/base/BaseFormGenerator';
export default {
  props: {
    masters: {
      type: Object,
      default: () => {},
    },
    intiValue: {
      type: Object,
      default: () => null,
    },
  },
  components: { BaseFormGenerator },
  data() {
    return {
      form: {},
      diklats: [{ nama: 'Diklat Pembimbing', tahun: '2020' }],
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
      return {
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
            mask: '################',
            counter: 16,
            grid: { cols: 12, md: 6 },
            labelColor: 'secondary',
          },
          {
            type: 'VTextField',
            name: 'nuptk',
            label: 'NUPTK',
            hint: '',
            required: false,
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
            model: [
              { value: 'l', text: 'Laki-Laki' },
              { value: 'p', text: 'Perempuan' },
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
            required: true,
            outlined: true,
            dense: true,
            singleLine: true,
          },
          {
            type: 'VTextField',
            name: 'institusi',
            label: `Institusi Pendidikan`,
            labelColor: 'secondary',
            hideDetails: false,
            placeholder: 'alamat',
            grid: { cols: 12, md: 4, sm: 12 },
            required: false,
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
            required: false,
            outlined: true,
            dense: true,
            singleLine: true,
          },
          {
            type: 'VTextField',
            name: 'jenjang',
            label: `Jenjang`,
            labelColor: 'secondary',
            hideDetails: false,
            placeholder: 'jenjang',
            grid: { cols: 12, md: 4, sm: 12 },
            required: false,
            outlined: true,
            dense: true,
            singleLine: true,
          },
          {
            type: 'cascade',
            configs: this.configs,
            grid: { cols: 8 },
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
            grid: { cols: 12, md: 4 },
            labelColor: 'secondary',
          },
          {
            type: 'VTextarea',
            name: 'alamat_ktp',
            label: `Alamat Sesuai KTP`,
            labelColor: 'secondary',
            hideDetails: false,
            placeholder: 'Alamat KTP',
            grid: { cols: 12, md: 12 },
            required: false,
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
            name: 'nama_instansi',
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
            name: 'jabatan',
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
            configs: this.configs,
            grid: { cols: 8 },
            labelColor: 'secondary',
          },
          {
            type: 'VTextField',
            name: 'kodepos_instansi',
            label: 'Kode Pos',
            hint: 'wajib diisi',
            required: false,
            hideDetails: false,
            outlined: true,
            dense: true,
            singleLine: true,
            mask: '######',
            grid: { cols: 12, md: 4 },
            labelColor: 'secondary',
          },
          {
            type: 'VTextarea',
            name: 'alamat_instansi',
            label: `Alamat Instansi`,
            labelColor: 'secondary',
            hideDetails: false,
            placeholder: 'Alamat Instansi',
            grid: { cols: 12, md: 12 },
            required: false,
            outlined: true,
            dense: true,
            singleLine: true,
          },
        ],
      };
    },
  },
  methods: {
    reset() {
      this.$set(this, 'reset', {});
    },

    initForm(value) {
      const formulir = [
        ...(this.schema.dasar || []),
        ...(this.schema.instansi || []),
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
  },

  watch: {
    initValue: 'initForm',
  },
};
</script>
