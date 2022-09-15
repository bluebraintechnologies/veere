require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import store from './store'
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        store.dispatch("getnavItems");
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(store)
            .mixin({ methods: { route } })
            .component("Link", Link)
            .component("Head", Head)
            .mount(el);
    },
});

// InertiaProgress.init({ color: '#4B5563' });
InertiaProgress.init({ color: '#4B5563', showSpinner: true, includeCSS: true });