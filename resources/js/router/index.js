import { createRouter, createWebHistory } from "vue-router";

import Main from '../components/pages/Main.vue'
import NotFound from '../components/pages/NotFound.vue'
import Register from '../components/pages/Register.vue'
import Login from '../components/pages/Login.vue'
import Projects from '../components/pages/Projects.vue'


const routes = [
    {
        path: '/',
        component: Main
    },
    {
        path: '/projects',
        component: Projects,
        name: 'projects'
    },
    {
        path: '/register',
        component: Register,
        name: 'user.register'
    },
    {
        path: '/login',
        component: Login,
        name: 'user.login'
    },
    {
        path: '/:pathMatch(.*)*',
        component: NotFound
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('x_xsrf_token')

    if (!token) {
        if (to.name === 'user.login' || to.name === 'user.register') {
            return next()
        } else {
            return next({
                name: 'user.login'
            })
        }      
    }
    if (to.name === 'user.login' || to.name === 'user.register' && token) { 
        return next({
            name: 'projects'
        })
    }
    next()

})
export default router