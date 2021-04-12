'use strict';
import Vue from 'vue';
import Kitsu from 'kitsu';
import store from '@store';

let ajaxRequestCall = 0;
// before a request is made start the nprogress
export const requestResolve = (config) => {
  ajaxRequestCall++;
  if (store.state && store.state.autosave) {
    store.commit('SET_LOADING', false);
  } else {
    store.commit('SET_LOADING', true);
  }
  return config;
};

export const requestReject = (error) => {
  ajaxRequestCall = 0;
  store.commit('SET_LOADING', false);
  return Promise.reject(error);
};

export const responseResolve = (response) => {
  ajaxRequestCall--;
  // hide loader jika semua request selesai
  if (ajaxRequestCall === 0) {
    store.commit('SET_LOADING', false);
  }
  // Do something with response data
  return response;
};
export const responseReject = (error) => {
  ajaxRequestCall = 0;
  store.commit('SET_LOADING', false);

  // cek status error
  const { status = 500 } = error.response ? error.response : {};
  let errorMessage = `Maaf!, Ada kesalahan pada data yang membuat aplikasi tidak berjalan.<br/>Silakan memuat ulang halaman`;
  let conectionLost = `Terjadi kegagalan koneksi, pastikan Anda terhubung internet.<br/>Silakan mencoba kembali.`;
  let message =
    (error.response && error.response.data && error.response.data.title) ||
    (status === 500 ? errorMessage : conectionLost);

  const statusForNotify = [400, 401, 403, 404, 405, 422, 500, 501, 502, 503, 504, 520, 521];

  if (statusForNotify.includes(status)) {
    if (status === 403 && error.response && error.response.data && error.response.data.redirect)
      return (window.location = error.response.data && error.response.data.redirect);

    if (status !== 401) {
      Vue.prototype.$error(message || (status === 500 ? errorMessage : conectionLost));
    } else {
      if (store.getters['auth/loggedIn']) {
        Vue.prototype.$error('Mohon maaf, sesi Anda telah habis. Silakan melakukan relogin!', [
          {
            label: 'Login',
            event: () => {
              // 401 redirect ke auth login
              store.dispatch('auth/logout').then(() => {
                window.location.href = process.env.VUE_APP_API_URL + `/auth/logout`;
              });
            },
          },
        ]);
        return;
      }
    }
  }

  // Do something with response error
  return Promise.reject(message || (status === 500 ? errorMessage : conectionLost));
};

let defaultConfig = {
  // baseURL: process.env.baseURL || process.env.apiUrl || ""
  baseURL: process.env.VUE_APP_API_URL,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
  pluralize: false,
  axiosOptions: {
    withCredentials: true,
  },
};

export default (config = {}) => {
  const api = new Kitsu(Object.assign({}, defaultConfig, config));

  // Add a response interceptor
  api.interceptors.request.use(requestResolve, requestReject);
  api.interceptors.response.use(responseResolve, responseReject);

  return api;
};
