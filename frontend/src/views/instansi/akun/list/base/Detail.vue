<template>
  <v-card tile flat class="my-5">
    <v-card-text class="pa-0">
      <v-row no-gutters>
        <v-col cols="2">
          <div class="bg-kiri"></div>
        </v-col>
        <v-col cols="10" class="pa-5">
          <h1 class="headline black--text" v-html="`Detail ${title}`"></h1>
          <v-row class="my-2">
            <v-col cols="12" md="2" sm="12">
              <v-avatar color="primary" size="100">
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
      const biodata = this.$getDeepObj(this.detail, 'akun.data');
      const instansi = this.$getDeepObj(this.detail, 'instansi.data');
      const konfirmasi = {
        biodata: [
          [
            {
              key: 'nama',
              label: 'Nama',
              value: this.$getDeepObj(biodata, 'nama') || '-',
            },
          ],
          [
            {
              key: 'lahir',
              label: 'Tempat, Tanggal Lahir',
              value: [
                this.$getDeepObj(biodata, 'tmp_lahir') || '-',
                this.$localDate(this.$getDeepObj(biodata, 'tgl_lahir') || '-'),
              ].join(', '),
            },
            {
              key: 'kelamin',
              label: 'Jenis Kelamin',
              value:
                this.$getDeepObj(biodata, 'kelamin') === 'L'
                  ? 'Laki - laki'
                  : this.$getDeepObj(biodata, 'kelamin') === 'P'
                  ? 'Perempuan'
                  : '',
            },
          ],
          [
            {
              key: 'no_wa',
              label: 'Nomor Telepon (terhubung WhatsApp)',
              value: this.$getDeepObj(biodata, 'no_hp') || '-',
            },
            {
              key: 'email',
              label: 'Surel (untuk Kontak)',
              value: this.$getDeepObj(biodata, 'email') || '-',
            },
          ],
        ],
        program: [
          [
            {
              key: 'nip',
              label: 'NIP',
              value: this.$getDeepObj(biodata, 'nip') || '-',
            },
            {
              key: 'k_golongan',
              label: 'Golongan',
              value: this.$getDeepObj(biodata, 'm_golongan.data.keterangan'),
            },
          ],
          [
            {
              key: 'instansi_id',
              label: 'Instansi',
              value: this.$getDeepObj(instansi, 'nama'),
            },
          ],
        ],
        operator: [
          [
            {
              key: 'nip',
              label: 'NIP',
              value: this.$getDeepObj(biodata, 'nip') || '-',
            },
            {
              key: 'k_golongan',
              label: 'Golongan',
              value: this.$getDeepObj(biodata, 'm_golongan.data.keterangan'),
            },
          ],
        ],
        pengajar: [],
        kelas: [
          [
            {
              key: 'nik',
              label: 'NIK',
              value: this.$getDeepObj(biodata, 'nik') || '-',
            },
            {
              key: 'alamat',
              label: 'Alamat',
              value: [
                this.$getDeepObj(biodata, 'alamat') || '-',
                [
                  this.$getDeepObj(biodata, 'm_propinsi.data.keterangan'),
                  this.$getDeepObj(biodata, 'm_kota.data.keterangan'),
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
  background: #FFAB91;
  height: 100%;
}
</style>
