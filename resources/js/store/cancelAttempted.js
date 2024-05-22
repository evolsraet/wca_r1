const state = {
    cancelAttempted: {},
  };
  
  const getters = {
    getCancelAttempted: (state) => (auctionId) => {
      return state.cancelAttempted[auctionId] !== undefined ? state.cancelAttempted[auctionId] : 1;
    },
  };
  
  const actions = {
    setCancelAttempted({ commit }, { auctionId, value }) {
      commit('SET_CANCEL_ATTEMPTED', { auctionId, value });
    },
  };
  
  const mutations = {
    SET_CANCEL_ATTEMPTED(state, { auctionId, value }) {
      state.cancelAttempted = { ...state.cancelAttempted, [auctionId]: value };
    },
  };
  
  export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
  };
  