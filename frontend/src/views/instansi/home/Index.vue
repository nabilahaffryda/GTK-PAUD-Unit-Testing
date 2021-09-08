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

    <v-alert v-if="is_kesediaan" class="my-4" prominent color="orange" text icon="mdi-information">
      <v-row>
        <v-col class="grow black--text">
          <div class="font-weight-bold">KONFIRMASI KESEDIAAN</div>
          <span>
            Anda telah ditambahkan di kelas sebagai
            <template v-if="filteredGroup.length">
              <b v-for="(group, g) in filteredGroup" :key="group.value">
                {{ group.text }}
                <span v-if="g + 1 !== filteredGroup.length">, </span>
              </b>
            </template>
            <template v-else> (<b>Pengajar, Pengajar Tambahan atau Pembimbing Praktik</b>) </template>
            <br />
            Silakan untuk melakukan konfirmasi kesediaan
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

    <base-modal-full ref="modal" title="Konfirmasi Kesediaan" :useSave="false">
      <form-ketersediaan :items="data" ref="ketersediaan" @reload="onKonfirmasi(false)" />
    </base-modal-full>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';
import BaseCardMenus from '@/components/base/BaseCardMenus';
import FormKetersediaan from './components/Ketersediaan';
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
      is_kesediaan: (state) => state?.data.konfirmasi_kesediaan ?? false,
      groups: (state) => state?.data?.groups ?? {},
    }),

    menus() {
      return this.$store.state.menus || [];
    },

    filteredGroup() {
      return this.$mapForMaster(this.groups).filter((s) => [175, 176, 174].includes(s.value)) || [];
    },
  },
  methods: {
    ...mapActions('petugas', ['fetch']),

    onAction({ action }) {
      switch (action) {
        default:
          break;
      }
    },

    async onKonfirmasi(status = false) {
      const resp = await this.fetch().then(({ data }) => data);
      this.$set(this, 'data', resp || []);
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
