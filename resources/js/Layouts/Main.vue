<script>
import ThemeHeader from "./Elements/Header.vue";
import ThemeFooter from "./Elements/Footer.vue";
import SideCart from "./Elements/SideCart.vue";
import { mapActions, mapGetters } from 'vuex';
import axios from 'axios';
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
            showCookieBanner : true,
            cookieAccepted: false,
            showNavigation:false,
            pincode : '',
            address:'',
            // showCustomerLocationForm:true, // to show address selection form
            
        };
    },
    computed: {
        ...mapGetters(['wishlistItems', 'cartItems', 'cookieStatus', 'cartQuantity', 'showCustomerLocationForm'])
    },
    methods: {
        ...mapActions(['getWishlistItems', 'getCartItems', 'getDealItems', 'getStockDetails', 'setCookieStatus']),
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
        checkPincode(){
            // before saving check pincode exist in our system of not
            if(this.pincode == ''){
                this.$toast.error('Please enter pin code.');
                return false
            }
            axios.post('api/check-pincode-in-system', {pincode : this.pincode}).then((response) => {
                if(response.data.available){
                    // save this pincode in local storage
                    localStorage.setItem("pincode", this.pincode)
                    localStorage.setItem("location", response.data.location)
                    location.reload()
                }else{
                    this.$toast.error('Sorry, our service is not available at this location.');
                }
            })
            // get get data account to pincode mapping with business location
        },
        cancelLocation(){
            // if user refuse to give location
            localStorage.setItem("temp_pincode", 110001)
            localStorage.setItem("temp_location", 1)
            location.reload()
        },
        getStockDetailsInfo(){
            let location
            if(localStorage.getItem("location")){
                location = localStorage.getItem("location")
            }else if(localStorage.getItem("temp_location")){
                location = localStorage.getItem("temp_location")
            }
            this.getStockDetails([location])
        },
        locatorButtonPressed() {
            navigator.geolocation.getCurrentPosition(
                position => {
                    console.log(position.coords.latitude);
                    console.log(position.coords.longitude);
                    this.getStreetAddressFrom(position.coords.latitude, position.coords.longitude)
                },
                error => {
                    console.log(error.message);
                },
            )
        },
        async getStreetAddressFrom(lat, long) {
            try {
                await axios.get(
                "https://maps.googleapis.com/maps/api/geocode/json?latlng=" +
                    lat +
                    "," +
                    long +
                    "&key=AIzaSyB3GgFD8YthniTjvyzOr-b6jw-CftRmy4o",
                    {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        responseType: 'json',
        withCredentials: true,
    }
                ).then((res) => {
                    console.log('s')
                    console.log(res)
                    if(res.error_message) {
                        console.log(res.error_message)
                    } else {
                        this.address = res.results[0].formatted_address;
                    }
                })
            } catch (error) {
                console.log(error.message);
            } 
        }
    },
    created(){
        if(localStorage.getItem("temp_pincode")){
            console.log(1)
            this.$store.commit('PINCODE_FORM', false)
        }else if(localStorage.getItem("pincode")){
            console.log(2)
            this.$store.commit('PINCODE_FORM', false)
        }
        if(this.showCustomerLocationForm){
            console.log(3)
            //this.$store.commit('PINCODE_FORM', false)
        }else{
            console.log(4)
            this.getStockDetailsInfo();
            this.getDealItems();
            this.getCartItems();
            this.cookieAccepted = localStorage.getItem("user_accept_cookie")
            let location = localStorage.getItem('location')
        }
    },
    mounted() {
        // this.locatorButtonPressed()
        if(this.$page.props.auth.user) {
        }
        this.getWishlistItems();
        // $('#customerLocation').modal('show');
    }

};
</script>

<template>
    <div>
        <div v-if="!showCustomerLocationForm">
            <theme-header @show-sidecart="displaySideCart()" @show-mobilemenu="changeNavStatus()" :showNavigation="showNavigation" />
            <side-cart @close-sidebar="displaySideCart()" :showCart="sideCartStatus" />
            <slot />
            <theme-footer />
            <div class="ec-nav-toolbar">
                <div class="container">
                    <div class="ec-nav-panel">
                        <div class="ec-nav-panel-icons">
                            <a href="javascript:;" @click="showNavigation = !showNavigation" class="navbar-toggler-btn ec-header-btn ec-side-toggle">
                                <i class="ecicon eci-bars"></i>
                            </a>
                        </div>
                        <div class="ec-nav-panel-icons">
                            <a href="javascript:;" @click="sideCartStatus = !sideCartStatus" class="toggle-cart ec-header-btn ec-side-toggle">
                                <img src="/images/icons/cart.svg" class="svg_img header_svg" alt="" />
                                <span class="ec-cart-noti">{{cartQuantity}}</span>
                            </a>
                        </div>
                        <div class="ec-nav-panel-icons">
                            <Link :href="route('home')" class="ec-header-btn">
                                <img src="/images/icons/home.svg" class="svg_img header_svg" alt="" />
                            </Link>
                        </div>
                        <div class="ec-nav-panel-icons">
                            <Link :href="route('wishlist')" class="ec-header-btn">
                                <img src="/images/icons/wishlist.svg" class="svg_img header_svg" alt="" />
                                <span class="ec-cart-noti">0</span>
                            </Link>
                        </div>
                        <div class="ec-nav-panel-icons">
                            <Link :href="route('login')" class="ec-header-btn">
                                <img src="/images/icons/enter.png" class="svg_img header_svg" alt="" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center container mt-5 cookie-popup" v-if="cookieAccepted != 'accepted'">
                <div class="row">
                    <div class="col-md-10">
                        <div class="d-flex flex-row justify-content-between align-items-center card cookie p-3">
                            <div class="d-flex flex-row align-items-center">
                                <div class="ml-2 mr-2">
                                    <b>Do you like cookies?</b> 🍪 We use cookies to ensure you get the best experience on our website.
                                </div>
                            </div>
                            <div><button class="btn btn-dark" type="button" @click="saveCookieStatus()">Okay</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="d-flex justify-content-center container mt-5 cookie-popup " id="customerLocation"  data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="customerLocationTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document" style="width: 523px;">
                <div class="modal-content">
                    <form >
                        <div class="modal-header">
                            <h5 class="modal-title" id="customerLocationTitle">Location</h5>
                            
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" placeholder="Enter your address" v-model="address" ref="autocomplete"/>
                                
                                <button type="button" @click="locatorButtonPressed()">Location</button>
                            </div>
                            <div class="form-group">
                                <label for="zip-code" >Enter zip code</label>
                                <input v-model="pincode" id="zip-code" type="number" placeholder="Enter zip code" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" @click="checkPincode()"> Submit </button>
                            <button type="button" class="btn btn-danger" @click="cancelLocation()"> Cancel </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        

    </div>
</template>
<style>
    .card {
        flex-direction: column;
        min-width: 0;
        color: #000;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid #fff;
        border-radius: 6px;
        -webkit-box-shadow: 0px 0px 5px 0px rgb(249, 249, 250);
        -moz-box-shadow: 0px 0px 5px 0px rgba(212, 182, 212, 1);
        box-shadow: 0px 0px 5px 0px rgb(161, 163, 164);
    }

    .learn-more {
        text-decoration: none;
        color: #000;
        margin-top: 8px;
    }

    .learn-more:hover {
        text-decoration: none;
        color: blue;
        margin-top: 8px;
    }
</style>
