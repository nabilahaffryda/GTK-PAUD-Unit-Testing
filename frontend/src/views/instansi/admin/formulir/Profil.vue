<template>
  <div>
    <v-toolbar flat>
      <v-toolbar-title class="subtitle-1">
        <div class="font-weight-bold">Data CV</div>
        <span>
          Lengkapi data persyaratan sesuai kebutuhan sistem, Silakan <b>tekan tombol/icon pensil</b> di sebelah kanan
          untuk melakukan edit.
        </span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn v-if="$allow(`${jenis}-profil.update`)" depressed @click="$emit('edit')">
        <v-icon>mdi-pencil</v-icon>
      </v-btn>
    </v-toolbar>
    <v-row class="my-5">
      <v-col cols="12" md="2" sm="12">
        <base-photo-profil
          :photo="this.$getDeepObj(detail, 'akun.foto') || ''"
          photodef="default_foto_gp.png"
          :useBase64="true"
        />
      </v-col>
      <v-col cols="12" md="10" sm="12">
        <v-row>
          <v-col v-for="(profil, p) in profils" :key="p" cols="12" :md="p === 'dasar' ? 12 : 6" sm="12">
            <div v-if="p !== 'dasar'" class="text-h6 my-3 font-weight-bold"> Data {{ $titleCase(p) }} </div>
            <v-row>
              <v-col v-for="(sub, s) in profil" :key="s" v-bind="sub.grid">
                <div class="caption grey--text">{{ $getDeepObj(sub, 'title') || '-' }}</div>
                <h2 class="subtitle-1 black--text">{{ $getDeepObj(sub, 'value') || '-' }}</h2>
              </v-col>
            </v-row>
          </v-col>
          <!-- Sheet Diklat -->
          <v-col cols="12" md="6" sm="12">
            <div class="text-h6 my-3 font-weight-bold"> Data Diklat </div>
            <div class="grey--text">Pengalaman Melatih 2 Tahun Terakhir</div>
            <v-list three-line>
              <template v-for="(item, index) in $getDeepObj(detail, 'pengalaman')">
                <v-list-item :key="index">
                  <v-list-item-avatar tile>
                    <v-avatar tile color="secondary">
                      <span class="white--text">{{ index + 1 }}</span>
                    </v-avatar>
                  </v-list-item-avatar>

                  <v-list-item-content>
                    <v-list-item-title v-html="item.nama"></v-list-item-title>
                    <v-list-item-subtitle v-html="item.tahun"></v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
              </template>
            </v-list>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </div>
</template>
<script>
import BasePhotoProfil from '@components/base/BasePhotoProfil';
export default {
  props: {
    detail: {
      type: Object,
      default: () => {},
    },
    masters: {
      type: Object,
      default: () => {},
    },
    jenis: {
      type: String,
      default: 'pengajar',
    },
  },
  components: { BasePhotoProfil },
  computed: {
    profils() {
      const item = Object.assign({}, this.detail);
      return {
        dasar: [
          {
            title: 'Nama Lengkap',
            value: this.$getDeepObj(item, 'akun.data.nama') || '-',
            grid: { cols: 12, md: 6, sm: 12 },
          },
          {
            title: 'Jenis Kelamin',
            value: this.$fGender(this.$getDeepObj(item, 'akun.data.kelamin')) || '-',
            grid: { cols: 12, md: 6, sm: 12 },
          },
          {
            title: 'NIP / NUPTK',
            value: this.$getDeepObj(item, 'akun.data.nip') || '-',
            grid: { cols: 12, md: 6, sm: 12 },
          },
          {
            title: 'Pendidikan Terakhir',
            value:
              (this.$getDeepObj(this, `masters.m_kualifikasi.${this.$getDeepObj(item, 'k_kualifikasi')}`) || '-') +
              ' - ' +
              (this.$getDeepObj(item, 'lulusan') || '-'),
            grid: { cols: 12, md: 3, sm: 12 },
          },
          {
            title: 'Prodi',
            value: this.$getDeepObj(item, 'prodi') || '-',
            grid: { cols: 12, md: 3, sm: 12 },
          },
          {
            title: 'NIK',
            value: this.$getDeepObj(item, 'akun.data.nik') || '-',
            grid: { cols: 12, md: 6, sm: 12 },
          },
          {
            title: 'Email',
            value: this.$getDeepObj(item, 'akun.data.email') || '-',
            grid: { cols: 12, md: 6, sm: 12 },
          },
          {
            title: 'Tempat Tanggal Lahir',
            value:
              (this.$getDeepObj(item, 'akun.data.tmp_lahir') || '-') +
              ', ' +
              (this.$localDate(this.$getDeepObj(item, 'akun.data.tgl_lahir')) || '-'),
            grid: { cols: 12, md: 6, sm: 12 },
          },
          {
            title: 'Nomor HP Aktif',
            value: this.$getDeepObj(item, 'akun.data.no_hp') || '-',
            grid: { cols: 12, md: 6, sm: 12 },
          },
          {
            title: 'Alamat Sesuai KTP',
            value: this.$getDeepObj(item, 'akun.data.alamat') || '-',
            grid: { cols: 12, md: 6, sm: 12 },
          },
          {
            title: 'Keikutsertaan PCP',
            value: this.$getDeepObj(item, 'm_pcp_paud.data.keterangan') || '-',
            grid: { cols: 12, md: 6, sm: 12 },
          },
        ],
        instansi: [
          {
            title: 'Instansi',
            value: this.$getDeepObj(item, 'instansi_nama') || '-',
            grid: { cols: 12, md: 12, sm: 12 },
          },
          {
            title: 'Jabatan',
            value: this.$getDeepObj(item, 'instansi_jabatan') || '-',
            grid: { cols: 12, md: 12, sm: 12 },
          },
          {
            title: 'Alamat Instansi',
            value: this.$getDeepObj(item, 'instansi_alamat') || '-',
            grid: { cols: 12, md: 12, sm: 12 },
          },
        ],
      };
    },
  },
};
</script>
