/* eslint-disable no-empty-pattern */
import http from '@plugins/axios';
import kitsu from '@plugins/kitsu';
import { queryString } from '@utils/format';
let $ajax;

export const actions = {
  async fetch({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/admin`,
    });
    return await $ajax.get('/', { params: payload.params });
  },

  async getDetail({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/psp/i/${id}/admin`,
    });
    return await $ajax.get(`/${payload.id}`);
  },

  async lookup({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/psp/i/${id}/admin`,
    });
    return await $ajax.get(`/email/${payload}`).then(({ data }) => data);
  },

  async listGroups({ rootState }) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/psp/i/${id}/admin`,
    });
    return await $ajax.get('/groups').then(({ data }) => data);
  },

  create({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `psp/i/${id}/admin/create`;
    return http.post(url, payload.params).then(({ data }) => data);
  },

  update({ dispatch }, payload) {
    return dispatch('action', Object.assign({}, payload, { type: 'update' }));
  },

  action({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `psp/i/${id}/admin/${payload.id}/${payload.type}`;
    return http.post(url, payload.params).then(({ data }) => data);
  },

  templateUpload({ rootState }) {
    const id = rootState.auth.instansi_id;
    const url = `psp/i/${id}/admin/template`;
    return Promise.resolve(url);
  },

  upload({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `psp/i/${id}/admin/upload`;
    return http.post(url, payload.params, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
  },

  downloadList({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `${process.env.VUE_APP_API_URL}/psp/i/${id}/admin/${payload.url}?${queryString(payload.params)}`;
    return Promise.resolve(url);
  },
};
