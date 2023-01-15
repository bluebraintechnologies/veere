require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import store from './store'
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Swaariish';
const site_url = document.getElementsByName('site_url')[0].content;
const CSRF_TOKEN = document.getElementsByName('csrf-token')[0].content;
const LOCAL_MEDIA_URL = window.atob(document.getElementsByName('local_token')[0].content);
const MEDIA_URL = window.atob(document.getElementsByName('media_token')[0].content);
const OFFER_TOKEN = window.atob(document.getElementsByName('offer_token')[0].content);
const BANNER_TOKEN = window.atob(document.getElementsByName('banner_token')[0].content);
const INVOICE_TOKEN = window.atob(document.getElementsByName('invoice_token')[0].content);



import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';


createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        store.dispatch("getAllCategories");
        store.dispatch("getnavItems");
        const myApp = createApp({ 
                render: () => h(app, props),
            })
            .use(plugin)
            .use(VueSweetalert2)
            .use(store)
            .use(VueToast, { position: 'top-right' })
            .mixin({ methods: { route } })
            .component("Link", Link)
            .component("Head", Head);
            myApp.config.globalProperties.$local_media_url = LOCAL_MEDIA_URL;
            myApp.config.globalProperties.$media_url = MEDIA_URL;
            myApp.config.globalProperties.$site_url = site_url;
            myApp.config.globalProperties.$csrf_token = CSRF_TOKEN;
            myApp.config.globalProperties.$offer_token = OFFER_TOKEN;
            myApp.config.globalProperties.$invoice_token = INVOICE_TOKEN;
            myApp.config.globalProperties.$banner_token = BANNER_TOKEN;
            myApp.mount(el);
        return myApp;
    },
});

InertiaProgress.init({ color: '#4B5563', showSpinner: true, includeCSS: true });