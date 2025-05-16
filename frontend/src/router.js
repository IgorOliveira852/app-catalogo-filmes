import {createRouter, createWebHistory} from "vue-router";
import DefaultLayout from "./components/DefaultLayout.vue";
import Home from "./pages/Home.vue";
import MyImages from "./pages/MyImages.vue";
import Login from "./pages/Login.vue";
import Signup from "./pages/Signup.vue";
import NotFound from "./pages/NotFound.vue";
import Movies from "./pages/Movies.vue";
import Favorites from "./pages/Favorites.vue";

const routes = [
    {
        path: "/",
        component: DefaultLayout,
        children: [
            {path: '/', name: 'Home', component: Home},
            {path: '/images', name: 'MyImages', component: MyImages},
            {path: '/movies', name: 'Movies', component: Movies},
            {path: '/favorites', name: 'Favorites', component: Favorites},
        ]
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/singnup',
        name: 'Signup',
        component: Signup,
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: NotFound,
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;