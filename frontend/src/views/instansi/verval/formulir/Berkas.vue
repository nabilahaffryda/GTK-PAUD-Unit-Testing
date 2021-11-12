<template>
  <div class="mx-5">
    <v-row>
      <v-col cols="12" md="8" sm="12" :style="$vuetify.breakpoint.mdAndDown ? 'margin-bottom: 200px' : ''">
        <v-card flat style="margin-bottom: 10%">
          <v-toolbar flat>
            <v-toolbar-title>Verval Ajuan Profil</v-toolbar-title>
            <v-spacer />
            <v-chip dark :color="$getDeepObj(detail, 'status.color')">
              {{ $getDeepObj(detail, 'status.keterangan') }}
            </v-chip>
          </v-toolbar>
          <v-spacer />
          <v-card-text>
            <v-container class="black--text">
              <v-row>
                <v-col cols="12" sm="12" md="2">
                  <v-img
                    v-if="$getDeepObj(detail, `${obj}.data.foto_url`)"
                    :src="$getDeepObj(detail, `${obj}.data.foto_url`)"
                    :aspect-ratio="4 / 6"
                  ></v-img>
                  <v-avatar v-else color="primary" size="100" class="mx-auto">
                    <v-icon dark size="80">mdi-account-circle</v-icon>
                  </v-avatar>
                </v-col>
                <v-col cols="12" sm="12" md="10" class="px-5">
                  <div>
                    <h3>{{ $getDeepObj(detail, `${obj}.data.nama`) || '' }}</h3>
                    <div class="mt-2">
                      <v-icon left>mdi-email</v-icon> {{ $getDeepObj(detail, `${obj}.data.email`) || '-' }}
                    </div>
                    <div>
                      <v-icon left>mdi-phone-classic</v-icon>
                      {{
                        $getDeepObj(detail, `${obj}.data.no_telpon`) || $getDeepObj(detail, `${obj}.data.no_hp`) || '-'
                      }}
                    </div>
                    <div class="mt-5">
                      <div class="caption">Alamat</div>
                      {{ $getDeepObj(detail, `${obj}.data.alamat`) || '-' }}
                    </div>
                  </div>

                  <v-divider class="my-3" />

                  <div class="my-5" v-if="['pengajar', 'petugas'].includes(jenis)">
                    <v-row>
                      <v-col v-for="(item, index) in profils[jenis]" :key="index" v-bind="item.grid">
                        <div class="caption">{{ $getDeepObj(item, 'title') || '-' }}</div>
                        <h2 class="subtitle-1 black--text"><span v-html="$getDeepObj(item, 'value') || '-'" /></h2>
                      </v-col>
                    </v-row>
                  </div>
                </v-col>
                <v-col v-if="jenis === 'lpd'" cols="12" md="12" sm="12">
                  <div class="text-h6 my-3 font-weight-bold"> Data Tambahan </div>
                  <v-row no-gutters dense>
                    <v-col v-for="item in tambahans" :key="item.key" cols="12" md="4" sm="12">
                      <div class="caption">{{ item.label }}</div>
                      <div>{{ $getDeepObj(detail, `nama_${item.key}`) || '-' }}</div>
                      <div>{{ $getDeepObj(detail, `telp_${item.key}`) || '-' }}</div>
                    </v-col>
                  </v-row>
                </v-col>
                <template v-if="jenis === 'lpd'">
                  <v-col cols="12" md="12">
                    <h2 class="title">Data Diklat</h2>
                    <v-list three-line>
                      <template v-for="(item, index) in diklats">
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
                </template>
                <template v-else>
                  <v-col cols="12" md="6">
                    <h2 class="title">Data Diklat</h2>
                    <collection
                      v-for="(item, b) in diklats.filter((value) => value.k_diklat_paud !== 4)"
                      :key="b"
                      :nomor="b + 1"
                      :diklat="item"
                      :masters="masters"
                      @detil="onDetilDiklat"
                    />
                  </v-col>
                  <v-col cols="12" md="6">
                    <h2 class="title">Data Diklat Lainnya</h2>
                    <collection
                      v-for="(item, b) in diklats.filter((value) => value.k_diklat_paud === 4)"
                      :key="b"
                      :nomor="b + 1"
                      :diklat="item"
                      :masters="masters"
                      @detil="onDetilDiklat"
                    />
                  </v-col>
                </template>
                <v-col cols="12" sm="12" md="12">
                  <h2 class="title">Data Unggahan</h2>
                  <v-expansion-panels multiple tile>
                    <v-expansion-panel>
                      <v-expansion-panel-header>
                        <span class="body-1">Berkas Persyaratan</span>
                      </v-expansion-panel-header>
                      <v-expansion-panel-content>
                        <berkas-collection
                          v-for="(berkas, b) in berkases"
                          :key="b"
                          :berkas="berkas"
                          :type="berkas.type"
                          :valid="berkas.valid"
                          :with-action="false"
                          :value="berkas.value || {}"
                          :optional="berkas.optional"
                          @detil="onDetil"
                        />
                      </v-expansion-panel-content>
                    </v-expansion-panel>
                    <v-expansion-panel v-if="jenis === 'lpd'">
                      <v-expansion-panel-header>
                        <span class="body-1">Berkas Kelengkapan Sertifikat</span>
                      </v-expansion-panel-header>
                      <v-expansion-panel-content>
                        <berkas-collection
                          v-for="(berkas, b) in sertifikats"
                          :key="b"
                          :berkas="berkas"
                          :type="berkas.type"
                          :valid="berkas.valid"
                          :with-action="false"
                          :value="berkas.value || {}"
                          :optional="berkas.optional"
                          @detil="onDetil"
                        />
                      </v-expansion-panel-content>
                    </v-expansion-panel>
                  </v-expansion-panels>
                </v-col>
              </v-row>
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
  </div>
</template>
<script>
import Collection from './Collection';
import BerkasCollection from './CollectionBerkas';
import PopupPreviewDetail from '@components/popup/PreviewDetil';
import { ValidationProvider } from 'vee-validate';
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
  components: { Collection, BerkasCollection, PopupPreviewDetail, ValidationProvider },
  data() {
    return {
      preview: {},
      form: {},
      pilihan: null,
      tab: null,
      tambahans: [
        {
          label: 'Penanggung Jawab',
          key: 'penanggung_jawab',
        },
        {
          label: 'Bendahara',
          key: 'bendahara',
        },
        {
          label: 'Sekretaris',
          key: 'sekretaris',
        },
      ],
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
    };
  },
  computed: {
    profils() {
      const item = this.$getDeepObj(this, 'detail') || {};
      return {
        petugas: [
          {
            title: 'NIK',
            value: this.$getDeepObj(item, 'akun.data.nik') || '-',
            grid: { cols: 12, md: 4, sm: 12 },
          },
          {
            title: 'Tempat Tanggal Lahir',
            value:
              (this.$getDeepObj(item, 'akun.data.tmp_lahir') || '-') +
              ', ' +
              (this.$localDate(this.$getDeepObj(item, 'akun.data.tgl_lahir')) || '-'),
            grid: { cols: 12, md: 4, sm: 12 },
          },
          {
            title: 'Usia',
            value: `${this.$getAge(this.$getDeepObj(item, 'akun.data.tgl_lahir')) || '-'}`,
            grid: { cols: 12, md: 4, sm: 12 },
          },
          {
            title: 'Pendidikan Terakhir',
            value:
              (this.$getDeepObj(this, `masters.m_kualifikasi.${this.$getDeepObj(item, 'k_kualifikasi')}`) || '-') +
              ' - ' +
              (this.$getDeepObj(item, 'lulusan') || '-'),
            grid: { cols: 12, md: 4, sm: 12 },
          },
          {
            title: 'Alamat Sesuai KTP',
            value: this.$getDeepObj(item, 'akun.data.alamat') || '-',
            grid: { cols: 12 },
          },
          {
            title: 'Instansi',
            value: this.$getDeepObj(item, 'instansi_nama') || '-',
            grid: { cols: 12, md: 4, sm: 12 },
          },
          {
            title: 'Jabatan',
            value: this.$getDeepObj(item, 'instansi_jabatan') || '-',
            grid: { cols: 12, md: 4, sm: 12 },
          },
          {
            title: 'Alamat Instansi',
            value: this.$getDeepObj(item, 'instansi_alamat') || '-',
            grid: { cols: 12 },
          },
        ],
      };
    },

    diklats() {
      const data =
        this.jenis === 'petugas'
          ? this.$getDeepObj(this.detail, 'paud_petugas_diklats.data') || []
          : this.$getDeepObj(this.detail, 'diklat') || this.$getDeepObj(this.detail, 'pengalaman') || [];
      return typeof data === 'string' ? JSON.parse(data) : data;
    },

    berkases() {
      const berkas = this.berkas || [];
      let masters = this.$mapForMaster(this.$getDeepObj(this, `masters.m_berkas_${this.jenis}_paud`)) || [];
      const mBerkas = this.$arrToObj(berkas, `k_berkas_${this.jenis}_paud`);

      if (this.jenis === 'petugas') {
        masters = masters.filter((item) => item.value < 5);
      }

      if (this.jenis === 'lpd') {
        masters = masters.filter((item) => item.value < 8);
      }

      let temp = [];
      masters.forEach((key) => {
        temp.push({
          title: key.text,
          pesan: ``,
          valid: !!mBerkas[key.value],
          type: 'pelatihan',
          withAction: false,
          kBerkas: key.value,
          value: mBerkas[key.value],
          optional: [2, 4, 7].includes(Number(key.value)) && this.jenis === 'lpd',
        });
      });
      return temp;
    },

    sertifikats() {
      const berkas = this.berkas || [];
      let masters = this.$mapForMaster(this.$getDeepObj(this, `masters.m_berkas_${this.jenis}_paud`)) || [];
      const mBerkas = this.$arrToObj(berkas, `k_berkas_${this.jenis}_paud`);

      let temp = [];
      masters
        .filter((item) => item.value > 7)
        .forEach((key) => {
          temp.push({
            title: key.text,
            pesan: ``,
            valid: !!mBerkas[key.value],
            type: 'pelatihan',
            withAction: false,
            kBerkas: key.value,
            value: mBerkas[key.value],
            optional: [2, 4, 7].includes(Number(key.value)) && this.jenis === 'lpd',
          });
        });
      return temp;
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

    catatan() {
      const catatan =
        this.jenis === 'lpd'
          ? this.$getDeepObj(this.detail, 'alasan')
          : this.$getDeepObj(this.detail, 'paud_petugas_perans.data.0.alasan');
      return catatan;
    },
  },

  methods: {
    getValue() {
      return { pilihan: this.pilihan, id: this.id, alasan: (this.form && this.form.alasan) || '' };
    },

    reset() {
      this.pilihan = null;
      this.$set(this, 'form', {});
      this.tab = 0;
    },

    onDetil(berkas) {
      this.$set(this, 'preview', {});
      this.preview.url = this.$getDeepObj(berkas, 'value.url');
      this.preview.title = this.$getDeepObj(berkas, 'title');
      this.$nextTick(() => {
        this.$refs.popup.open();
      });
    },

    onDetilDiklat(data) {
      this.$set(this, 'preview', {});
      this.preview.url = this.$getDeepObj(data, 'url');
      this.preview.title = this.$getDeepObj(data, 'nama');
      this.$nextTick(() => {
        this.$refs.popup.open();
      });
    },

    initForm(value) {
      this.$set(this, 'pilihan', (value && value.pilihan) || null);
    },

    onSelected(value) {
      this.$set(this, 'pilihan', value);
    },
  },
  watch: {
    initValue: 'initForm',
  },
};
</script>
