/* eslint-disable no-empty-pattern */
import kitsu from '@plugins/kitsu';
import http from '@plugins/axios';
let $ajax;

export const state = {
  jenis: null,
};

export const mutations = {
  SET_JENIS(state, newValue) {
    state.jenis = newValue;
  },
};

export const actions = {
  async fetch({ rootState, commit }, payload) {
    const jenis = {
      daring: '',
      luring: '-luring',
    };
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas${jenis[payload.attr.jenis]}/kelas`,
    });
    commit('SET_JENIS', jenis[payload.attr.jenis]);
    return await $ajax.get('/', { params: payload.params });
  },

  async fetchPeserta({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas-luring/kelas/${payload?.attr?.id ?? payload.id}/nilai`,
    });
    return await $ajax.get('/', { params: payload.params });
  },

  async getDetail({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas${state.jenis}/kelas`,
    });
    return await $ajax.get(`/${payload.id}`);
  },

  async getDetailLuring({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas-luring/kelas`,
    });
    return await $ajax.get(`/${payload.id}`);
  },

  async getDetailPesertaNilai({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL:
        process.env.VUE_APP_API_URL +
        `/i/${id}/petugas-luring/kelas/${payload.kelas_id}${payload.page ? `/${payload.page}` : ''}/nilai`,
    });
    return await $ajax.get(`/${payload.id}`);
  },

  async getListKelas({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    const params = Object.assign({}, payload.params || {}, { page: payload.page || 1 });
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas${state.jenis}/kelas/${payload.id}/peserta`,
    });
    return await $ajax.get(`/`, { params });
  },

  action({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/petugas-luring/kelas/${payload.kelas_id}/nilai/${payload.id}/${payload.name}`;
    return http[payload.method || 'post'](url, payload.params).then(({ data }) => data);
  },

  async getListPesertaLaporan({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const params = Object.assign({}, payload.params || {}, { page: payload.page || 1 });
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas-${payload.jenis}/kelas/${payload.id}/laporan/peserta`,
    });
    return await $ajax.get(`/`, { params });
  },

  upload({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/petugas-${payload.jenis}/kelas/${payload.id}/laporan/unggah`;
    return http.post(url, payload.params, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
  },

  LaporanAction({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/petugas-${payload.jenis}/kelas/${payload.id}/laporan/${payload.aksi}`;
    return http[payload.method || 'post'](url, payload.params).then(({ data }) => data);
  },
};
