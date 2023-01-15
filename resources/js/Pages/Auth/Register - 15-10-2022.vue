<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeGuestLayout from '@/Layouts/Main.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    canResetPassword: Boolean,
    status: String,
    otpStatus : {
        type : Number,
        default : 0,
    }
});

const form = useForm({
    name: '',
    email: '',
    mobile: '',
    password: '',
    password_confirmation: '',
    terms: false,
    referer_code:''
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
function saveMobile(){
    if(form.mobile == ''){
        props.otpStatus = 1
    }
    return false
    axios.post('/api/save-customer-mobile', {form : form}).then((response) => {
        props.otpStatus = response.data.otpStatus
    })
}
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Log in" />

        <section class="ec-page-content section-space-p">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="section-title border-0">
                            <h2 class="ec-bg-title">Create Account</h2>
                            <h2 class="ec-title">Create Account</h2>
                            <p class="sub-title mb-3">Best place to buy and sell digital products</p>
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
                                <!-- <span class="ec-login-wrap">
                                    <BreezeLabel for="name" value="Name" />
                                    <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                                    </span>

                                    <span class="ec-login-wrap">
                                        <BreezeLabel for="email" value="Email" />
                                        <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />
                                    </span>

                                    <span class="ec-login-wrap">
                                        <BreezeLabel for="password" value="Password" />
                                        <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
                                    </span>

                                    <span class="ec-login-wrap">
                                        <BreezeLabel for="password_confirmation" value="Confirm Password" />
                                        <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
                                    </span>
                                    
                                    <span class="ec-login-wrap">
                                    <BreezeLabel for="referer_code" value="Referer Code" />
                                    <BreezeInput id="referer_code" type="text" class="mt-1 block w-full" v-model="form.referer_code" autofocus autocomplete="off" placeholder="optional" />
                                </span> -->
                                <span class="ec-login-wrap">
                                    <BreezeLabel for="mobile" value="Mobile" />
                                    <BreezeInput id="mobile" type="number" class="mt-1 block w-full" v-model="form.mobile" required autofocus autocomplete="mobile" />
                                    <div class="form-err-msg" v-if="mobileError" v-html="mobileErrorMessage" />
                                </span>
                                <span class="ec-login-wrap ec-login-btn">
                                    <BreezeButton class="btn btn-primary" :type="'button'" @click="saveMobile()">
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
