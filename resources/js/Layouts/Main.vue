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
            pincode:'',            
            addresses:'',
            selectedAddress:'',
            
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
            localStorage.setItem("user_accept_cookie", 1)
            this.cookieAccepted = localStorage.getItem("user_accept_cookie")
        },
        checkPincode(){
            // before saving check pincode exist in our system of not
            if(this.pincode == '' && this.selectedAddress == ''){
                this.$toast.error('Please select address or enter pin code.');
                return false
            }
            var pincode;
            if(this.pincode != '' ){
                pincode = this.pincode
            }
            if(this.selectedAddress != '' ){
                pincode = this.selectedAddress
            }
            this.pincode = pincode
            axios.post('/api/check-pincode-in-system', {pincode : pincode}).then((response) => {
                if(response.data.available){
                    // save this pincode in local storage
                    localStorage.setItem("pincode", this.pincode)
                    localStorage.setItem("location", response.data.location)
                    localStorage.removeItem("temp_pincode")
                    localStorage.removeItem("temp_location")
                    location.reload()
                }else{
                    this.$toast.error('Sorry, our service is not available at this location.');
                }
            }) 
                        
            // get get data account to pincode mapping with business location
        },
        cancelLocation(){
            // if user refuse to give location
            if(localStorage.getItem("pincode")){                
                this.$store.commit('PINCODE_FORM', false)
            }else{
                if(localStorage.getItem("temp_pincode")){
                    this.$store.commit('PINCODE_FORM', false)
                }else{
                    localStorage.setItem("temp_pincode", 110001)
                    localStorage.setItem("temp_location", 1)
                    location.reload()
                }
            }
        },
        getStockDetailsInfo(){
            let location = 1
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
                    this.getStreetAddressFrom(position.coords.latitude, position.coords.longitude)
                },
                error => {
                    this.$toast.error('Sorry for inconvenience, please try again!!');    
                },
            )            
        },
        getStreetAddressFrom(lat, long){
            axios.get('/api/get-street-address-from?lat=' + lat +'&long=' + long).then((response) => {                
                if(parseInt(response.data.postal_code)){
                    var p = parseInt(response.data.postal_code)
                    axios.post('/api/check-pincode-in-system', {pincode : response.data.postal_code}).then((response) => {
                        if(response.data.available){
                            // save this pincode in local storage
                            localStorage.setItem("pincode", this.pincode)
                            localStorage.setItem("location", response.data.location)
                            localStorage.removeItem("temp_pincode")
                            localStorage.removeItem("temp_location")
                            location.reload()
                        }else{
                            this.$toast.error('Sorry, our service is not available at postal code: ' + p);
                        }
                    })
                }else{
                    this.$toast.error('Please select address or enter pin code.');
                }
            })
        },
        storeAddressInLocalStorage(){
            if(localStorage.getItem('user_accept_cookie')){
                axios.get('/api/store-address-in-local-storage').then((response) => {
                    if(response.data.address.length > 0){
                        if(localStorage.getItem('addresses')){
                            localStorage.removeItem('addresses')
                        }
                        localStorage.setItem('addresses', JSON.stringify(response.data.address))
                    }
                })
            }
        }
    },
    created(){
        if(localStorage.getItem('addresses')){
            this.addresses = JSON.parse(localStorage.getItem('addresses'))
        }
        if(localStorage.getItem("pincode")){            
            this.$store.commit('PINCODE_FORM', false)
        }else if(localStorage.getItem("temp_pincode")){            
            this.$store.commit('PINCODE_FORM', false)
        }
        // if(this.showCustomerLocationForm){            
        //     //this.$store.commit('PINCODE_FORM', false)
        // }else{            
            this.getStockDetailsInfo();
            this.getDealItems();
            this.getCartItems();
            if(localStorage.getItem("user_accept_cookie")){
                this.cookieAccepted = localStorage.getItem("user_accept_cookie")
            }
            
        // }
    },
    mounted() {
        // this.locatorButtonPressed()
        if(this.$page.props.auth.user) {
        }
        this.getWishlistItems();
        this.storeAddressInLocalStorage()
        // $('#customerLocation').modal('show');
    }

};
</script>

<template>
    <div>
        
        
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
            <div class="d-flex justify-content-center container mt-5 cookie-popup cookie-popup-veere" v-if="!cookieAccepted" style="">
                <div class="row">
                    <div class="col-md-10">
                        <div class="d-flex flex-row justify-content-between align-items-center card cookie p-3">
                            <div class="d-flex flex-row align-items-center">
                                <div class="ml-2 mr-2">
                                    <b>Accept Cookies</b>
                                </div>
                            </div>
                            <div><button class="btn btn-dark" type="button" @click="saveCookieStatus()">Okay</button></div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="showCustomerLocationForm && cookieAccepted == 1" class="d-flex justify-content-center container mt-5 cookie-popup cookie-popup-veere-add" id="customerLocation"  data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="customerLocationTitle" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document" style="width: 523px;">
                    <div class="modal-content">
                        <form >
                            <div class="modal-header">
                                <h5 class="modal-title" id="customerLocationTitle">Location</h5>
                                
                            </div>
                            <div class="modal-body">
                                <div class="form-group" style="border:1px solid black !important; width:100%; line-height: 42px; height: 42px; font-size: 14px;">                                
                                    <button type="button" @click="locatorButtonPressed()">Location (GPS)</button>
                                </div>
                                <div class="form-group" v-if="addresses.length > 0">
                                    <label for="zip-code" >Address</label>
                                    <select v-model="selectedAddress" style="border:1px solid black !important; width:100%; line-height: 42px; height: 42px; font-size: 14px;" >
                                        <option selected value="">Select</option>
                                        <option  v-for="add in addresses" :key=" 'address-' + add.id" :value="add.postal_code">{{ add.name +', '+ add.address +', ' + add.landmark + ', ' + add.city + ', Pin code ' + add.postal_code }}</option>
                                    </select>
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
    .cookie-popup-veere{
        height: 100%; width: 100%; 
        text-align: center; 
        background-color: #9b881c2e;
    }
    .cookie-popup-veere-add{
        height: 100%; width: 100%;         
        background-color: #9b881c2e;
    }
</style>
