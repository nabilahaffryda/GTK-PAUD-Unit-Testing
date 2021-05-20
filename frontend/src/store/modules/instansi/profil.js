/* eslint-disable no-empty-pattern */
import kitsu from '@plugins/kitsu';
import http from '@plugins/axios';
let $ajax;

export const actions = {
  async fetch({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas/profil`,
    });
    return await $ajax.get('/', { params: payload.params });
  },
  update({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/petugas/profil/${payload.id}${payload.jenis === 'diklat' ? '/diklat' : ''}/update`;
    return http
      .post(url, payload.params, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      .then(({ data }) => data);
  },
  async getDiklat({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas/profil/${payload.id}/diklat`,
    });
    return await $ajax.get('/', { params: payload.params });
  },
  async getBerkas({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas/profil/${payload.id}/berkas`,
    });
    return await $ajax.get('/', { params: payload.params });
  },
  setBerkas({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/petugas/profil/${payload.id}/berkas/create`;
    return http.post(url, payload.params).then(({ data }) => data);
  },
  ajuan({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/${payload.jenis === 'lpd' ? payload.jenis : 'petugas'}/${
      payload.jenis === 'lpd' ? '' : 'profil/'
    }${payload.id}/ajuan/create`;
    return http.post(url, payload.params).then(({ data }) => data);
  },
  batalAjuan({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/${payload.jenis === 'lpd' ? payload.jenis : 'petugas'}/${
      payload.jenis === 'lpd' ? '' : 'profil/'
    }${payload.id}/ajuan/delete`;
    return http.post(url, payload.params).then(({ data }) => data);
  },
};
