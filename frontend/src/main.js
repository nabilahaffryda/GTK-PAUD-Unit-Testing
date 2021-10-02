import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import vuetify from './plugins/vuetify';
import mixins from '@mixins/global';
import { mask } from 'vue-the-mask';
import VueAnalytics from 'vue-analytics';
import BaseModalFull from '@components/base/BaseModalFull';
import './plugins/vee-validate';
// import './sentry';
// css
import 'roboto-fontface/css/roboto/roboto-fontface.css';
import '@mdi/font/css/materialdesignicons.css';
import '@assets/css/main.css';

Vue.mixin(mixins);
Vue.directive('mask', mask);
Vue.component('base-modal-full', BaseModalFull);
Vue.config.productionTip = false;

if (process.env.NODE_ENV !== 'development') {
  Vue.use(VueAnalytics, {
    id: 'UA-57652882-56',
    router,
  });
}

export default new Vue({
  router,
  store,
  vuetify,
  render: (h) => h(App),
}).$mount('#app');
