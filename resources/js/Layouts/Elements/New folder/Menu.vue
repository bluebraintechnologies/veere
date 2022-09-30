<template>
    <!-- EC Main Menu Start -->
    <div id="ec-main-menu-desk" class="d-none d-lg-block" :class="[(stickymode)?'sticky':'']">
        <div class="container position-relative">
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
    </div>
</template>
<script>
import { mapGetters } from "vuex";

export default {
   name: "Nav Menu",
   emits:[
        'show-sidecart'
    ],
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