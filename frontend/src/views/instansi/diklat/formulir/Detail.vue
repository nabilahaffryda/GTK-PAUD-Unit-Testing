<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-container class="black--text">
        <div class="body-1 font-weight-medium">Info dan Detil Kelas</div>
        <v-row dense no-gutters class="my-5">
          <v-col cols="12" md="1" sm="1" class="pa-0">
            <v-avatar color="secondary" size="60">
              <v-icon dark>mdi-teach</v-icon>
            </v-avatar>
          </v-col>
          <v-col cols="12" md="9" sm="9" class="px-0">
            <div>
              <div class="label--text">Nama Kelas</div>
              <div class="body-1 font-weight-medium">{{ $getDeepObj(kelas, 'nama') || '-' }}</div>
            </div>
            <div class="my-5">
              <div class="label--text">Deskripsi Kelas</div>
              <div class="body-1">{{ $getDeepObj(kelas, 'deskripsi') || '-' }}</div>
            </div>
            <v-row class="my-5">
              <v-col>
                <div class="label--text">Tanggal Mulai Kelas</div>
                <div class="body-1">-</div>
              </v-col>
              <v-col>
                <div class="label--text">Tanggal Selesai Kelas</div>
                <div class="body-1">-</div>
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
              <v-btn class="mt-n3" color="primary" v-if="tab > 0" @click="onAddPetugas">
                <v-icon left>mdi-plus</v-icon>Tambah
              </v-btn>
            </v-toolbar>

            <div class="my-4">
              <v-data-table
                v-model="peserta"
                :headers="headers"
                :items="items"
                :single-select="false"
                item-key="nama"
                show-select
                :no-data-text="`Daftar ${item.text} tidak ditemukan`"
              >
                <template v-slot:[`item.aksi`]="{ item }">
                  <v-btn icon @click="onDelete(item)"> <v-icon small>mdi-trash-can</v-icon></v-btn>
                </template>
              </v-data-table>
            </div>
          </v-tab-item>
        </v-tabs-items>
      </v-container>
    </v-card-text>
    <base-list-popup
      ref="popup"
      :api="`diklatKelas/getListKelas`"
      :id="$getDeepObj(detail, 'paud_diklat_id')"
      title="Pilih Petugas Diklat"
      multiselect
    />
  </v-card>
</template>
<script>
import { mapActions } from 'vuex';
import BaseListPopup from '@components/base/BaseListPopup';
export default {
  components: { BaseListPopup },
  props: {
    detail: {
      type: Object,
      default: () => {},
    },
  },

  data() {
    return {
      kelas: {},
      pesertas: [],
      peserta: null,
      petugas: [],
      tab: null,
      tabItems: [
        { value: 'peserta', kPetugas: 0, text: 'Peserta' },
        { value: 'admin', kPetugas: 4, text: 'Admin Kelas' },
        { value: 'pembimbing-praktik', kPetugas: 3, text: 'Pembimbing Praktik' },
        { value: 'pengajar', kPetugas: 1, text: 'Pengajar' },
        { value: 'pengajar-tambahan', kPetugas: 2, text: 'Pengajar Tambahan' },
      ],
    };
  },
  computed: {
    headers() {
      let temp = [
        {
          text: 'Nama Lengkap',
          align: 'start',
          sortable: false,
          value: 'nama',
        },
        { text: 'Surel', value: 'email', sortable: false },
      ];

      if (this.tab > 0) {
        temp.push({ text: 'Status', value: 'status', sortable: false });
        temp.push({ text: '', value: 'aksi', sortable: false });
      }

      return temp;
    },

    items() {
      return this.pesertas.map((item) => {
        return {
          nama: this.$getDeepObj(item, 'ptk.data.nama') || '-',
          email: this.$getDeepObj(item, 'ptk.data.email') || '-',
          status: 'Belum Kofirmasi',
        };
      });
    },
  },
  methods: {
    ...mapActions('diklatKelas', ['getListKelas']),

    reset() {
      this.tab = 0;
      this.kelas = {};
    },

    fetch(tipe, k_petugas = null) {
      if (!Object.keys(this.kelas).length) return;
      this.getListKelas({
        diklat_id: this.detail.paud_diklat_id,
        id: this.$getDeepObj(this.kelas, 'paud_kelas_id'),
        tipe: tipe,
        params: {
          k_petugas_paud: k_petugas,
        },
      }).then(({ data }) => {
        this.pesertas = data || [];
      });
    },

    onDelete() {},

    onAddPetugas() {
      const fields = [
        {
          key: 'ptk.data.nama',
          title: 'Nama',
          icon: 'mdi-account-circle',
          grid: { md: 4, sm: 12, cols: 12 },
        },
        {
          key: 'ptk_id',
          title: 'No UKG',
          grid: { md: 2, sm: 12, cols: 12 },
        },
        {
          key: 'ptk.data.email',
          title: 'Alamat Surel',
          grid: { md: 4, sm: 12, cols: 12 },
        },
      ];

      const params = {
        diklat_id: this.detail.paud_diklat_id,
        id: this.$getDeepObj(this.kelas, 'paud_kelas_id'),
        tipe: 'petugas',
        params: {
          k_petugas_paud: this.tabItems[+this.tab]['kPetugas'],
        },
      };

      this.$refs.popup.open(fields, params).then((data) => {
        if (data) {
          this.petugas = Object.assign({}, data);
        }
      });
    },
  },
  watch: {
    tab: function (value) {
      switch (Number(value)) {
        case 0:
          this.fetch('peserta');
          break;
        case 1:
          this.fetch('petugas', 4);
          break;
        case 2:
          this.fetch('petugas', 3);
          break;
        case 3:
          this.fetch('petugas', 1);
          break;
        case 4:
          this.fetch('petugas', 2);
          break;
      }
    },
    kelas: {
      handler(value) {
        if (value && value.paud_kelas_id) {
          this.fetch('peserta');
        }
      },
      deep: true,
    },
  },
};
</script>
