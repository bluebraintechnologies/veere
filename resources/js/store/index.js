import { createStore } from 'vuex'
import cart from './modules/cart';
import settings from "./modules/settings";
import nav from "./modules/navs";
import home from "./modules/home";
import assets from './modules/assets';
import productModule from './modules/products';
import wishlist from './modules/wishlist/index';

export default createStore({
    modules: {
        cart,
        nav,
        productModule,
        assets,
        home,
        wishlist,
        settings
    }
})