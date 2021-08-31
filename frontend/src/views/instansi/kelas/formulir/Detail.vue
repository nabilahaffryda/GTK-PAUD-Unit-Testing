<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-container class="black--text">
        <div class="body-1 font-weight-medium">Info dan Detil Kelas</div>
        <v-row dense no-gutters class="my-2">
          <v-col cols="12" md="1" sm="2" class="pa-0">
            <v-avatar color="primary" size="60">
              <v-icon dark>mdi-teach</v-icon>
            </v-avatar>
          </v-col>
          <v-col cols="12" md="11" sm="10" class="px-0">
            <div>
              <div class="label--text">Nama Kelas</div>
              <div class="body-1 font-weight-medium">{{ $getDeepObj(kelas, 'nama') || '-' }}</div>
            </div>
            <div class="my-2">
              <div class="label--text">Deskripsi Kelas</div>
              <div class="body-1">{{ $getDeepObj(kelas, 'deskripsi') || '-' }}</div>
            </div>
            <v-row class="my-2">
              <v-col>
                <div class="label--text">Jumlah Pengajar</div>
                <div class="body-1">
                  <v-chip class="ma-2" color="green" text-color="white">
                    {{ $getDeepObj(kelas, 'jml_pengajar') || '-' }}
                  </v-chip>
                </div>
              </v-col>
            </v-row>
            <v-row class="my-2">
              <v-col>
                <div class="label--text">Tanggal Mulai Kelas</div>
                <div class="body-1">
                  {{ $localDate($getDeepObj(kelas, 'paud_diklat.data.paud_periode.data.tgl_diklat_mulai')) || '-' }}
                </div>
              </v-col>
              <v-col>
                <div class="label--text">Tanggal Selesai Kelas</div>
                <div class="body-1">
                  {{ $localDate($getDeepObj(kelas, 'paud_diklat.data.paud_periode.data.tgl_diklat_selesai')) || '-' }}
                </div>
              </v-col>
            </v-row>
            <div class="my-2" v-if="false">
              <v-btn depressed link><v-icon left>mdi-link</v-icon> Menuju LMS</v-btn>
            </div>
          </v-col>
          <v-col cols="12" md="2" sm="12">
            <v-chip v-if="false" color="success">TERSINKRON</v-chip>
          </v-col>
        </v-row>
        <div>
          <v-toolbar flat>
            <v-toolbar-title class="title secondary--text">Daftar Peserta Diklat</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              dense
              placeholder="Pencarian Data"
              append-icon="mdi-magnify"
              @keyup.enter="fetch"
              @click:append="fetch"
            ></v-text-field>
            <v-btn v-if="false" class="mt-n3" icon><v-icon>mdi-download</v-icon></v-btn>
          </v-toolbar>
          <div class="my-4">
            <v-data-table
              :headers="headers"
              :items="items"
              :options.sync="options"
              :server-items-length="totalItems"
              :loading="loading"
              :no-data-text="`Daftar Peserta Diklat belum ditemukan`"
            >
            </v-data-table>
          </div>
        </div>
      </v-container>
    </v-card-text>
  </v-card>
</template>
<script>
import { mapActions } from 'vuex';
export default {
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
      loading: true,
      options: {},
      totalItems: 0,
      search: '',
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
      return temp;
    },

    items() {
      return (this.pesertas || []).map((item) => {
        return {
          nama: this.$getDeepObj(item, 'ptk.data.nama') || this.$getDeepObj(item, 'akun.data.nama') || '-',
          email: this.$getDeepObj(item, 'ptk.data.email') || this.$getDeepObj(item, 'akun.data.email') || '-',
          status: this.$getDeepObj(item, 'm_konfirmasi_paud.data.keterangan') || '-',
          paud_kelas_petugas_id: this.$getDeepObj(item, 'paud_kelas_petugas_id'),
          paud_kelas_peserta_id: this.$getDeepObj(item, 'paud_kelas_peserta_id') || '',
          ptk_id: this.$getDeepObj(item, 'ptk_id') || '',
        };
      });
    },

    kelasId() {
      return this.$getDeepObj(this.kelas, 'paud_kelas_id');
    },
  },
  methods: {
    ...mapActions('petugasKelas', ['getListKelas']),

    reset() {
      this.tab = 0;
      this.kelas = {};
      this.pesertas = [];
      this.peserta = [];
      this.petugas = [];
      this.search = '';
    },

    fetch() {
      if (!this.kelasId) return;

      const params = Object.assign({}, this.search && this.search.length >= 3 ? { keyword: this.search || '' } : {});
      this.getListKelas({
        id: this.kelasId,
        page: this.options.page || 1,
        params,
      }).then(({ data, meta }) => {
        this.loading = false;
        this.pesertas = data || [];
        this.totalItems = (meta && meta.total) || 0;
      });
    },

    onReload() {
      this.$emit('reload');
      this.fetch();
    },
  },

  watch: {
    kelasId: 'fetch',
    options: {
      handler() {
        this.fetch();
      },
      deep: true,
    },
  },
};
</script>
