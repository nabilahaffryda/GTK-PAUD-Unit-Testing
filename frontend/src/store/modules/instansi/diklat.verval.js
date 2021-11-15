/* eslint-disable no-empty-pattern */
import kitsu from '@plugins/kitsu';
import http from '@plugins/axios';
import { queryString } from '@utils/format';
let $ajax;

export const actions = {
  async fetch({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/kelas`,
    });
    return await $ajax.get('/', { params: payload.params });
  },

  async getDetail({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/kelas/${payload.id}`,
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

  action({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/kelas/${payload.id}${payload && payload.type ? '/' + payload.type : ''}`;
    return http[payload.method || 'post'](url, payload.params).then(({ data }) => data);
  },

  async getListKelas({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/kelas/${payload.id}/${payload.tipe}`,
    });
    return await $ajax.get(`/`, { params: payload.params });
  },

  downloadList({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `${process.env.VUE_APP_API_URL}/i/${id}/verval/${payload.jenis}/${payload.url}?${queryString(
      payload.params
    )}`;
    return Promise.resolve(url);
  },
};
