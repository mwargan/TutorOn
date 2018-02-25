
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// vendor
require('./bootstrap');
window.Vue = require('vue');

// 3rd party
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';

Vue.use(Vuetify, {
  theme: {
    primary: '#0A2463',
    secondary: '#3E92CC',
    accent: '#1E1B18',
    error: '#D8315B'
  }
});
Vue.component('moon-loader', require('vue-spinner/src/MoonLoader.vue'));

// app
import router from './router/';
import store from './store/';
import eventBus from './event/';
import formatters from './common/Formatters';
import { mapGetters } from 'vuex';
import VuetifyGoogleAutocomplete from 'vuetify-google-autocomplete';

Vue.use(formatters);
Vue.use(eventBus);
Vue.use(VuetifyGoogleAutocomplete, {
  apiKey: 'key'
});

const admin = new Vue({
    el: '#admin',
    eventBus,
    router,
    store,
    data: () => ({
        drawer: true,
        subjects: [],
        user: null
    }),
    created() {

        const self = this;

        self.loadSubjects(() => {});
        self.loadUser(() => {});

    },
    computed: {
        ...mapGetters({
               showLoader: 'index/showLoader',
               showSnackbar: 'index/showSnackbar',
               hideSnackbar: 'index/hideSnackbar',
               hideDialog: 'index/hideDialog',
               dialogOk: 'index/dialogOk',
               dialogCancel: 'index/dialogCancel'

             }),
        showSnackbar: {
            get() {
                return store.getters['index/showSnackbar'];
            },
            set(val) {
                if(!val) store.commit('index/hideSnackbar');
            }
        },
        unread_notifications() {
            return this.user.notifications.filter(notification => {
              return notification.read_at === null
            })
        },
        unread_count() {
            return this.unread_notifications.length;
        },
        snackbarMessage() {
            return store.getters['index/snackbarMessage'];
        },
        snackbarColor() {
            return store.getters['index/snackbarColor'];
        },
        snackbarDuration() {
            return store.getters['index/snackbarDuration'];
        },

        // dialog
        showDialog: {
            get() {
                return store.getters['index/showDialog'];
            },
            set(val) {
                if(!val) store.commit('index/hideDialog');
            }
        },
        dialogType() {
            return store.getters['index/dialogType']
        },
        dialogTitle() {
            return store.getters['index/dialogTitle']
        },
        dialogMessage() {
            return store.getters['index/dialogMessage']
        }
    },
    methods: {
        menuClick(routeName, routeType) {

            let rn = routeType || 'vue';

            if(rn==='vue') {

                this.$router.push({name: routeName});
            }
            if(rn==='full_load') {

                window.location.href = routeName;
            }
        },
        clickLogout(logoutUrl,afterLogoutRedirectUrl) {
            axios.post(logoutUrl).then((r)=>{
                window.location.href = afterLogoutRedirectUrl;
            });
        },
        dialogOk() {
            store.commit('index/dialogOk');
        },
        dialogCancel() {
            store.commit('index/dialogCancel');
        },
        loadSubjects(cb) {

            const self = this;


            axios.get('/admin/subjects').then(function(response) {
                console.log(response.data)
                self.subjects = response.data.data.data;
                $.each(response.data.data.data, function(res, val) {
                    self.$store.dispatch('subject/addSubject', val)
                 });
            });
        },
        loadUser(cb) {

            const self = this;
            axios.get('/public/me').then(function(response) {
                console.log(response.data);
                self.user = response.data;
                (cb || Function)();
            });
        }
    }
});