<script>
import ThemeHeader from "./Elements/Header.vue";
import ThemeFooter from "./Elements/Footer.vue";
import SideCart from "./Elements/SideCart.vue";
import { mapActions, mapGetters } from 'vuex';

export default {
    components: {
        ThemeHeader,
        ThemeFooter,
        SideCart,
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
        <slot />
        <theme-footer />
    </div>
</template>
