import { createStore } from 'vuex'

import nav from "./modules/navs";

export default createStore({
    modules: {
        nav,
    }
})