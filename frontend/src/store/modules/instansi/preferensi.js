import http from '@plugins/axios';
import kitsu from '@plugins/kitsu';
let $ajax;
/* eslint-disable no-empty-pattern */
export const state = {
  data: null,
};

export const mutations = {
  SET_DATA(state, newValue) {
    state.data = newValue ?? null;
  },
};

export const getters = {
  akseses(state) {
    const akses = Object.keys(state?.data?.akses ?? {});
    return akses || [];
  },
};

export const actions = {
  async getPreferensi({ commit, rootState }, reset) {
    if (state.data && !reset) return Promise.resolve(state.data);
    const id = rootState.auth.instansi_id;
    const responses = await http.get(`i/${id}/preferensi`, { params: {} }).then(({ data }) => data);
    commit('SET_DATA', responses ?? null);
    return Promise.resolve(responses ?? null);
  },

  async getInstansi({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/psp/i/${id}/preferensi`,
    });
    return await $ajax.get('/instansi', { params: payload.params });
  },
};
