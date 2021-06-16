/* eslint-disable no-empty-pattern */
import kitsu from '@plugins/kitsu';
import http from '@plugins/axios';
import { queryString } from '@utils/format';
let $ajax;

export const actions = {
  async fetch({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/verval/${payload.attr.tipe}`,
    });
    return await $ajax.get('/', { params: payload.params });
  },

  async getDetail({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/verval/${payload.tipe}/${payload.id}`,
    });
    return await $ajax.get('/');
  },

  action({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/verval/${payload.jenis}/${payload.id}${payload && payload.type ? '/' + payload.type : ''}`;
    return http[payload.method || 'post'](url, payload.params).then(({ data }) => data);
  },

  downloadList({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `${process.env.VUE_APP_API_URL}/i/${id}/verval/${payload.jenis}/${payload.url}?${queryString(
      payload.params
    )}`;
    return Promise.resolve(url);
  },
};
