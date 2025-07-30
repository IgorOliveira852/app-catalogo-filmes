// router.js
import { createRouter, createWebHistory } from 'vue-router'
import DefaultLayout  from './components/DefaultLayout.vue'
import Home           from './pages/Home.vue'
import Movies         from './pages/Movies.vue'
import Favorites      from './pages/Favorites.vue'
import Login          from './pages/Login.vue'
import Signup         from './pages/Signup.vue'
import NotFound       from './pages/NotFound.vue'
import {useUserStore} from './store/user.js'

const routes = [
    {
        path: '/',
        component: DefaultLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '',           name: 'Home',      component: Home },
            { path: 'movies',     name: 'Movies',    component: Movies },
            { path: 'favorites',  name: 'Favorites', component: Favorites },
        ]
    },

    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { isGuest: true }
    },
    {
        path: '/signup',
        name: 'Signup',
        component: Signup,
        meta: { isGuest: true }
    },

    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// guard global
router.beforeEach(async (to, from, next) => {
    const userStore = useUserStore()

    if (!userStore.user && localStorage.getItem('AUTH_TOKEN')) {
        try {
            await userStore.fetchUser()
        } catch (err) {
            userStore.logout()
        }
    }

    if (to.meta.requiresAuth && !userStore.user) {
        return next({ name: 'Login' })
    }

    if (to.meta.isGuest && userStore.user) {
        return next({ name: 'Home' })
    }

    next()
})

export default router