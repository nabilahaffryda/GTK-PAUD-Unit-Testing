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
              <v-chip v-if="detail.is_lulus" class="ma-2" color="success" text-color="white" small>
                <v-avatar left>
                  <v-icon small>mdi-checkbox-marked-circle</v-icon>
                </v-avatar>
                LULUS
              </v-chip>
              <v-chip v-else class="ma-2" color="error" text-color="white" small>
                <v-avatar left>
                  <v-icon small>mdi-alert-decagram</v-icon>
                </v-avatar>
                TIDAK LULUS
              </v-chip>
            </div>
            <template v-for="(item, i) in info">
              <v-row dense :key="i">
                <v-col>{{ item.label }}</v-col>
                <v-col>: {{ item.value }}</v-col>
              </v-row>
            </template>

            <p class="my-4">
              <template v-if="detail.is_lulus">
                Selamat, Anda telah dinyatakan <b>LULUS</b> dalam kelas Diklat berjenjang tingkat dasar PAUD. Silakan
                mengunduh sertifikat dibawah ini
              </template>
              <template v-else>
                Mohon maaf, Anda <b>Belum Lulus</b> dalam kelas Diklat berjenjang tingkat dasar PAUD, Silakan
                menghubungi LPD terkait untuk bisa mengikuti periode berikutnya
              </template>
            </p>
            <v-btn v-if="detail.is_lulus" depressed color="secondary" class="white--text" @click="onSertifikat">
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
                      ><v-icon color="#FFA800" small>mdi-star</v-icon> Nilai Diklat (<b>{{ detail.predikat }}</b
                      >)</p
                    >
                    <h1 class="green--text py-5" style="font-size: 60px">{{ detail.nilai }}</h1>
                  </v-card-text>
                </v-card>
              </v-col>
              <v-col cols="12" v-if="detail.is_lulus">
                <v-card flat color="#FFEECF" style="border-radius: 7px">
                  <v-card-text class="text-center">
                    <p class="text-left"
                      ><v-icon color="#FFA800" small>mdi-medal</v-icon> Medali Nilai (<b>{{ detail.medali }}</b
                      >)</p
                    >
                    <h1 class="green--text py-2" style="font-size: 60px">
                      <v-icon :color="medals[detail.medali]" style="font-size: 60px">mdi-trophy-variant</v-icon>
                    </h1>
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
import { mapActions } from 'vuex';
export default {
  name: 'DetailKelas',
  components: { BaseBreadcrumbs },
  data() {
    return {
      detail: '',
      medals: {
        Gold: '#FFA800',
        Silver: '#C0C0C0',
        Copper: '#E3BFA4',
      },
    };
  },
  computed: {
    id() {
      return this.$route.params.id;
    },
    breadcrumbs() {
      return [{ text: 'Kelas Diklat', to: 'kelas' }, { text: 'Hasil Kelas Diklat' }];
    },

    info() {
      return [
        { label: 'Nama Peserta', value: this.$getDeepObj(this.detail, 'ptk.nama') },
        { label: 'Nomor Peserta', value: this.$getDeepObj(this.detail, 'ptk_id') },
        { label: 'Instansi LPD', value: this.$getDeepObj(this.detail, 'ptk.instansi') },
        { label: 'Angkatan Diklat', value: `Angkatan ${this.$getDeepObj(this.detail, 'angkatan')}` },
      ];
    },
  },
  async mounted() {
    const { ptkValidateSurvey } = await this.getHasil(Number(this.id));
    this.detail = ptkValidateSurvey?.kelasPeserta || {};
  },
  methods: {
    ...mapActions('diklat', ['getHasil', 'getSertifikat']),

    async onSertifikat() {
      try {
        const { ptkValidateSertifikat } = await this.getSertifikat(Number(this.id));
        window.open(ptkValidateSertifikat?.url_download);
      } catch (error) {
        this.$error(error);
      }
    },
  },
};
</script>

<style scoped>
.bg-kiri {
  background: #ffab91;
  height: 100%;
}
</style>
