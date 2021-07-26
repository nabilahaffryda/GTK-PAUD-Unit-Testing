<template>
  <div>
    <v-card flat>
      <v-card-title>Pengaturan Jadwal diklat</v-card-title>
      <v-card-text>
        <template v-for="(jadwal, i) in jadwals">
          <div :key="i">
            <div class="text-right">
              <v-btn v-if="jadwals.length > 1" rounded small depressed dark color="red" @click="onDeleteJadwal(i)">
                <v-icon left>mdi-close</v-icon> Hapus
              </v-btn>
            </div>
            <base-form-generator :schema="schema" v-model="jadwals[i]"></base-form-generator>
          </div>
        </template>
        <div class="text-right">
          <v-btn color="blue" dark outlined depressed @click="onAddJadwal">
            <v-icon left>mdi-plus-circle</v-icon> Jadwal Diklat
          </v-btn>
        </div>
      </v-card-text>
    </v-card>
  </div>
</template>
<script>
import BaseFormGenerator from '../../../../components/base/BaseFormGenerator';
export default {
  components: { BaseFormGenerator },
  props: {
    initValue: {
      type: Object,
      default: () => null,
    },
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      jadwals: [],
    };
  },
  computed: {
    schema() {
      return [
        {
          type: 'VTextField',
          name: 'tahap',
          label: 'Tahapan Diklat',
          dense: true,
          hint: 'wajib diisi',
          required: true,
          hideDetails: false,
          outlined: true,
          singleLine: true,
          grid: { cols: 12, md: 4 },
          labelColor: 'secondary',
        },
        {
          type: 'VDatePicker',
          name: 'wkt_mulai',
          label: 'Waktu Mulai',
          dense: true,
          hint: 'wajib diisi',
          required: true,
          hideDetails: false,
          outlined: true,
          singleLine: true,
          grid: { cols: 12, md: 4 },
          labelColor: 'secondary',
        },
        {
          type: 'VDatePicker',
          name: 'wkt_selesai',
          label: 'Waktu Selesai',
          dense: true,
          hint: 'wajib diisi',
          required: true,
          hideDetails: false,
          outlined: true,
          singleLine: true,
          grid: { cols: 12, md: 4 },
          labelColor: 'secondary',
        },
      ];
    },
  },
  methods: {
    initForm() {},

    reset() {
      this.jadwals = [];
      this.onAddJadwal();
    },

    onAddJadwal() {
      this.jadwals.push({ tahap: '', wkt_mulai: '', wkt_selesai: '' });
    },

    onDeleteJadwal(index) {
      this.jadwals.splice(index, 1);
    },
  },

  watch: {
    initValue: 'initForm',
  },
};
</script>
