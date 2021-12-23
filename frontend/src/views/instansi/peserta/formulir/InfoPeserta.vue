<template>
  <div class="mx-auto container">
    <v-card>
      <v-card-text>
        <v-row class="pa-5">
          <v-col cols="12" md="2" sm="12" v-if="$getDeepObj(initValue, 'foto_url')">
            <v-img :src="$imgUrl($getDeepObj(initValue, 'foto_url') || 'default_foto_lpd.png')"></v-img>
          </v-col>
          <v-col cols="12" :md="$getDeepObj(initValue, 'foto_url') ? 10 : 12" sm="12">
            <div class="mx-3">
              <div class="text-h6 black--text">Data Peserta</div>
              <base-list-info class="px-0" :info="info.umum"></base-list-info>
            </div>
            <div class="ma-3">
              <div class="text-h6 black--text">Data Diklat</div>
              <base-list-info class="px-0" :info="info.diklat"></base-list-info>
            </div>
            <div class="ma-3">
              <div class="text-h6 black--text">Data Unggahan</div>
              <berkas-collection
                v-for="(berkas, b) in berkases"
                :key="b"
                :berkas="berkas"
                :type="berkas.type"
                :valid="berkas.valid"
                :with-action="false"
                :value="berkas.value || {}"
                :optional="berkas.optional"
                @detil="onDetilBerkas"
              />
            </div>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <popup-preview-detail ref="popup" :url="$getDeepObj(preview, 'url')" :title="$getDeepObj(preview, 'title')" />
  </div>
</template>
<script>
import { mapState } from 'vuex';
import BaseListInfo from '@components/base/BaseListInfo';
import BerkasCollection from '@views/instansi/verval/formulir/CollectionBerkas';
import PopupPreviewDetail from '@components/popup/PreviewDetil';

export default {
  props: {
    initValue: {
      type: Object,
      default: () => {},
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

  components: { BaseListInfo, BerkasCollection, PopupPreviewDetail },

  computed: {
    ...mapState('preferensi', {
      instansi: (state) => state?.data?.instansi ?? {},
    }),

    info() {
      return {
        umum: [
          [
            {
              key: 'nama',
              label: 'Nama',
              value: this.$getDeepObj(this.initValue, 'nama') || '-',
            },
            {
              key: 'email',
              label: 'Alamat Email',
              value: this.$getDeepObj(this.initValue, 'email') || '-',
            },
          ],
          [
            {
              key: 'nik',
              label: 'NIK',
              value: this.$getDeepObj(this.initValue, 'nik') || '-',
            },
            {
              key: 'no_hp',
              label: 'Nomor Telepon',
              value: this.$getDeepObj(this.initValue, 'no_hp') || '-',
            },
          ],
          [
            {
              key: 'lahir',
              label: 'Tempat, Tanggal Lahir',
              value: this.$getDeepObj(this.initValue, 'tmp_lahir') || '-',
            },
            {
              key: 'kelamin',
              label: 'Jenis Kelamin',
              value:
                this.$getDeepObj(this.initValue, 'kelamin') === 'L'
                  ? 'Laki - laki'
                  : this.$getDeepObj(this.initValue, 'kelamin') === 'P'
                  ? 'Perempuan'
                  : '',
            },
          ],
          [
            {
              key: 'tgl_lahir',
              label: 'Tanggal Lahir',
              value: this.$localDate(this.$getDeepObj(this.initValue, 'tgl_lahir') || '-'),
            },
            {
              key: 'unit_kerja',
              label: 'Unit Kerja',
              value: this.$getDeepObj(this.initValue, 'unit_kerja') || '-',
            },
          ],
          [
            {
              key: 'alamat',
              label: 'Alamat Sesuai KTP',
              value: [
                this.$getDeepObj(this.initValue, 'alamat') || '-',
                this.$getDeepObj(this.initValue, `m_propinsi.data.keterangan`) || '-',
                this.$getDeepObj(this.initValue, `m_kota.data.keterangan`) || '-',
              ].join(', '),
            },
          ],
        ],
        diklat: [
          [
            {
              key: 'jenjang_diklat',
              label: 'Jenis Diklat',
              value: this.$getDeepObj(this.masters, `m_diklat_paud.${this.initValue.k_diklat_paud}`) || '',
            },
            {
              key: 'jenis_diklat',
              label: 'Jenis Diklat',
              value:
                this.$getDeepObj(this.masters, `m_jenjang_diklat_paud.${this.initValue.k_jenjang_diklat_paud}`) || '',
            },
          ],
        ],
      };
    },

    berkases() {
      let temp = [];
      const mBerkas = [
        { title: 'Sertifikat / Ijazah', key: 'sertifikat' },
        { title: 'Scan Ktp', key: 'ktp' },
        { title: 'Surat Keterangan Instansi', key: 'sk_instansi' },
      ];

      mBerkas.forEach((item, index) => {
        temp.push({
          title: item.title,
          pesan: ``,
          type: item.key,
          valid: !!this.$getDeepObj(this.initValue, `${item.key}_url`),
          withAction: false,
          value: this.$getDeepObj(this.initValue, `${item.key}_url`)
            ? { nama: item.title, url: this.$getDeepObj(this.initValue, `${item.key}_url`) }
            : {},
          url: this.$getDeepObj(this.initValue, `${item.key}_url`) || '',
          kBerkas: index,
        });
      });

      return temp;
    },
  },

  methods: {
    reset() {
      this.preview = {};
    },

    onDetilBerkas(data) {
      this.$set(this, 'preview', {});
      this.preview.url = this.$getDeepObj(data, 'url');
      this.preview.title = this.$getDeepObj(data, 'title');
      this.$nextTick(() => {
        this.$refs.popup.open();
      });
    },
  },
};
</script>
