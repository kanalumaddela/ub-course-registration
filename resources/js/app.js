require('./bootstrap');

// Import modules...
import Vue from 'vue';
import {App as InertiaApp, plugin as InertiaPlugin} from '@inertiajs/inertia-vue';
import PortalVue from 'portal-vue';
import VueObserveVisibility from 'vue-observe-visibility';
// import vSelect from 'vue-select';

Vue.mixin({ methods: { route } });
Vue.use(InertiaPlugin);
Vue.use(PortalVue);
Vue.use(VueObserveVisibility);
// Vue.component('v-select', vSelect);

Vue.mixin({
    methods: {
        friendlyTime: function (time) {
            return (new Date(`January 1, 2021 ${time}`)).toLocaleTimeString('en-US');
        },
        hasRole(role) {
            return this.$page?.props?.user?.is_super || this.$page?.props?.user?.roles.indexOf(role) !== -1;
        }
    },
})

const app = document.getElementById('app');

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
