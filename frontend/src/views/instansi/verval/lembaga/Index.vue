<template>
  <div>
    <!--notif jadwal-->
    <v-card tile flat class="my-5">
      <v-card-text class="pa-0">
        <v-row no-gutters>
          <v-col cols="2">
            <div class="bg-kiri"></div>
          </v-col>
          <v-col cols="10" class="pa-5">
            <h1 class="headline black--text"> <strong>Verval</strong> Profil</h1>
            <div> Modul ini digunakan untuk melakukan {{ $route.meta.title }} </div>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <list-verval :actions="actions"></list-verval>
  </div>
</template>

<script>
import ListVerval from '../base/Main';
import Actions from './actions';
export default {
  name: 'Program',
  components: { ListVerval },
  data() {
    return {
      actions: Actions,
      desc: '',
    };
  },
};
</script>

<style scoped></style>
