<template>
  <div>
    <v-card tile flat class="my-5">
      <v-card-text class="pa-0">
        <v-row class="bg-kiri" no-gutters>
          <v-col cols="2" class="mt-5"> </v-col>
          <v-col cols="10" class="pa-5" style="background-color: white">
            <h1 class="headline info--text">
              <v-row dense>
                <v-col cols="12" md="8" sm="8"> Selamat Datang, <strong>Nama Pengajar</strong> </v-col>
                <v-col cols="12" md="4" sm="4" class="text-right right-aligned">
                  <v-chip class="caption" color="error" dark>
                    {{ $titleCase(title) }}
                  </v-chip>
                </v-col>
              </v-row>
            </h1>
            <div>
              <h3 class="subtitle-1 black--text">
                Silakan lengkapi persyaratan dibawah ini
              </h3>
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
              &nbsp;Status syarat sudah dilengkapi / dissi<br />
              <i
                class="v-icon notranslate mdi mdi-alert-circle theme--light grey--text grey-lighten-2"
                style="font-size: 1rem"
              />
              &nbsp; Status syarat wajib dilengkapi / diisi namun belum dilengkapi/diisi
            </div>

            <!--ajuan button-->
            <template>
              <div>
                <v-btn depressed color="success" class="mt-2" :disabled="!isLengkap">
                  Kirim Berkas
                </v-btn>
              </div>
            </template>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <div class="my-5">
      <h3 class="my-5">PERSYARATAN REGISTRASI</h3>
      <daftar :contents="contents" :berkases="berkases[jenis]" :detail="detail" @upload="upload" @edit="edit" />
    </div>
    <base-modal-full ref="modal" colorBtn="primary" generalError :title="formulir.title">
      <component
        ref="formulir"
        :is="formulir.form"
        :masters="masters"
        :title="formulir.title"
        :initValue="formulir.init"
        :type="formulir.type"
        :max="formulir.max"
        :format="formulir.format"
        :rules="formulir.rules"
        :detail="formulir.detail"
      />
    </base-modal-full>
  </div>
</template>
<script>
import mixin from './mixin';
import Daftar from './Daftar';
import FormUnggah from '@components/form/Unggah';
import FormProfil from '../formulir/FormProfil';
export default {
  mixins: [mixin],
  components: { Daftar, FormUnggah, FormProfil },
  props: {
    jenis: {
      type: String,
      default: '',
    },
    title: {
      type: String,
      default: '',
    },
    desc: {
      type: String,
      default: '',
    },
  },
  created() {
    this.getMasters({
      name: ['m_propinsi', 'm_kota', 'kualifikasi'].join(';'),
      filter: {
        0: {
          k_propinsi: {
            op: '<',
            val: 90,
          },
        },
      },
    });
    this.fetchProfil();
  },
};
</script>
<style scoped>
.bg-kiri {
  background: #f0e987;
  height: 100%;
}
.sc-notif {
  background-color: #c8e6c9;
}
</style>
