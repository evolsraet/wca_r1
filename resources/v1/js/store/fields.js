import { cmmn } from '@/hooks/cmmn';
const { wicac } = cmmn();

export default {
  namespaced: true,
  state: {
    data: {},
  },
  getters: {
    data(state) {
      return state.data;
    },
  },
  mutations: {
    SET_DATA(state, value) {
      state.data[value.key] = value.val;
    },
  },
  actions: {
    async getData({ commit }) {
      let loopLabel = ['auctions','users','dealers'];
      for (let label of loopLabel) {
        wicac.conn()
        .log()
        .url('/api/lib/fields/'+label)
        .callback(function(result) {
          commit("SET_DATA", {key:label, val:result.data});
        })
        .get();
      }
    },
  },
};
