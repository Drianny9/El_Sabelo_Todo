import { authStore } from "../store/auth";

const AuthenticatedLayout = () => import('../layouts/AdminLayout.vue');
const AuthenticatedUserLayout = () => import('../layouts/UserLayout.vue');
const GuestLayout = () => import('../layouts/GuestLayout.vue');

async function requireLogin(to, from, next) {
    const auth = authStore();
    const isLogin = !!auth.authenticated;

    if (isLogin) {
        next()
    } else {
        next('/login')
    }
}

const hasAdmin = (roles = []) =>
    roles.some((role) => role?.name?.toLowerCase().includes('admin'));

async function guest(to, from, next) {
    const auth = authStore()
    let isLogin = !!auth.authenticated;

    if (isLogin) {
        next('/')
    } else {
        next()
    }
}

async function requireAdmin(to, from, next) {
    const auth = authStore();
    let isLogin = !!auth.authenticated;
    let user = auth.user;

    if (isLogin) {
        if (hasAdmin(user.roles)) {
            next()
        } else {
            next('/app')
        }
    } else {
        next('/login')
    }
}

export default [
    {
        path: '/',
        component: GuestLayout,
        children: [
            {
                path: '/',
                name: 'home',
                component: () => import('../views/public/home/index.vue'),
            },

            {
                path: 'login',
                name: 'auth.login',
                component: () => import('../views/auth/login/Login.vue'),
                beforeEnter: guest,
            },
            {
                path: 'register',
                name: 'auth.register',
                component: () => import('../views/auth/register/index.vue'),
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
        path: '/app',
        component: AuthenticatedUserLayout,
        name: 'app',
        beforeEnter: requireLogin,
        meta: { breadCrumb: '.' },
        children: [
            {
                name: 'app.profile',
                path: 'profile',
                component: () => import('../views/user/profile.vue'),
                meta: {
                    breadCrumb: 'Perfil',
                },
            },

        ]
    },


    {
        path: '/admin',
        component: AuthenticatedLayout,
        beforeEnter: requireAdmin,
        meta: { breadCrumb: 'Dashboard' },
        children: [
            {
                name: 'admin.index',
                path: '',
                component: () => import('../views/admin/index.vue'),
                meta: {
                    breadCrumb: 'Admin',
                    hideBreadcrumb: true
                }
            },
            {
                name: 'profile.index',
                path: 'profile',
                component: () => import('../views/admin/profile/index.vue'),
                meta: { breadCrumb: 'Profile' }
            },

            {
                name: 'categories',
                path: 'categories',
                meta: { breadCrumb: 'Categories' },
                children: [
                    {
                        name: 'categories.index',
                        path: '',
                        component: () => import('../views/admin/categories/Index.vue'),
                        meta: {
                            breadCrumb: 'View category',
                            hideBreadcrumb: true
                        }
                    },
                ]
            },

             {
                name: 'posts',
                path: 'posts',
                meta: { breadCrumb: 'Posts' },
                children: [
                    {
                        name: 'posts.index',
                        path: '',
                        component: () => import('../views/admin/posts/index.vue'),
                        meta: {
                            breadCrumb: 'View posts',
                            hideBreadcrumb: true
                        }
                    },
                ]
            },

            {
                name: 'permissions',
                path: 'permissions',
                meta: { breadCrumb: 'Permisos' },
                children: [
                    {
                        name: 'permissions.index',
                        path: '',
                        component: () => import('../views/admin/permissions/Index.vue'),
                        meta: {
                            breadCrumb: 'Permissions',
                            hideBreadcrumb: true
                        }
                    },
                ]
            },
            {
                name: 'users',
                path: 'users',
                meta: { breadCrumb: 'Usuarios' },
                children: [
                    {
                        name: 'users.index',
                        path: '',
                        component: () => import('../views/admin/users/Index.vue'),
                        meta: {
                            breadCrumb: 'Usuarios',
                            hideBreadcrumb: true // Ocultar breadcrumb del layout porque la Card tiene su propio header
                        }
                    },
                    {
                        name: 'users.create',
                        path: 'create',
                        component: () => import('../views/admin/users/Create.vue'),
                        meta: {
                            breadCrumb: 'Crear Usuario',
                            linked: false
                        }
                    },
                    {
                        name: 'users.edit',
                        path: 'edit/:id',
                        component: () => import('../views/admin/users/Edit.vue'),
                        meta: {
                            breadCrumb: 'Editar Usuario',
                            linked: false
                        }
                    }
                ]
            },

            {
                name: 'roles',
                path: 'roles',
                meta: { breadCrumb: 'Roles' },
                children: [
                    {
                        name: 'roles.index',
                        path: '',
                        component: () => import('../views/admin/roles/Index.vue'),
                        meta: {
                            breadCrumb: 'Roles',
                            hideBreadcrumb: true
                        }
                    },
                    {
                        name: 'admin.roles.edit',
                        path: 'edit/:id',
                        component: () => import('../views/admin/roles/Edit.vue'),
                        meta: {
                            breadCrumb: 'Editar Rol',
                            linked: false
                        }
                    }
                ]
            },

            {
                name: 'questions',
                path: 'questions',
                meta: { breadCrumb: 'Preguntas' },
                children: [
                    {
                        name: 'questions.index',
                        path: '',
                        component: () => import('../views/admin/questions/Index.vue'),
                        meta: {
                            breadCrumb: 'Lista',
                            hideBreadcrumb: true,
                            permission: 'all'
                        }
                    },
                    {
                        name: 'questions.create',
                        path: 'create',
                        component: () => import('../views/admin/questions/Create.vue'),
                        meta: {
                            breadCrumb: 'Crear pregunta',
                            linked: false,
                            permission: 'all'
                        }
                    },
                    {
                        name: 'questions.edit',
                        path: ':id/edit',
                        component: () => import('../views/admin/questions/Edit.vue'),
                        meta: {
                            breadCrumb: 'Editar Pregunta',
                            linked: false,
                            permission: 'all'
                        }
                    }
                ]
            },

        ]
    },
    {
        path: "/:pathMatch(.*)*",
        name: 'NotFound',
        component: () => import("../views/errors/404.vue"),
    },
];
