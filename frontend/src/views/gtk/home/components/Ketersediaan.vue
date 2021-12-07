<template>
  <div class="mx-auto">
    <v-card flat>
      <v-card-text class="black--text">
        <div class="font-weight-bold headline">Daftar Konfirmasi Kesediaan</div>
        <div>Silakan lakukan konfirmasi kesediaan Anda untuk mengisi kelas diklat</div>

        <div class="mt-5">
          <v-row v-if="$vuetify.breakpoint.mdAndUp">
            <v-col cols="12" md="3" sm="12">
              <span class="font-weight-medium">Nama Instansi</span>
            </v-col>
            <v-col cols="12" md="3" sm="12">
              <span class="font-weight-medium">Nama Kelas</span>
            </v-col>
            <v-col cols="12" md="2" sm="12">
              <span class="font-weight-medium">Jadwal Pelaksanaan</span>
            </v-col>
            <v-col cols="12" md="2" sm="12">
              <span class="font-weight-medium">Status</span>
            </v-col>
          </v-row>
          <v-row v-for="(item, i) in items" :key="i">
            <v-col class="py-0" cols="12" md="3">
              <v-list-item class="px-0">
                <v-list-item-content class="pa-0">
                  <div class="black--text">
                    {{ $getDeepObj(item, 'paud_kelas.paud_diklat.instansi.nama') || '-' }}
                  </div>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col class="py-0" cols="12" md="3">
              <v-list-item class="px-0">
                <v-list-item-content class="pa-0">
                  <div class="black--text">
                    {{ $getDeepObj(item, 'paud_kelas.paud_diklat.nama') || '-' }} -
                    {{ $getDeepObj(item, 'paud_kelas.nama') || '-' }}
                  </div>
                  <div v-if="$getDeepObj(item, 'paud_kelas.url_jadwal')" class="blue--text caption">
                    <v-icon left small>mdi-file</v-icon>
                    <a class="blue--text" :href="$getDeepObj(item, 'paud_kelas.url_jadwal')" target="_blank">
                      Dokumen Jadwal Diklat
                    </a>
                  </div>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col class="py-0" cols="12" md="2">
              <v-list-item class="px-0">
                <v-list-item-content class="pa-0">
                  <div>
                    {{
                      $durasi(
                        $getDeepObj(item, 'paud_kelas.paud_diklat.paud_periode.tgl_diklat_mulai'),
                        $getDeepObj(item, 'paud_kelas.paud_diklat.paud_periode.tgl_diklat_selesai')
                      )
                    }}
                  </div>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col class="py-0" cols="12" md="2">
              <v-list-item class="px-0">
                <v-list-item-content class="pa-0">
                  <span
                    :class="
                      +item.k_konfirmasi_paud === 3
                        ? 'success--text'
                        : +item.k_konfirmasi_paud === 4
                        ? 'red--text'
                        : 'grey--text'
                    "
                  >
                    <i>
                      {{ $getDeepObj(item, 'm_konfirmasi_paud.keterangan') }}
                    </i>
                  </span>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col class="py-0" cols="12" md="2">
              <v-list-item class="pa-0">
                <v-list-item-content>
                  <div>
                    <v-btn
                      color="orange"
                      block
                      depressed
                      dark
                      small
                      :outlined="+item.k_konfirmasi_paud !== 2"
                      @click="onDetail(item)"
                    >
                      {{ +item.k_konfirmasi_paud === 2 ? 'Konfirmasi' : 'Detail' }}
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
            <v-toolbar-title>Konfirmasi Kesediaan</v-toolbar-title>
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
                <div class="font-weight-medium body-1">
                  {{ $getDeepObj(diklat, 'paud_kelas.paud_diklat.nama') || '-' }} -
                  {{ $getDeepObj(diklat, 'paud_kelas.nama') || '-' }}<br />
                  <span class="body-2">
                    {{ $getDeepObj(diklat, 'paud_kelas.paud_diklat.instansi.nama') || '-' }}
                  </span>
                </div>
              </v-toolbar>
              <v-spacer></v-spacer>
              <v-chip
                :color="+diklat.k_konfirmasi_paud === 3 ? 'success' : +diklat.k_konfirmasi_paud === 4 ? 'red' : ''"
                small
                dark
              >
                {{ $getDeepObj(diklat, 'm_konfirmasi_paud.keterangan') }}
              </v-chip>
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
                        {{
                          $durasi(
                            $getDeepObj(diklat, 'paud_kelas.paud_diklat.paud_periode.tgl_diklat_mulai'),
                            $getDeepObj(diklat, 'paud_kelas.paud_diklat.paud_periode.tgl_diklat_selesai')
                          )
                        }}
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
                        {{ $getDeepObj(diklat, 'paud_kelas.paud_diklat.m_kota.keterangan') || '-' }}
                      </div>
                    </v-list-item-content>
                  </v-list-item>
                </v-col>
                <v-col cols="12" sm="12" md="4">
                  <v-list-item>
                    <v-list-item-content class="py-0 mt-3">
                      <div class="label--text">Kecamatan</div>
                      <div class="body-1 black--text">
                        {{ $getDeepObj(diklat, 'paud_kelas.m_kecamatan.keterangan') || '-' }}
                      </div>
                    </v-list-item-content>
                  </v-list-item>
                </v-col>
                <v-col cols="12" sm="12" md="4">
                  <v-list-item>
                    <v-list-item-content class="py-0 mt-3">
                      <div class="label--text">Kelurahan</div>
                      <div class="body-1 black--text">
                        {{ $getDeepObj(diklat, 'paud_kelas.m_kelurahan.keterangan') || '-' }}
                      </div>
                    </v-list-item-content>
                  </v-list-item>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <div class="text-right">
            <template v-if="+diklat.k_konfirmasi_paud === 2">
              <v-btn color="red" dark depressed @click="onKonfirmasi('tolak')">Tidak Bersedia</v-btn>
              <v-btn class="mx-md-1" color="success" dark depressed @click="onKonfirmasi('setuju')">Bersedia</v-btn>
            </template>
            <template v-else-if="+diklat.k_konfirmasi_paud !== 1">
              <v-btn color="red" dark depressed @click="onKonfirmasi('reset')">
                <v-icon left>mdi-close</v-icon>
                Batalkan
              </v-btn>
            </template>
          </div>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import { mapActions } from 'vuex';
import { getDeepObj } from '../../../../utils/format';
export default {
  props: {
    items: {
      type: Array,
      default: () => null,
    },
  },
  data() {
    return {
      dialog: false,
      diklat: {},
    };
  },
  computed: {},
  methods: {
    ...mapActions('diklat', ['fetch', 'getDetail', 'actions']),

    close() {
      this.dialog = false;
    },

    async onDetail(data) {
      this.dialog = true;
      this.$nextTick(() => {
        this.$set(this, 'diklat', data);
      });
    },

    onKonfirmasi(status) {
      if (![1].includes(Number(this.diklat?.paud_kelas?.k_verval_paud))) {
        this.$error('Konfirmasi sudah dikunci karena kelas sudah diajukan atau diproses');
        return;
      }
      this.actions({ id: this.$getDeepObj(this.diklat, 'paud_kelas_peserta_id'), name: status }).then(
        ({ kelas, data }) => {
          this.$set(this, 'diklat', {});
          const mAction = {
            setuju: 'ptkKelasPesertaBersedia',
            tolak: 'ptkKelasPesertaTolak',
            reset: 'ptkKelasPesertaBatal',
          };

          const peserta = getDeepObj(data, `${mAction[status]}`) || {};

          this.$success('Aksi berhasil dijalankan');
          this.dialog = false;
          this.$set(this, 'diklat', peserta);
          this.$emit('reload', kelas);
        }
      );
    },
  },
};
</script>
