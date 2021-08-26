<template>
  <div class="home">
    <v-row>
      <v-col>
        <v-card tile flat class="my-5">
          <v-card-text class="pa-0">
            <v-row no-gutters>
              <v-col cols="2">
                <div class="bg-kiri"></div>
              </v-col>
              <v-col cols="10" class="pa-5">
                <h1 class="headline black--text">DIKLAT BERJENJANG GTK PAUD</h1>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <v-alert v-if="is_konfirmasi" class="my-4" prominent color="orange" text icon="mdi-information">
      <v-row>
        <v-col class="grow black--text">
          <div class="font-weight-bold">KONFIRMASI KETERSEDIAAN</div>
          <span>
            Anda telah ditambahkan di kelas sebagai (<b>Peserta Diklat</b>)<br />
            Silakan untuk melakukan konfirmasi ketersediaan
          </span>
        </v-col>
        <v-col class="shrink">
          <v-btn class="mt-4" color="orange" depressed dark @click="onKonfirmasi(true)">konfirmasi</v-btn>
        </v-col>
      </v-row>
    </v-alert>

    <v-row>
      <template v-for="(menu, i) in menus">
        <template v-if="menu.program">
          <v-col cols="12" md="12" lg="12" class="pa-3" :key="i" v-if="$akseses(menu, 'menu').length">
            <div class="headline" v-html="menu.program" />
            <v-divider class="py-3" />
            <v-row>
              <template v-for="(child, c) in menu.menu">
                <v-col cols="12" md="4" lg="4" :key="c" v-if="$allow(child.akses)">
                  <base-card-menus v-bind="child" @action="onAction" :key="c" />
                </v-col>
              </template>
            </v-row>
          </v-col>
        </template>
        <template v-else>
          <template v-for="(child, c) in menu.menu">
            <v-col cols="12" md="4" lg="4" :key="c" v-if="$allow(child.akses)">
              <base-card-menus v-bind="child" @action="onAction" :key="c" />
            </v-col>
          </template>
        </template>
      </template>
    </v-row>

    <base-modal-full ref="modal" title="Konfirmasi Ketersediaan" :useSave="false">
      <form-ketersediaan :items="data" ref="ketersediaan" @reload="onKonfirmasi(false)" />
    </base-modal-full>
  </div>
</template>

<script>
import BaseCardMenus from '@/components/base/BaseCardMenus';
import FormKetersediaan from './components/Ketersediaan';
import { mapActions, mapState } from 'vuex';
export default {
  name: 'home',
  components: {
    BaseCardMenus,
    FormKetersediaan,
  },
  data() {
    return {
      data: [],
    };
  },
  computed: {
    ...mapState('preferensi', {
      is_konfirmasi: (state) => state?.data?.preferensiPtk?.konfirmasiKesediaan ?? false,
    }),

    menus() {
      return this.$store.state.menus || [];
    },
  },
  methods: {
    ...mapActions('diklat', {
      fetch: 'fetch',
    }),

    onAction({ action }) {
      switch (action) {
        default:
          break;
      }
    },

    async onKonfirmasi(status = false) {
      const resp = await this.fetch().then(({ ptkListKelasPeserta }) => ptkListKelasPeserta);
      this.$set(this, 'data', (resp && resp.data) || []);

      if (status) this.$refs.modal.open();
    },
  },
};
</script>

<style scoped>
.bg-kiri {
  background: #ffab91;
  height: 100%;
}
</style>
