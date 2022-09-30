<script>
import Form from "vform";
import { HasError } from 'vform/src/components/bootstrap5';
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { mapActions, mapGetters } from 'vuex';
import { Inertia } from '@inertiajs/inertia';

export default {
    name: "Dashboard",
    components:{},
    props: {
       addresses:Object|Array,
       userinfo:Object,
    },
    components: {
        ...mapGetters(['cityList']),
        AuthenticatedLayout,
    },
    data(){
        return{
            showAddress : true,
            editMode : false,
            form: new Form({
                id:'',
                name:'',
                phone:'',
                address:'',
                city:'',
                postal_code:'',
                landmark:''
            })
        }
    },
    methods: {
        ...mapActions(['getWishlistItems', 'getCartItems']),
        saveAddress(){
            this.form.user_id = this.userinfo.id
            this.form.post('/api/address').then((response) => {
                this.$toast.success('address saved successfully.')
                this.form.reset()
                this.showAddress = true
                Inertia.reload()
            })
        },
        updateAddress(){
            this.form.user_id = this.userinfo.id
            this.form.put('/api/address/'+ this.form.id).then((response) => {
                this.$toast.success('address updated successfully.')
                this.form.reset()
                this.editMode = false
                this.showAddress = true
                Inertia.reload()
            })
        },
        editAddress(address){
            this.form.fill(address)
            this.showAddress = false
            this.editMode = true
        },
        deleteAddress(aid) {
            this.$swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this address!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/api/address/'+aid, {id:aid}).then((response) => {
                        this.$toast.success('address deleted successfully.')
                        Inertia.reload()
                    })
                }
            })
        },
        setAsDefaultDelivery(address){
            axios.post('/api/set-as-delivery-address', {address : address}).then((response) => {
                Inertia.reload()
            })
        },
        setAsDefaultBilling(address){
            axios.post('/api/set-as-billing-address', {address : address}).then((response) => {
                Inertia.reload()
            })
        },
    },
    mounted() {
        this.getWishlistItems();
        this.getCartItems();
    }
    
};
</script>
<template>
    <Head title="User Dashboard" />

    <AuthenticatedLayout>
        <div class="ec-vendor-dashboard-card space-bottom-30">
            <div class="ec-vendor-card-header">
                <h5>Address Book</h5>
                <div class="ec-header-btn">
                    <button type="button" class="btn btn-lg btn-primary" @click="showAddress = !showAddress">
                       <i class="ecicon eci-plus"></i> Add New Address
                    </button>
                </div>
            </div>
            <div class="ec-vendor-card-body">
                <div class="row" v-show="showAddress">
                    <div class="col-md-4 col-sm-12 mb-4" v-for="(address, index) in addresses" :key="'address-' + index">
                        <div class="ec-vendor-detail-block ec-vendor-block-address border">
                            <h6>{{ address.name }}
                                <span>
                                    <button type="button" title="Set Default Billing" @click="[(address.set_default_billing) ? '' : setAsDefaultBilling(address)]">
                                        <i class="bi " :class="[(address.set_default_billing) ? 'bi bi-file-earmark-easel-fill' : 'bi bi-file-earmark-easel']"></i>
                                    </button>
                                    <button type="button" title="Set Default Delivery" @click="[(address.set_default) ? '' : setAsDefaultDelivery(address)]">
                                        <i class="bi " :class="[(address.set_default) ? 'bi bi-cart-check-fill' : 'bi-cart']"></i>
                                    </button>
                                    <!-- <i class="bi bi-bookmark-check-fill"></i> -->
                                    <button type="button" title="Edit Address" @click="editAddress(address)">
                                        <i class="ecicon eci-edit"></i>
                                    </button>
                                    <button type="button" title="Delete Address" @click="deleteAddress(address.id)">
                                        <i class="ecicon eci-trash text-danger"></i>
                                    </button>
                                </span>
                            </h6>
                            <ul>
                                <li>{{ address.address }}</li>
                                <li><b>Landmark - </b> {{ address.landmark }}</li>
                                <li><b>Mob No. </b> {{ address.phone }}</li>
                                <li><b>City </b> {{ address.city }}</li>
                                <li><b>Zip Code </b> {{ address.postal_code }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="" v-show="!showAddress">
                    <!--  -->
                    <div class="ec-register-wrapper" style="max-width:100% !important;">
                        <div class="ec-register-container">
                            <div class="ec-register-form">
                                <form action="#" method="post">
                                    <span class="ec-register-wrap ec-register-half">
                                        <label>Full Name*</label>
                                        <input v-model="form.name" type="text" name="name" placeholder="Enter your name" required />
                                        <div class="form-err-msg-sm" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                                    </span>
                                    <span class="ec-register-wrap ec-register-half">
                                        <label>Phone Number*</label>
                                        <input v-model="form.phone" type="text" name="phonenumber" placeholder="Enter your phone number" required />
                                        <div class="form-err-msg-sm" v-if="form.errors.has('phone')" v-html="form.errors.get('phone')" />
                                    </span>
                                    <span class="ec-register-wrap ec-register-full">
                                        <label>Address</label>
                                        <textarea v-model="form.address" class="address" placeholder="Enter your address" required rows="5"></textarea>
                                        <div class="form-err-msg-sm" v-if="form.errors.has('address')" v-html="form.errors.get('address')" />
                                    </span>
                                    <span class="ec-register-wrap ec-register-full">
                                        <label>Landmark</label>
                                        <textarea v-model="form.landmark" class="address" placeholder="Enter your landmark" required rows="2"></textarea>
                                        <div class="form-err-msg-sm" v-if="form.errors.has('landmark')" v-html="form.errors.get('landmark')" />
                                    </span>
                                    <span class="ec-register-wrap ec-register-half">
                                        <label>City </label>
                                        <input v-model="form.city" type="text" placeholder="Enter your city" required />
                                        <div class="form-err-msg-sm" v-if="form.errors.has('city')" v-html="form.errors.get('city')" />
                                    </span>
                                    <span class="ec-register-wrap ec-register-half">
                                        <label>Zip Code</label>
                                        <input v-model="form.postal_code" type="text" name="postalcode" placeholder="Post Code" />
                                        <div class="form-err-msg-sm" v-if="form.errors.has('postal_code')" v-html="form.errors.get('postal_code')" />
                                    </span>
                                    <span class="ec-register-wrap ec-register-half">
                                        <button class="btn btn-primary" type="button" v-if="!editMode" @click="saveAddress()">Save</button>
                                        <button class="btn btn-primary" type="button" v-if="editMode" @click="updateAddress()">Update</button>
                                    </span>
                                    <span class="ec-register-wrap ec-register-half">
                                        <button class="btn btn-warning" type="button" @click="showAddress = true">Cancel</button>
                                    </span>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
.address{
    border: 1px solid #ededed;
}
</style>