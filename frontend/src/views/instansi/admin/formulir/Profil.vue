<template>
  <div>
    <v-toolbar flat>
      <v-toolbar-title class="subtitle-1">
        <div>Data CV</div>
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
  computed: {
    profils() {
      return [
        { title: 'Nama Lembaga', value: 'Syamroni Abdul Marona', grid: { cols: 12, md: 6, sm: 12 } },
        { title: 'Alamat Sesuai KTP', value: 'Jl. Sirip Hui Nomor 231', grid: { cols: 12, md: 6, sm: 12 } },
        { title: 'Email', value: 'Abadilah@gmail.com', grid: { cols: 12, md: 6, sm: 12 } },
        { title: 'Propinsi', value: 'Jawa Timur', grid: { cols: 12, md: 3, sm: 6 } },
        { title: 'Kabupaten/Kota', value: 'Malang', grid: { cols: 12, md: 3, sm: 6 } },
        { title: 'Nomor HP Aktif', value: '081929382912', grid: { cols: 12, md: 6, sm: 12 } },
        { title: 'Kode Pos', value: '65164', grid: { cols: 12, md: 6, sm: 12 } },
        { title: 'NIK', value: '3507282912939281', grid: { cols: 12, md: 6, sm: 12 } },
        { title: 'Keikutsertaan PCP', value: 'YA', grid: { cols: 12, md: 6, sm: 12 } },
        { title: 'Tempat Tanggal Lahir', value: 'Malang, 20 Mei 1997', grid: { cols: 12, md: 3, sm: 12 } },
        { title: 'Usia', value: '22 Tahun', grid: { cols: 12, md: 3, sm: 12 } },
        { title: 'Pengalaman Melatih 2 Tahun Terakhir', value: '5 Kali', grid: { cols: 12, md: 4, sm: 12 } },
        { title: 'Pendidikan Terakhir', value: 'S1 - Universitas Brawijawa', grid: { cols: 12, md: 6, sm: 12 } },
      ];
    },
  },
};
</script>
