/* eslint-disable no-empty-pattern */
import kitsu from '@plugins/kitsu';
let $ajax;

export const actions = {
  async fetch({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas/kelas`,
    });
    return await $ajax.get('/', { params: payload.params });
  },

  async getDetail({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas/kelas`,
    });
    return await $ajax.get(`/${payload.id}`);
  },

  async getListKelas({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const params = Object.assign({}, payload.params || {}, { page: payload.page || 1 });
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas/kelas/${payload.id}/peserta`,
    });
    return await $ajax.get(`/`, { params });
  },
};
