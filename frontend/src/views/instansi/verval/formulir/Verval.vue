<template>
  <div class="mx-5">
    <v-row>
      <v-col cols="12" md="8" sm="12" :style="$vuetify.breakpoint.mdAndDown ? 'margin-bottom: 200px' : ''">
        <v-card flat style="margin-bottom: 10%">
          <v-toolbar flat class="pa-2">
            <v-toolbar-title>Info dan Detil Kelas</v-toolbar-title>
            <v-spacer />
          </v-toolbar>
          <v-spacer />
          <v-card-text>
            <v-container class="black--text">
              <v-row dense no-gutters class="my-5">
                <v-col cols="12" md="1" sm="2" class="pa-0">
                  <v-avatar color="primary" size="60">
                    <v-icon dark>mdi-teach</v-icon>
                  </v-avatar>
                </v-col>
                <v-col cols="12" md="11" sm="10" class="px-0">
                  <div>
                    <div class="label--text">Nama Kelas</div>
                    <div class="body-1">
                      {{ $getDeepObj(detail, 'paud_mapel_kelas.data.nama') || '-' }} -
                      {{ $getDeepObj(detail, 'nama') || '-' }}
                    </div>
                    <div class="font-italic">
                      {{ $getDeepObj(detail, `paud_diklat_luring.data.instansi.data.nama`) || '-' }}
                    </div>
                  </div>
                  <div class="my-2">
                    <div v-if="form.no_sertifikat">
                      <span class="label--text">Nomor Sertifikat : </span>
                      {{ $getDeepObj(form, 'no_sertifikat') || '' }}
                    </div>
                    <div v-if="form.tgl_sertifikat">
                      <span class="label--text">Tanggal Sertifikat : </span>
                      {{ $localDate($getDeepObj(form, 'tgl_sertifikat')) || '' }}
                    </div>
                  </div>

                  <v-row>
                    <v-col cols="12" md="12" sm="12">
                      <berkases
                        :berkas="berkases"
                        :valid="($getDeepObj(berkases, 'url') || '') !== ''"
                        :use-icon="false"
                        :value="berkases"
                        @detil="onPreview"
                      />
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>

              <v-tabs v-model="tab" fixed-tabs>
                <v-tab v-for="item in tabItems" :key="item.value" id="tab">
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
                    ></v-text-field>
                  </v-toolbar>

                  <div class="my-4">
                    <v-data-table
                      :headers="headers"
                      :items="filteredItems"
                      :no-data-text="`Daftar ${item.text} belum ditemukan`"
                    >
                      <template v-slot:[`item.aksi`]="{ item }">
                        <template>
                          <v-btn depressed dark color="blue" small @click="onDetil(item)"> detail </v-btn>
                        </template>
                      </template>
                    </v-data-table>
                  </div>
                </v-tab-item>
              </v-tabs-items>
            </v-container>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col
        cols="12"
        md="4"
        sm="12"
        :style="`position: fixed; right: 10px; ${$vuetify.breakpoint.mdAndDown ? 'bottom: 0;' : ''} z-index: 1`"
      >
        <v-card flat>
          <v-card-text>
            <v-container>
              <v-row no-gutters dense>
                <v-col cols="12" md="12" sm="12">
                  <div class="black--text my-2 ml-1">
                    <h3>Pemeriksaan Dokumen</h3>
                    Berikan Penilaian pada kelengkapan dan keabsahan dokumen ini,
                    <b>Apakah semua dokumen sah dan sesuai</b>
                    dengan data yang di inputkan kandidat ?
                  </div>
                  <v-card flat :disabled="isDisable">
                    <v-row dense>
                      <template v-for="item in mBtnPilihan">
                        <v-col :key="item.value" md="12" sm="12" class="my-auto">
                          <v-btn
                            block
                            dark
                            :color="item.color"
                            :outlined="pilihan !== item.value"
                            @click="onSelected(item.value)"
                            style="background-color: white"
                          >
                            <v-icon small left>{{ item.icon }}</v-icon
                            >{{ item.label }}
                          </v-btn>
                        </v-col>
                      </template>
                    </v-row>
                  </v-card>
                </v-col>
                <v-col cols="12" :class="[isEdit ? 'mt-3' : '']">
                  <template v-if="pilihan && !isDisable">
                    <div class="pa-4" v-if="$isObject(schema[pilihan])">
                      <b class="black--text">{{ schema[pilihan]['label'] }} *</b><br />
                      <validation-provider
                        mode="passive"
                        :name="schema[pilihan]['label']"
                        rules="required"
                        v-slot="{ errors }"
                      >
                        <v-textarea
                          required
                          v-model="form.alasan"
                          :error-messages="errors"
                          :disabled="isDisable"
                          rows="1"
                          single-line
                          outlined
                        />
                      </validation-provider>
                    </div>
                  </template>
                  <template v-if="!isEdit || isDisable">
                    <b class="black--text">Catatan Verval*</b><br />
                    <v-card flat outlined>
                      <v-card-text v-html="catatan"></v-card-text>
                    </v-card>
                  </template>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <popup-preview-detail ref="popup" :url="$getDeepObj(preview, 'url')" :title="$getDeepObj(preview, 'title')" />
    <base-modal-full ref="modal" colorBtn="primary" generalError :title="formulir.title" :useSave="formulir.useSave">
      <component
        ref="formulir"
        :is="formulir.form"
        :type="formulir.type"
        :detail="detail"
        :masters="masters"
        :initValue="formulir.initValue"
        :jenis="jenis"
      ></component>
    </base-modal-full>
    <v-dialog v-model="dialog" max-width="600px">
      <v-card flat>
        <v-card-title class="pa-0">
          <v-toolbar color="secondary" dark>
            <v-toolbar-title>Generate Sertifikat</v-toolbar-title>
            <v-spacer />
            <v-btn text icon @click="dialog = false"><v-icon>mdi-close</v-icon></v-btn>
          </v-toolbar>
        </v-card-title>
        <v-card-text class="pt-2 black--text">
          <div class="body-1 font-weight-bold">Input Kebutuhan Sertifikat</div>
          <span class="my-2">
            Silakan isikan no sertifikat dan tanggal sertifikat untuk laporan kelas diklat luring dibawah ini.
          </span>
          <div class="mt-4" v-if="Number(pilihan) === 2">
            <validation-observer ref="observer" v-slot="{ errors }">
              <span class="label--text" v-show="false"> {{ errors }}</span>
              <base-form-generator v-model="form" :schema="formSertifikat" />
            </validation-observer>
          </div>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click.native="dialog = false"> Batal </v-btn>
          <v-btn depressed color="primary" @click="saveSertifikat"> Simpan </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate';
import { mapActions } from 'vuex';
import Berkases from '../../profil/formulir/Berkas';
import DetailNilai from '../../kelas/formulir/DetailNilai';
import PopupPreviewDetail from '@components/popup/PreviewDetil';
import BaseFormGenerator from '../../../../components/base/BaseFormGenerator';
export default {
  props: {
    detail: {
      type: Object,
      default: () => {},
    },
    berkas: {
      type: Array,
      default: () => [],
    },
    masters: {
      type: Object,
      default: () => {},
    },
    jenis: {
      type: String,
      default: 'lpd',
    },
    obj: {
      type: String,
      default: 'instansi',
    },
    initValue: {
      type: Object,
      default: () => null,
    },
    isDisable: {
      type: Boolean,
      default: false,
    },
    isEdit: {
      type: Boolean,
      default: false,
    },
    id: {
      type: [String, Number],
      default: null,
    },
  },
  components: { BaseFormGenerator, ValidationObserver, ValidationProvider, Berkases, PopupPreviewDetail, DetailNilai },
  data() {
    return {
      preview: {},
      form: {},
      pesertas: [],
      pilihan: null,
      tab: null,
      search: '',
      mBtnPilihan: [
        {
          label: 'SETUJU',
          icon: 'mdi-check',
          color: 'success',
          value: 2,
          akses: true,
        },
        {
          label: 'PERBAIKAN',
          icon: 'mdi-pencil',
          color: 'warning',
          value: 3,
          akses: true,
        },
        {
          label: 'TOLAK',
          icon: 'mdi-close',
          color: 'red',
          value: 1,
          akses: true,
        },
      ],
      tabItems: [
        { value: 'peserta', kPetugas: 0, text: 'Peserta' },
        { value: 'admin', kPetugas: 4, text: 'Admin Kelas' },
        { value: 'pembimbing-praktik', kPetugas: 3, text: 'Pembimbing Praktik' },
        { value: 'pengajar', kPetugas: 1, text: 'Pengajar' },
        { value: 'pengajar-tambahan', kPetugas: 2, text: 'Pengajar Tambahan' },
      ],
      formulir: {},
      dialog: false,
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

      if (Number(this.tab) === 0) {
        temp.push({ text: 'Nilai', value: 'nilai', sortable: false });
        temp.push({ text: '', value: 'aksi', sortable: false });
      }

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
          nilai: this.$getDeepObj(item, 'nilai'),
          dapodik: item && Number(item.is_nonptk) === 1 ? 'Non Dapodik' : 'Dapodik',
          paud_kelas_petugas_id: this.$getDeepObj(item, 'paud_kelas_petugas_id'),
          paud_kelas_peserta_id: this.$getDeepObj(item, 'paud_kelas_peserta_id') || '',
          paud_kelas_peserta_luring_id: this.$getDeepObj(item, 'paud_kelas_peserta_luring_id') || '',
          ptk_id: this.$getDeepObj(item, 'ptk_id') || '',
          propinsi: this.$getDeepObj(item, 'akun.data.m_propinsi.data.keterangan') || '-',
          kota: this.$getDeepObj(item, 'akun.data.m_kota.data.keterangan') || '-',
        };
      });
    },

    filteredItems() {
      return this.items.filter((s) => {
        return s.nama.toLowerCase().includes(this.search.toLowerCase());
      });
    },

    schema() {
      return {
        1: {
          label: 'Alasan ajuan ditolak',
          items: this.catatan,
        },
        2: {},
        3: {
          label: 'Alasan ajuan butuh diperbaiki',
          items: this.catatan,
        },
      };
    },

    formSertifikat() {
      return [
        {
          type: 'VTextField',
          name: 'no_sertifikat',
          label: `Nomor Sertifikat`,
          labelColor: 'secondary',
          hideDetails: false,
          placeholder: 'Nomor Sertifikat',
          hint: 'wajib diisi',
          grid: { cols: 12 },
          required: true,
          outlined: true,
          dense: true,
          singleLine: true,
        },
        {
          type: 'VDatePicker',
          name: 'tgl_sertifikat',
          label: 'Tanggal Sertifikat',
          dense: true,
          hint: 'wajib diisi',
          grid: { cols: 12 },
          required: true,
          outlined: true,
          singleLine: true,
        },
      ];
    },

    catatan() {
      const catatan =
        this.jenis === 'lpd'
          ? this.$getDeepObj(this.detail, 'alasan')
          : this.$getDeepObj(this.detail, 'paud_petugas_perans.data.0.alasan');
      return catatan;
    },

    berkases() {
      return {
        title: 'Jadwal Diklat Dasar',
        pesan: `<span class='grey--text'><i>* Silakan unduh template Jadwal dasar <a class="blue--text" href="https://files1.simpkb.id/berkas/paud/Template_Jadwal Diklat_Dasar_Rev.docx" target="_blank">UNDUH DISINI</a> </i> </span>`,
        url: this.$getDeepObj(this, 'detail.url_jadwal'),
      };
    },
  },

  methods: {
    ...mapActions('luringLaporanVerval', ['getListKelas', 'getDetailPesertaNilai']),

    getValue() {
      let params = { pilihan: this.pilihan, id: this.id, alasan: (this.form && this.form.alasan) || '' };
      if (this.pilihan === 2) {
        Object.assign(params, { no_sertifikat: this.form.no_sertifikat, tgl_sertifikat: this.form.tgl_sertifikat });
      }

      return params;
    },

    reset() {
      this.pilihan = null;
      this.$set(this, 'form', {});
      this.tab = 0;
      this.search = '';
    },

    initForm(value) {
      this.$set(this, 'pilihan', (value && value.pilihan) || null);
    },

    fetch(tipe, k_petugas = null) {
      if (!this.$getDeepObj(this.detail, 'id')) return;
      this.getListKelas({
        id: this.$getDeepObj(this.detail, 'id'),
        tipe: tipe,
        jenis: 'luring',
        params: {
          k_petugas_paud: k_petugas,
          count: 50,
        },
      }).then(({ data }) => {
        this.pesertas = data || [];
      });
    },

    onSelected(value) {
      if (+value === 2) {
        this.dialog = true;
        if (this.$refs.observer) this.$refs.observer.reset();
        this.$set(this.form, 'no_sertifikat', '');
        this.$set(this.form, 'tgl_sertifikat', '');
      } else {
        this.$set(this, 'pilihan', value);
      }
    },

    onPreview(berkas) {
      this.$set(this, 'preview', {});
      this.preview.url = this.$getDeepObj(berkas, 'url');
      this.preview.title = this.$getDeepObj(berkas, 'title');
      this.$nextTick(() => {
        this.$refs.popup.open();
      });
    },

    onLms(url) {
      window.open(url, '_blank');
    },

    getNilai(item) {
      return this.$getDeepObj(item, 'n_pendalaman_materi') || this.$getDeepObj(item, 'n_tugas_mandiri') || null;
    },

    async onDetil(item) {
      const resp = await this.getDetailPesertaNilai({
        kelas_id: this.$getDeepObj(this.detail, 'id'),
        id: this.$getDeepObj(item, 'paud_kelas_peserta_luring_id'),
      }).then(({ data }) => data);

      this.$set(this.formulir, 'title', 'Penilaian Peserta');
      this.$set(this.formulir, 'form', 'DetailNilai');
      this.$set(this.formulir, 'useSave', false);
      this.$refs.modal.open();

      this.$nextTick(() => {
        // this.$refs.formulir.reset();
        this.$set(
          this.formulir,
          'initValue',
          Object.assign({
            peserta: Object.assign(item, { is_nilai: this.getNilai(item) }),
            instruments: resp,
          })
        );
      });
    },

    async saveSertifikat() {
      const valid = await this.$refs.observer.validate();
      if (valid) {
        this.dialog = false;
        this.pilihan = 2;
      }
    },
  },
  watch: {
    initValue: 'initForm',
    tab: function (value) {
      this.pesertas = [];
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
    detail: {
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
