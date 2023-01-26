<template>
    <header class="ec-header">
        <div class="ec-header-bottom d-none d-lg-block">
            <div class="container position-relative">
                <div class="row">
                    <div class="ec-flex">
                        <div class="align-self-center">
                            <div class="header-logo">
                                <a target="_self" :href="route('home')">
                                    <img :src="$site_url+'/images/logo.png'" alt="Veeere" />
                                    <img class="dark-logo" :src="$site_url+'/images/logo.png'" alt="Veeere" style="display: none;" />
                                </a>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <div class="header-search">
                                <form class="ec-btn-group-form" action="/search">
                                    <input class="form-control" placeholder="Enter Your Product Name..." type="text" name="keyword">
                                    <button class="submit" type="submit">
                                        <img :src="$site_url+'/images/icons/search.svg'" class="svg_img header_svg" alt="" />
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <div class="ec-header-bottons">
                                <!-- <a href="javascript:void(0)" v-if="loggedIn"></a> -->
                                <Link :href="(loggedIn) ? route('dashboard') : route('login')" class="ec-header-btn ec-side-toggle">
                                    <div class="header-icon">
                                        <img :src="$site_url+'/images/icons/wallet-64.png'" class="svg_img header_svg" alt="" />
                                    </div>
                                    <span class="ec-cart-title fs-11" v-if="loggedIn">
                                        <span class="text-muted d-block" title="Earned Reward Points">{{ user_reward_points_earned }}</span>
                                        <span class="ec-cart-count" title="Reward points on the way">{{ user_reward_points_ontheway }}</span>
                                    </span>
                                    <span class="ec-cart-title fs-11" v-else>
                                        <span class="text-muted d-block">00</span>
                                        <span class="ec-cart-count">00</span>
                                    </span>
                                </Link>
                                <Link :href="(loggedIn) ? route('dashboard') : route('login')" class="ec-header-btn ec-side-toggle">
                                    <div class="header-icon">
                                        <img src="/images/icons/user.svg" class="svg_img header_svg" alt="" />
                                    </div>
                                    <span class="ec-cart-title fs-11" v-if="loggedIn">
                                        <span class="text-muted d-block" style="text-transform: capitalize;">Hi, {{ username }}</span>
                                        <!-- <span class="ec-cart-count">Dashboard</span> -->
                                    </span>
                                    <span class="ec-cart-title fs-11" v-else>
                                        <span class="text-muted d-block">Account</span>
                                        <span class="ec-cart-count">LOGIN</span>
                                    </span>
                                </Link>
                                <Link :href="route('wishlist')" class="ec-header-btn ec-side-toggle">
                                    <div class="header-icon">
                                        <!-- <img :src="$site_url+'/images/icons/wishlist.svg" class="svg_img header_svg" alt="" /> -->
                                        <i class="ecicon eci-heart text-danger eci-1x" v-if="wishlistItemQty > 0"></i>
                                        <i class="ecicon eci-heart-o eci-1x" v-else></i>
                                    </div>
                                    <span class="ec-cart-title fs-11">
                                        <span class="text-muted d-block">Wishlist</span>
                                        <span class="ec-cart-count"> {{ wishlistItemQty }} ITEMS</span>
                                    </span>
                                </Link>
                                <a href="javascript:;" @click="$emit('show-sidecart')" class="ec-header-btn ec-side-toggle">
                                    <div class="header-icon">
                                        <img :src="$site_url+'/images/icons/cart.svg'" class="svg_img header_svg" alt="" />
                                    </div>
                                    <span class="ec-cart-title fs-11">
                                        <span class="text-muted d-block">Cart</span>
                                        <span class="ec-cart-count">{{cartQuantity}} ITEMS</span>
                                    </span>
                                </a>
                                <a href="javascript:;" @click="showAddressForm()" class="ec-header-btn ec-side-toggle">
                                    <!-- <div class="header-icon">
                                        <img :src="$site_url+'/images/icons/cart.svg'" class="svg_img header_svg" alt="" />
                                    </div> -->
                                    <span class="ec-cart-title fs-11">
                                        <span class="text-muted d-block">Location</span>
                                        <!-- <span class="ec-cart-count" v-if="currentLocation">Location :{{currentLocation}} </span> -->
                                        <span class="ec-cart-count" v-if="currentPincode">Pincode : {{currentPincode}} </span>
                                        <!-- <span class="ec-cart-count" v-if="currentAddress">Add : {{currentAddress}} </span> -->
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
                            <a target="_self" :href="route('home')">
                                <img :src="$site_url+'/images/logo.png'" alt="Veeere" />
                                <img class="dark-logo" :src="$site_url+'/images/logo.png'" alt="Veeere" style="display: none;" />
                            </a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="header-search">
                            <form class="ec-btn-group-form" action="/search">
                                <input class="form-control" placeholder="Enter Your Product Name..." type="text" name="keyword">
                                <button class="submit" type="submit">
                                    <img :src="$site_url+'/images/icons/search.svg'" class="svg_img header_svg" alt="icon" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="ec-mobile-menu" class="ec-side-cart ec-mobile-menu" v-show="showNavigation" :class="[(showNavigation)?'ec-open':'']">
            <div class="ec-menu-title">
                <span class="menu_title">
                    <a target="_self" :href="route('home')">
                        <img :src="$site_url+'/images/logo.png'" alt="Veeere" />
                        <img class="dark-logo" :src="$site_url+'/images/logo.png'" alt="Veeere" style="display: none;" />
                    </a>
                </span>
                <button class="ec-close"  @click="$emit('show-mobilemenu')">�</button>
            </div>
            <div class="ec-menu-inner">
                <div class="ec-menu-content">
                    <div class="ec-main-menu">
                        <ul>
                            <li v-for="(navItem, index) in navItems" :key="'cat-' + index">
                                <Link :href="route('category', navItem.slug)">
                                    {{ navItem.name }}
                                </Link>
                            </li>
                            <li>
                                <Link class="text-color1" :href="route('offers')">Offers</Link>
                            </li>
                            <li >
                                <Link class="text-color1" :href="route('grocery-list-front')">Grocery List</Link>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="header-res-lan-curr">
                    <div class="header-res-social">
                        <div class="header-top-social">
                            <ul class="mb-0">
                                <li class="list-inline-item" v-if="business.business_facebook">
                                    <a :href="business.business_facebook" target="_blank">
                                        <i class="ecicon eci-facebook"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item" v-if="business.business_instagram">
                                    <a target="_blank" :href="business.business_instagram">
                                        <i class="ecicon eci-instagram"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item" v-if="business.business_linkedin">
                                    <a target="_blank" :href="business.business_linkedin">
                                        <i class="ecicon eci-linkedin"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item" v-if="business.business_twitter">
                                    <a target="_blank" :href="business.business_twitter">
                                        <i class="ecicon eci-twitter"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item" v-if="business.youtube_link">
                                    <a target="_blank" :href="business.youtube_link">
                                        <i class="ecicon eci-youtube"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <theme-menu v-on:show-sidecart="$emit('show-sidecart')" />
        <div class="ec-side-cart-overlay" style="display:block" v-show="showNavigation"></div>
    </header>
</template>
<script>
import ThemeMenu from "@/Layouts/Elements/Menu.vue";
import { mapActions, mapGetters } from 'vuex';
import Price from '@/Pages/Product/Elemants/Price.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

export default {
    
    components: {
        ThemeMenu,
        Price,
        ApplicationLogo
    },
    props:{
        showNavigation: [Boolean],
    },
    data(){
        return {
            currentAddress:'',
            currentLocation:'',
            currentPincode:'',
        }
    },
    emits:[
        'show-sidecart'
    ],
    computed:{
        ...mapGetters(['cartTotal', 'cartQuantity', 'wishlistItemQty', 'user_reward_points_earned', 'user_reward_points_ontheway', 'business', 'navItems']),
        currencyIcon() {
            return 'Rs. ';
        },
        loggedIn(){
            return this.$page.props.isUserLogged
        },
        username(){
            if(this.$page.props.loggedinUserDetail){
                return this.$page.props.loggedinUserDetail.name
            }else{
                return ''
            }
        }        
    },
    methods:{
        ...mapActions(['getUserRewardPointsEarned', 'getUserRewardPointsOntheway']),        
        showAddressForm(){
            this.$store.commit('PINCODE_FORM', true)
        }

    },
    mounted(){
        this.getUserRewardPointsEarned()
        this.getUserRewardPointsOntheway()
    },
    created(){
        // console.log('attr', this.$attrs.auth)
        if(localStorage.getItem('location')){
            this.currentLocation = localStorage.getItem('location')
        }
        if(localStorage.getItem('temp_location')){
            this.currentLocation = localStorage.getItem('temp_location')
        }
        if(localStorage.getItem('pincode')){
            this.currentPincode = localStorage.getItem('pincode')
        }
        if(localStorage.getItem('temp_pincode')){
            this.currentPincode = localStorage.getItem('temp_pincode')
        }
        if(localStorage.getItem('address')){
            this.currentAddress = localStorage.getItem('address')
        }
    }
};
</script>