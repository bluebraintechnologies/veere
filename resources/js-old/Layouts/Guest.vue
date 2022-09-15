<script>
import ThemeHeader from "./Elements/Header.vue";
import ThemeFooter from "./Elements/Footer.vue";
import Model from '../Pages/Home/Model.vue';
import SideStyle from '../Pages/Home/SideStyle.vue';
import NewsletterModel from '../Pages/Home/NewsletterModel.vue';
import FooterResponsive from '../Pages/Home/FooterResponsive.vue';
import PurchasePopup from '../Pages/Home/PurchasePopup.vue';
import ToastNotification from '../Pages/Home/ToastNotification.vue';
import MainSlider from '../Pages/Home/MainSlider.vue';

export default {
    components: {
        ThemeHeader,
        ThemeFooter,
        Model,
        SideStyle,
        NewsletterModel,
        FooterResponsive,
        PurchasePopup,
        ToastNotification,
        MainSlider,
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
    methods: {
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
};
</script>

<template>
    <div>
        <ThemeHeader @show-sidecart="displaySideCart()" />
        <side-cart @close-sidebar="displaySideCart()" :showCart="sideCartStatus" />
        <slot />
        <theme-footer />
        <Footer />
        <Model />
        <SideStyle />
        <NewsletterModel />
        <FooterResponsive />
        <PurchasePopup />
        <ToastNotification />
    </div>
</template>
