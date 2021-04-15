<template>
  <div>
    <v-toolbar flat>
      <v-toolbar-title class="subtitle-1">
        <div class="font-weight-bold">Data CV</div>
        <span>
          Lengkapi data persyaratan sesuai kebutuhan sistem, Silakan <b>tekan tombol/icon pensil</b> di sebelah kanan
          untuk melakukan edit.
        </span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn depressed @click="$emit('edit')">
        <v-icon>mdi-pencil</v-icon>
      </v-btn>
    </v-toolbar>
    <v-row class="my-5">
      <v-col cols="12" md="2" sm="12"> </v-col>
      <v-col cols="12" md="10" sm="12" class="px-md-0">
        <v-row>
          <v-col v-for="(profil, p) in profils" :key="p" v-bind="profil.grid">
            <div class="caption grey--text">{{ $getDeepObj(profil, 'title') || '-' }}</div>
            <h2 class="subtitle-1 black--text">{{ $getDeepObj(profil, 'value') || '-' }}</h2>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </div>
</template>
<script>
export default {
  props: {
    detail: {
      type: Object,
      default: () => {},
    },
  },
  computed: {
    profils() {
      const item = Object.assign({}, this.detail);
      return [
        {
          title: 'Nama Lembaga',
          value: this.$getDeepObj(item, 'akun.data.nama') || '-',
          grid: { cols: 12, md: 6, sm: 12 },
        },
        {
          title: 'Alamat Sesuai KTP',
          value: this.$getDeepObj(item, 'akun.data.alamat') || '-',
          grid: { cols: 12, md: 6, sm: 12 },
        },
        { title: 'Email', value: this.$getDeepObj(item, 'akun.data.email') || '-', grid: { cols: 12, md: 6, sm: 12 } },
        { title: 'Propinsi', value: this.$getDeepObj(item, ''), grid: { cols: 12, md: 3, sm: 6 } },
        { title: 'Kabupaten/Kota', value: this.$getDeepObj(item, ''), grid: { cols: 12, md: 3, sm: 6 } },
        {
          title: 'Nomor HP Aktif',
          value: this.$getDeepObj(item, 'akun.data.no_hp') || '-',
          grid: { cols: 12, md: 6, sm: 12 },
        },
        { title: 'Kode Pos', value: this.$getDeepObj(item, 'akun.data.kodepos'), grid: { cols: 12, md: 6, sm: 12 } },
        { title: 'NIK', value: this.$getDeepObj(item, 'akun.data.nik'), grid: { cols: 12, md: 6, sm: 12 } },
        { title: 'Keikutsertaan PCP', value: this.$getDeepObj(item, ''), grid: { cols: 12, md: 6, sm: 12 } },
        {
          title: 'Tempat Tanggal Lahir',
          value:
            (this.$getDeepObj(item, 'akun.data.tmp_lahir') || '-') +
            ', ' +
            (this.$localDate(this.$getDeepObj(item, 'akun.data.tgl_lahir')) || '-'),
          grid: { cols: 12, md: 6, sm: 12 },
        },
        { title: 'Pengalaman Melatih 2 Tahun Terakhir', value: '', grid: { cols: 12, md: 4, sm: 12 } },
        { title: 'Pendidikan Terakhir', value: this.$getDeepObj(item, ''), grid: { cols: 12, md: 6, sm: 12 } },
      ];
    },
  },
};
</script>
