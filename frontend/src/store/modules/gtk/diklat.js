import graphqlClient from '@plugins/graphql';
import graphQuery from '../../../graphql/kelas';
import { getDeepObj } from '../../../utils/format';

export const actions = {
  async fetch() {
    const resp = await graphqlClient.query({
      query: graphQuery['LIST_KONFIRMASI'],
    });

    const { data } = resp;
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
  async getListKelas({}, payload) {
    const resp = await graphqlClient.query({
      query: graphQuery['KELAS'],
      variables: {
        page: payload.params.page,
        keyword: getDeepObj(payload, 'params.filter.keyword') || '',
      },
    });

    const { data } = resp;
    return Promise.resolve(data ?? null);
  },

  // eslint-disable-next-line no-empty-pattern
  async actions({}, payload) {
    const resp = await graphqlClient.mutate({
      mutation: graphQuery[payload.name.toUpperCase()],
      variables: {
        kelasPesertaId: payload.id,
      },
    });

    const { data } = resp;
    return Promise.resolve(data ?? null);
  },
};
