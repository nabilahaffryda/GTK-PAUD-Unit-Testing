/* eslint-disable no-empty-pattern */
import kitsu from '@plugins/kitsu';
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

  async getDetail({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas${state.jenis}/kelas`,
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
};
