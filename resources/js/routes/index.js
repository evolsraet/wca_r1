import { createRouter, createWebHistory } from "vue-router";
import routes from './routes.js'
import store from '../store';
const router = createRouter({
    history: createWebHistory(),
    routes
})

/*router.beforeEach((to, from, next) => {

    if (store.getters.user) {
        if (to.matched.some(route => route.meta.guard === 'guest')) next({ name: 'home' })
        else next();

    } else {
        if (to.matched.some(route => route.meta.guard === 'auth')) next({ name: 'login' })
        else next();
    }
})*/

router.beforeEach((to, from, next) => {
    const isAuthenticated = store.getters['auth/isAuthenticated'];
    if (to.meta.requiresAuth && !isAuthenticated) {
      next({ name: 'auth.login' }); // 로그인 페이지로 이동
    } else {
      next();
    }
});
  
export default router;
