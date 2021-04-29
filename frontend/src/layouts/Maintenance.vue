<template>
  <v-container style="width: 50%">
    <v-layout text-center wrap>
      <v-flex xs12 class="mt-12">
        <v-img :src="$imgUrl('bg-maintenance.png')" class="my-3" contain height="200" />
      </v-flex>
      <v-flex mb-4>
        <h1 class="headline font-weight-bold mb-3"> Situs eWarga Sedang Dalam Pemutakhiran </h1>
        <p class="subheading grey--text">
          Maaf, situs eWarga sedang dalam perawatan berkala, hal ini dilakukan untuk memberikan layanan Aplikasi yang
          lebih baik lagi kepada pengguna
          <br />
        </p>
      </v-flex>
      <v-flex class="mt-12 grey--text caption" style="alignment: bottom">
        Copyright © 2019, eWarga Smart Village All right served
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  name: 'maintenance',
};
</script>

<style scoped></style>
