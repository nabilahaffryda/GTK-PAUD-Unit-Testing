<template>
  <div>
    <v-expansion-panels v-model="panel" multiple>
      <v-expansion-panel v-for="(item, i) in contents" :key="i">
        <v-expansion-panel-header>
          <span class="text-h6">{{ item.title }}</span>
        </v-expansion-panel-header>
        <v-expansion-panel-content>
          <template v-if="i === 'berkas'">
            <div class="mb-4">
              <div class="subtitle-1 font-weight-bold">Berkas Unggahan</div>
              <span>
                Lengkapi data persyaratan sesuai kebutuhan sistem, Silakan <b>tekan tombol/icon pensil</b> di sebelah
                kanan untuk melakukan edit
              </span>
            </div>
            <template v-for="(item, b) in berkases">
              <berkas
                :key="b"
                :berkas="item"
                :type="item.type"
                :valid="item.valid"
                :with-action="item.withAction"
                :value="item.value || {}"
                :optional="item.optional"
                :useDelete="$allow(`${jenis}-profil-berkas.delete`) && item.optional && !isAjuan"
                @detil="onDetil"
                @upload="$emit('upload', item.type)"
                @delete="$emit('delete', item.type)"
              />
            </template>
          </template>
          <template v-else-if="i === 'sertifikat'">
            <div class="mb-4">
              <div class="subtitle-1 font-weight-bold">Berkas Kelengkapan Sertifikat</div>
            </div>
            <template v-for="(item, b) in sertifikats">
              <berkas
                :key="b"
                :berkas="item"
                :type="item.type"
                :valid="item.valid"
                :with-action="item.withAction"
                :value="item.value || {}"
                :optional="item.optional"
                :useDelete="$allow(`${jenis}-profil-berkas.delete`) && item.optional && !isAjuan"
                use-panduan
                @detil="onDetil"
                @upload="$emit('upload', item.type)"
                @delete="$emit('delete', item.type)"
                @panduan="onPanduan"
              />
            </template>
          </template>
          <template v-else-if="i === 'diklat'">
            <v-list-item class="px-0">
              <v-list-item-content>
                <div class="font-weight-bold">Data Pengalaman Diklat</div>
                <span>
                  Lengkapi data persyaratan sesuai kebutuhan sistem, Silakan <b>tekan tombol/icon pensil</b> di sebelah
                  kanan untuk melakukan edit.
                </span>
              </v-list-item-content>
              <v-list-item-action>
                <v-btn
                  :disabled="!$allow(`${jenis === 'lpd' ? `${jenis}-profil` : 'petugas-profil-diklat'}.update`)"
                  depressed
                  color="primary"
                  @click="$emit('edit', i)"
                >
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
              </v-list-item-action>
            </v-list-item>
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
          </template>
          <template v-else>
            <component
              :is="item.component"
              :detail="detail"
              :masters="masters"
              :jenis="jenis"
              @edit="$emit('edit', i)"
            />
          </template>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
    <popup-preview-detail ref="popup" :url="preview && preview.url" :title="$getDeepObj(preview, 'title')" />
    <v-dialog v-model="panduan" max-width="800" :scrollable="false" @keydown.esc="panduan = false">
      <v-card>
        <v-toolbar flat>
          <v-toolbar-title>Informasi Kelengkapan Sertifikat</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon @click="panduan = false"><v-icon>mdi-close</v-icon></v-btn>
        </v-toolbar>
        <v-divider></v-divider>
        <v-card-text class="black--text">
          <v-img class="ma-5" :src="img"> </v-img>
          <div class="body-1 font-weight-medium mt-2">Keterangan</div>
          <ol>
            <li>Logo LPD yang Anda Unggah</li>
            <li>Tanda Tangan dan stempel yang Anda Unggah</li>
          </ol>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import Berkas from '../formulir/Berkas';
import Profil from '../formulir/Profil';
import Collection from '../formulir/Collection';
import PopupPreviewDetail from '@components/popup/PreviewDetil';
import { mapActions } from 'vuex';
export default {
  props: {
    contents: {
      type: Object,
      default: () => {},
    },
    berkases: {
      type: Array,
      default: () => [],
    },
    diklats: {
      type: Array,
      default: () => [],
    },
    sertifikats: {
      type: Array,
      default: () => [],
    },
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
    isAjuan: {
      type: Boolean,
      default: false,
    },
  },
  components: { Berkas, Profil, Collection, PopupPreviewDetail },
  data() {
    return {
      panel: [0, 1],
      preview: {
        url: '',
        title: '',
      },
      panduan: false,
      img: '',
    };
  },
  methods: {
    ...mapActions('profil', ['sertifikatUrl']),
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
    async onPanduan({ type }) {
      // const url = await this.sertifikatUrl({
      //   jenis: this.jenis,
      //   id: this.$getDeepObj(berkas, 'value.paud_instansi_id'),
      // });
      // this.$set(this, 'preview', {
      //   url: window.location.origin + url + '.pdf',
      //   title: 'Sertifikat',
      // });
      //
      // this.$nextTick(() => {
      //   this.$refs.popup.open();
      // });

      const imgUrl = {
        logo:
          'https://cdn.siap.id/s3/simpkb/asset%20img/ayo%20guru%20belajar%20&%20berbagi/GTK-PAUD/Panduan_sertif_logo.png',
        lainnya:
          'https://cdn.siap.id/s3/simpkb/asset%20img/ayo%20guru%20belajar%20&%20berbagi/GTK-PAUD/Panduan_sertif_ttd_stempel.png',
      };

      this.img = imgUrl[type] || imgUrl['lainnya'];
      this.panduan = true;
    },
  },
};
</script>
