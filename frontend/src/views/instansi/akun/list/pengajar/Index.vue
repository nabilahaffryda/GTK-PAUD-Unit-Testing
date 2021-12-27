<template>
  <div data-app>
    <!--notif jadwal-->
    <v-card tile flat class="my-5">
      <v-card-text class="pa-0">
        <v-row no-gutters>
          <v-col cols="2">
            <div class="bg-kiri"></div>
          </v-col>
          <v-col cols="10" class="pa-5">
            <h1 class="headline black--text" v-html="`Daftar Akun Pengajar`"></h1>
            <p v-html="desc"></p>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <list-admin jenis="pengajar" akses="pengajar" title="Pengajar" desc="" :actions="actions"></list-admin>
  </div>
</template>

<script>
import ListAdmin from '../base/Main';
import Actions from './actions';
export default {
  name: 'Program',
  components: { ListAdmin },
  data() {
    return {
      actions: Actions,
      desc: '',
    };
  },
};
</script>

<style scoped></style>
