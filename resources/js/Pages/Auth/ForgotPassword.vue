<script>
import Form from "vform";
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Main.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

export default {
    props:{
        status: String,
    },
    components: {
        BreezeButton, BreezeGuestLayout, BreezeInput, BreezeLabel, BreezeValidationErrors
    },
    data() {
        return {
            form: new Form({
                mobile: '',
                otp:'',
                password:'12345678',
                password_confirmation: '12345678',
            }),
            showOtpPassword : false,
            otpError:false,
            otpErrorMsg:false,
            otpWrongError:false,
        }
    },
    methods:{
        getOTP(){
            this.showOtpPassword = false
            this.otpError = false
            this.otpErrorMsg = false
            
            this.form.post('/api/generate-forgot-password-otp').then((response) => {
                if(response.data.status == 'success'){
                    this.showOtpPassword = true
                    //this.form.otp = response.data.otp
                }else{
                    this.otpError = true;
                    this.otpErrorMsg = response.data.msg
                }
            })            
        },
        digits_count(n) {
            var count = 0;
            if (n >= 1) ++count;

            while (n / 10 >= 1) {
                n /= 10;
                ++count;
            }

            return count;
        },
        changePassword(){
            this.form.post('/api/change-password-customer').then((response) => {
                if(response.data.status == 'success'){
                    Inertia.get('/loginRedirect')
                }
            })
        }
    },
    mounted() {
        
    }    
    // form.post(route('password.email'));
}
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Forgot Password" />

        <section class="ec-page-content section-space-p">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="section-title">
                            <h2 class="ec-bg-title">Forgot Your Password?</h2>
                            <h2 class="ec-title">Forgot Your Password?</h2>
                            <p class="sub-title mb-3">No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
                        </div>
                        <BreezeValidationErrors class="mb-4 text-danger" />
                        <div v-if="status" class="mb-4 font-medium text-sm text-success">
                            {{ status }}
                        </div>
                    </div>
                </div>
                <div class="ec-login-wrapper">
                    <div class="ec-login-container">
                        <div class="ec-login-form">
                            <form @submit.prevent="submit">
                                <span class="ec-login-wrap">
                                    <label>Mobile No*</label>
                                    <BreezeInput id="email" type="text" class="mt-1" v-model="form.mobile" required autofocus  />
                                    <div class="form-err-msg" v-if="form.errors.has('mobile')" v-html="form.errors.get('mobile')" />   
                                </span>
                                <span class="ec-login-wrap" v-if="showOtpPassword">
                                    <BreezeLabel for="otp" value="OTP" />
                                    <BreezeInput id="otp" type="number" class="mt-1 block w-full" v-model="form.otp" autofocus autocomplete="off" placeholder="Enter OTP" />
                                    <div class="form-err-msg" v-if="otpError" v-html="otpErrorMsg" /> 
                                    <div class="form-err-msg" v-if="otpWrongError" v-html="'Incorrect OTP'" /> 
                                    <div class="form-err-msg" v-if="form.errors.has('otp')" v-html="form.errors.get('otp')" />
                                </span> 
                                <span class="ec-login-wrap" v-if="showOtpPassword">
                                    <BreezeLabel for="password" value="Password" />
                                    <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
                                    <div class="form-err-msg" v-if="form.errors.has('password')" v-html="form.errors.get('password')" />
                                </span>
                                    
                                <span class="ec-login-wrap" v-if="showOtpPassword">
                                    <BreezeLabel for="password_confirmation" value="Confirm Password" />
                                    <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
                                    <div class="form-err-msg" v-if="form.errors.has('password_confirmation')" v-html="form.errors.get('password_confirmation')" />
                                </span>
                                <span class="ec-login-wrap ec-login-btn">
                                    
                                    <BreezeButton class="btn btn-primary" type="button" @click="changePassword()" v-if="showOtpPassword">
                                        Change Password
                                    </BreezeButton>
                                    <BreezeButton class="btn btn-primary" type="button" @click="getOTP()" v-else>
                                        Continue
                                    </BreezeButton>
                                    <hr>
                                    <Link :href="route('register')" class="text-center margin-auto text-sm">Don't have account, Register here</Link>
                                </span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </BreezeGuestLayout>
</template>
