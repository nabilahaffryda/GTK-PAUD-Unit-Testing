import gql from 'graphql-tag';
import graphqlClient from '@plugins/graphql';

/* eslint-disable no-empty-pattern */
export const state = {
  data: null,
};

export const mutations = {
  SET_DATA(state, newValue) {
    state.data = newValue ?? null;
  },
};

export const actions = {
  async getPreferensi({ commit }, reset) {
    if (state.data && !reset) return Promise.resolve(state.data);
    const resp = await graphqlClient.query({
      query: gql`
        query GetPreferensi {
          ptk {
            ptk_id
            nama
          }
          preferensiPtk {
            konfirmasiKesediaan
          }
        }
      `,
    });

    const { data } = resp;

    commit('SET_DATA', data ?? null);
    return Promise.resolve(data ?? null);
  },
};
