/**
 * TODO:딜러 권한 부여서 메인으로 안남어가는 현상(auth.js 로그인 부분과 충돌)
 * 
 */
import Cookies from 'js-cookie'
import store from "../store";

const AuthenticatedLayout = () => import('../layouts/Authenticated.vue')
const GuestLayout = ()  => import('../layouts/Guest.vue');

const PostsIndex  = ()  => import('../views/admin/posts/Index.vue');
const PostsCreate  = ()  => import('../views/admin/posts/Create.vue');
const PostsEdit  = ()  => import('../views/admin/posts/Edit.vue');

function requireLogin(to, from, next) {
    let isLogin = false;
    isLogin = !!store.state.auth.authenticated;
    console.log(1111);
    console.log(store.state.auth.user);
    if (isLogin) {
        next()
    } else {
        next('/login')
    }
}

function guest(to, from, next) {
    let isLogin;
    isLogin = !!store.state.auth.authenticated;

    if (isLogin) {
        next('/')
    } else {
        next()
    }
}
function requireRole(role) {
    return function (to, from, next) {
        let isLogin = !!store.state.auth.authenticated;
        let hasRole = store.state.auth.user && store.state.auth.user.roles.includes(role);

        if (isLogin && hasRole) {
            next();
        } else {
            next('/login');  
        }
    };
}
function requireAct(act) {
    return function (to, from, next) {
        if(act != null && act && act != undefined ) {
            const isLogin = store.state.auth.authenticated && store.state.auth.user;
            if(!isLogin) {
                next('/login');
            } else {
                const userActs = store.state.auth.user.act;
                console.log(userActs);
                let canProceed = false;
                userActs.forEach(element => {
                    if(act.includes(element)) {
                        canProceed = true;
                       // console.log('canProceed : ' + element);
                    } else {
                       // console.log('notProceed : ' + element);
                    }
                });
                if(canProceed) next();
                else next('/');
            }
        } else {
            next();
        }

    };
}

//url 추후에 리네임 
export default [
    {
        path: '/',
        // redirect: { name: 'login' },
        component: GuestLayout,
        children: [
            {
                path: '/',
                name: 'home',
                component: () => import('../views/home/index.vue'),

            },
            {
                path: '/auction/:id',
                name: 'AuctionDetail',
                component: () => import('../views/auction/Details/details.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.dealer','act.user']),
            },        
            {
                path: '/sell',
                name: 'sell',
                component: () => import('../views/auction/sell/index.vue'),

            },
            {
                path: '/selldt',
                name: 'selldt',
                component: () => import('../views/auction/sell/Details.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.user']),
            },
            {
                path: '/selldt2',
                name: 'selldt2',
                component: () => import('../views/auction/sell/AuctionEntry.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.user']),
            },
            {
                path: '/updateinfo',
                name: 'sell.update-info',
                component: () => import('../views/auction/sell/Update.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.user']),
            },
            {
                path: 'posts',
                name: 'public-posts.index',
                component: () => import('../views/posts/index.vue'),
            },
            {
                path: '/auction',
                name: 'auction.index',
                component: () => import('../views/auction/index.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.dealer','act.user']),
              
            },
            {
                path: '/dealerbid',
                name: 'autction.dealerbid',
                component: () => import('../views/dealer/auction/index.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.dealer']),
              
            },
            {
                path: '/dealermodal',
                name: 'dealermodal',
                component: () => import('../views/modal/auction/connectDealer.vue'),
              
            },
            {
                path: 'posts/:id',
                name: 'public-posts.details',
                component: () => import('../views/posts/details.vue'),
            },
            {
                path: 'category/:id',
                name: 'category-posts.index',
                component: () => import('../views/category/posts.vue'),
            },
            {
                path: '/list-do',
                name: 'user.review',
                component: () => import('../views/bbs/review.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.user']),
            },
            {
                path: '/view-do/:id',
                name: 'user.create-review',
                component: () => import('../views/bbs/Create.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.user']),
            },
            {
                path: '/edit-do/:id',
                name: 'user.edit-review',
                component: () => import('../views/bbs/Edit.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.user']),
            },
            {
                path: '/alllist-do',
                name: 'index.allreview',
                component: () => import('../views/bbs/allreview.vue'),
            },
            {
                path: '/introduce',
                name: 'index.introduce',
                component: () => import('../views/home/intro.vue'),
            },
            
            {
                path: '/dealer',
                name: 'dealer.index',
                component: () => import('../views/dealer/main.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.dealer']),
            },
            {
                path: '/profile',
                name: 'dealer.profile',
                component: () => import('../views/dealer/profile/index.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.dealer']),
            },
            {
                path: '/dealerbids',
                name: 'dealer.bids',
                component: () => import('../views/dealer/dealer.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.dealer']),
            },
            {
                path: '/bidhistory',
                name: 'dealer.bidList',
                component: () => import('../views/dealer/history/bidList.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.dealer']),
            },
            {
                path: '/notices',
                name: 'index.notices',
                component: () => import('../views/notices/Notices.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.dealer']),
            },
            {
                path: '/claim',
                name: 'index.claim',
                component: () => import('../views/notices/claim.vue'),
                beforeEnter: requireAct(['act.super','act.admin','act.dealer']),
            },
            {
                path: 'login',
                name: 'auth.login',
                component: () => import('../views/login/Login.vue'),
                beforeEnter: guest,
            },
            {
                path: 'register',
                name: 'auth.register',
                component: () => import('../views/register/index.vue'),
                beforeEnter: guest,
            },
            {
                path: 'forgot-password',
                name: 'auth.forgot-password',
                component: () => import('../views/auth/passwords/Email.vue'),
                beforeEnter: guest,
            },
            {
                path: 'reset-password/:token',
                name: 'auth.reset-password',
                component: () => import('../views/auth/passwords/Reset.vue'),
                beforeEnter: guest,
            },
        ]
    },
    {
        path: '/admin',
        component: AuthenticatedLayout,
        // redirect: {
        //     name: 'admin.index'
        // },
        beforeEnter: requireLogin,
        children: [
            {
                name: 'admin.index',
                path: '',
                component: () => import('../views/admin/index.vue'),
                meta: { breadCrumb: 'Admin' }
            },
            {
                name: 'profile.index',
                path: 'profile',
                component: () => import('../views/admin/profile/index.vue'),
                meta: { breadCrumb: 'Profile' }
            },
            {
                name: 'posts.index',
                path: 'posts',
                component: PostsIndex,
                meta: { breadCrumb: 'Posts' }
            },
            {
                name: 'posts.create',
                path: 'posts/create',
                component: PostsCreate,
                meta: { breadCrumb: 'Add new post' }
            },
            {
                name: 'posts.edit',
                path: 'posts/edit/:id',
                component: PostsEdit,
                meta: { breadCrumb: 'Edit post' }
            },
            {
                name: 'categories.index',
                path: 'categories',
                component: () => import('../views/admin/categories/Index.vue'),
                meta: { breadCrumb: 'Categories' }
            },
            {
                name: 'categories.create',
                path: 'categories/create',
                component: () => import('../views/admin/categories/Create.vue'),
                meta: { breadCrumb: 'Add new category' }
            },
            {
                name: 'categories.edit',
                path: 'categories/edit/:id',
                component: () => import('../views/admin/categories/Edit.vue'),
                meta: { breadCrumb: 'Edit Category' }
            },
            {
                name: 'permissions.index',
                path: 'permissions',
                component: () => import('../views/admin/permissions/Index.vue'),
                meta: { breadCrumb: 'Permissions' }
            },
            {
                name: 'permissions.create',
                path: 'permissions/create',
                component: () => import('../views/admin/permissions/Create.vue'),
                meta: { breadCrumb: 'Create Permission' }
            },
            {
                name: 'permissions.edit',
                path: 'permissions/edit/:id',
                component: () => import('../views/admin/permissions/Edit.vue'),
                meta: { breadCrumb: 'Permission Edit' }
            },
            {
                name: 'roles.index',
                path: 'roles',
                component: () => import('../views/admin/roles/Index.vue'),
                meta: { breadCrumb: 'Roles' }
            },
            {
                name: 'roles.create',
                path: 'roles/create',
                component: () => import('../views/admin/roles/Create.vue'),
                meta: { breadCrumb: 'Create Role' }
            },
            {
                name: 'roles.edit',
                path: 'roles/edit/:id',
                component: () => import('../views/admin/roles/Edit.vue'),
                meta: { breadCrumb: 'Role Edit' }
            },
            {
                name: 'users.index',
                path: 'users',
                component: () => import('../views/admin/users/Index.vue'),
                meta: { breadCrumb: 'Users' }
            },
            {
                name: 'users.create',
                path: 'users/create',
                component: () => import('../views/admin/users/Create.vue'),
                meta: { breadCrumb: 'Add New' }
            },
            {
                name: 'users.edit',
                path: 'users/edit/:id',
                component: () => import('../views/admin/users/Edit.vue'),
                meta: { breadCrumb: 'User Edit' }
            },
            {
                name: 'deposit.index',
                path: 'deposit',
                component: () => import('../views/admin/deposit/Index.vue'),
                meta: { breadCrumb: 'deposit' }
            },
            {
                name: 'auctions.index',
                path: 'auction',
                component: () => import('../views/admin/auction/Index.vue'),
                meta: { breadCrumb: 'auction' }
            },
            {
                name: 'auction.approve',
                path: 'auction/approve/:id',
                component: () => import('../views/admin/auction/approve.vue'),
                meta: { breadCrumb: 'auction approve' }
            },
        ]
    },
    {
        path: "/:pathMatch(.*)*",
        name: 'NotFound',
        component: () => import("../views/errors/404.vue"),
    },
    
];
