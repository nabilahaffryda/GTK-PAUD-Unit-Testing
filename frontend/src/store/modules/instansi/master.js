import http from '@plugins/axios';
import { queryString } from '@utils/format';

export const state = {
  masters: null,
};

export const mutations = {
  SET_MASTERS(state, newValue) {
    state.masters = Object.assign({}, state.masters, newValue);
  },
};

export const actions = {
  async getMasters({ state, commit, rootState }, payload) {
    // check masters on state
    const id = rootState.auth.instansi_id;
    const masters = (state.masters && Object.keys(state.masters)) || [];
    const mastersRequest = (payload || '').split(';') || [];
    const mastersForRequest = mastersRequest.filter((item) => masters.indexOf(item) === -1);
    if (mastersForRequest && mastersForRequest.length) {
      const data = await http
        .get(`/i/${id}/master?${queryString({ name: mastersForRequest })}`)
        .then(({ data }) => data);

      let master = {};
      mastersForRequest.forEach((key, index) => {
        master[key] = data['data'][index];
      });

      commit('SET_MASTERS', master);
    }

    return state.masters;
  },
};
