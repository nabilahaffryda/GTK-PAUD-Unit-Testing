import graphqlClient from '@plugins/graphql';
import graphQuery from '../../../graphql/kelas';
import { getDeepObj } from '../../../utils/format';

export const state = {
  data: null,
};

export const mutations = {
  SET_DATA(state, newValue) {
    state.data = newValue;
  },
};

export const actions = {
  async fetch({ commit, rootState }) {
    rootState.loading = true;
    const resp = await graphqlClient.query({
      query: graphQuery['LIST_KONFIRMASI'],
    });

    const { data } = resp;
    commit('SET_DATA', data?.ptkListKelasPeserta?.data ?? []);
    rootState.loading = false;
    return Promise.resolve(data ?? null);
  },

  // eslint-disable-next-line no-empty-pattern
  async getDetail({}, payload) {
    const resp = await graphqlClient.query({
      query: graphQuery['DETAIL_KONFIRMASI'],
      variables: {
        id: payload.id,
      },
    });

    const { data } = resp;
    return Promise.resolve(data ?? null);
  },

  // eslint-disable-next-line no-empty-pattern
  async getListKelas({ rootState }, payload) {
    rootState.loading = true;
    const resp = await graphqlClient.query({
      query: graphQuery['KELAS'],
      variables: {
        page: payload.params.page,
        keyword: getDeepObj(payload, 'params.filter.keyword') || '',
      },
    });

    const { data } = resp;
    rootState.loading = false;
    return Promise.resolve(data ?? null);
  },

  // eslint-disable-next-line no-empty-pattern
  async actions({ state, commit, rootState }, payload) {
    rootState.loading = true;
    const mAction = {
      setuju: 'ptkKelasPesertaBersedia',
      tolak: 'ptkKelasPesertaTolak',
      reset: 'ptkKelasPesertaBatal',
    };

    const resp = await graphqlClient.mutate({
      mutation: graphQuery[payload.name.toUpperCase()],
      variables: {
        kelasPesertaId: payload.id,
      },
      refetchQueries: [
        () => {
          return {
            query: graphQuery['LIST_KONFIRMASI'],
          };
        },
      ],
      update: (store, { data }) => {
        const peserta = getDeepObj(data, `${mAction[payload.name]}`) || {};
        const write = store.readQuery({
          query: graphQuery['DETAIL_KONFIRMASI'],
          variables: {
            id: peserta.paud_kelas_peserta_id,
          },
        });

        store.writeQuery({
          query: graphQuery['DETAIL_KONFIRMASI'],
          variables: { id: peserta.paud_kelas_peserta_id },
          data: write,
        });
      },
    });

    const { data } = resp;

    const peserta = getDeepObj(data, `${mAction[payload.name]}`) || {};
    let kelas = [...state.data].map((key) => {
      if (key.paud_kelas_peserta_id === peserta.paud_kelas_peserta_id) {
        return peserta;
      }
    });

    commit('SET_DATA', kelas);
    rootState.loading = false;
    return Promise.resolve({ kelas, data } ?? null);
  },
};
