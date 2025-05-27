import { cmmn } from '@/hooks/cmmn';
const { wicac } = cmmn();

export default {
  namespaced: true,
  state: {
    authenticated: false,
    user: {},
    errorMessage: '', 
  },
  getters: {
    authenticated(state) {
      return state.authenticated;
    },
    user(state) {
      return state.user;
    },
    errorMessage(state) {
      return state.errorMessage;
    },
  },
  mutations: {
    SET_AUTHENTICATED(state, value) {
      state.authenticated = value;
    },
    SET_USER(state, value) {
      state.user = value;
    },
    SET_USER_ACT(state, value) {
      state.user.act = value;
    },
    SET_ERROR_MESSAGE(state, value) {
      state.errorMessage = value;
    },
    CLEAR_ERROR_MESSAGE(state) {
      state.errorMessage = '';
    },
  },
  actions: {
    async login({ commit }, credentials) {
      commit('CLEAR_ERROR_MESSAGE');
      let loginForm = { 
        email: credentials.email,
        password: credentials.password ,
      }
      
      return wicac.conn()
      .url(`/v1/login`) //호출 URL
      .param(loginForm)
      .callback(function(result) {
          if(result.isError) {
            commit("SET_USER", '123123123');
            commit("SET_AUTHENTICATED", false);
            commit('SET_ERROR_MESSAGE', result.msg);
          } else {
            if(result.isSuccess) {
              commit("SET_USER", result.data.user);
              commit("SET_AUTHENTICATED", true);
            } else {
              commit("SET_USER", {});
              commit("SET_AUTHENTICATED", false);
              commit('SET_ERROR_MESSAGE', result.msg);
            }
          }
          return result; //결과값을 후 처리 할때 필수 선언
      })
      .post();
    },
    async getUser({ commit }) {
      return wicac.conn()
      .url('/api/users/me') //호출 URL
      .callback(function(result) {
          if(result.isError) {
            commit("SET_USER", {});
            commit("SET_AUTHENTICATED", false);
          } else {
            commit("SET_USER", result.data);
            commit("SET_AUTHENTICATED", true);
          }
      })
      .get();
    },
    async getAbilities({ commit }) {
      return wicac.conn()
      .url('/api/users/abilities') //호출 URL
      .callback(function(result) {
          if(result.isError) {
            commit("SET_USER_ACT", null);
          } else {
            commit("SET_USER_ACT", result.data);
          }
      })
      .get();
    },
    logout({ commit }) {
      localStorage.clear();
      commit("SET_USER", {});
      commit("SET_AUTHENTICATED", false);
      router.push({ name: "auth.login" });
    },

    async socialLogin({ commit }, { provider }) {
      try {
        const response = await fetch(`/auth/${provider}/redirect`, {
          method: 'GET',
          credentials: 'include',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        
        const data = await response.json();
        
        if (data.isSuccess) {
          commit('SET_USER', data.data.user);
          return {
            isError: false,
            isSuccess: true,
            data: data.data
          };
        }
        
        return {
          isError: true,
          isAlert: true,
          msg: data.msg || '로그인 처리 중 오류가 발생했습니다.'
        };
      } catch (error) {
        return {
          isError: true,
          isAlert: true,
          msg: '로그인 처리 중 오류가 발생했습니다.'
        };
      }
    }

  },
};
