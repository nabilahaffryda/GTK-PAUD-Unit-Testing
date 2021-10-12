import kitsu from '@plugins/kitsu';
import http from '@plugins/axios';
let $ajax;

export const actions = {
  async fetch({ rootState }) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas/konfirmasi`,
    });
    return await $ajax.get('/', {});
  },

  async getDetail({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}/petugas/konfirmasi`,
    });
    return await $ajax.get(`/${payload.id}`);
  },

  actions({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const url = `i/${id}/petugas/konfirmasi/${payload.id}/${payload.name}`;
    return http.get(url).then(({ data }) => data);
  },
};
