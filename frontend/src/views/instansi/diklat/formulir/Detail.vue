<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-container class="black--text">
        <div class="body-1 font-weight-medium">Info dan Detil Kelas</div>
        <v-row dense no-gutters class="my-5">
          <v-col cols="12" md="1" sm="1" class="pa-0">
            <v-avatar color="info" size="60">
              <v-icon dark>mdi-teach</v-icon>
            </v-avatar>
          </v-col>
          <v-col cols="12" md="9" sm="9" class="px-0">
            <div>
              <div class="label--text">Nama Kelas</div>
              <div class="body-1 font-weight-medium">Diklat Metematika Dasar - Kelas A</div>
            </div>
            <div class="my-5">
              <div class="label--text">Deskripsi Kelas</div>
              <div class="body-1">Diklat Metematika Dasar - Kelas A</div>
            </div>
            <v-row class="my-5">
              <v-col>
                <div class="label--text">Tanggal Mulai Kelas</div>
                <div class="body-1">Rabu 10 November 2021</div>
              </v-col>
              <v-col>
                <div class="label--text">Tanggal Selesai Kelas</div>
                <div class="body-1">Rabu 10 November 2021</div>
              </v-col>
            </v-row>
            <div class="my-5">
              <v-btn depressed link><v-icon left>mdi-link</v-icon> Menuju LMS</v-btn>
            </div>
          </v-col>
          <v-col cols="12" md="2" sm="12">
            <v-chip color="success">TERSINKRON</v-chip>
          </v-col>
        </v-row>

        <v-tabs v-model="tab" fixed-tabs>
          <v-tab v-for="item in tabItems" :key="item.value">
            {{ item.text }}
          </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab" class="my-3">
          <v-tab-item v-for="item in tabItems" :key="item.value">
            <v-toolbar flat>
              <v-toolbar-title class="body-2">Daftar {{ item.text }}</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-text-field dense placeholder="Pencarian Data" append-icon="mdi-magnify"></v-text-field>
              <v-btn class="mt-n3" icon><v-icon>mdi-download</v-icon></v-btn>
              <v-btn class="mt-n3" color="primary"><v-icon left>mdi-plus</v-icon>Tambah</v-btn>
            </v-toolbar>

            <div class="my-4">
              <v-data-table v-model="peserta" :headers="headers" :items="[]" single-select item-key="name" show-select>
              </v-data-table>
            </div>
          </v-tab-item>
        </v-tabs-items>
      </v-container>
    </v-card-text>
  </v-card>
</template>
<script>
export default {
  data() {
    return {
      pesertas: [],
      peserta: null,
      tab: null,
      tabItems: [
        { value: 'peserta', text: 'Peserta' },
        { value: 'admin', text: 'Admin Kelas' },
        { value: 'pembimbing-praktik', text: 'Pembimbing Praktik' },
        { value: 'pengajar', text: 'Pengajar' },
        { value: 'pengajar-tambahan', text: 'Pengajar Tambahan' },
      ],
      headers: [
        {
          text: 'Nama Lengkap',
          align: 'start',
          sortable: false,
          value: 'nama',
        },
        { text: 'Surel', value: 'email', sortable: false },
        { text: 'Status', value: 'status', sortable: false },
        { text: '', value: 'aksi', sortable: false },
      ],
    };
  },
  methods: {
    reset() {},
  },
};
</script>
