<template>
  <div class="mx-auto">
    <v-card flat>
      <v-card-text class="black--text">
        <div class="font-weight-bold headline">Daftar Konfirmasi Ketersediaan</div>
        <div>Silakan lakukan konfirmasi ketersediaan Anda untuk mengisi kelas diklat</div>

        <div class="mt-5">
          <v-row v-for="(item, i) in items" :key="i">
            <v-col class="py-0" cols="12" md="4">
              <v-list-item class="px-0">
                <v-list-item-content class="pa-0">
                  <div class="black--text">
                    {{ $getDeepObj(item, 'nama') || '-' }}
                  </div>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col class="py-0" cols="12" md="3">
              <v-list-item class="px-0">
                <v-list-item-content class="pa-0">
                  {{ $localDate($getDeepObj(item, 'wkt_mulai')) || '-' }} s/d
                  {{ $localDate($getDeepObj(item, 'wkt_selesai')) || '-' }}
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col class="py-0" cols="12" md="3">
              <v-list-item class="px-0">
                <v-list-item-content class="pa-0">
                  <span :class="item.status === 1 ? 'success--text' : item.status === 0 ? 'red--text' : 'grey--text'">
                    <i>
                      {{ item.status === 1 ? 'Bersedia' : item.status === 0 ? 'Tidak Bersedia' : 'Belum Konfirmasi' }}
                    </i>
                  </span>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col class="py-0" cols="12" md="2">
              <v-list-item class="pa-0">
                <v-list-item-content>
                  <div>
                    <v-btn color="orange" block depressed dark :outlined="item.status !== null" @click="onDetail(item)">
                      {{ item.status === null ? 'Konfirmasi' : 'Detail' }}
                    </v-btn>
                  </div>
                </v-list-item-content>
              </v-list-item>
            </v-col>
          </v-row>
        </div>
      </v-card-text>
    </v-card>
    <v-dialog v-model="dialog" max-width="650" persistent transition="dialog-bottom-transition" scrollable>
      <v-card>
        <v-card-title class="pa-0">
          <v-toolbar flat dark color="secondary">
            <v-toolbar-title>Konfirmasi Ketersediaan</v-toolbar-title>
            <v-spacer />
            <v-btn right icon dark @click="close">
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-toolbar>
        </v-card-title>
        <v-card-text class="pb-0 black--text my-5">
          <v-card outlined flat>
            <v-card-title>
              <v-toolbar flat>
                {{ $getDeepObj(diklat, 'nama') || '-' }}
              </v-toolbar>
              <v-spacer></v-spacer>
              <v-chip>Belum Konfirmasi</v-chip>
            </v-card-title>
            <v-card-text>
              <v-row>
                <v-col cols="12" sm="12" md="12">
                  <v-list-item>
                    <v-list-item-avatar color="primary">
                      <v-icon dark>mdi-calendar-clock</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content class="py-0 mt-3">
                      <div class="label--text">Jadwal Diklat</div>
                      <div class="body-1 black--text">
                        {{ $localDate($getDeepObj(diklat, 'wkt_mulai')) || '-' }} s/d
                        {{ $localDate($getDeepObj(diklat, 'wkt_selesai')) || '-' }}
                      </div>
                    </v-list-item-content>
                  </v-list-item>
                </v-col>
                <v-col cols="12" sm="12" md="4">
                  <v-list-item>
                    <v-list-item-avatar color="primary">
                      <v-icon dark>mdi-map-check</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content class="py-0 mt-3">
                      <div class="label--text">Kota/Kab</div>
                      <div class="body-1 black--text">
                        {{ $getDeepObj(diklat, 'm_kota.keterangan') || '-' }}
                      </div>
                    </v-list-item-content>
                  </v-list-item>
                </v-col>
                <v-col cols="12" sm="12" md="4">
                  <v-list-item>
                    <v-list-item-content class="py-0 mt-3">
                      <div class="label--text">Kecamatan</div>
                      <div class="body-1 black--text">
                        {{ $getDeepObj(diklat, 'm_kota.keterangan') || '-' }}
                      </div>
                    </v-list-item-content>
                  </v-list-item>
                </v-col>
                <v-col cols="12" sm="12" md="4">
                  <v-list-item>
                    <v-list-item-content class="py-0 mt-3">
                      <div class="label--text">Kelurahan</div>
                      <div class="body-1 black--text">
                        {{ $getDeepObj(diklat, 'm_kota.keterangan') || '-' }}
                      </div>
                    </v-list-item-content>
                  </v-list-item>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
          <div>
            Apakan Anda bersedia berpartisipasi dalam program diklat gtk paud sebagi
            <b>[PENGAJAR, PEMBIMBING PRAKTIK]</b>
          </div>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <div class="text-right">
            <v-btn color="red" dark depressed>Tidak Bersedia</v-btn>
            <v-btn class="mx-md-1" color="success" dark depressed>Bersedia</v-btn>
          </div>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  data() {
    return {
      dialog: false,
      diklat: {},
    };
  },
  computed: {
    items() {
      return [
        {
          nama: 'Diklat Matematika Dasat - Kelas A',
          wkt_mulai: '2021-03-06',
          wkt_selesai: '2021-05-07',
          status: null,
          m_kota: {
            keterangan: 'Malang',
          },
        },
        {
          nama: 'Ilmu Pengetahuan Alam',
          wkt_mulai: '2021-03-06',
          wkt_selesai: '2021-05-07',
          status: 0,
          m_kota: {
            keterangan: 'Malang',
          },
        },
        {
          nama: 'Matematika Kelas 12 B',
          wkt_mulai: '2021-03-06',
          wkt_selesai: '2021-05-07',
          status: 1,
          m_kota: {
            keterangan: 'Malang',
          },
        },
      ];
    },
  },
  methods: {
    close() {
      this.dialog = false;
    },

    onDetail(data) {
      this.dialog = true;
      this.$nextTick(() => {
        this.$set(this, 'diklat', data);
      });
    },
  },
};
</script>
