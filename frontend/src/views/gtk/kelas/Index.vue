<template>
  <div>
    <!--notif jadwal-->
    <v-card tile flat class="my-5">
      <v-card-text class="pa-0">
        <v-row no-gutters>
          <v-col cols="2">
            <div class="bg-kiri"></div>
          </v-col>
          <v-col cols="10" class="pa-5 black--text">
            <h1 class="headline black--text--text"> Kelas Diklat </h1>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <v-card>
      <v-card-title class="pa-0">
        <base-table-header @search="onSearch" @reload="onReload">
          <template v-slot:subtitle>
            <div class="subtitle-1 black--text">
              <b>{{ total }}</b> Kelas Diklat
            </div>
          </template>
        </base-table-header>
      </v-card-title>
      <v-divider />
      <v-card-text class="pa-0">
        <base-list-table
          :hideHeader="true"
          :loading="loading"
          :item="data"
          :total="total"
          :usePaging="false"
          @fetch="fetchData"
        >
          <template slot-scope="{ item }">
            <td>
              <v-list-item dense class="px-0">
                <v-list-item-content>
                  <v-row>
                    <v-col class="py-2" cols="12" md="5">
                      <v-list-item class="px-0">
                        <v-list-item-avatar color="primary">
                          <v-icon dark>mdi-teach</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content class="py-0 mt-3">
                          <div class="label--text">Nama Kelas</div>
                          <div class="body-1 black--text">
                            <strong>{{ $getDeepObj(item, 'nama') || '-' }}</strong>
                          </div>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col class="py-0" cols="12" md="3">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0 mt-3">
                          <div class="label--text">Jadwal Pelaksanaan</div>
                          {{
                            $durasi(
                              $getDeepObj(item, 'paud_diklat.paud_periode.tgl_diklat_mulai'),
                              $getDeepObj(item, 'paud_diklat.paud_periode.tgl_diklat_selesai')
                            )
                          }}
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col class="py-0" cols="12" md="2">
                      <v-list-item>
                        <v-list-item-content class="py-0 mt-3">
                          <v-btn color="success" depressed small block @click="toHasil(item)">
                            <v-icon left>mdi-file-document-edit</v-icon>
                            Hasil Kelas
                          </v-btn>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col class="py-0" cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0 mt-3">
                          <v-btn
                            color="success"
                            small
                            :disabled="!$getDeepObj(item, 'lms_url')"
                            @click="onLms($getDeepObj(item, 'lms_url'))"
                          >
                            <v-icon left>mdi-link</v-icon>
                            Menuju LMS
                          </v-btn>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                  </v-row>
                </v-list-item-content>
              </v-list-item>
            </td>
          </template>
        </base-list-table>
      </v-card-text>
      <v-card-actions>
        <base-table-footer :pageTotal="pageTotal" @changePage="onChangePage"></base-table-footer>
      </v-card-actions>
    </v-card>
  </div>
</template>
<script>
import { mapActions } from 'vuex';
import list from '@mixins/list';
export default {
  mixins: [list],
  data() {
    return {
      formulir: {},
    };
  },
  computed: {},
  created() {},
  methods: {
    ...mapActions('diklat', {
      fetch: 'getListKelas',
    }),

    fetchData: function () {
      return new Promise((resolve) => {
        const params = Object.assign({}, this.params, this.$isObject(this.filters) ? { filter: this.filters } : {});
        const attr = Object.assign({}, this.attr);
        this.fetch({ params, attr }).then(({ ptkListKelas }) => {
          this.data = ptkListKelas.data || [];
          this.total = ptkListKelas?.paginatorInfo?.total ?? 0;
          this.pageTotal = ptkListKelas?.paginatorInfo?.lastPage ?? 0;
          resolve(true);
        });
      });
    },

    allow(action, data) {
      let disabled = false;
      switch (action.event) {
        case 'onAktif':
          disabled = !Number(this.$getDeepObj(data, 'akun.is_aktif') || 0);
          break;
        case 'onNonAktif':
          disabled = Number(this.$getDeepObj(data, 'akun.is_aktif') || 0);
          break;
        default:
          disabled = this.$allow(action.akses, data.policies);
          break;
      }
      return disabled;
    },

    onLms(url) {
      window.open(url, '_blank');
    },

    toHasil(item) {
      console.log(item);
      const title = `Kuesioner Diklat Berjenjang GTK Paud`;
      const message = `
      <h2>Evaluasi Penyelenggaraan Diklat Berjenjang Daring Kombinasi</h2>
      <p>
      Anda telah menyelesaikan Diklat Berjenjang tingkat dasar PAUD, mohon meluangkan waktu untuk mengisi
      kuesioner sebagai syarat untuk melihat nilat diklat dan mengunduh sertifikat jika dinyatakan Lulus.
      </p>
      `;
      this.$notifikasi(message, title, {
        noIcon: true,
        width: '550px',
        btnColor: '#37474F',
        btnLabel: 'Isi Kuesioner',
        action: () => {
          this.$router.push({ name: 'kelas-detail', params: { id: item.paud_kelas_id } });
        },
      });
    },
  },
};
</script>
<style scoped>
.bg-kiri {
  background: #ffab91;
  height: 100%;
}
.sc-notif {
  background-color: #c8e6c9;
}
</style>
