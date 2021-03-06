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
    const mastersRequest = (payload.name || '').split(';') || [];
    const mastersForRequest = mastersRequest.filter((item) => masters.indexOf(item) === -1);

    // remove filter jika ada di state
    for (let i = 0; i < mastersRequest.length; i++) {
      if (masters.includes(mastersRequest[i])) {
        delete payload.filter[i];
      }
    }

    if (mastersForRequest && mastersForRequest.length) {
      const data = await http
        .get(`/i/${id}/master?${queryString({ name: mastersForRequest, filter: payload.filter })}`)
        .then(({ data }) => data);

      let master = {};
      mastersForRequest.forEach((key, index) => {
        master[key] = data['data'][index];
      });

      commit('SET_MASTERS', master);
    }

    return state.masters;
  },

  async fetchMasters({ state, commit, rootState }, payload) {
    const id = rootState.auth.instansi_id;
    const mastersRequest = (payload.name || '').split(';') || [];

    if (mastersRequest.length) {
      const data = await http
        .get(`/i/${id}/master?${queryString({ name: mastersRequest, filter: payload.filter })}`)
        .then(({ data }) => data);

      let master = {};
      mastersRequest.forEach((key, index) => {
        master[key] = data['data'][index];
      });

      commit('SET_MASTERS', master);
    }

    return state.masters;
  },
};
