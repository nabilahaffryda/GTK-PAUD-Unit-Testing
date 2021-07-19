<template>
  <div>
    <v-card tile flat class="my-5">
      <v-card-text class="pa-0">
        <v-row no-gutters>
          <v-col cols="2">
            <div class="bg-kiri"></div>
          </v-col>
          <v-col cols="10" class="pa-5">
            <h1 class="headline black--text" v-html="`Daftar Pengajar Tambahan`"></h1>
            <p v-html="desc"></p>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <v-tabs
        v-model="tab"
        color="secondary"
    >
      <v-tab
          v-for="item in items"
          :key="item.tab"
      >
        {{ item.tab }}
      </v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab">
      <v-tab-item
          v-for="item in items"
          :key="item.tab"
      >
        <list-admin
            jenis="kelas"
            akses="pengajar-tambahan"
            :title="item.tab"
            desc=""
            :actions="actions"
        ></list-admin>
      </v-tab-item>
    </v-tabs-items>
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
      tab: null,
      items: [
        { tab: 'Pengajar Tambahan (Guru)', content: 'Tab 1 Content' },
        { tab: 'Pengajar Tambahan (Dosen)', content: 'Tab 2 Content' },
      ],
    };
  },
};
</script>

<style scoped></style>
