<template>
  <popup-notifikasi ref="popup">
    <template slot="content">
      <v-row class="my-3 black--text">
        <template v-if="['tbs', 'tbs-demo'].includes(key)">
          <v-row>
            <v-col cols="12">
              <v-alert
                icon="mdi-information"
                close-text="Tutup"
                colored-border
                border="left"
                type="info"
                class="bg-notif mt-3 mb-3"
                color="yellow darken-2"
              >
                Anda memiliki waktu <strong>{{ isDemo ? '35' : '85' }} Menit</strong> untuk menyelesaikan
                {{ isDemo ? 'Uji Coba' : '' }} Tes Bakat Skolastik.
              </v-alert>
              <ol class="body-1">
                <li>
                  Tes Bakat Skolastik (TBS) terdiri dari 3 (tiga) subtes, yaitu subtes
                  <strong>Verbal, Kuantitatif, dan Penalaran.</strong> Untuk subtes Verbal disediakan waktu
                  {{ isDemo ? '7' : '15' }} menit, Kuantitatif {{ isDemo ? '12' : '35' }} menit, dan Penalaran
                  {{ isDemo ? '7' : '35' }} menit. Untuk seluruh subtes yang terdiri atas
                  {{ isDemo ? '22' : '55' }} butir soal disediakan waktu {{ isDemo ? '26' : '85' }} menit.
                </li>
                <li>
                  Subtes Verbal dikerjakan terlebih dahulu, setelah waktunya selesai peserta tes baru melanjutkan ke
                  subtes Kuantitatif dan kemudian subtes Penalaran.
                </li>
                <li>
                  Anda tidak dapat mengerjakan subtes sebelumnya atau sesudahnya selain alokasi waktu yang sudah
                  ditentukan.
                </li>
                <li> Ikutilah alur pengerjaan dengan alokasi waktu masing-masing subtes. </li>
                <li>
                  Calon peserta wajib menjawab semua soal karena tidak ada pengurangan nilai untuk soal yang salah.
                </li>
                <li>
                  Dilarang merekam/menyalin dan menyebarkan informasi terkait TBS kepada orang lain baik melalui media
                  daring maupun media sosial
                </li>
              </ol>
            </v-col>
            <v-col cols="12" class="body-1 blue lighten-5">
              <strong>Dengan ini saya menyatakan:</strong>
              <v-checkbox class="mt-0" v-model="isChecked" hide-details>
                <template v-slot:label>
                  <div class="black--text">
                    Telah membaca dan siap mengerjakan {{ isDemo ? 'Uji Coba' : '' }} Tes Bakat Skolastik (TBS) sesuai
                    dengan instruksi yang ada
                  </div>
                </template>
              </v-checkbox>
            </v-col>
          </v-row>
        </template>
        <template v-else>
          <v-col cols="12">
            <p class="body-1">Dengan ini Saya menyatakan</p>
            <ol class="body-2" style="line-height: 2em">
              <li> Akan mengikuti proses seleksi program Sekolah Penggerak dengan <b>sebaik - baiknya.</b> </li>
              <li>
                <b>Tidak akan</b> melakukan perekaman atau melakukan tangkapan layar saat mengikuti proses seleksi dan
                tidak akan membagikan rekaman atau tangkapan layar proses seleksi
                <b>dengan cara apapun (UU no 11 2008/UU ITE)</b>.
              </li>
              <li> Tidak akan melakukan <b>plagiarisme</b>. </li>
              <li> Bersedia <b>menerima konsekuensi</b> pelanggaran ketentuan di atas. </li>
            </ol>
          </v-col>
          <v-col cols="12" class="body-1 blue lighten-5">
            <v-checkbox class="mt-0 mb-3" v-model="isChecked" hide-details>
              <template v-slot:label>
                <div class="black--text"> Saya mengerti dan telah membaca semua informasi yang disebutkan di atas </div>
              </template>
            </v-checkbox>
          </v-col>
        </template>
      </v-row>
    </template>
    <template slot="action">
      <v-spacer></v-spacer>
      <div class="text-right">
        <v-btn text class="mr-2" color="grey" right dark @click="close"> Batal </v-btn>
        <v-btn color="info" :disabled="!isChecked" right @click="onConfirm">
          {{ isMulai ? 'Lanjutkan' : 'Mulai' }} Pengerjaan
        </v-btn>
      </div>
    </template>
  </popup-notifikasi>
</template>

<script>
export default {
  name: 'GotoModul',
  components: {
    PopupNotifikasi: () => import('@components/popup/Notifikasi'),
  },
  emit: ['agree'],
  data() {
    return {
      isChecked: false,
      isMulai: false,
      isDemo: false,
      key: '',
      action: '',
    };
  },
  methods: {
    open({ title, key, action, is_mulai }) {
      this.key = key;
      this.action = action;
      this.isChecked = false;
      this.isMulai = is_mulai || false;
      this.isDemo = key.includes('demo');
      this.$refs.popup.open(false, title);
    },
    close() {
      this.$refs.popup.onClose();
    },
    onConfirm() {
      this.$emit('agree', { action: this.action });
      this.close();
    },
  },
};
</script>

<style scoped></style>
