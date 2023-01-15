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
    pre_path: String,
    pre_path_segment: String
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
    pre_path: '',
});
const submit = () => {
    form.pre_path = props.pre_path
    form.pre_path_segment = props.pre_path_segment
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Log in" />

        <section class="ec-page-content section-space-p">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="section-title border-0">
                            <h2 class="ec-bg-title">Log In</h2>
                            <h2 class="ec-title">Log In</h2>
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
                                <span class="ec-login-wrap">
                                    <label>Email Address*</label>
                                    <BreezeInput id="email" type="email" class="mt-1" v-model="form.email" required autofocus autocomplete="username" />
                                </span>
                                <span class="ec-login-wrap">
                                    <label>Password*</label>
                                    <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
                                </span>
                                <span class="ec-login-wrap ec-login-fp">
                                    <label>
                                        <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                                            Forgot your password?
                                        </Link>
                                    </label>
                                </span>
                                <!-- <input type="text" id="back-url" name="pre_path" v-model="form.pre_path" :value="pre_path"/> -->
                                <span class="ec-login-wrap ec-login-btn">
                                    <BreezeButton class="btn btn-primary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Log in
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