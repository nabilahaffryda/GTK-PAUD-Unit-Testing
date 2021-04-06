<template>
  <v-container style="width: 50%">
    <v-layout text-center wrap>
      <v-flex xs12 class="mt-12">
        <v-img :src="$imgUrl('bg-404.png')" class="my-3" contain height="200" />
      </v-flex>
      <v-flex mb-4>
        <h1 class="headline font-weight-bold mb-3">
          Maaf, halaman yang Anda cari tidak kami temukan
        </h1>
        <p>
          Anda dapat melakukan muat ulang halaman atau klik tombol dibawah ini
        </p>
        <p class="subheading grey--text">
          <v-btn color="primary" @click="goBack">Kembali</v-btn>
        </p>
      </v-flex>
      <v-flex class="mt-12 grey--text caption" style="alignment: bottom">
        Copyright © 2020
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  methods: {
    goBack() {
      this.$router.go(-1);
    },
  },
};
</script>

<style scoped></style>
