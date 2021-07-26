/* eslint-disable no-empty-pattern */
import http from '@plugins/axios';
import kitsu from '@plugins/kitsu';
let $ajax;

export const actions = {
  async fetch({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/periode`,
    });
    return await $ajax.get('/', { params: payload.params });
  },

  async getDetail({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/periode`,
    });
    return await $ajax.get(`/${payload.id}`);
  },

  create({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `i/${id}/periode/create`;
    return http.post(url, payload.params).then(({ data }) => data);
  },

  update({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `i/${id}/periode/${payload.id}/update`;
    return http.post(url, payload.params).then(({ data }) => data);
  },

  delete({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `i/${id}/periode/${payload.id}/delete`;
    return http.post(url).then(({ data }) => data);
  },
};
