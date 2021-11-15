<template>
  <div>
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
    <v-tabs v-model="tab" color="secondary" data-testid="tab-item">
      <v-tab v-for="item in items" :key="item.tab">
        {{ item.tab }}
      </v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab">
      <v-tab-item v-for="item in items" :key="item.tab">
        <list-verval :paramsTipe="item.params" :actions="actions"></list-verval>
      </v-tab-item>
    </v-tabs-items>
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
      tab: null,
      items: [
        { tab: 'Pengajar Tambahan (Guru)', params: { k_unsur_pengajar_paud: 1 } },
        { tab: 'Pengajar Tambahan (Dosen)', params: { k_unsur_pengajar_paud: 2 } },
      ],
    };
  },
};
</script>

<style scoped></style>
