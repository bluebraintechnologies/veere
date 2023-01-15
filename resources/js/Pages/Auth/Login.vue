<script>
import Form from "vform";
import BreezeButton from '@/Components/Button.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeGuestLayout from '@/Layouts/Main.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
export default {
    props: {
        canResetPassword: Boolean,
        status: String,
        pre_path: String,
        pre_path_segment: String
    },
    components: {
        BreezeButton, BreezeCheckbox, BreezeGuestLayout, BreezeInput, BreezeLabel, BreezeValidationErrors
    },
    data() {
        return {
            form: new Form({
                mobile: '',
                otp:'',
                password: '',
                remember: false,
                pre_path: '',
            }),
            showOtpBlock:false,
            otpError:false,
            otpErrorMsg:false,
            otpWrongError:false,
        }
    },
    methods:{
        getOTP(){
            this.otpWrongError = false;
            this.showOtpBlock = false
            this.otpError = false
            this.otpErrorMsg = false
            this.form.post('/api/login-otp').then((response) => {
                if(response.data.status == 'success'){
                    this.showOtpBlock = true
                    this.form.otp = response.data.otp
                    this.form.pre_path = this.pre_path
                    this.form.pre_path_segment = this.pre_path_segment
                }else{
                    this.otpError = true;
                    this.otpErrorMsg = response.data.msg
                }
            }) 
        },
        login(){
            this.otpWrongError = false;
            this.form.post('/api/check-login').then((response) => {
                if(response.data.status == 'success'){
                    this.form.post('/login').then((response) => {
                        location.reload()
                    })
                }else{
                    this.otpWrongError = true
                }
            }) 
        }
    }
}
    // const props = defineProps({
    //     canResetPassword: Boolean,
    //     status: String,
    //     pre_path: String,
    //     pre_path_segment: String
    // });

    // const form = useForm({
    //     email: '',
    //     password: '',
    //     remember: false,
    //     pre_path: '',
    // });
    // const submit = () => {
    //     form.pre_path = props.pre_path
    //     form.pre_path_segment = props.pre_path_segment
    //     form.post(route('login'), {
    //         onFinish: () => form.reset('password'),
    //     });
    // };
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Log in" />

        <section class="ec-page-content mobile-page section-space-p">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="section-title border-0">
                            <h2 class="ec-bg-title">Log In</h2>
                            <h2 class="ec-title">Log In</h2>
                            <!-- <p class="sub-title mb-3">Best place to buy and sell digital products</p> -->
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
                                    <label>Mobile</label>
                                    <BreezeInput id="mobile" type="text" class="mt-1" v-model="form.mobile" required autofocus autocomplete="mobile" placeholder="Enter your mobile" :readonly="showOtpBlock"/>
                                    <div class="form-err-msg" v-if="form.errors.has('mobile')" v-html="form.errors.get('mobile')" ></div>
                                    <div class="form-err-msg" v-if="otpError" v-html="otpErrorMsg" ></div>
                                    <div class="form-err-msg" v-if="otpWrongError" v-html="'Incorrect OTP'" ></div>
                                </span>
                                <span class="ec-login-wrap" v-if="showOtpBlock">
                                    <label>otp*</label>
                                    <BreezeInput id="password" type="text" class="mt-1 block w-full" v-model="form.otp" required placeholder="Enter otp"/>
                                    <div class="form-err-msg" v-if="form.errors.has('otp')" v-html="form.errors.get('otp')" ></div>
                                </span>
                                <span class="ec-login-wrap ec-login-btn">                                    
                                    <BreezeButton class="btn btn-primary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" v-if="showOtpBlock" @click="login()">
                                        Log in
                                    </BreezeButton>
                                    <BreezeButton class="btn btn-primary" type="button" @click="getOTP()" v-else>
                                        Continue
                                    </BreezeButton>
                                    <hr>
                                    <Link :href="route('register')" class="text-center margin-auto text-sm">
                                        Don't have account, Register here
                                    </Link>
                                    <!-- <Link v-if="canResetPassword" :href="route('password.request')" class="text-center margin-auto text-sm">
                                        Forgot your password?
                                    </Link> -->
                                </span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </BreezeGuestLayout>
</template>