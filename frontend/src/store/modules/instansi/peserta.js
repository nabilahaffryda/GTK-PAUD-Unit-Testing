/* eslint-disable no-empty-pattern */
import kitsu from '@plugins/kitsu';
import http from '@plugins/axios';
let $ajax;

export const actions = {
  async fetch({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/peserta/nonptk`,
    });
    return await $ajax.get('/', { params: payload.params });
  },

  async getDetail({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/peserta/nonptk`,
    });
    return await $ajax.get(`/${payload.id}`);
  },

  create({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/peserta/nonptk/create`;
    return http
      .post(url, payload.params, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      .then(({ data }) => data);
  },

  update({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/peserta/nonptk/${payload.id}/update`;
    return http
      .post(url, payload.params, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      .then(({ data }) => data);
  },

  delete({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/peserta/nonptk/${payload.id}/delete`;
    return http.post(url, payload.params).then(({ data }) => data);
  },
};
