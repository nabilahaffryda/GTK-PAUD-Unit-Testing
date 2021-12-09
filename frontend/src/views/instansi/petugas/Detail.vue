<template>
  <v-card tile flat data-app>
    <v-card-text>
      <template>
        <v-row class="my-5">
          <v-col cols="12" md="2" sm="12">
            <base-photo-profil
              :photo="$getDeepObj(detail, 'akun.data.foto_url') || $getDeepObj(detail, 'instansi.data.foto_url') || ''"
              :photodef="jenis === 'lpd' ? 'default_foto_lpd.png' : 'default_foto_gp.png'"
              :useBase64="true"
            />
          </v-col>
          <v-col cols="12" md="10" sm="12">
            <v-row>
              <v-col v-for="(profil, p) in profils" :key="p" cols="12" md="12" sm="12">
                <div v-if="p !== 'dasar'" class="text-h6 my-3 font-weight-bold"> Data {{ $titleCase(p) }} </div>
                <v-row>
                  <v-col v-for="(sub, s) in profil" :key="s" v-bind="sub.grid">
                    <div class="caption">{{ $getDeepObj(sub, 'title') || '-' }}</div>
                    <h2 class="subtitle-1 black--text"><span v-html="$getDeepObj(sub, 'value') || '-'" /></h2>
                  </v-col>
                </v-row>
              </v-col>
              <!-- Sheet Diklat -->
              <v-col cols="12" md="12" sm="12">
                <v-row>
                  <v-col cols="12" md="6">
                    <h2 class="subtitle-1 font-weight-bold">Data Diklat</h2>
                    <collection
                      v-for="(item, b) in diklats.filter((value) => value.k_diklat_paud !== 4)"
                      :key="b"
                      :nomor="b + 1"
                      :diklat="item"
                      @detil="onDetilDiklat"
                    />
                  </v-col>
                  <v-col cols="12" md="6">
                    <h2 class="subtitle-1 font-weight-bold">Data Diklat Lainnya</h2>
                    <collection
                      v-for="(item, b) in diklats.filter((value) => value.k_diklat_paud === 4)"
                      :key="b"
                      :nomor="b + 1"
                      :diklat="item"
                      @detil="onDetilDiklat"
                    />
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </template>
    </v-card-text>
    <popup-preview-detail ref="popup" :url="$getDeepObj(preview, 'url')" :title="$getDeepObj(preview, 'title')" />
  </v-card>
</template>

<script>
import BasePhotoProfil from '@components/base/BasePhotoProfil';
import Collection from '@views/instansi/profil/formulir/Collection';
import PopupPreviewDetail from '@components/popup/PreviewDetil';
export default {
  name: 'Detail.vue',
  components: { PopupPreviewDetail, Collection, BasePhotoProfil },
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
    masters: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      preview: {},
    };
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
        bimtek: [],
        admin: [],
      };
      return [...konfirmasi['biodata'], ...konfirmasi[this.jenis]];
    },

    diklats() {
      const diklatPetugas = this.$getDeepObj(this.detail, 'paud_petugas_diklats.data');
      return diklatPetugas || [];
    },

    profils() {
      const item = this.detail;
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
                this.$getDeepObj(item, 'akun.data.m_kota.data.keterangan') || '-',
                this.$getDeepObj(item, 'akun.data.m_propinsi.data.keterangan') || '-',
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
              grid: { cols: 12, md: 6, sm: 12 },
            },
            {
              title: 'Jabatan',
              value: this.$getDeepObj(item, 'instansi_jabatan') || '-',
              grid: { cols: 12, md: 6, sm: 12 },
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
                this.$getDeepObj(item, 'akun.data.m_kota.data.keterangan') || '-',
                this.$getDeepObj(item, 'akun.data.m_propinsi.data.keterangan') || '-',
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
            {
              title: 'Kota/Kab. dan Provinsi',
              value: [
                this.$getDeepObj(item, 'akun.data.m_kota.data.keterangan') || '-',
                this.$getDeepObj(item, 'akun.data.m_propinsi.data.keterangan') || '-',
              ].join(' - '),
              grid: { cols: 12, md: 6, sm: 12 },
            },
            {
              title: 'Kodepos',
              value: this.$getDeepObj(item, 'instansi_kodepos') || '-',
              grid: { cols: 12, md: 6, sm: 12 },
            },
          ],
        },
      };
      return profil[this.jenis];
    },
  },
  methods: {
    onDetilDiklat(data) {
      this.$set(this, 'preview', {});
      this.preview.url = this.$getDeepObj(data, 'url');
      this.preview.title = this.$getDeepObj(data, 'nama');
      this.$nextTick(() => {
        this.$refs.popup.open();
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
</style>
