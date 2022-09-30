<template>
    <header class="ec-header">
        <div class="ec-header-bottom d-none d-lg-block">
            <div class="container position-relative">
                <div class="row">
                    <div class="ec-flex">
                        <div class="align-self-center">
                            <div class="header-logo">
                                <a target="_self" href="/">
                                    <img src="/images/logo.png" alt="Veeere" />
                                    <img class="dark-logo" src="/images/logo.png" alt="Veeere" style="display: none;" />
                                </a>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <div class="header-search">
                                <form class="ec-btn-group-form" action="#">
                                    <input class="form-control" placeholder="Enter Your Product Name..." type="text">
                                    <button class="submit" type="submit">
                                        <img src="/images/icons/search.svg" class="svg_img header_svg" alt="" />
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <div class="ec-header-bottons">
                                <!-- <a href="javascript:void(0)" v-if="loggedIn"></a> -->
                                <a :href="(loggedIn) ? route('dashboard') : route('login')" class="ec-header-btn ec-side-toggle">
                                    <div class="header-icon">
                                        <img src="/images/icons/user.svg" class="svg_img header_svg" alt="" />
                                    </div>
                                    <span class="ec-cart-title fs-11" v-if="loggedIn">
                                        <span class="text-muted d-block">Account</span>
                                        <span class="ec-cart-count">Dashboard</span>
                                    </span>
                                    <span class="ec-cart-title fs-11" v-else>
                                        <span class="text-muted d-block">Account</span>
                                        <span class="ec-cart-count">LOGIN</span>
                                    </span>
                                </a>
                                <a :href="route('wishlist')" class="ec-header-btn ec-side-toggle">
                                    <div class="header-icon">
                                        <img src="/images/icons/wishlist.svg" class="svg_img header_svg" alt="" />
                                    </div>
                                    <span class="ec-cart-title fs-11">
                                        <span class="text-muted d-block">Wishlist</span>
                                        <span class="ec-cart-count"> {{ wishlistItemQty }} ITEMS</span>
                                    </span>
                                </a>
                                <a href="javascript:;" @click="$emit('show-sidecart')" class="ec-header-btn ec-side-toggle">
                                    <div class="header-icon">
                                        <img src="/images/icons/cart.svg" class="svg_img header_svg" alt="" />
                                    </div>
                                    <span class="ec-cart-title fs-11">
                                        <span class="text-muted d-block">Cart</span>
                                        <span class="ec-cart-count">{{cartQuantity}} ITEMS</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ec-header-bottom d-lg-none">
            <div class="container position-relative">
                <div class="row ">
                    <div class="col">
                        <div class="header-logo">
                            <Link :href="route('home')">
                                <img src="/images/logo.png" alt="Veeere" />
                                <img class="dark-logo" src="/images/logo.png" alt="Veeere" style="display: none;" />
                            </Link>
                        </div>
                    </div>
                    <div class="col">
                        <div class="header-search">
                            <form class="ec-btn-group-form" action="#">
                                <input class="form-control" placeholder="Enter Your Product Name..." type="text">
                                <button class="submit" type="submit">
                                    <img src="/images/icons/search.svg" class="svg_img header_svg" alt="icon" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <theme-menu v-on:show-sidecart="$emit('show-sidecart')" />
    </header>
</template>
<script>
import ThemeMenu from "@/Layouts/Elements/Menu.vue";
import { mapGetters } from 'vuex';
import Price from '@/Pages/Product/Elemants/Price.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

export default {
    components: {
        ThemeMenu,
        Price,
        ApplicationLogo
    },
    emits:[
        'show-sidecart'
    ],
    computed:{
        ...mapGetters(['cartTotal', 'cartQuantity', 'wishlistItemQty']),
        currencyIcon() {
            return 'Rs. ';
        },
        loggedIn(){
            return this.$page.props.isUserLogged
        }

    },
};
</script>