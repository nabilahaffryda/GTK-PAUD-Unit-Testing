/* eslint-disable no-empty-pattern */
import http from '@plugins/axios';
import kitsu from '@plugins/kitsu';
let $ajax;

export const actions = {
  async fetch({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/lpd`,
    });
    return await $ajax.get('/', { params: payload.params });
  },

  async getDetail({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/lpd`,
    });
    return await $ajax.get(`/${payload.id}`);
  },

  create({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `i/${id}/lpd/create`;
    return http.post(url, payload.params).then(({ data }) => data);
  },

  update({ dispatch }, payload) {
    return dispatch('action', Object.assign({}, payload, { type: 'update' }));
  },

  action({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `i/${id}/lpd/${payload.id}/${payload.type}`;
    return http.post(url, payload.params).then(({ data }) => data);
  },
};
