<script>
import ThemeHeader from "./Elements/Header.vue";
import ThemeFooter from "./Elements/Footer.vue";
import SideCart from "./Elements/SideCart.vue";
import UserBreadcumb from "./Elements/UserBreadcumb.vue";
import UserMenu from "./Elements/UserMenu.vue";
import { mapActions, mapGetters } from 'vuex';

export default {
    components: {
        ThemeHeader,
        ThemeFooter,
        SideCart,
        UserBreadcumb,
        UserMenu
    },
    props: {
        categories: [Array, Object],
    },
    data() {
        return {
            sideCartStatus: false,
            /*  showNavigation:false,
                activeCondesnedMenu: false,
                darkModeStatus: false */
        };
    },
    computed: {
        ...mapGetters(['wishlistItems', 'cartItems'])
    },
    methods: {
         ...mapActions(['getWishlistItems', 'getCartItems']),
        displaySideCart() {
            this.sideCartStatus = !this.sideCartStatus;
        },
        /* changeNavStatus() {
                this.showNavigation = !this.showNavigation
            },
            changeSidemenu() {
                this.activeCondesnedMenu = !this.activeCondesnedMenu
            },
            changeThemeMode() {
                this.darkModeStatus =  !this.darkModeStatus
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark')
                } else {
                    document.documentElement.classList.remove('dark')
                }
            } */
    },
    mounted() {
        if(this.$page.props.auth.user) {
            this.getWishlistItems();
        }
        this.getCartItems();
    }
};
</script>

<template>
    <div>
        <theme-header @show-sidecart="displaySideCart()" />
        <side-cart @close-sidebar="displaySideCart()" :showCart="sideCartStatus" />
        <user-breadcumb></user-breadcumb>
        <section class="ec-page-content ec-vendor-uploads ec-user-account section-space-p mb-8">
            <div class="container">
                <div class="row">
                    <div class="ec-shop-leftside ec-vendor-sidebar col-lg-3 col-md-12">
                        <user-menu :activemenu="$page.props.smenu"></user-menu>
                    </div>
                    <div class="ec-shop-rightside col-lg-9 col-md-12">
                        <slot />
                    </div>
                </div>
            </div>
        </section>
        <theme-footer />
    </div>
</template>
