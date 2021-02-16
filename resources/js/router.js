import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

/** admin project components */
import home from './components/pages/home.vue'
import tags from './admin/pages/tags.vue'
import categories from './admin/pages/category.vue'
import adminusers from './admin/pages/adminusers'

import usecom from './vuex/usecom.vue'

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
    {
        path: '/categories',
        component: categories
    },
    {
        path: '/admin-users',
        component: adminusers
    },
    {
        path: '/vuex',
        component: usecom
    }
]

export default new Router({
    mode: 'history',
    routes
})
