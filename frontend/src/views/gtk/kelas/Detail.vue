<template>
  <div>
    <v-card flat class="mb-4 pa-4">
      <base-breadcrumbs :items="breadcrumbs" class="px-0 py-0" />
    </v-card>
    <v-card flat class="pa-4">
      <v-card-text>
        <v-row>
          <v-col cols="12" md="8" style="border: 3px solid #efefef; border-radius: 7px">
            <div style="display: flex; justify-content: space-between">
              <h3 class="secondary--text">Detail Diklat</h3>
              <v-chip class="ma-2" color="success" text-color="white" small>
                <v-avatar left>
                  <v-icon small>mdi-checkbox-marked-circle</v-icon>
                </v-avatar>
                Lulus
              </v-chip>
            </div>
            <template v-for="(item, i) in info">
              <v-row dense :key="i">
                <v-col>{{ item.label }}</v-col>
                <v-col>: {{ item.value }}</v-col>
              </v-row>
            </template>

            <p class="my-4">
              Selamat, Anda telah dinyatakan LULUUS dalam kelas Dikklatt Berjenjang tingkat dasar PAUD. Silakan
              mengunduh sertifikat dibawah ini
            </p>
            <v-btn depressed color="secondary" class="white--text">
              <v-icon left dark> mdi-certificate </v-icon>
              Unduh Sertifikat
            </v-btn>
          </v-col>
          <v-col cols="12" md="4" class="py-0">
            <v-row>
              <v-col cols="12">
                <v-card flat color="#CFFFE6" style="border-radius: 7px">
                  <v-card-text class="text-center">
                    <p class="text-left"
                      ><v-icon color="#FFA800" small>mdi-star</v-icon> Nilai Diklat (<b>Amat Baik</b>)</p
                    >
                    <h1 class="green--text py-5" style="font-size: 60px">90</h1>
                  </v-card-text>
                </v-card>
              </v-col>
              <v-col cols="12">
                <v-card flat color="#FFEECF" style="border-radius: 7px">
                  <v-card-text class="text-center">
                    <p class="text-left"
                      ><v-icon color="#FFA800" small>mdi-star</v-icon> Nilai Diklat (<b>Amat Baik</b>)</p
                    >
                    <h1 class="green--text py-5" style="font-size: 60px">90</h1>
                  </v-card-text>
                </v-card>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import BaseBreadcrumbs from '@components/base/BaseBreadcrumbs';
export default {
  name: 'DetailKelas',
  components: { BaseBreadcrumbs },
  data() {
    return {};
  },
  computed: {
    breadcrumbs() {
      return [{ text: 'Kelas Diklat', to: 'kelas' }, { text: 'Hasil Kelas Diklat' }];
    },

    info() {
      return [
        { label: 'Nama Peserta', value: 'Intan Kurnia Safitri' },
        { label: 'Nomor Peserta', value: '98768789' },
        { label: 'Instansi LPD', value: 'UMM' },
        { label: 'Angkatan Diklat', value: 'Angkatan 1' },
      ];
    },
  },
  methods: {},
};
</script>

<style scoped>
.bg-kiri {
  background: #ffab91;
  height: 100%;
}
</style>
