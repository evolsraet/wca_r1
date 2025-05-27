import { createStore } from 'vuex'
import auth from './auth'
import lang from './lang'
import cancelAttempted from './cancelAttempted'
// import lib_storage from '../store/lib_storage'
import enums from './enums'
import fields from './fields'

const store = createStore({
  state: {
    commercialName: localStorage.getItem('commercialName') || '위카'
  },
  mutations: {
    setCommercialName(state, name) {
      state.commercialName = name
      localStorage.setItem('commercialName', name)
    }
  },
  modules: {
    auth,
    lang,
    cancelAttempted,
    // lib_storage,
    enums,
    fields
  }
})

export default store