<script setup>
import GuestLayout from "@/Layouts/Guest.vue";
import { defineComponent } from 'vue'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

defineComponent({
  
    HeaderVue,SideCart,Footer
});
defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <section class="ec-page-content section-space-p">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="section-title">
                            <!-- <h2 class="ec-bg-title">Log In</h2> -->
                            <h2 class="ec-title">Log In</h2>
                            <p class="sub-title mb-3">Best place to buy and sell digital products</p>
                        </div>
                    </div>
                    <div class="ec-login-wrapper">
                        <div class="ec-login-container">
                            <div class="ec-login-form">
                                <BreezeValidationErrors class="mb-4" />
                                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                                    {{ status }}
                                </div>
                                <form @submit.prevent="submit">
                                    <div>
                                        <BreezeLabel for="email" value="Email" />
                                        <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="username" />
                                    </div>

                                    <div class="mt-4">
                                        <BreezeLabel for="password" value="Password" />
                                        <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
                                    </div>

                                    <div class="block mt-4">
                                        <label class="flex items-center">
                                            <BreezeCheckbox name="remember" v-model:checked="form.remember" />
                                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                                        </label>
                                    </div>

                                    <div class="flex items-center justify-end mt-4">
                                        <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                                            Forgot your password?
                                        </Link>

                                        <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                            Log in
                                        </BreezeButton>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>
