<script>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { mapActions } from 'vuex';
import { useForm } from '@inertiajs/inertia-vue3';
import axios from 'axios';
export default {
    name: "Dashboard",
    props: {
       user:Object,
    },
    components: {
        AuthenticatedLayout,
    },
    data() {
        return {
            
        }
    },
    methods: {
        ...mapActions(['getWishlistItems', 'getCartItems']),
       
    },
    mounted() {
       
    }
    
};
</script>
<template>
    <Head title="Refer & Earn" />

    <AuthenticatedLayout>
        <div class="ec-vendor-dashboard-card ec-vendor-setting-card">
            <div class="ec-vendor-card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="ec-vendor-block-profile ec-check-login-form">
                            <div class="ec-vendor-block-img space-bottom-30">
                                <div class="ec-vendor-block-bg" >
                                  <img src="/images/refer-earn-desktop.png" style="max-height:200px; width:100%;"/>
                                </div>
                              </div>
                              
                            <h5>Rerer & Earn</h5>
                            <button type="button" class="btn btn-primary">
                                {{ user.c_referer_code }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
.pic23{
    line-height: 23px;
    cursor: pointer;
    padding-top: 9px;
    padding-left: 9px;
}
.error{
    color: red;
    font-size:small;
}
</style>