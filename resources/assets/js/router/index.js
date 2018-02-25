import Vue from 'vue';
import Router from 'vue-router';
import store from '../store';

Vue.use(Router);

const router = new Router({
    routes: [
        {
            path: '/',
            redirect: '/dashboard',
        },
        {
            path: '/area',
            component: require('../pages/Area.vue')
        },

        { path: '/dashboard', component: require('../pages/home/index'), children: [
            { path: '', redirect: { name: 'home.explore' }},
            { path: 'explore', name: 'home.explore',  component: require('../pages/home/explore') },
            { path: 'tutoring', name: 'home.tutoring', component: require('../pages/home/tutoring') },
            { path: 'learning', name: 'home.learning', component: require('../pages/home/learning') }
          ]
        },
        {
            name: 'users',
            path: '/users',
            component: require('../pages/Users.vue'),
        },
        {
            name: 'files',
            path: '/files',
            component: require('../pages/Files.vue'),
        },
        {
            name: 'settings',
            path: '/settings',
            component: require('../pages/Settings.vue'),
        },
        {
            name: 'subjects',
            path: '/subjects',
            component: require('../pages/Subjects.vue'),
        },
        {
            name: 'subject',
            path: '/subject/:name',
            component: require('../pages/Subject.vue'),
        }
    ],
});

router.beforeEach((to, from, next) => {
    //store.commit('index/showLoader');
    next();
});

router.afterEach((to, from) => {
    setTimeout(()=>{
        //store.commit('index/hideLoader');
    },1300);
});

export default router;