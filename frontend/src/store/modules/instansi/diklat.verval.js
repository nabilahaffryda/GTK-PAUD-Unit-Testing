/* eslint-disable no-empty-pattern */
import kitsu from '@plugins/kitsu';
import http from '@plugins/axios';
import { queryString } from '@utils/format';
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
    const mTipe = {
      daring: '',
      luring: 'luring/',
    };
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/${mTipe[payload.attr.type]}kelas`,
    });
    commit('SET_TIPE', mTipe[payload.attr.type]);
    return await $ajax.get('/', { params: payload.params });
  },

  async getDetail({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/${state.jenis}kelas/${payload.id}`,
    });
    return await $ajax.get('/');
  },

  async getDetailPeserta({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/luring/kelas/${payload.id}/peserta/${payload.peserta_id}`,
    });
    return await $ajax.get('/');
  },

  async getPeriode({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/kelas/periode`,
    });
    return await $ajax.get('/', { params: payload.params });
  },

  action({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/${state.jenis}kelas/${payload.id}${payload && payload.type ? '/' + payload.type : ''}`;
    return http[payload.method || 'post'](url, payload.params).then(({ data }) => data);
  },

  async getListKelas({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/${state.jenis}kelas/${payload.id}/${payload.tipe}`,
    });
    return await $ajax.get(`/`, { params: payload.params });
  },

  downloadList({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `${process.env.VUE_APP_API_URL}/i/${id}/${state.jenis}kelas/${payload.url}?${queryString(
      payload.params
    )}`;
    return Promise.resolve(url);
  },
};
