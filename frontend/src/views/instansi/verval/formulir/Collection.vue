<template>
  <v-list-item class="pa-0">
    <v-list-item-avatar tile>
      <v-avatar tile color="secondary">
        <span class="white--text">{{ nomor }}</span>
      </v-avatar>
    </v-list-item-avatar>
    <v-list-item-content class="px-0">
      <v-row>
        <v-col cols="12" md="8" sm="12">
          <span class="body-1">
            <b>{{ diklat.nama }} - {{ diklat.tahun_diklat }}</b>
          </span>
          <p class="body-2 grey--text text--darken-1">
            <span>{{
              diklat.k_diklat_paud === 4
                ? diklat.tingkatan
                : masters &&
                  masters['tingkat_diklat_paud'] &&
                  masters['tingkat_diklat_paud'][$getDeepObj(diklat, 'k_tingkat_diklat_paud')]
            }}</span
            ><br />
            <span>Penyelenggara: {{ diklat.penyelenggara }}</span>
          </p>
        </v-col>
        <v-col cols="12" md="4" class="mt-4">
          <v-btn depressed small @click="onDetil(diklat)" color="info">
            <v-icon>mdi-eye</v-icon>
          </v-btn>
          <v-btn class="ml-md-1" depressed small @click="onView(diklat.url)" color="success">
            <v-icon>mdi-download</v-icon>
          </v-btn>
        </v-col>
      </v-row>
    </v-list-item-content>
  </v-list-item>
</template>

<script>
export default {
  name: 'ViewData',
  props: {
    nomor: {
      type: Number,
    },
    diklat: {
      type: Object,
      default: () => {},
    },
    masters: {
      type: Object,
      default: () => {},
    },
  },
  methods: {
    onView(url) {
      this.$downloadFile(url);
    },
    onDetil(data) {
      this.$emit('detil', data);
    },
  },
};
</script>

<style scoped></style>
