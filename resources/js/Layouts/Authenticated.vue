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
            showCookieBanner : true,
            cookieAccepted: false,
            showNavigation:false,
        };
    },
    computed: {
        ...mapGetters(['wishlistItems', 'cartItems', 'cookieStatus', 'cartQuantity'])
    },
    methods: {
         ...mapActions(['getWishlistItems', 'getCartItems', 'getDealItems', 'getRewardPoints' ,'getStockDetails', 'getStockDetails', 'setCookieStatus']),
        displaySideCart() {
            this.sideCartStatus = !this.sideCartStatus;
        },
        changeNavStatus() {
            this.showNavigation = !this.showNavigation;
        },
        saveCookieStatus(){
            // this.setCookieStatus();
            localStorage.setItem("user_accept_cookie", 'accepted')
            this.cookieAccepted = localStorage.getItem("user_accept_cookie")
        },
        getStockDetailsInfo(){
            let location
            if(localStorage.getItem("location")){
                location = localStorage.getItem("location")
            }else if(localStorage.getItem("temp_location")){
                location = localStorage.getItem("temp_location")
            }
            this.getStockDetails([location])
        }
    },
    created(){
        this.getStockDetailsInfo();
        this.getDealItems();
        this.getCartItems();
        this.cookieAccepted = localStorage.getItem("user_accept_cookie")
    },
    mounted() {        
        this.getRewardPoints()
        this.getWishlistItems();
    }
};
</script>

<template>
    <div>
        <theme-header @show-sidecart="displaySideCart()" @show-mobilemenu="changeNavStatus()" :showNavigation="showNavigation" />
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
