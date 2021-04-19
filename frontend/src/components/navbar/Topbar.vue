<template>
  <v-app-bar color="secondary" dark app flat>
    <v-app-bar-nav-icon @click="toggleL"></v-app-bar-nav-icon>
    <v-toolbar-title v-html="title"></v-toolbar-title>
    <v-spacer></v-spacer>
    <template>
      <v-list color="transparent">
        <v-list-item @click="toggleR" class="pa-0 ma-0">
          <v-list-item-avatar :size="32" color="grey lighten-4" class="ma-0 mr-1">
            <v-img :src="$imgUrl(avatar)" :aspect-ratio="4 / 6" class="grey lighten-2"></v-img>
          </v-list-item-avatar>
          <v-list-item-title class="d-none d-sm-block">
            {{ username }}
          </v-list-item-title>
        </v-list-item>
      </v-list>
    </template>
  </v-app-bar>
</template>
<script>
import { mapState } from 'vuex';

export default {
  computed: {
    ...mapState('auth', {
      role: (state) => state?.role ?? 'gtk',
    }),

    ...mapState('preferensi', {
      ptk: (state) => state?.data?.ptk ?? {},
      akun: (state) => state?.data?.akun ?? {},
    }),

    title() {
      return (this.$route.meta && this.$route.meta.title) || 'Beranda';
    },

    username() {
      return this.role === 'instansi' ? this.akun?.nama ?? 'Admin' : this.ptk?.nama ?? 'Gtk';
    },

    avatar() {
      return this.role === 'instansi' ? this.akun?.foto_url ?? 'avatar.png' : this.ptk?.foto ?? 'avatar.png';
    },
  },
  methods: {
    toggleL() {
      this.$emit('toggleL');
    },
    toggleR() {
      this.$emit('toggleR');
    },
  },
};
</script>
<style scoped>
.logos {
  font-family: Roboto;
  font-weight: bold;
}
</style>
