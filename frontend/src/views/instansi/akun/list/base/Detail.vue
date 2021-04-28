<template>
  <v-card tile flat class="my-5">
    <v-card-text class="pa-0">
      <v-row no-gutters>
        <v-col cols="2">
          <div class="bg-kiri"></div>
        </v-col>
        <v-col cols="10" class="pa-5">
          <h1 class="headline secondary--text" v-html="`Detail ${title}`"></h1>
          <v-row class="my-2">
            <v-col cols="12" md="2" sm="12">
              <v-avatar color="secondary" size="100">
                <v-icon dark size="80">mdi-account-circle</v-icon>
              </v-avatar>
            </v-col>
            <v-col cols="12" md="10" sm="12" class="px-0">
              <base-list-info class="px-0" :info="info"></base-list-info>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
import BaseListInfo from '@components/base/BaseListInfo';
export default {
  name: 'Detail.vue',
  components: { BaseListInfo },
  props: {
    title: {
      type: String,
      default: 'Akun Admin Program',
    },
    jenis: {
      type: String,
      required: true,
    },
    detail: {
      type: Object,
      required: true,
    },
  },
  computed: {
    info() {
      const konfirmasi = {
        biodata: [
          [
            {
              key: 'nama',
              label: 'Nama',
              value: this.$getDeepObj(this.detail, 'nama') || '-',
            },
          ],
          [
            {
              key: 'lahir',
              label: 'Tempat, Tanggal Lahir',
              value: [
                this.$getDeepObj(this.detail, 'tmp_lahir') || '-',
                this.$localDate(this.$getDeepObj(this.detail, 'tgl_lahir') || '-'),
              ].join(', '),
            },
            {
              key: 'kelamin',
              label: 'Jenis Kelamin',
              value:
                this.$getDeepObj(this.detail, 'kelamin') === 'L'
                  ? 'Laki - laki'
                  : this.$getDeepObj(this.detail, 'kelamin') === 'P'
                  ? 'Perempuan'
                  : '',
            },
          ],
          [
            {
              key: 'no_wa',
              label: 'Nomor Telepon (terhubung WhatsApp)',
              value: this.$getDeepObj(this.detail, 'no_hp') || '-',
            },
            {
              key: 'email',
              label: 'Surel (untuk Kontak)',
              value: this.$getDeepObj(this.detail, 'email') || '-',
            },
          ],
        ],
        program: [
          [
            {
              key: 'nip',
              label: 'NIP',
              value: this.$getDeepObj(this.detail, 'nip') || '-',
            },
            {
              key: 'k_golongan',
              label: 'Golongan',
              value: this.$getDeepObj(this.detail, 'm_golongan.data.keterangan'),
            },
          ],
          [
            {
              key: 'instansi_id',
              label: 'Instansi',
              value: this.$getDeepObj(this.detail, 'instansi_asal'),
            },
          ],
        ],
        operator: [
          [
            {
              key: 'nip',
              label: 'NIP',
              value: this.$getDeepObj(this.detail, 'nip') || '-',
            },
            {
              key: 'k_golongan',
              label: 'Golongan',
              value: this.$getDeepObj(this.detail, 'm_golongan.data.keterangan'),
            },
          ],
        ],
        pengajar: [],
        kelas: [
          [
            {
              key: 'nik',
              label: 'NIK',
              value: this.$getDeepObj(this.detail, 'nik') || '-',
            },
            {
              key: 'alamat',
              label: 'Alamat',
              value: [
                this.$getDeepObj(this.detail, 'alamat') || '-',
                [
                  this.$getDeepObj(this.detail, 'm_propinsi.data.keterangan'),
                  this.$getDeepObj(this.detail, 'm_kota.data.keterangan'),
                ].join(' - '),
              ].join('<br/>'),
            },
          ],
        ],
      };
      return [...konfirmasi['biodata'], ...konfirmasi[this.jenis]];
    },
  },
};
</script>

<style scoped>
.bg-kiri {
  background: #f0e987;
  height: 100%;
}
</style>
