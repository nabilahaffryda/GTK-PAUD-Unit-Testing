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
    const id = rootState.auth.instansi_id;
    const jenis = {
      daring: '',
      luring: '-luring',
    };
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas${jenis[payload]}/konfirmasi`,
    });

    commit('SET_JENIS', jenis[payload]);
    return await $ajax.get('/', {});
  },

  async getDetail({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas${state.jenis}/konfirmasi`,
    });
    return await $ajax.get(`/${payload.id}`);
  },

  actions({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `i/${id}/petugas${state.jenis}/konfirmasi/${payload.id}/${payload.name}`;
    return http.get(url).then(({ data }) => data);
  },
};
