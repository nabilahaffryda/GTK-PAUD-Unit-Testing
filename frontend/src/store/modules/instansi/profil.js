/* eslint-disable no-empty-pattern */
import kitsu from '@plugins/kitsu';
import http from '@plugins/axios';
let $ajax;

export const actions = {
  async fetch({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/${payload.jenis}/profil`,
    });
    return await $ajax.get('/', payload.params);
  },
  update({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/${payload.jenis}/profil/${payload.id}/update`;
    return http.post(url, payload.params).then(({ data }) => data);
  },
  async getBerkas({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/${payload.jenis}/profil/${payload.id}/berkas`,
    });
    return await $ajax.get('/', payload.params);
  },
  setBerkas({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `/i/${id}/${payload.jenis}/profil/${payload.id}/berkas/update`;
    return http.post(url, payload.params).then(({ data }) => data);
  },
};
