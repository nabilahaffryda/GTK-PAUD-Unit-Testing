/* eslint-disable no-empty-pattern */
import http from '@plugins/axios';
import kitsu from '@plugins/kitsu';
let $ajax;

export const state = {
  jenis: null,
};

export const mutations = {
  SET_TIPE(state, newValue) {
    state.jenis = newValue;
  },
};

export const actions = {
  async fetch({ rootState, commit }, payload) {
    const id = rootState.auth.instansi_id;
    const tipe = {
      luring: 'luring/',
      daring: '',
    };
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/${tipe[payload.attr.jenis]}diklat`,
    });
    commit('SET_TIPE', tipe[payload.attr.jenis]);
    return await $ajax.get('/', { params: payload.params });
  },

  async getDetail({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/${state.jenis}diklat`,
    });
    return await $ajax.get(`/${payload.id}`);
  },

  async getPeriode({ rootState }) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/diklat/periode`,
    });
    return await $ajax.get(`/`);
  },

  create({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `i/${id}/${state.jenis}diklat/create`;
    return http.post(url, payload.params).then(({ data }) => data);
  },

  update({ dispatch }, payload) {
    return dispatch('action', Object.assign({}, payload, { type: 'update' }));
  },

  action({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `i/${id}/${state.jenis}diklat/${payload.id}/${payload.type}`;
    return http.post(url, payload.params).then(({ data }) => data);
  },
};
