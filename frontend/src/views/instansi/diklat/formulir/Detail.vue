<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-container class="black--text">
        <div class="body-1 font-weight-medium">Info dan Detil Kelas</div>
        <v-row dense no-gutters class="my-5">
          <v-col cols="12" md="1" sm="2" class="pa-0">
            <v-avatar color="primary" size="60">
              <v-icon dark>mdi-teach</v-icon>
            </v-avatar>
          </v-col>
          <v-col cols="12" md="11" sm="10" class="px-0">
            <div>
              <div class="label--text">Nama Kelas</div>
              <div class="body-1 font-weight-medium">{{ $getDeepObj(kelas, 'nama') || '-' }}</div>
            </div>
            <div class="my-5">
              <div class="label--text">Deskripsi Kelas</div>
              <div class="body-1">{{ $getDeepObj(kelas, 'deskripsi') || '-' }}</div>
            </div>
            <v-row class="my-5">
              <v-col>
                <div class="label--text">Lokasi</div>
                <div class="body-1">
                  {{
                    [
                      $getDeepObj(kelas, `m_kelurahan.data.keterangan`) || '-',
                      $getDeepObj(kelas, `m_kecamatan.data.keterangan`) || '-',
                      $getDeepObj(detail, `m_kota.data.keterangan`) || '',
                      $getDeepObj(detail, `m_propinsi.data.keterangan`) || '',
                    ].join(', ')
                  }}
                </div>
              </v-col>
              <v-col>
                <div class="label--text">Jumlah Pengajar</div>
                <div class="body-1">
                  <v-chip class="ma-2" color="green" text-color="white">
                    {{ $getDeepObj(kelas, 'jml_pengajar') || '-' }}
                  </v-chip>
                </div>
              </v-col>
            </v-row>
            <v-row class="my-5">
              <v-col>
                <div class="label--text">Tanggal Mulai Kelas</div>
                <div class="body-1">
                  <template v-if="jenis === 'daring'">
                    {{ $localDate($getDeepObj(detail, 'paud_periode.data.tgl_diklat_mulai')) || '-' }}
                  </template>
                  <template v-else>
                    {{ $localDate($getDeepObj(detail, 'tgl_mulai') || '') }}
                  </template>
                </div>
              </v-col>
              <v-col>
                <div class="label--text">Tanggal Selesai Kelas</div>
                <div class="body-1">
                  <template v-if="jenis === 'daring'">
                    {{ $localDate($getDeepObj(detail, 'paud_periode.data.tgl_diklat_selesai')) || '-' }}
                  </template>
                  <template v-else>
                    {{ $localDate($getDeepObj(detail, 'tgl_selesai') || '') }}
                  </template>
                </div>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="12" sm="12">
                <div>
                  <berkases
                    :berkas="berkas"
                    :valid="$getDeepObj(berkas, 'url')"
                    :use-icon="false"
                    :value="berkas"
                    @upload="onUpload"
                    @detil="onPreview"
                  />
                </div>
                <v-btn v-if="$allow('lpd-kelas.upload-jadwal')" color="secondary" small depressed @click="onUpload"
                  >Unggah Jadwal</v-btn
                >
              </v-col>
            </v-row>
            <div class="my-5" v-if="Number(kelas.k_verval_paud) === 6">
              <v-btn
                color="success"
                depressed
                :disabled="!$getDeepObj(kelas, 'lms_url')"
                small
                @click="onLms($getDeepObj(kelas, 'lms_url'))"
                ><v-icon left>mdi-link</v-icon> Menuju LMS</v-btn
              >
            </div>
          </v-col>
          <v-col cols="12" md="2" sm="12">
            <v-chip v-if="false" color="success">TERSINKRON</v-chip>
          </v-col>
        </v-row>

        <v-tabs v-model="tab" fixed-tabs>
          <v-tab v-for="item in tabItems" :key="item.value">
            {{ item.text }}
          </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab" class="my-3">
          <v-tab-item v-for="item in tabItems" :key="item.value">
            <v-toolbar flat>
              <v-toolbar-title class="body-2">Daftar {{ item.text }}</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-text-field
                v-model="search"
                dense
                placeholder="Pencarian Data"
                append-icon="mdi-magnify"
                @keyup.enter="onReload"
              ></v-text-field>
              <v-btn v-if="false" class="mt-n3" icon><v-icon>mdi-download</v-icon></v-btn>
              <v-btn
                class="mt-n4 ml-2"
                small
                color="secondary"
                depressed
                v-if="isPeserta ? $allow('lpd-kelas-peserta.create') : $allow('lpd-kelas-petugas.create')"
                @click="onAddPetugas"
              >
                <v-icon left>mdi-plus</v-icon>Tambah
              </v-btn>
            </v-toolbar>
            <v-alert dense text type="info" v-if="tab > 1">
              <template v-if="tab === 2">
                Penambahan <b>{{ tabItems[tab]['text'] }}</b> pada kelas sebanyak
                {{ $getDeepObj(kelas, `${tipediklat}.data.paud_instansi.data.jml_pembimbing`) || 0 }}
              </template>
              <template v-else>
                Penambahan <b>{{ tabItems[tab]['text'] }}</b> pada kelas sebanyak
                <b>
                  {{
                    tab === 3
                      ? `${
                          100 -
                          Number(
                            $getDeepObj(kelas, `${tipediklat}.data.paud_instansi.data.ratio_pengajar_tambahan`) || 0
                          )
                        }%`
                      : `${Number(
                          $getDeepObj(kelas, `${tipediklat}.data.paud_instansi.data.ratio_pengajar_tambahan`) || 0
                        )}%`
                  }}
                </b>
                dari total Pengajar
              </template>
            </v-alert>
            <div class="my-4">
              <v-data-table
                v-model="peserta"
                :headers="headers"
                :items-per-page="10"
                :items="filteredItems"
                :single-select="false"
                :no-data-text="`Daftar ${item.text} belum ditemukan`"
                hide-default-footer
              >
                <template v-slot:[`item.aksi`]="{ item }">
                  <template v-if="Number((item && item.k_konfirmasi_paud) || 1) !== 3">
                    <v-btn
                      v-if="isPeserta ? $allow('lpd-kelas-peserta.delete') : $allow('lpd-kelas-petugas.delete')"
                      icon
                      @click="onDelete(item)"
                    >
                      <v-icon small>mdi-trash-can</v-icon>
                    </v-btn>
                  </template>
                </template>
                <template v-slot:footer>
                  <div class="text-right">
                    <v-spacer></v-spacer>
                    <span class="grey--text">{{ `${meta.from || 0} - ${meta.to || 0} of ${meta.total || 0}` }}</span>
                    <span class="mx-2"></span>
                    <v-btn color="grey" :disabled="page === 1" icon small @click="onChangePage('prev')">
                      <v-icon>mdi-chevron-left</v-icon>
                    </v-btn>
                    <v-btn
                      color="grey"
                      :disabled="Number(page) === Number(meta.last_page || 0)"
                      icon
                      small
                      @click="onChangePage('next')"
                    >
                      <v-icon>mdi-chevron-right</v-icon>
                    </v-btn>
                  </div>
                </template>
              </v-data-table>
            </div>
          </v-tab-item>
        </v-tabs-items>
      </v-container>
    </v-card-text>
    <component
      :is="+tab === 0 ? 'PopupPeserta' : 'BaseListPopup'"
      ref="popup"
      :api="`diklatKelas/getListKandidat`"
      :id="tab === 0 ? 'ptk_id' : 'akun_id'"
      :title="`Pilih ${tab === 0 ? 'Peserta' : 'Petugas'} Diklat`"
      multiselect
    />
    <base-modal-full ref="modal" colorBtn="primary" generalError :title="formulir.title" @save="upload">
      <component
        ref="formulir"
        :is="'FormUnggah'"
        :title="formulir.title"
        :type="formulir.type"
        :max="formulir.max"
        :format="formulir.format"
        :rules="formulir.rules"
      />
    </base-modal-full>
    <popup-preview-detail ref="filepopup" :url="$getDeepObj(preview, 'url')" :title="$getDeepObj(preview, 'title')" />
  </v-card>
</template>
<script>
import { mapActions } from 'vuex';
import BaseListPopup from '@components/base/BaseListPopup';
import Berkases from '../../profil/formulir/Berkas';
import FormUnggah from '@components/form/Unggah';
import PopupPreviewDetail from '@components/popup/PreviewDetil';
import PopupPeserta from './PopupPeserta';
export default {
  components: { BaseListPopup, Berkases, FormUnggah, PopupPreviewDetail, PopupPeserta },
  props: {
    detail: {
      type: Object,
      default: () => {},
    },
    jenis: {
      type: String,
      default: 'daring',
    },
  },

  data() {
    return {
      kelas: {},
      pesertas: [],
      peserta: null,
      petugas: [],
      tab: null,
      tabItems: [
        { value: 'peserta', kPetugas: 0, text: 'Peserta' },
        { value: 'admin', kPetugas: 4, text: 'Admin Kelas' },
        { value: 'pembimbing-praktik', kPetugas: 3, text: 'PPTM' },
        { value: 'pengajar', kPetugas: 1, text: 'PPM' },
        { value: 'pengajar-tambahan', kPetugas: 2, text: 'PPM Tambahan' },
      ],
      search: '',
      meta: {},
      page: 1,
      formulir: {},
      preview: {},
    };
  },
  computed: {
    headers() {
      let temp = [
        {
          text: 'Nama Lengkap',
          align: 'start',
          sortable: false,
          value: 'nama',
        },
        { text: 'Surel', value: 'email', sortable: false },
      ];

      // Tambahkan info provinsi dan kota pada tab pembimbing praktik
      if (this.tab >= 2) {
        temp.push({ text: 'Provisi', value: 'propinsi', sortable: false });
        temp.push({ text: 'Kota/Kabupaten', value: 'kota', sortable: false });
      }

      if (this.tab !== 1) {
        temp.push({ text: 'Status', value: 'status', sortable: false });
      }

      temp.push({ text: '', value: 'aksi', sortable: false });

      return temp;
    },

    items() {
      return (this.pesertas || []).map((item) => {
        return {
          nama:
            this.$getDeepObj(item, 'ptk.data.nama') ||
            this.$getDeepObj(item, 'akun.data.nama') ||
            this.$getDeepObj(item, 'paud_peserta_nonptk.data.nama') ||
            '-',
          email:
            this.$getDeepObj(item, 'ptk.data.email') ||
            this.$getDeepObj(item, 'akun.data.email') ||
            this.$getDeepObj(item, 'paud_peserta_nonptk.data.email') ||
            '-',
          status: this.$getDeepObj(item, 'm_konfirmasi_paud.data.keterangan') || '-',
          k_konfirmasi_paud: (item && item.k_konfirmasi_paud) || 1,
          paud_kelas_petugas_id:
            this.$getDeepObj(item, 'paud_kelas_petugas_id') || this.$getDeepObj(item, 'paud_kelas_petugas_luring_id'),
          paud_kelas_peserta_id:
            this.$getDeepObj(item, 'paud_kelas_peserta_id') || this.$getDeepObj(item, 'paud_kelas_peserta_luring_id'),
          ptk_id: this.$getDeepObj(item, 'ptk_id') || '',
          propinsi: this.$getDeepObj(item, 'akun.data.m_propinsi.data.keterangan') || '-',
          kota: this.$getDeepObj(item, 'akun.data.m_kota.data.keterangan') || '-',
        };
      });
    },

    filteredItems() {
      // Disable inline filter
      // return this.items.filter((s) => {
      //   return s.nama.toLowerCase().includes(this.search.toLowerCase());
      // });
      return this.items || [];
    },

    isPeserta() {
      return this.tab === 0;
    },

    tipediklat() {
      return this.jenis === 'daring' ? 'paud_diklat' : 'paud_diklat_luring';
    },

    berkas() {
      return {
        title: 'Jadwal Diklat Dasar',
        pesan: `<span class='grey--text'><i>* Silakan unduh template Jadwal dasar <a class="blue--text" href="https://files1.simpkb.id/berkas/paud/Template_Jadwal Diklat_Dasar_Rev.docx" target="_blank">UNDUH DISINI</a> </i> </span>`,
        url: this.$getDeepObj(this, 'kelas.url_jadwal'),
      };
    },
  },
  methods: {
    ...mapActions('diklatKelas', ['getListKelas', 'action']),

    reset() {
      this.tab = 0;
      this.kelas = {};
      this.pesertas = [];
      this.peserta = [];
      this.petugas = [];
      this.search = '';
    },

    fetch(tipe, k_petugas = null) {
      if (!Object.keys(this.kelas).length) return;
      let params = {
        k_petugas_paud: k_petugas,
        page: this.page,
      };

      if (this.search) this.$set(params, 'keyword', this.search);

      this.getListKelas({
        diklat_id: this.$getDeepObj(this.detail, `id`),
        id: this.$getDeepObj(this.kelas, 'id'),
        tipe: tipe,
        params: params,
      }).then(({ data, meta }) => {
        this.meta = meta;
        this.pesertas = data || [];
      });
    },

    onDelete(item) {
      const url = `${this.isPeserta ? 'peserta' : 'petugas'}/${
        item.paud_kelas_petugas_id || item.paud_kelas_peserta_id
      }/delete`;
      this.$confirm(
        `Apakan anda ingin membatalkan ${this.isPeserta ? 'peserta' : 'petugas'} ?`,
        `Hapus ${this.isPeserta ? 'Peserta' : 'Petugas'}`,
        {
          tipe: 'warning',
          data: [
            {
              icon: 'mdi-teach',
              iconSize: 30,
              iconColor: 'secondary',
              title: `${this.$getDeepObj(item, 'nama')}`,
              subtitles: [
                this.isPeserta
                  ? `<span>Email: ${this.$getDeepObj(item, 'email') || '-'}</span>`
                  : `<span>Status: ${this.$getDeepObj(item, 'status') || '-'}</span>`,
              ],
            },
          ],
        }
      ).then(() => {
        const params = {
          ptk_id: [item.ptk_id],
        };
        this.action({
          id: this.$getDeepObj(this.kelas, 'id'),
          diklat_id: this.$getDeepObj(this.detail, 'id'),
          type: url,
          method: this.isPeserta ? 'post' : this.jenis === 'Daring' ? 'get' : 'post',
          params: this.isPeserta ? params : {},
        }).then(() => {
          this.$success(`${this.isPeserta ? 'Peserta' : 'Petugas'} berhasil dihapus`);
          this.onReload();
        });
      });
    },

    onReload() {
      this.$emit('reload');
      if (this.isPeserta) {
        this.fetch('peserta');
      } else {
        this.fetch('petugas', this.tabItems[+this.tab]['kPetugas']);
      }
    },

    onAddPetugas() {
      const fields = {
        kandidat: [
          {
            key: 'nama',
            title: 'Nama',
            icon: 'mdi-account-circle',
            grid: { md: 4, sm: 12, cols: 12 },
          },
          {
            key: 'email',
            title: 'Email',
            grid: { md: 6, sm: 12, cols: 12 },
          },
        ],
        petugas: [
          {
            key: 'akun.data.nama',
            title: 'Nama',
            icon: 'mdi-account-circle',
            grid: { md: 4, sm: 12, cols: 12 },
          },
          {
            key: 'akun.data.id',
            title: 'No UKG',
            grid: { md: 2, sm: 12, cols: 12 },
          },
          {
            key: 'akun.data.email',
            title: 'Alamat Surel',
            grid: { md: 4, sm: 12, cols: 12 },
          },
        ],
      };

      const params = {
        diklat_id: this.$getDeepObj(this.detail, 'id'),
        id: this.$getDeepObj(this.kelas, 'id'),
        tipe: this.tab === 0 ? 'peserta/kandidat' : 'petugas/kandidat',
        params: {
          k_petugas_paud: this.tabItems[+this.tab]['kPetugas'],
        },
        jenis: this.jenis,
      };

      this.$refs.popup.open(fields[this.tab === 0 ? 'kandidat' : 'petugas'], params).then((data) => {
        if (data) {
          this.petugas = Object.assign({}, data);
          if (this.tab === 0) {
            this.onSavePeserta();
          } else {
            this.onSavePetugas();
          }
        }
      });
    },

    onSavePetugas() {
      const petugas = this.petugas;
      this.action({
        id: this.$getDeepObj(this.kelas, 'id'),
        diklat_id: this.$getDeepObj(this.detail, 'id'),
        type: 'petugas/create',
        params: {
          k_petugas_paud: this.tabItems[+this.tab]['kPetugas'],
          akun_id: petugas,
        },
      }).then(() => {
        this.$success(`Data petugas berhasil ditambahkan`);
        this.onReload();
      });
    },

    onSavePeserta() {
      const mAksi = {
        0: 'peserta/create',
        1: 'peserta/create-sd',
        2: 'peserta/create-simpatika',
        3: 'peserta/create-nonptk',
      };
      const petugas = this.petugas;
      const jenis = mAksi[Number(this.$refs.popup.tab)];
      let params = {};
      if (Number(this.$refs.popup.tab) === 3) {
        params = { paud_peserta_nonptk_id: petugas };
      } else {
        params = { ptk_id: petugas };
      }

      this.action({
        id: this.$getDeepObj(this.kelas, 'id'),
        diklat_id: this.$getDeepObj(this.detail, 'id'),
        type: jenis,
        params: params,
      }).then(() => {
        this.$success(`Data peserta berhasil ditambahkan`);
        this.onReload();
      });
    },

    onChangePage(key) {
      if (key === 'next') {
        this.page++;
      } else {
        this.page--;
      }

      this.onReload();
    },

    onSearch() {},

    onUpload() {
      const rules = 'PDF/JPEG/JPG/PNG';

      this.$set(this.formulir, 'form', 'FormUnggah');
      this.$set(this.formulir, 'title', `Jadwal Diklat Dasar`);
      this.$set(this.formulir, 'format', `harus bertipe ${rules}`);
      this.$set(this.formulir, 'rules', { format: rules, required: true });
      this.$set(this.formulir, 'mode', 'upload');
      this.$set(this.formulir, 'init', null);
      this.$set(this.formulir, 'max', 1500);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
        // this.$set(this.formulir, 'init', berkas[0])
      });
    },

    upload() {
      const params = this.$refs.formulir.form || {};
      const formData = new FormData();

      if (!this.$getDeepObj(params, 'file')) {
        this.$error('Berkas belum di pilih');
        this.$refs.modal.loading = false;
        return;
      }

      formData.append('file', params['file']);

      this.action({
        diklat_id: this.$getDeepObj(this.detail, 'id'),
        id: this.$getDeepObj(this.kelas, 'id'),
        type: 'upload-jadwal',
        params: formData,
        config: {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        },
      })
        .then(() => {
          this.$success('Berkas berhasil di unggah');
          this.$emit('reload', this.kelas, true);
          this.$refs.modal.close();
        })
        .catch(() => {
          this.$refs.modal.loading = false;
        });
    },

    onPreview(berkas) {
      this.$set(this, 'preview', {});
      this.preview.url = this.$getDeepObj(berkas, 'url');
      this.preview.title = this.$getDeepObj(berkas, 'title');
      this.$nextTick(() => {
        this.$refs.filepopup.open();
      });
    },

    onLms(url) {
      window.open(url, '_blank');
    },
  },
  watch: {
    tab: function (value) {
      this.pesertas = [];
      this.page = 1;
      switch (Number(value)) {
        case 0:
          this.fetch('peserta');
          break;
        case 1:
          this.fetch('petugas', 4);
          break;
        case 2:
          this.fetch('petugas', 3);
          break;
        case 3:
          this.fetch('petugas', 1);
          break;
        case 4:
          this.fetch('petugas', 2);
          break;
      }
    },
    kelas: {
      handler(value) {
        if (value && value.id) {
          this.fetch('peserta');
        }
      },
      deep: true,
    },
  },
};
</script>
