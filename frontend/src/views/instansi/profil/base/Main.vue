<template>
  <div>
    <v-card tile flat class="my-5">
      <v-card-text class="pa-0">
        <v-row class="bg-kiri" no-gutters>
          <v-col cols="2" class="mt-5"> </v-col>
          <v-col cols="10" class="pa-5" style="background-color: white">
            <h1 class="headline primary--text">
              <v-row dense>
                <v-col cols="12" md="8" sm="8">
                  Selamat Datang,
                  <strong>
                    {{ $getDeepObj(detail, 'akun.data.nama') || $getDeepObj(detail, 'instansi.data.nama') || '' }}
                  </strong>
                </v-col>
                <v-col cols="12" md="4" sm="4" class="text-right right-aligned">
                  <v-chip color="error" dark>
                    {{
                      $titleCase(
                        ['pengajar', 'pembimbing'].includes(jenis)
                          ? masters &&
                              masters['petugas_paud'] &&
                              masters['petugas_paud'][$getDeepObj(detail, 'k_petugas_paud')]
                          : jenis === 'lpd'
                          ? 'Lembaga'
                          : jenis
                      )
                    }}
                  </v-chip>
                </v-col>
              </v-row>
            </h1>
            <div>
              <h3 class="subtitle-1 black--text"> Silakan lengkapi persyaratan dibawah ini </h3>
            </div>
            <v-simple-table dense>
              <template v-slot:default>
                <thead>
                  <tr style="background-color: #eeeeee">
                    <th class="text-left body-2 font-weight-bold px-0">
                      <v-row no-gutters class="py-1">
                        <v-col cols="12" md="7">Persyaratan</v-col>
                        <v-col class="d-none d-sm-flex" cols="12" md="5">Status</v-col>
                      </v-row>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, i) in status || []" :key="i">
                    <td class="px-0">
                      <v-row no-gutters class="py-2">
                        <v-col cols="10" md="7"> <span v-html="item.label" />&nbsp; </v-col>
                        <v-col cols="2" md="5">
                          <v-icon class="mr-5" :color="statusLabel[item.value].color">
                            {{ statusLabel[item.value].icon }}
                          </v-icon>
                        </v-col>
                      </v-row>
                    </td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
            <div class="mt-5 grey lighten-4 black--text pa-2" style="font-size: 0.8rem; border-radius: 5px">
              <b>Catatan :</b> <br />
              <i class="v-icon notranslate mdi mdi-check-circle theme--light success--text" style="font-size: 1rem" />
              &nbsp;Status syarat sudah dilengkapi / diisi<br />
              <i
                class="v-icon notranslate mdi mdi-alert-circle theme--light grey--text grey-lighten-2"
                style="font-size: 1rem"
              />
              &nbsp; Status syarat wajib dilengkapi / diisi namun belum dilengkapi/diisi
            </div>

            <!--ajuan button-->
            <template v-if="$allow(`${jenis === 'lpd' ? jenis : 'petugas'}-profil-ajuan.create`)">
              <div>
                <v-btn v-if="[2, 3, 6].includes(Number(kVerval))" depressed color="success" class="mt-2" outlined>
                  {{ kVerval === 6 ? 'Berkas Telah Disetujui' : 'Berkas Terkirim' }}
                </v-btn>
                <v-btn
                  v-else-if="Number(kVerval) === 5"
                  depressed
                  color="success"
                  class="mt-2"
                  :disabled="!isLengkap"
                  @click="onAjuan"
                >
                  Kirim Ulang Berkas
                </v-btn>
                <v-btn v-else-if="Number(kVerval) === 4" depressed color="error" class="mt-2" outlined>
                  Berkas Ditolak
                </v-btn>
                <v-btn v-else depressed color="success" class="mt-2" :disabled="!isLengkap" @click="onAjuan">
                  Kirim Berkas
                </v-btn>
              </div>
              <template v-if="[1, 2, 3].includes(Number(kVerval))">
                <!-- is_kirim_ci -->
                <v-alert dense text type="info" class="mt-4" v-if="[2, 3].includes(Number(kVerval))">
                  <template v-if="Number(kVerval) === 2">
                    Anda sudah mengirimkan berkas Anda untuk diproses, Informasi lebih lanjut tentang pendaftaran akun
                    kami kirimkan ke alamat surel Anda, Apabila Anda ingin membatalkan pengiriman berkas dan mengubah
                    isian berkas Anda.<br />
                    <a style="font-weight: bold; text-decoration: underline" @click="onBatalAjuan">klik disini</a>
                  </template>
                  <template v-else>
                    Berkas Anda sudah dalam proses verifikasi, pantau terus hasil verifikasi di halaman ini.
                  </template>
                </v-alert>
                <v-alert v-else dense text type="info" class="mt-4">
                  Pastikan Anda melengkapi semua tugas, agar dapat melakukan proses pengiriman berkas
                </v-alert>
              </template>
              <template v-else>
                <v-alert
                  icon="mdi-information"
                  colored-border
                  border="left"
                  class="pa-2 my-2 bg-notif"
                  color="warning"
                  v-if="[4, 5].includes(kVerval)"
                >
                  <template v-if="kVerval === 5">
                    Mohon melakukan perbaikan sesuai informasi tim Verifikasi sebagai berikut:<br />
                    <p class="pa-2 my-2 mr-5 white darken-2 subtitle-2">
                      <strong v-html="catatan || '-'"></strong>
                    </p>
                  </template>
                  <template v-else>
                    <br />
                    Mohon maaf pendaftaran Anda ditolak dengan alasan sebagai berikut:<br />
                    <p class="pa-2 my-2 mr-5 white darken-2 subtitle-2">
                      <strong v-html="catatan || '-'"></strong>
                    </p>
                  </template>
                </v-alert>
              </template>
            </template>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <div class="my-5">
      <h3 class="my-5">PERSYARATAN REGISTRASI</h3>
      <daftar
        v-if="masters && $isObject(masters)"
        :contents="contents"
        :berkases="berkases[jenis]"
        :sertifikats="berkases.sertifikat"
        :diklats="diklat"
        :detail="detail"
        :masters="masters"
        :jenis="jenis"
        :isAjuan="isAjuan"
        @upload="upload"
        @edit="edit"
        @delete="deleteBerkas"
      />
    </div>
    <base-modal-full ref="modal" colorBtn="primary" generalError :title="formulir.title" @save="onSave">
      <component
        ref="formulir"
        :is="formulir.form"
        :masters="masters"
        :title="title"
        :initValue="formulir.init"
        :items="formulir.items"
        :type="formulir.type"
        :max="formulir.max"
        :format="formulir.format"
        :rules="formulir.rules"
        :detail="detail"
        :jenis="jenis"
        uimodel="card"
        @reset="onResetForm"
      />
    </base-modal-full>
  </div>
</template>
<script>
import mixin from './mixin';
import Daftar from './Daftar';
import FormUnggah from '@components/form/Unggah';
import FormProfil from '../formulir/FormProfil';
import FormCollection from '../formulir/FormCollection';
export default {
  mixins: [mixin],
  components: { FormCollection, Daftar, FormUnggah, FormProfil },
  computed: {
    jenis() {
      return this.$route.meta.tipe;
    },
    title() {
      return this.$route.meta.title;
    },
  },
  created() {
    this.getMasters({
      name: [
        'm_propinsi',
        'm_kota',
        'm_kualifikasi',
        'm_pcp_paud',
        'm_diklat_paud',
        'm_tingkat_diklat_paud',
        'petugas_paud',
        'verval_paud',
      ].join(';'),
      filter: {
        0: {
          k_propinsi: {
            op: '<',
            val: 90,
          },
        },
      },
    }).then(() => {
      this.fetchProfil();
    });
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
