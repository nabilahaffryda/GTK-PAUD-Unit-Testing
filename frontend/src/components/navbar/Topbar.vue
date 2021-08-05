<template>
  <v-app-bar color="secondary" dark app flat>
    <v-app-bar-nav-icon @click="toggleL"></v-app-bar-nav-icon>
    <v-toolbar-title v-html="title"></v-toolbar-title>
    <v-spacer></v-spacer>
    <template>
      <v-divider vertical inset dark></v-divider>
      <v-tooltip bottom v-if="layanans.length">
        <template v-slot:activator="{ on, attrs }">
          <div v-bind="attrs" v-on="on">
            <v-menu v-model="menu" left :close-on-content-click="false" offset-y :nudge-width="200">
              <template v-slot:activator="{ on, attrs }">
                <v-btn icon dark class="mx-2" v-bind="attrs" v-on="on">
                  <v-icon>mdi-apps</v-icon>
                </v-btn>
              </template>

              <v-card>
                <v-list>
                  <template v-for="(item, i) in layanans">
                    <v-list-item :key="i" :href="item.url">
                      <v-list-item-avatar size="16">
                        <img src="/favicon.png" />
                      </v-list-item-avatar>
                      <v-list-item-content>
                        <v-list-item-title class="caption" v-html="item.nama"></v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-divider :key="`div_${i}`"></v-divider>
                  </template>
                </v-list>
              </v-card>
            </v-menu>
          </div>
        </template>
        <span>Program Lain</span>
      </v-tooltip>
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
import { mapActions, mapState } from 'vuex';

export default {
  data: () => ({
    menu: false,
    layanans: [],
  }),
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
  created() {
    this.changeLayanan();
  },
  methods: {
    ...mapActions('preferensi', ['getLayanan']),

    toggleL() {
      this.$emit('toggleL');
    },
    toggleR() {
      this.$emit('toggleR');
    },

    changeLayanan() {
      if (!this.layanans.length) {
        this.getLayanan().then(({ data }) => {
          this.layanans = (data && data.layanan) || [];
        });
      }
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
