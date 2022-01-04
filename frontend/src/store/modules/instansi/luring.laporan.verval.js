/* eslint-disable no-empty-pattern */
import kitsu from '@plugins/kitsu';
import http from '@plugins/axios';
import { queryString } from '@utils/format';
let $ajax;

export const actions = {
  async fetch({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/luring/kelas-laporan`,
    });
    return await $ajax.get('/', { params: payload.params });
  },

  async getDetail({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/luring/kelas-laporan/${payload.id}`,
    });
    return await $ajax.get('/');
  },

  async getDetailPeserta({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL:
        process.env.VUE_APP_API_URL + `/i/${id}/luring/kelas-laporan/${payload.id}/peserta/${payload.peserta_id}`,
    });
    return await $ajax.get('/');
  },

  action({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/luring/kelas-laporan/${payload.id}${payload && payload.type ? '/' + payload.type : ''}`;
    return http[payload.method || 'post'](url, payload.params).then(({ data }) => data);
  },

  async getListKelas({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/luring/kelas-laporan/${payload.id}/${payload.tipe}`,
    });
    return await $ajax.get(`/`, { params: payload.params });
  },

  async getDetailPesertaNilai({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL:
        process.env.VUE_APP_API_URL + `/i/${id}/luring/kelas-laporan/${payload.kelas_id}/peserta/${payload.id}/nilai`,
    });
    return await $ajax.get(`/`);
  },

  downloadList({ rootState, state }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `${process.env.VUE_APP_API_URL}/i/${id}/${state.jenis}kelas/${payload.url}?${queryString(
      payload.params
    )}`;
    return Promise.resolve(url);
  },
};
