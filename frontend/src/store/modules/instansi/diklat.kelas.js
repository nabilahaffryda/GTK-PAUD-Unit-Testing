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
      baseURL:
        process.env.VUE_APP_API_URL + `/i/${id}/${tipe[payload.attr.jenis]}diklat/${payload.attr.diklat_id}/kelas`,
    });
    commit('SET_TIPE', tipe[payload.attr.jenis]);
    return await $ajax.get('/', { params: payload.params });
  },

  async getDetail({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/${state.jenis}diklat/${payload.diklat_id}/kelas`,
    });
    return await $ajax.get(`/${payload.id}`);
  },

  async getMapels({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const tipe = {
      luring: 'luring/',
      daring: '',
    };
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/${tipe[payload]}diklat/mapel-kelas`,
    });
    return await $ajax.get(`/`);
  },

  create({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `i/${id}/${state.jenis}diklat/${payload.diklat_id}/kelas/create`;
    return http.post(url, payload.params).then(({ data }) => data);
  },

  update({ dispatch }, payload) {
    return dispatch('action', Object.assign({}, payload, { type: 'update' }));
  },

  action({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `i/${id}/${state.jenis}diklat/${payload.diklat_id}/kelas/${payload.id}/${payload.type}`;
    return http[payload.method || 'post'](url, payload.params, payload.config || {}).then(({ data }) => data);
  },

  async getListKelas({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL:
        process.env.VUE_APP_API_URL +
        `/i/${id}/${state.jenis}diklat/${payload.diklat_id}/kelas/${payload.id}/${payload.tipe}`,
    });
    return await $ajax.get(`/`, { params: payload.params });
  },

  async getListKandidat({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL:
        process.env.VUE_APP_API_URL +
        `/i/${id}/${state.jenis}diklat/${payload.diklat_id}/kelas/${payload.id}/${payload.tipe}`,
    });

    let params = Object.assign({}, payload.params, { page: payload.page || 1 });

    if (payload.keyword) params['keyword'] = payload.keyword;

    return await $ajax.get(`/`, {
      params: params,
    });
  },
};
