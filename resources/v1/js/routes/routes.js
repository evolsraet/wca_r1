import { createRouter, createWebHistory } from 'vue-router';
import store from "../store";
import Cookies from 'js-cookie';

const AuthenticatedLayout = () => import('../layouts/Authenticated.vue');
const GuestLayout = () => import('../layouts/Guest.vue');
import BoardLayout from '@/views/layout/BoardLayout.vue';

const PostsIndex = () => import('../views/admin/posts/PostsIndex.vue');
const PostsCreate = () => import('../views/admin/posts/PostsCreate.vue');
const PostsEdit = () => import('../views/admin/posts/PostsEdit.vue');


function guest(to, from, next) {
    let isLogin = !!store.state.auth.authenticated;
    if (isLogin) {
        next('/v1/');
    } else {
        next();
    }
}

function requireAct(act) {
    return function (to, from, next) {
        if (act != null && act && act != undefined) {
            const isLogin = store.state.auth.authenticated && store.state.auth.user;
            if (!isLogin) {
                next('/v1/login');
            } else {
                const userActs = store.state.auth.user.act;
                let canProceed = false;
                userActs.forEach(element => {
                    if (act.includes(element)) {
                        canProceed = true;
                    }
                });
                if (canProceed) next();
                else next('/v1/');
            }
        } else {
            next();
        }
    };
}

function takingVerificationHomeRole(next) {
    let isLogin = !!store.state.auth.authenticated;
    if (isLogin) {
        let isAdmin = store.state.auth.user && store.state.auth.user.roles.includes('admin');
        let isDealer = store.state.auth.user && store.state.auth.user.roles.includes('dealer');
        let isUser = store.state.auth.user && store.state.auth.user.roles.includes('user');
        if (isAdmin) {
            next('/admin');
        } else if (isDealer) {
            next('/dealer');
        } else if (isUser) {
            next();
        } else {
            next();
        }
    } else {
        next();
    }
}
async function determineLayout(to, from, next) {
    const isAdmin = store.state.auth.user && store.state.auth.user.act.includes('act.admin');
    if (isAdmin) {
        const component = await AuthenticatedLayout();
        to.matched[0].components.default = component.default;
    } else {
        const component = await GuestLayout();
        to.matched[0].components.default = component.default;
    }
    next();
}

// url 추후에 리네임 
export default [
    {
        path: '/v1/',
        component: GuestLayout,
        children: [
            {
                path: '/v1/',
                name: 'home',
                component: () => import('../views/home/index.vue'),
                beforeEnter: (to, from, next) => {
                    takingVerificationHomeRole(next);
                },
            },
            {
                path: '/v1/carfind-do',
                name: 'carfind',
                component: () => import('../views/home/index.vue'),
                beforeEnter: (to, from, next) => {
                    takingVerificationHomeRole(next);
                },
            },
            {
                path: '/v1/user',
                name: 'user.index',
                component: () => import('../views/user/index.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.user']),
            },
            {
                path: '/v1/auction/:id',
                name: 'AuctionDetail',
                component: () => import('../views/auction/Details/details.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.dealer', 'act.user']),
            },
            {
                path: '/v1/profiledt',
                name: 'profile',
                component: () => import('../views/profile/index.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.dealer', 'act.user']),
            },
            {
                path: '/v1/edit-profile',
                name: 'edit-profile',
                component: () => import('../views/profile/index.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.dealer', 'act.user']),
            },
            {
                path: '/v1/sell',
                name: 'sell',
                component: () => import('../views/auction/sell/index.vue'),
            },
            {
                path: '/v1/selldt',
                name: 'selldt',
                component: () => import('../views/auction/sell/Details.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.user']),
            },
            {
                path: '/v1/selldt2',
                name: 'selldt2',
                component: () => import('../views/auction/sell/AuctionEntry.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.user']),
            },
            {
                path: '/v1/updateinfo',
                name: 'sell.update-info',
                component: () => import('../views/auction/sell/Update.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.user']),
            },
            {
                path: '/v1/addr-create',
                name: 'addr.create',
                component: () => import('../views/dealer/addr/Create.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.dealer']),
            },
            {
                path: '/v1/addr-update/:id',
                name: 'addr.update',
                component: () => import('../views/dealer/addr/Update.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.dealer']),
            },
            {
                path: '/v1/addr',
                name: 'dealer.address',
                component: () => import('../views/dealer/addr/address.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.dealer']),
            },
            {
                path: '/v1/posts',
                name: 'public-posts.index',
                component: () => import('../views/posts/index.vue'),
            },
            {
                path: '/v1/auction',
                name: 'auction.index',
                component: () => import('../views/auction/index.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.dealer', 'act.user']),
            },
            {
                path: '/v1/dealerbid',
                name: 'autction.dealerbid',
                component: () => import('../views/dealer/auction/index.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.dealer']),
            },
            {
                path: '/v1/completion',
                name: 'AuctionCompletionPage',
                component: () => import('../views/consignment/consignment.vue'),
            },
            {
                path: '/v1/completionsuccess/:id',
                name: 'completionsuccess',
                component: () => import('../views/consignment/success.vue'),
            },
            {
                path: '/v1/dealermodal',
                name: 'dealermodal',
                component: () => import('../views/modal/auction/connectDealer.vue'),
            },
            {
                path: '/v1/posts/:id',
                name: 'public-posts.details',
                component: () => import('../views/posts/details.vue'),
            },
            {
                path: '/v1/category/:id',
                name: 'category-posts.index',
                component: () => import('../views/category/posts.vue'),
            },
            {
                path: '/v1/transferGuide-do',
                name: 'user.transferGuide',
                component: () => import('../views/guide/view.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.user']),
            },
            {
                path: '/v1/list-do',
                name: 'user.review',
                component: () => import('../views/bbs/review.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.user']),
            },
            {
                path: '/v1/view-do/:id',
                name: 'user.create-review',
                component: () => import('../views/bbs/Create.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.user']),
            },
            {
                path: '/v1/edit-do/:id',
                name: 'user.edit-review',
                component: () => import('../views/bbs/Edit.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.user']),
            },
            {
                path: '/v1/alllist-do',
                name: 'index.allreview',
                component: () => import('../views/bbs/allreview.vue'),
            },
            {
                path: '/v1/review/:id',
                name: 'user.review-detail',
                component: () => import('../views/bbs/Detail.vue'),
            },
            {
                path: '/v1/introduce',
                name: 'index.introduce',
                component: () => import('../views/home/intro.vue'),
            },
            {
                path: '/v1/dealer',
                name: 'dealer.index',
                component: () => import('../views/dealer/main.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.dealer']),
            },
            {
                path: '/v1/profile',
                name: 'dealer.profile',
                component: () => import('../views/dealer/profile/index.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.dealer']),
            },
            {
                path: '/v1/bidhistory',
                name: 'dealer.bidList',
                component: () => import('../views/dealer/history/bidList.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.dealer']),
            },
            {
                path: '/v1/searchbid',
                name: 'dealer.searchbid',
                component: () => import('../views/dealer/history/searchbid.vue'),
                beforeEnter: requireAct(['act.super', 'act.admin', 'act.dealer']),
            },
            {
                path: '/v1/login',
                name: 'auth.login',
                component: () => import('../views/login/Login.vue'),
            },
            {
                path: '/v1/register',
                name: 'auth.register',
                component: () => import('../views/register/index.vue'),
                beforeEnter: guest,
            },
            {
                path: '/v1/forgot-password',
                name: 'auth.forgot-password',
                component: () => import('../views/auth/passwords/Email.vue'),
                beforeEnter: guest,
            },
            {
                path: '/v1/reset-password/:token',
                name: 'auth.reset-password',
                component: () => import('../views/auth/passwords/Reset.vue'),
                beforeEnter: guest,
            },
            {
                path: '/v1/resetPasswordLogin/:code',
                name: 'auth.resetPasswordLogin',
                component: () => import('../views/auth/passwords/resetPasswordLogin.vue'),
            },
            {
                path: '/v1/board/:boardId',
                beforeEnter: determineLayout,
                children: [
                    {
                        path: '',
                        name: 'posts.index',
                        component: PostsIndex,
                        meta: { breadCrumb: 'Posts' }
                    },
                    {
                        path: 'create/:auctionId', 
                        name: 'posts.create.withAuctionId',
                        component: PostsCreate,
                        meta: { breadCrumb: 'Add new post' }
                      },
                    {
                        path: 'create',
                        name: 'posts.create',
                        component: PostsCreate,
                        meta: { breadCrumb: 'Add new post' }
                    },
                    {
                        path: 'edit/:id',
                        name: 'posts.edit',
                        component: PostsEdit,
                        meta: { breadCrumb: 'Edit post' }
                    },
                ]
            },
            {
                path: '/v1/terms',
                name: 'terms',
                component: () => import('../views/auction/terms.vue'),
            },
            {
                path: '/v1/privacy',
                name: 'privacy',
                component: () => import('../views/auction/privacy.vue'),
            },
                
        ]
    },
    {
        path: '/v1/admin',
        component: AuthenticatedLayout,
        beforeEnter: requireAct(['act.super', 'act.admin']),
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
                name: 'deposit.approve',
                path: 'deposit/approve/:id',
                component: () => import('../views/admin/deposit/approve.vue'),
                meta: { breadCrumb: 'deposit approve' }
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
            {
                name: 'review.approve',
                path: 'review/approve/:id',
                component: () => import('../views/admin/review/approve.vue'),
                meta: { breadCrumb: 'review approve' }
            },
            {
                name: 'review.index',
                path: 'review',
                component: () => import('../views/admin/review/Index.vue'),
                meta: { breadCrumb: 'review' }
            },
            {
                name: 'myinfo.edit',
                path: 'myinfo/edit',
                component: () => import('../views/admin/myInfo/Edit.vue'),
                meta: { breadCrumb: 'MyInfo Edit' }
            },
        ]
    },
    {
        path: "/v1/:pathMatch(.*)*",
        name: 'NotFound',
        component: () => import("../views/errors/404.vue"),
    },
];