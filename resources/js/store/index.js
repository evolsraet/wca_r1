import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import auth from '../store/auth'
import lang from '../store/lang'
import cancelAttempted from './cancelAttempted';
//import lib_storage from '../store/lib_storage' 

const store = createStore({
    state: {
        commercialName: '위카' // 초기 상업명 설정
      },
    plugins:[
        createPersistedState()
    ],
    modules:{
        auth,
        lang,
        cancelAttempted,
        //lib_storage, //저장소 라이브러리 명
    }
})

export default store
