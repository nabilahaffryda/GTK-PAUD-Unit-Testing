import kitsu from '@plugins/kitsu';
import gql from 'graphql-tag';
import graphqlClient from '@plugins/graphql';

let $ajax;
/* eslint-disable no-empty-pattern */
export const state = {
  data: null,
};

export const mutations = {
  SET_DATA(state, newValue) {
    state.data = newValue ?? null;
  },
};

export const getters = {
  akseses(state) {
    const akses = Object.keys(state?.data?.akses ?? {});
    return akses || [];
  },
};

export const actions = {
  async getPreferensi({ commit, rootState }, reset) {
    if (state.data && !reset) return Promise.resolve(state.data);
    const id = rootState.auth.instansi_id;
    console.log(id)
    const response = await graphqlClient.query({
      query: gql`query GetPreferensi($instansi_id: Int!) {
          preferensi(instansi_id: $instansi_id) {
            groups {
              k_group
              keterangan
            }
            akseses {
              akses
              is_aktif
            }
            aktivasi
            konfig {
              simpkb
            }
          }
        }
      `,
      variables: { instansi_id: Number(id) },
    });

    console.log(response)
    commit('SET_DATA', response ?? null);
    return true
  },

  async getInstansi({ rootState }, payload) {
    const id = rootState.auth.instansi_id;
    $ajax = kitsu({
      baseURL: process.env.VUE_APP_API_URL + `/i/${id}`,
    });
    return await $ajax.get('/instansi', { params: payload.params });
  },
};
