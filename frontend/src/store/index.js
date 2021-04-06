import Vue from 'vue';
import Vuex from 'vuex';
import auth from './modules/auth';
import access from './modules/access';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    splash: true,
    loading: false,
    autosave: false,
    initapp: false,
    menus: false,
  },
  mutations: {
    SET_SPLASH(state, value) {
      state.splash = value;
    },

    SET_LOADING(state, value) {
      state.loading = value;
    },

    SET_AUTOSAVE(state, value) {
      state.autosave = value;
    },

    SET_FIRSTAPP(state, value) {
      state.initapp = value;
    },

    SET_MENUS(state, value) {
      state.menus = value;
    },
  },
  actions: {},
  modules: {
    auth,
    access,
  },
});
