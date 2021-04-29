<template>
  <div>
    <v-card flat style="margin-bottom: 10%">
      <v-toolbar flat>
        <v-toolbar-title>Verval Ajuan</v-toolbar-title>
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
              <v-avatar v-else color="secondary" size="100" class="mx-auto">
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
                  {{ $getDeepObj(detail, `${obj}.data.no_telpon`) || $getDeepObj(detail, `${obj}.data.no_hp`) || '-' }}
                </div>
                <div class="mt-5">
                  <div class="caption grey--text">Alamat</div>
                  {{ $getDeepObj(detail, `${obj}.data.alamat`) || '-' }}
                </div>
              </div>

              <v-divider class="my-3" />

              <div class="my-5" v-if="['pengajar'].includes(jenis)">
                <v-row>
                  <v-col v-for="(item, index) in profils[jenis]" :key="index" v-bind="item.grid">
                    <div class="caption grey--text">{{ $getDeepObj(item, 'title') || '-' }}</div>
                    <h2 class="subtitle-1 black--text"><span v-html="$getDeepObj(item, 'value') || '-'" /></h2>
                  </v-col>
                </v-row>
              </div>
            </v-col>
            <v-col v-if="jenis === 'lpd'" cols="12" md="12" sm="12">
              <div class="text-h6 my-3 font-weight-bold"> Data Tambahan </div>
              <v-row no-gutters dense>
                <v-col v-for="item in tambahans" :key="item.key" cols="12" md="4" sm="12">
                  <div class="caption grey--text">{{ item.label }}</div>
                  <div>{{ $getDeepObj(detail, `nama_${item.key}`) || '-' }}</div>
                  <div>{{ $getDeepObj(detail, `telp_${item.key}`) || '-' }}</div>
                </v-col>
              </v-row>
            </v-col>
            <v-col cols="12" md="12" sm="12">
              <div class="text-h6 my-3 font-weight-bold"> Data Diklat </div>
              <v-list three-line>
                <template v-for="(item, index) in $getDeepObj(detail, 'diklat') || $getDeepObj(detail, 'pengalaman')">
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
            <v-col cols="12" sm="12" md="12">
              <div class="text-h6 my-3 font-weight-bold"> Data Unggahan </div>
              <v-expansion-panels tile flat v-model="tab">
                <v-expansion-panel>
                  <v-expansion-panel-header class="px-4 subtitle-1">
                    <strong>Berkas Persyaratan</strong>
                  </v-expansion-panel-header>
                  <v-divider></v-divider>
                  <v-expansion-panel-content class="px-0">
                    <berkas-collection
                      v-for="(berkas, b) in berkases"
                      :key="b"
                      :berkas="berkas"
                      :type="berkas.type"
                      :valid="berkas.valid"
                      :with-action="false"
                      :value="berkas.value || {}"
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
    <div style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 1" v-if="!isDisable">
      <v-card flat>
        <v-card-text>
          <v-container>
            <v-row no-gutters dense>
              <v-col cols="12" md="1" sm="12">
                <v-avatar tile rounded class="ma-5">
                  <v-icon size="50">mdi-file-document-edit-outline</v-icon>
                </v-avatar>
              </v-col>
              <v-col cols="12" md="11" sm="12">
                <div class="black--text my-2 ml-1">
                  <h3>Pemeriksaan Dokumen</h3>
                  Berikan Penilaian pada kelengkapan dan keabsahan dokumen ini, Apakah semua dokumen sah dan sesuai
                  dengan data yang di inputkan kandidat ?
                </div>
                <v-card flat :disabled="isDisable">
                  <v-row dense>
                    <template v-for="item in mBtnPilihan">
                      <v-col :key="item.value" md="3" sm="12" class="my-auto">
                        <v-btn
                          block
                          dark
                          :color="item.color"
                          :outlined="pilihan !== item.value"
                          @click="pilihan = item.value"
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
            </v-row>
          </v-container>
        </v-card-text>
      </v-card>
    </div>
    <popup-preview-detail ref="popup" :url="$getDeepObj(preview, 'url')" :title="$getDeepObj(preview, 'title')" />
  </div>
</template>
<script>
import BerkasCollection from './CollectionBerkas';
import PopupPreviewDetail from '@components/popup/PreviewDetil';
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
  components: { BerkasCollection, PopupPreviewDetail },
  data() {
    return {
      preview: {},
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
        pengajar: [
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
            title: 'Alamat Lembaga',
            value: this.$getDeepObj(item, 'instansi_alamat') || '-',
            grid: { cols: 12, md: 12, sm: 12 },
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
            title: 'Keikutsertaan PCP',
            value: this.$getDeepObj(item, 'm_pcp_paud.data.keterangan') || '-',
            grid: { cols: 12, md: 6, sm: 12 },
          },
          {
            title: 'Pengalaman Melatih 2 Tahun Terakhir',
            value: item && item.pengalaman && item.pengalaman.length ? item.pengalaman.length + 1 + ' Kali' : '0 Kali',
            grid: { cols: 12, md: 6, sm: 12 },
          },
        ],
      };
    },

    berkases() {
      const berkas = this.$getDeepObj(this, `detail.paud_${this.jenis}_berkases.data`) || [];
      const masters = this.$mapForMaster(this.$getDeepObj(this, `masters.m_berkas_${this.jenis}_paud`));
      const mBerkas = this.$arrToObj(berkas, `k_berkas_${this.jenis}_paud`);

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
        });
      });

      return temp;
    },
  },

  methods: {
    getValue() {
      return { pilihan: this.pilihan, id: this.id };
    },

    reset() {
      this.pilihan = null;
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

    initForm(value) {
      this.$set(this, 'pilihan', (value && value.pilihan) || null);
    },
  },
  watch: {
    initValue: 'initForm',
  },
};
</script>
