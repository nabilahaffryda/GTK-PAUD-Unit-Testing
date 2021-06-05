<template>
  <div id="dasbor">
    <div class="corner-ribbon top-left red shadow" v-if="env !== 'production'"> DEMO </div>
    <left-bar v-model="drawerL"></left-bar>
    <topbar @toggleL="drawerL = !drawerL" @toggleR="drawerR = !drawerR"></topbar>
    <right-bar v-model="drawerR"></right-bar>
    <v-main>
      <v-container class="ppg-wrapper" fluid>
        <router-view :key="$route.fullPath" />
      </v-container>
      <v-fab-transition v-if="!exceptFab.includes($route.name) && useFab">
        <v-btn color="deep-orange darken-4" style="bottom: 2rem" dark fixed bottom right fab @click="toLink">
          <v-icon>mdi-home</v-icon>
        </v-btn>
      </v-fab-transition>
    </v-main>
  </div>
</template>

<script>
import Topbar from '@components/navbar/Topbar';
import LeftBar from '@components/navbar/SideLBar';
import RightBar from '@components/navbar/SideRBar';
import { mapState } from 'vuex';
export default {
  components: {
    Topbar,
    LeftBar,
    RightBar,
  },
  data: () => ({
    drawerL: false,
    drawerR: false,
    exceptFab: ['home', '404', 'auth', 'aktivasi', 'daftar-gpm', 'aktivasi-gpm'],
  }),
  computed: {
    ...mapState('auth', {
      role: (state) => state.role,
      env: (state) => state.env || 'production',
    }),

    useFab() {
      return true;
    },
  },
  methods: {
    toLink() {
      this.$router.push({ name: 'home' });
    },
  },
};
</script>

<style scoped>
.ppg-wrapper {
  max-width: 1280px;
}
</style>
