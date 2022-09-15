<script>
import AuthenticatedLayout from "@/Layouts/Main.vue";
import { mapActions } from 'vuex';

export default {
    name: "Page",
    props: {
       page:Object
    },
    components: {
        AuthenticatedLayout,
        
    },
   
};
</script>
<template>
    <Head title="Page" />

    <AuthenticatedLayout>
        <section class="ec-page-content section-space-p mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="section-title">
                        <h2 class="ec-bg-title">{{ page.title }}</h2>
                        <h2 class="ec-title">{{ page.title }}</h2>
                        <p class="sub-title mb-3">{{ page.meta_description }}</p>
                    </div>
                </div>
                <div class="ec-common-wrapper" v-html="page.content">
                    
                </div>
            </div>
        </div>
    </section>
    </AuthenticatedLayout>
</template>
