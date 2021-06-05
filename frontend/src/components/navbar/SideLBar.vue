<template>
  <v-navigation-drawer v-model="drawer" fixed light :temporary="isFloating" floating app width="300">
    <v-list class="py-0">
      <v-list-item dark class="darken-1 pa-0 secondary" :style="{ height: '64px' }" @click="toUrl">
        <v-img :height="'64px'" :src="$imgUrl('bg_sidenav.png')"></v-img>
      </v-list-item>
    </v-list>
    <v-list class="secondary darken-1">
      <v-list-item>
        <v-list-item-content>
          <span class="white--text">Selamat Datang di Aplikasi<br /></span>
          <h4 class="white--text">DIKLAT BERJENJANG GTK PAUD</h4><br />
        </v-list-item-content>
      </v-list-item>
    </v-list>
    <v-list tile class="pa-0 mb-4">
      <v-list-item-group>
        <v-list-item :to="{ name: 'home' }" :value="menuAktif === 'home'">
          <v-list-item-icon>
            <v-icon v-text="'mdi-home'" />
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title v-text="'Beranda'" />
          </v-list-item-content>
        </v-list-item>
        <template v-for="item in menus">
          <template v-if="item.program && item.submenu">
            <v-list-group
              v-if="$akseses(item, 'menu').length"
              :key="item.title"
              :prepend-icon="item.icon"
              :value="menuAktif === item.link"
            >
              <template v-slot:activator>
                <v-list-item-content>
                  <v-list-item-title>{{ item.program }}</v-list-item-title>
                </v-list-item-content>
              </template>
              <template v-for="(child, i) in item.menu">
                <v-list-item
                  v-if="checkMenu(child)"
                  :key="i"
                  :to="child.to || undefined"
                  :href="child.href || undefined"
                  :target="child.target || undefined"
                  @click="onAction({ action: child.action, title: child.title })"
                >
                  <v-list-item-icon v-if="child.icon">
                    <v-icon v-text="child.icon || 'mdi-help-circle'" />
                  </v-list-item-icon>
                  <v-list-item-content :class="{ 'pl-13': !child.icon }">
                    <v-list-item-title v-text="child.title" />
                  </v-list-item-content>
                </v-list-item>
              </template>
            </v-list-group>
          </template>
          <template v-else>
            <template v-if="item.menu">
              <div :key="item.title">
                <template v-if="$akseses(item, 'menu').length">
                  <v-subheader v-if="item.subheading" v-html="item.subheading" :key="item.title" />
                  <v-divider v-if="item.subheading" />
                </template>
                <template v-for="(child, i) in item.menu">
                  <v-list-item
                    v-if="$allow(child.akses)"
                    :key="i"
                    :disabled="child.disable"
                    :to="child.to || undefined"
                    :href="child.href || undefined"
                    :target="child.target || undefined"
                    @click="onAction({ action: child.action, title: child.title })"
                  >
                    <v-list-item-icon v-if="child.icon">
                      <v-icon v-text="child.icon || 'mdi-help-circle'" />
                    </v-list-item-icon>
                    <v-list-item-content :class="{ 'pl-13': !child.icon }">
                      <v-list-item-title v-text="child.title" />
                    </v-list-item-content>
                  </v-list-item>
                </template>
              </div>
            </template>
            <template v-else>
              <div v-if="item.akses" :key="item.title">
                <v-divider v-if="item.dividerTop" class="mt-3"></v-divider>
                <v-list-item
                  v-if="item.action"
                  :disabled="
                    item.key === 'tbs' && ((configTbs && configTbs.is_berlangsung) || aksesTimeTbs)
                      ? false
                      : item.disable
                  "
                  :active-class="menuAktif === item.key ? 'highlighted' : ''"
                  :class="menuAktif === item.key ? 'highlighted' : ''"
                  @click="onAction({ action: item.action, title: item.title })"
                >
                  <v-list-item-icon>
                    <v-icon v-text="item.icon || 'mdi-help-circle'" />
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title v-html="item.title" />
                  </v-list-item-content>
                </v-list-item>
                <v-list-item
                  v-else-if="$allow(item.akses) && !item.action"
                  :disabled="item.disable"
                  :to="item.to || undefined"
                  :href="item.href || undefined"
                  exact
                  :target="item.newtab ? '_blank' : '_self'"
                >
                  <v-list-item-icon>
                    <v-icon v-text="item.icon || 'mdi-help-circle'" />
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title v-text="item && item.subheading ? item.subheading : item.title" />
                  </v-list-item-content>
                  <v-list-item-action-text v-show="false" v-if="item.badge">
                    <v-chip x-small color="info">Tahap 2</v-chip>
                  </v-list-item-action-text>
                </v-list-item>
              </div>
            </template>
          </template>
        </template>
      </v-list-item-group>
      <v-list-item class="mt-1" v-if="role === 'gtk'" @click="onAction({ action: 'link-gurubelajar' })">
        <v-img :src="'https://cdn.siap.id/s3/simpkb/asset%20img/guru-praktik-baik/promo-potrait.png'" />
      </v-list-item>
      <v-list-item class="mt-1" v-if="role === 'gtk'" @click="onAction({ action: 'link-apksimpkb' })">
        <v-img
          :src="'https://cdn.siap.id/s3/simpkb/asset%20img/banner-info/Leaderboard-iklan-simpkb-mobile-version.png'"
        />
      </v-list-item>
    </v-list>
    <goto-modul ref="toModul" @agree="onAction"></goto-modul>
  </v-navigation-drawer>
</template>

<script>
import { mapState } from 'vuex';
import GotoModul from '@/components/popup/GotoModul';
import Vue from 'vue';
import mixinAction from '@mixins/action.js';
export default {
  components: { GotoModul },
  props: ['value'],
  mixins: [mixinAction],
  data() {
    return {
      notif: {},
      drawer: false,
      isFloating: true,
      isTbsDemo: false,
    };
  },
  computed: {
    ...mapState('auth', {
      role: (state) => state && state.role,
    }),

    ...mapState('preferensi', {
      config: (state) => state.data?.config ?? {},
      peserta: (state) => state.data?.peserta ?? {},
      kasek: (state) => state.data?.kasek ?? {},
      instruktur: (state) => state.data?.instruktur ?? {},
      simpkbUrl: (state) => state.data?.simpkb ?? '',
      peserta_status: (state) => state.data?.peserta_status ?? {},
      instruktur_status: (state) => state.data?.instruktur_status ?? {},
      aksesTimeTbs: (state) => state.aksesTimeTbs || false,
    }),

    statusLengkap() {
      return this.isKasek ? this.peserta_status || {} : this.instruktur_status || {};
    },

    profilID() {
      const id = this.peserta?.psp_profil_id ?? this.instruktur?.psp_profil_id;
      return id;
    },

    tahap() {
      return this.peserta?.gelombang ?? 1;
    },

    menus() {
      return this.$store.state.menus || [];
    },

    isRegistered() {
      return (Object.keys(this.peserta).length || Object.keys(this.instruktur).length) > 0;
    },

    menuAktif() {
      const route = (this.$route.name || 'home').split('-');
      // remove last index
      if (route.length > 1) route.splice(-1, 1);
      return route.join('-');
    },

    isKasek() {
      return !!Object.keys(this.kasek).length;
    },

    prefTbs() {
      return this.$getDeepObj(this.peserta, 'psp_profil_tbses') || [];
    },

    configTbs() {
      return this.prefTbs.filter((item) => !!Number(item.is_demo) === false)[0] || {};
    },

    configTbsDemo() {
      return this.prefTbs.filter((item) => !!Number(item.is_demo) === true)[0] || {};
    },

    tutupPendaftaran() {
      return this.isKasek ? this.peserta_status.tutup || false : this.instruktur_status.tutup || false;
    },
  },
  created() {
    // Sesuaikan autoexpand aplikasi gurupenggerak
    const isMobile = this.$vuetify.breakpoint.mobile;
    if (!isMobile) {
      this.drawer = true;
      this.isFloating = false;
    }
  },
  mounted() {
    Vue.prototype.$popupGoto = (key, title, action, is_mulai) => {
      this.$refs.toModul.open({ key, title, action, is_mulai });
    };
  },
  methods: {
    toUrl() {
      this.$router.push({ name: 'home' });
    },

    checkMenu(menu) {
      return this.$allow(menu.akses);
    },
  },
  watch: {
    value(val) {
      this.drawer = val;
    },
    drawer(val) {
      this.$emit('input', val);
    },
  },
};
</script>
<style scoped>
.highlighted {
  background-color: #e5e5e5 !important;
}

.pl-13 {
  padding-left: 56px !important;
}
</style>
