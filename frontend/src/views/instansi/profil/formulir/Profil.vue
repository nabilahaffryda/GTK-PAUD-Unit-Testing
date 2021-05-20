<template>
  <div>
    <v-list-item class="px-0">
      <v-list-item-content>
        <div class="subtitle-1 font-weight-bold">Data CV</div>
        <span>
          Lengkapi data persyaratan sesuai kebutuhan sistem, Silakan <b>tekan tombol/icon pensil</b> di sebelah kanan
          untuk melakukan edit.
        </span>
      </v-list-item-content>
      <v-list-item-action>
        <v-btn :disabled="!$allow(`petugas-profil.update`)" depressed @click="$emit('edit')">
          <v-icon>mdi-pencil</v-icon>
        </v-btn>
      </v-list-item-action>
    </v-list-item>
    <v-row class="my-5">
      <v-col cols="12" md="2" sm="12">
        <base-photo-profil
          :photo="$getDeepObj(detail, 'akun.data.foto_url') || $getDeepObj(detail, 'instansi.data.foto_url') || ''"
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
                <h2 class="subtitle-1 black--text"><span v-html="$getDeepObj(sub, 'value') || '-'" /></h2>
              </v-col>
            </v-row>
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
    diklats() {
      return this.$getDeepObj(this.detail, 'pengalaman') || this.$getDeepObj(this.detail, 'diklat') || [];
    },

    profils() {
      const item = Object.assign({}, this.detail);
      const profil = {
        pengajar: {
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
              title: 'Kota/Kab. dan Provinsi',
              value: [
                this.$getDeepObj(item, 'akun.data.k_kota')
                  ? this.masters.m_kota && this.masters.m_kota[this.$getDeepObj(item, 'akun.data.k_kota')]
                  : '-',
                this.$getDeepObj(item, 'akun.data.k_propinsi')
                  ? this.masters.m_propinsi && this.masters.m_propinsi[this.$getDeepObj(item, 'akun.data.k_propinsi')]
                  : '-',
              ].join(' - '),
              grid: { cols: 12, md: 6, sm: 12 },
            },
            {
              title: 'Kodepos',
              value: this.$getDeepObj(item, 'akun.data.kodepos') || '-',
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
        },
        pembimbing: {
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
              title: 'Kota/Kab. dan Provinsi',
              value: [
                this.$getDeepObj(item, 'akun.data.k_kota')
                  ? this.masters.m_kota && this.masters.m_kota[this.$getDeepObj(item, 'akun.data.k_kota')]
                  : '-',
                this.$getDeepObj(item, 'akun.data.k_propinsi')
                  ? this.masters.m_propinsi && this.masters.m_propinsi[this.$getDeepObj(item, 'akun.data.k_propinsi')]
                  : '-',
              ].join(' - '),
              grid: { cols: 12, md: 6, sm: 12 },
            },
            {
              title: 'Kodepos',
              value: this.$getDeepObj(item, 'akun.data.kodepos') || '-',
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
        },
        'admin-kelas': {
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
              title: 'Tempat, Tanggal Lahir',
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
              title: 'Kota/Kab. dan Provinsi',
              value: [
                this.$getDeepObj(item, 'akun.data.k_kota')
                  ? this.masters.m_kota && this.masters.m_kota[this.$getDeepObj(item, 'akun.data.k_kota')]
                  : '-',
                this.$getDeepObj(item, 'akun.data.k_propinsi')
                  ? this.masters.m_propinsi && this.masters.m_propinsi[this.$getDeepObj(item, 'akun.data.k_propinsi')]
                  : '-',
              ].join(' - '),
              grid: { cols: 12, md: 6, sm: 12 },
            },
            {
              title: 'Kodepos',
              value: this.$getDeepObj(item, 'akun.data.kodepos') || '-',
              grid: { cols: 12, md: 6, sm: 12 },
            },
          ],
        },
        lpd: {
          dasar: [
            {
              title: 'Nama Lembaga',
              value: this.$getDeepObj(item, 'instansi.data.nama') || '-',
              grid: { cols: 12, md: 6, sm: 12 },
            },
            {
              title: 'Alamat Lembaga',
              value: this.$getDeepObj(item, 'instansi.data.alamat') || '-',
              grid: { cols: 12, md: 6, sm: 12 },
            },
            {
              title: 'Email',
              value: this.$getDeepObj(item, 'instansi.data.email') || '-',
              grid: { cols: 12, md: 6, sm: 12 },
            },
            {
              title: 'Propinsi',
              value: this.$getDeepObj(item, 'instansi.data.m_propinsi.data.keterangan') || '-',
              grid: { cols: 12, md: 3, sm: 12 },
            },
            {
              title: 'Kabupaten/Kota',
              value: this.$getDeepObj(item, 'instansi.data.m_kota.data.keterangan') || '-',
              grid: { cols: 12, md: 3, sm: 12 },
            },
            {
              title: 'Nomor Telepon',
              value: this.$getDeepObj(item, 'instansi.data.no_telpon') || '-',
              grid: { cols: 12, md: 6, sm: 12 },
            },
            {
              title: 'Kodepos',
              value: this.$getDeepObj(item, 'kodepos') || '-',
              grid: { cols: 12, md: 6, sm: 12 },
            },
          ],
          tambahan: [
            {
              title: 'Penanggung Jawab',
              value:
                (this.$getDeepObj(item, 'nama_penanggung_jawab') || '-') +
                '<br/>' +
                (this.$getDeepObj(item, 'telp_penanggung_jawab') || '-'),
              grid: { cols: 12, md: 12, sm: 12 },
            },
            {
              title: 'Sekertaris',
              value:
                (this.$getDeepObj(item, 'nama_sekretaris') || '-') +
                '<br/>' +
                (this.$getDeepObj(item, 'telp_sekretaris') || '-'),
              grid: { cols: 12, md: 12, sm: 12 },
            },
            {
              title: 'Bendahara',
              value:
                (this.$getDeepObj(item, 'nama_bendahara') || '-') +
                ' <br/>' +
                (this.$getDeepObj(item, 'telp_bendahara') || '-'),
              grid: { cols: 12, md: 12, sm: 12 },
            },
          ],
        },
      };

      return profil[this.jenis];
    },
  },
};
</script>
