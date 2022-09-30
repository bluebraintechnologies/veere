<template>
    <div id="ec-main-menu-desk" class="d-none d-lg-block" :class="[(stickymode)?'sticky':'']">
        <div class="container position-relative" v-if="stickymode">
            <div class="row">
                <div class="col col-3">
                    <div class="header-logo">
                        <Link :href="route('home')">
                            <img src="/images/logo.png" alt="Veeere" />
                            <img class="dark-logo" src="/images/logo.png" alt="Veeere" style="display: none;" />
                        </Link>
                    </div>
                </div>
                <div class="col col-6 d-flex">
                    <div>
                        <DropdownMenuVue menuTitle="All Categories">
                            <div v-for="(navItem, index) in navItems" :key="'cat-' + index">
                                <Link :href="route('category', navItem.slug)">
                                    {{ navItem.name }}
                                </Link>
                            </div>
                        </DropdownMenuVue>
                    </div>
                    <div class="header-search">
                        <form class="ec-btn-group-form" action="#">
                            <input class="form-control" placeholder="Enter Your Product Name..." type="text">
                            <button class="submit" type="submit">
                                <img src="/images/icons/search.svg" class="svg_img header_svg" alt="icon" />
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col col-3 text-right">
                    <ul>
                        <li class="position-relative">
                            <span class="main-label-note-new" data-toggle="tooltip" title="" data-bs-original-title="NEW" aria-label="NEW"></span>
                            <Link class="text-color1" :href="route('offers')">Veeere Offers</Link>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- <div class="yk-cart-block">
                <a href="javascript:;" @click="$emit('show-sidecart')">
                    <div class="header-icon">
                        <img src="/images/icons/bag.png" class="header_svg" alt="" />
                    </div>
                    <span class="ec-btn-title">
                        <span class="ec-cart-count">{{cartQuantity}}</span> Item(s)
                    </span>
                </a>
            </div> -->
        </div>
        <div class="container position-relative" v-else>
            <div class="row">
                <div class="col-md-12 align-self-center">
                    <div class="ec-main-menu">
                        <ul>
                            <li v-for="(navItem, index) in navItems" :key="'cat-' + index">
                                <Link :href="route('category', navItem.slug)">
                                    {{ navItem.name }}
                                </Link>
                            </li>
                            <!-- <li class="dropdown position-static"><a href="javascript:void(0)">Categories</a>
                                <ul class="mega-menu d-block">
                                    <li class="d-flex">
                                        <ul class="d-block">
                                            <a v-for="(navItem, index) in navItems" :key="'cat-' + index">
                                                {{ navItem.name }}
                                            </a>
                                        </ul>                                        
                                    </li>
                                </ul>
                            </li> -->
                        </ul>
                        <ul>
                            <li class="position-relative">
                                <span class="main-label-note-new" data-toggle="tooltip" title="" data-bs-original-title="NEW" aria-label="NEW"></span>
                                <Link class="text-color1" :href="route('offers')">Offers</Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- <div class="yk-cart-block">
                <a href="javascript:;" @click="$emit('show-sidecart')">
                    <div class="header-icon">
                        <img src="/images/icons/bag.png" class="header_svg" alt="" />
                    </div>
                    <span class="ec-btn-title">
                        <span class="ec-cart-count">{{cartQuantity}}</span> Item(s)
                    </span>
                </a>
            </div> -->
        </div>
        <div  v-if="stickymode" class="floatting-cart">
            <div class="bag" @click="$emit('show-sidecart')">
                <div class="qty">{{cartQuantity}}</div>
            </div>
            <div class="cost">{{currencyIcon+cartTotal}}</div>
        </div>
    </div>
</template>
<script>
import { mapGetters } from "vuex";
import DropdownMenuVue from './DropdownMenu.vue';

export default {
   name: "Nav Menu",
   emits:[
        'show-sidecart'
    ],
    components: {
        DropdownMenuVue
    },
    computed: {
        ...mapGetters(['cartTotal', 'cartQuantity', 'navItems']),
        currencyIcon() {
            return 'Rs. ';
        },
    },
    data() {
        return {
            stickymode:false,
            scrollPosition:''
        }
    },
    methods:{
        updateScroll() {
            this.scrollPosition = window.scrollY
            if(this.scrollPosition >= 125) {
                this.stickymode = true
            } else {
                this.stickymode = false
            }
        }
    },
    mounted() {
        window.addEventListener('scroll', this.updateScroll);
    },
};
</script>