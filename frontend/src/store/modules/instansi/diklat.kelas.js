/* eslint-disable no-empty-pattern */
import http from '@plugins/axios';
import kitsu from '@plugins/kitsu';
let $ajax;

export const actions = {
  async fetch({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/diklat/${payload.attr.diklat_id}/kelas`,
    });
    return await $ajax.get('/', { params: payload.params });
  },

  async getDetail({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/diklat/${payload.diklat_id}/kelas`,
    });
    return await $ajax.get(`/${payload.id}`);
  },

  async getMapels({ rootState }) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/diklat/mapel-kelas`,
    });
    return await $ajax.get(`/`);
  },

  create({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `i/${id}/diklat/${payload.diklat_id}/kelas/create`;
    return http.post(url, payload.params).then(({ data }) => data);
  },

  update({ dispatch }, payload) {
    return dispatch('action', Object.assign({}, payload, { type: 'update' }));
  },

  action({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `i/${id}/diklat/${payload.diklat_id}/kelas/${payload.id}/${payload.type}`;
    return http[payload.method || 'post'](url, payload.params).then(({ data }) => data);
  },

  async getListKelas({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/diklat/${payload.diklat_id}/kelas/${payload.id}/${payload.tipe}`,
    });
    return await $ajax.get(`/`, { params: payload.params });
  },

  async getListKandidat({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/diklat/${payload.diklat_id}/kelas/${payload.id}/${payload.tipe}`,
    });

    return await $ajax.get(`/`, {
      params: Object.assign(payload.params, { page: payload.page || 1, filter: { keyword: payload.keyword || '' } }),
    });
  },
};
