<template>
  <div>
    <v-card tile flat class="my-5">
      <v-card-text class="pa-0">
        <v-row no-gutters>
          <v-col cols="2">
            <div class="bg-kiri"></div>
          </v-col>
          <v-col cols="10" class="pa-5">
            <h1 class="headline black--text" v-html="`Daftar Petugas Kelas`"></h1>
            <p v-html="desc"></p>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <v-tabs v-model="tab" color="secondary">
      <v-tab v-for="item in items" :key="item.tab">
        {{ item.tab }}
      </v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab">
      <v-tab-item v-for="item in items" :key="item.tab">
        <list-view :paramsTipe="item.params" :title="item.tab" :jenis="item.jenis" :actions="actions"></list-view>
      </v-tab-item>
    </v-tabs-items>
  </div>
</template>

<script>
import ListView from './List';
import Actions from './actions';
export default {
  name: 'Program',
  components: { ListView },
  data() {
    return {
      actions: Actions,
      desc: '',
      tab: null,
      items: [
        { tab: 'Pengajar', params: { k_petugas_paud: 1 }, jenis: 'pengajar' },
        { tab: 'Pembimbing Praktik', params: { k_petugas_paud: 3 }, jenis: 'pembimbing' },
      ],
    };
  },
};
</script>

<style scoped></style>
