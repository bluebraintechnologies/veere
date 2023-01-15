<script>
import Form from "vform";
import BreezeButton from '@/Components/Button.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeGuestLayout from '@/Layouts/Main.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

export default {
    props:{

    },
    components: {
        BreezeButton,
        BreezeCheckbox,
        BreezeGuestLayout,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
    },
    data() {
        return {
            form: new Form({
                name: '',
                email: '',
                mobile: '',
                password: '',
                password_confirmation: '',
                terms: false,
                referer_code:'',
                otp:'',
                showotp:false,
            }),
            emailMobileExistError:false,
            otpError:false,
            otpWrongError:false,
            showOTP:false,
            alreadyRegisteredError: false,
        }
    },
    methods:{
        getOTP(){
            this.form.post('/api/get-otp').then((response) => {
                if(response.data.status == 'emai-mobile-exist' || response.data.status == 'mobile-exist' || response.data.status == 'emai-exist'){
                    this.emailMobileExistError = true
                } else if(response.data.status == 'success'){
                    this.$toast.success('An OTP has been sent on your mobile')
                    this.showOTP = true
                    if(response.data.env == 'local'){
                        this.form.otp = response.data.otp
                    }
                } else if(response.data.status == 'already-registered'){
                    this.alreadyRegisteredError = true
                }
            })
        },
        registerUser(){
            if(this.form.otp == ''){
                this.otpError = true
                return false;
            }
            this.otpError = false
            this.otpWrongError = false
            
            this.form.post('/api/register-customer').then((response) => {
                
                if(response.data.status == 'success'){
                    Inertia.get('/loginRedirect')
                } else if(response.data.status == 'otp-wrong'){
                    this.otpWrongError = true
                } 
            })
        }
    },
    mounted() {
        
    }
}

</script>
<style>
.form-err-msg{
    top: -15px;
}
</style>
<template>
    <BreezeGuestLayout>
        <Head title="Log in" />

        <section class="ec-page-content mobile-page section-space-p">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="section-title border-0">
                            <h2 class="ec-bg-title">Create Account</h2>
                            <h2 class="ec-title">Create Account</h2>
                            <p class="sub-title mb-3">Best place to buy products</p>
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
                            
                            <div class="form-err-msg" v-if="alreadyRegisteredError" v-html="'Email/Mobile already registered '" /> 
                            <div class="form-err-msg" v-if="emailMobileExistError" v-html="'Emial/Mobile already exists'" /> 
                             <form >
                                <span class="ec-login-wrap">
                                    <BreezeLabel for="mobile" value="Mobile" />
                                    <BreezeInput id="mobile" type="number" class="mt-1 block w-full" v-model="form.mobile" required autofocus autocomplete="mobile" />  
                                    <div class="form-err-msg" v-if="form.errors.has('mobile')" v-html="form.errors.get('mobile')" />                                  
                                </span>
                                <span class="ec-login-wrap">
                                    <BreezeLabel for="name" value="Name" />
                                    <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                                    <div class="form-err-msg" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                                </span>

                                <span class="ec-login-wrap">
                                    <BreezeLabel for="email" value="Email" />
                                    <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />
                                    <div class="form-err-msg" v-if="form.errors.has('email')" v-html="form.errors.get('email')" />
                                </span>

                                <!-- <span class="ec-login-wrap">
                                    <BreezeLabel for="password" value="Password" />
                                    <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
                                    <div class="form-err-msg" v-if="form.errors.has('password')" v-html="form.errors.get('password')" />
                                </span>
                                    
                                <span class="ec-login-wrap">
                                    <BreezeLabel for="password_confirmation" value="Confirm Password" />
                                    <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
                                    <div class="form-err-msg" v-if="form.errors.has('password_confirmation')" v-html="form.errors.get('password_confirmation')" />
                                </span> -->
                                <span class="ec-login-wrap">
                                    <BreezeLabel for="referer_code" value="Referer Code" />
                                    <BreezeInput id="referer_code" type="text" class="mt-1 block w-full" v-model="form.referer_code" autofocus autocomplete="off" placeholder="optional" />
                                </span>
                                <span class="ec-login-wrap" v-if="showOTP">
                                    <BreezeLabel for="otp" value="OTP" />
                                    <BreezeInput id="otp" type="number" class="mt-1 block w-full" v-model="form.otp" autofocus autocomplete="off" placeholder="Enter OTP" />
                                    <div class="form-err-msg" v-if="otpError" v-html="'Please enter OTP'" /> 
                                    <div class="form-err-msg" v-if="otpWrongError" v-html="'Incorrect OTP'" /> 
                                    <div class="form-err-msg" v-if="form.errors.has('otp')" v-html="form.errors.get('otp')" />
                                </span>                                
                                <span class="ec-login-wrap ec-login-btn">
                                    <BreezeButton class="btn btn-primary" :type="'button'" v-if="showOTP" @click="registerUser()">
                                        Register
                                    </BreezeButton>
                                    <BreezeButton class="btn btn-primary" :type="'button'" @click="getOTP()" v-else>
                                        Continue
                                    </BreezeButton>
                                    <hr>
                                    <Link :href="route('login')" class="text-center margin-auto text-sm">Already registered? Login Heree</Link>
                                </span>
                                <!-- <span class="ec-login-wrap ec-login-btn">
                                    <BreezeButton class="btn btn-primary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Register
                                    </BreezeButton>
                                    <hr>
                                    <Link :href="route('login')" class="text-center margin-auto text-sm">Already registered? Login Heree</Link>
                                </span> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </BreezeGuestLayout>
</template>
