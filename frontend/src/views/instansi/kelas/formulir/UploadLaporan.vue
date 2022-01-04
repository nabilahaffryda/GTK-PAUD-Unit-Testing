<template>
  <v-card class="mx-auto" flat>
    <v-card-text>
      <v-alert text outlined dense color="secondary">
        <div class="pa-4">
          <div class="font-weight-bold">Unggah {{ title }}</div>
          <div class="caption">
            Anda dapat mengggunnakan template laporan di bawah ini.
            <ol>
              <li>Download format template dibawah ini.</li>
              <li>Silakan isi data sesuai format yang tersedia pada template</li>
              <li>
                Unggah Berkas yang sudah Anda isi pada langkah selanjutnya. Silakan tekan
                <b>tombol selanjutnya</b>
              </li>
            </ol>
            <v-btn
              depressed
              class="ma-2"
              color="secondary"
              @click="$emit('unduhTemplate')"
              style="text-transform: none"
            >
              <v-icon left>mdi-file</v-icon>Template Laporan
            </v-btn>
          </div>
        </div>
      </v-alert>
      <v-divider class="my-2"></v-divider>
      <form-unggah
        ref="formulir"
        :title="title"
        format="PDF"
        uimodel="row"
        :rules="{ format: 'pdf', required: true }"
      ></form-unggah>
    </v-card-text>
  </v-card>
</template>
<script>
import FormUnggah from '@/components/form/Unggah';
export default {
  props: {
    title: {
      type: String,
      default: 'Laporan Pelaksanaan Diklat Luring',
    },
    initValue: {
      default: () => null,
    },
  },
  components: { FormUnggah },
  data() {
    return {
      id: null,
      form: {},
      file: null,
    };
  },
  computed: {},
  methods: {
    reset() {
      this.$set(this, 'id', null);
      this.$set(this, 'form', {});
      this.file = null;
    },

    next() {
      this.step++;
    },

    onPilih() {
      this.isPilih = this.pilihan;
    },

    onResetPilih() {
      this.pilihan = null;
      this.isPilih = null;
    },

    setFile(file) {
      this.file = file;
    },
  },
  watch: {
    initValue: 'initForm',
  },
};
</script>
