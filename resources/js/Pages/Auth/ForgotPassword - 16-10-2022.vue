<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Main.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
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
                                    <label>Email Address*</label>
                                    <BreezeInput id="email" type="email" class="mt-1" v-model="form.email" required autofocus autocomplete="username" />
                                </span>
                                <span class="ec-login-wrap ec-login-btn">
                                    <BreezeButton class="btn btn-primary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Recover Password
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
