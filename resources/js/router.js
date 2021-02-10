import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

/** all test components */
import firstPage from './components/pages/myFirstVuePage.vue'
import newRoutePage from './components/pages/newRoutePage.vue'
import hooks from './components/pages/basic/hooks.vue'
import methods from './components/pages/basic/methods.vue'

/** project components */
import home from './components/pages/home.vue'
import tags from './components/pages/tags.vue'

const routes = [
    // project routes
    {
        path: '/',
        component: home
    },

    {
        path: '/tags',
        component: tags
    },

    // all test routes
    {
        path: '/my-new-vue-route',
        component: firstPage
    },
    {
        path: '/new-route',
        component: newRoutePage
    },

    // vue hooks
    {
        path: '/hooks',
        component: hooks
    },

    // more basic
    {
        path: '/methods',
        component: methods
    }
]

export default new Router({
    mode: 'history',
    routes
})