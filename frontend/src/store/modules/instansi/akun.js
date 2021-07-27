/* eslint-disable no-empty-pattern */
import http from '@plugins/axios';
import kitsu from '@plugins/kitsu';
import { queryString } from '@utils/format';
let $ajax;

export const actions = {
  async fetch({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/akun/${payload.attr.tipe}`,
    });
    return await $ajax.get('/', { params: payload.params });
  },

  async getDetail({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/akun`,
    });
    return await $ajax.get(`/${payload.id}`);
  },

  async lookup({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/akun`,
    });
    return await $ajax.get(`/email/${payload}`).then(({ data }) => data);
  },

  async listGroups({ rootState }) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/akun`,
    });
    return await $ajax.get('/groups').then(({ data }) => data);
  },

  async listInstansis({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/lpd`,
    });
    return await $ajax.get('/', { params: payload.params }).then(({ data }) => data);
  },

  create({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/akun/${payload.name}/create`;
    return http.post(url, payload.params).then(({ data }) => data);
  },

  update({ dispatch }, payload) {
    return dispatch('action', Object.assign({}, payload, { type: 'update' }));
  },

  setStatus({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/akun/${payload.name}/${payload.type}`;
    return http.post(url, payload.params).then(({ data }) => data);
  },

  action({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/akun/${payload.name}/${payload.id}/${payload.type}`;
    return http.post(url, payload.params).then(({ data }) => data);
  },

  templateUpload({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `${process.env.VUE_APP_API_URL}/i/${id}/akun/${payload.tipe}/template`;
    return Promise.resolve(url);
  },

  upload({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/akun/${payload.tipe}/upload`;
    return http.post(url, payload.params, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
  },

  downloadList({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `${process.env.VUE_APP_API_URL}/i/${id}/akun/${payload.tipe}/${payload.url}?${queryString(
      payload.params
    )}`;
    return Promise.resolve(url);
  },
};
