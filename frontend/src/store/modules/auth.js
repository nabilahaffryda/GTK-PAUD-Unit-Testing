/* eslint-disable no-empty-pattern */
import kitsu from '@plugins/kitsu';
import $http from '@plugins/axios';
import store from '@store';
import { getDeepObj, isObject } from '@/utils/format';
import gql from 'graphql-tag';
import graphqlClient from '@plugins/graphql';
const $get = kitsu();

const newState = {
  isLogin: false,
  role: null,
  akun: null,
  ptk: null,
  env: null,
  instansi_id: null,
};

export const state = newState;

export const mutations = {
  SET_LOGIN(state, value) {
    state.isLogin = value ?? false;
  },
  SET_AKUN(state, akun) {
    state.akun = akun ?? null;
  },
  SET_ENV(state, newValue) {
    state.env = newValue ?? null;
  },
  SET_PTK(state, ptk) {
    state.ptk = ptk ?? null;
  },
  SET_INSTANSI_ID(state, newValue) {
    state.instansi_id = newValue ?? null;
  },
  SET_ROLE(state, role) {
    state.role = role ?? null;
  },
};

export const getters = {
  instansiId(state) {
    return state.instansi_id;
  },

  loggedIn(state) {
    return state.isLogin;
  },
};

export const actions = {
  logout({ commit }) {
    commit('SET_LOGIN', false);
    return Promise.resolve(true);
  },

  cekEmail({}, email) {
    return $get
      .get(`/psp/registrasi-instruktur/email`, {
        params: { email: email },
      })
      .then(({ data }) => data);
  },

  daftar({}, params) {
    return $http.post('psp/registrasi-instruktur/create', params).then(({ data }) => data);
  },

  fetchAktivasi({}, token) {
    return $get.get(`/psp/aktivasi-instruktur`, {
      params: { token: token },
    });
  },

  aktivasi({}, params) {
    return $http.post(`psp/aktivasi-instruktur/save`, params).then(({ data }) => data);
  },

  reaktivasi({}, params) {
    return $http.post(`psp/registrasi-instruktur/resend-email`, params).then(({ data }) => data);
  },

  setInstansiId({ commit }, id) {
    commit('SET_INSTANSI_ID', id);
    return Promise.resolve(true);
  },

  // commit, dispatch, rootState
  async checkUser({ commit, dispatch }) {
    // sudah pernah login
    if (state && state.isLogin) {
      const role = state?.akun && isObject(state?.akun) ? 'instansi' : 'gtk';
      const done = await dispatch('setRole', role);
      return Promise.resolve(done);
    }

    const resp = await graphqlClient.query({
      query: gql`
        query GetAkun {
          me {
            ... on Akun {
              __typename
              akun_id
              namaAkun: nama
            }
            ... on Ptk {
              __typename
              ptk_id
              namaPtk: nama
            }
          }
          instansisAkun(first: 1) {
            data {
              instansi_id
              nama
            }
          }
        }
      `,
    });

    const { me, instansisAkun } = (resp && resp.data) || {};
    const isLogin = (state && state.isLogin) || isObject(me || {});

    // Error belum login
    if (!isLogin) return Promise.reject(new Error('User belum login!'));

    commit('SET_LOGIN', isLogin);
    commit('SET_AKUN', me && me.__typename === 'Akun' ? me : null);
    commit('SET_PTK', me && me.__typename === 'Ptk' ? me : null);
    const role = me && me.__typename === 'Akun' ? 'instansi' : 'gtk';
    if (role === 'instansi') commit('SET_INSTANSI_ID', getDeepObj(instansisAkun, 'data.0.instansi_id') || 800001);

    const done = await dispatch('setRole', role);
    return Promise.resolve(done);
  },

  async setRole({ commit }, role) {
    commit('SET_ROLE', role);
    await regModules(role);
    return Promise.resolve(true);
  },

  async userPreferensi({ dispatch }, id) {
    let preferensi;

    if (id) await dispatch('setInstansiId', id);

    try {
      preferensi = await dispatch('preferensi/getPreferensi', null, {
        root: true,
      });
    } catch (err) {
      return Promise.reject(new Error('Mohon maaf, Data Preferensi Anda gagal dimuat!'));
    }
    return Promise.resolve(preferensi);
  },
};

async function regModules(role) {
  const modules = await import(`./${role}`);

  // hapus module yang teregister agar tidak duplicate
  for (let key in modules.default) {
    if (store && store.state && store.state[key]) {
      store.unregisterModule(key);
    }
  }
  // register module dynamically
  for (let key in modules.default) {
    if (!(store && store.state && store.state[key])) {
      store.registerModule(key, modules.default[key]);
    }
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  getters,
  actions,
};
