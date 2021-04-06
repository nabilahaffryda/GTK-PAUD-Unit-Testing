<template>
  <v-app id="inspire" class="notranslate">
    <component :is="layout"></component>
    <base-toast ref="toast"></base-toast>
    <base-confirmation ref="confirm"></base-confirmation>
    <popup-notifikasi ref="notifikasi"></popup-notifikasi>
    <loader :loading="loading"></loader>
  </v-app>
</template>

<script>
import Vue from 'vue';
import { mapState } from 'vuex';
import Splash from '@layouts/Splash';
import Dasbor from '@layouts/Dasbor';
import Layout from '@layouts/FullLayout';
import Maintenance from '@layouts/Maintenance';
import Loader from '@components/popup/Loader';
export default {
  name: 'App',
  components: {
    BaseConfirmation: () => import('@components/base/BaseConfirmation'),
    BaseToast: () => import('@components/base/BaseToast'),
    Loader,
    Dasbor,
    Splash,
    Maintenance,
    PopupNotifikasi: () => import('@components/popup/Notifikasi'),
    Layout,
  },
  computed: {
    ...mapState(['splash', 'loading']),

    ...mapState('auth', {
      role: (state) => state.role,
      program: (state) => (state && state.program) || '',
    }),

    layout() {
      return Number(process.env.VUE_APP_MAINTENANCE)
        ? Maintenance
        : this.splash
        ? 'Splash'
        : this.$route.meta.layouts || 'Dasbor';
    },
  },
  mounted() {
    Vue.prototype.$toast = (color, text, options = {}) => {
      this.$refs.toast.show({ color, text, ...options });
    };

    Vue.prototype.$confirm = (message, title, options = {}) => {
      return this.$refs.confirm.open(message, title, options);
    };

    Vue.prototype.$notifikasi = (message, title, options = {}) => {
      return this.$refs.notifikasi.open(message, title, options);
    };

    Vue.prototype.$error = (message, button, color) => {
      this.$toast(color || '#E53935', message, {
        icon: 'mdi-alert',
        timeout: 0,
        button: button,
      });
    };

    Vue.prototype.$info = (message) => {
      this.$toast('#1E88E5', message, {
        icon: 'mdi-information',
        timeout: 5000,
      });
    };

    Vue.prototype.$success = (message) => {
      this.$toast('#2E7D32', message, {
        icon: 'mdi-check-circle',
        timeout: 5000,
      });
    };

    Vue.prototype.$warning = (message) => {
      this.$toast('#FF8F00', message, {
        icon: 'mdi-alert',
        timeout: 5000,
      });
    };
  },
};
</script>
