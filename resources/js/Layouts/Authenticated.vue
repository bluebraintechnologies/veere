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
            pincode:'',            
            addresses:'',
            selectedAddress:'',
        };
    },
    computed: {
        ...mapGetters(['wishlistItems', 'cartItems', 'cookieStatus', 'cartQuantity', 'showCustomerLocationForm'])
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
            localStorage.setItem("user_accept_cookie", 1)
            this.cookieAccepted = localStorage.getItem("user_accept_cookie")
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
        },
        cancelLocation(){
            // if user refuse to give location
            if(localStorage.getItem("temp_pincode")){                
                this.$store.commit('PINCODE_FORM', false)
            }else{
                localStorage.setItem("temp_pincode", 110001)
                localStorage.setItem("temp_location", 1)
                location.reload()
            }
        },
        locatorButtonPressed() {
            navigator.geolocation.getCurrentPosition(
                position => {
                    this.getStreetAddressFrom(position.coords.latitude, position.coords.longitude)
                },
                error => {
                    
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
                    location.reload()
                }else{
                    this.$toast.error('Sorry, our service is not available at this location.');
                }
            }) 
                    
        },
    },
    created(){
        
        this.cookieAccepted = localStorage.getItem("user_accept_cookie")
        if(localStorage.getItem('addresses')){
            this.addresses = JSON.parse(localStorage.getItem('addresses'))
        }
        if(localStorage.getItem('addresses')){
            this.addresses = JSON.parse(localStorage.getItem('addresses'))
        }
        
        if(localStorage.getItem("pincode")){            
            this.$store.commit('PINCODE_FORM', false)
        }else if(localStorage.getItem("temp_pincode")){            
            this.$store.commit('PINCODE_FORM', false)
        }

        if(this.showCustomerLocationForm){            
            //this.$store.commit('PINCODE_FORM', false)
        }else{ 
            this.getStockDetailsInfo();
            this.getDealItems();
            this.getCartItems();
        }
    },
    mounted() {        
        this.getRewardPoints()
        this.getWishlistItems();
        this.storeAddressInLocalStorage()
    }
};
</script>

<template>
        <div v-if="showCustomerLocationForm" class="d-flex justify-content-center container mt-5 cookie-popup " id="customerLocation"  data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="customerLocationTitle" aria-hidden="true">
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
        <div v-else>
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
