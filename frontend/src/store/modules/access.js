/* eslint-disable no-empty-pattern */
import http from '@plugins/axios';
import { queryString } from '@utils/format';

export const actions = {
  getGroup({}, params) {
    return http.get(`psp/akses/groups?${queryString(params)}`).then(({ data }) => data);
  },

  async fetch({}, params) {
    let detail;
    try {
      detail = await http.get(`psp/akses?${queryString(params)}`).then(({ data }) => data);
    } catch (e) {
      detail = null;
    }
    return detail;
  },

  download({}, params) {
    return Promise.resolve(process.env.VUE_APP_API_URL + `/psp/akses/download?${queryString(params)}`);
  },

  async save({}, payload) {
    return await http.post(`psp/akses/save`, payload);
  },

  async saveAktif({}, payload) {
    return await http.post(`psp/akses/save-aktif`, payload);
  },

  async saveTutup({}, payload) {
    return await http.post(`psp/akses/save-tutup`, payload);
  },
};

export default {
  namespaced: true,
  actions,
};
