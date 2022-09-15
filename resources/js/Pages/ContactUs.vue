<template>
    <Head title="Page" />
    <AuthenticatedLayout>
        <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row ec_breadcrumb_inner">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="ec-breadcrumb-title">Contact Us</h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <ul class="ec-breadcrumb-list">
                                    <li class="ec-breadcrumb-item"><Link :href="route('home')">Home</Link></li>
                                    <li class="ec-breadcrumb-item active">Contact Us</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="ec-page-content section-space-p">
            <div class="container">
                <div class="row">
                    <div class="ec-common-wrapper">
                        <div class="ec-contact-leftside">
                            <div class="ec-contact-container">
                                <div class="ec-contact-form">
                                    <form @submit.prevent="submit">
                                        <span class="ec-contact-wrap">
                                            <label>Name*</label>
                                            <input type="text" name="name" placeholder="Enter your name" v-model="form.name" />
                                             <div class="form-err-msg" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                                        </span>
                                        <span class="ec-contact-wrap">
                                            <label>Email*</label>
                                            <input type="email" name="email" placeholder="Enter your email address" v-model="form.email" />
                                             <div class="form-err-msg" v-if="form.errors.has('email')" v-html="form.errors.get('email')" />
                                        </span>
                                        <span class="ec-contact-wrap">
                                            <label>Phone Number*</label>
                                            <input type="text" name="phone" placeholder="Enter your phone number" v-model="form.phone" />
                                             <div class="form-err-msg" v-if="form.errors.has('phone')" v-html="form.errors.get('phone')" />
                                        </span>
                                        <span class="ec-contact-wrap">
                                            <label>Comments/Questions*</label>
                                            <textarea name="message" v-model="form.message" placeholder="Please leave your comments here.."></textarea>
                                             <div class="form-err-msg" v-if="form.errors.has('message')" v-html="form.errors.get('message')" />
                                        </span>
                                        <span class="ec-contact-wrap ec-contact-btn">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </span>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="ec-contact-rightside">
                            <div class="ec_contact_map">
                                <div class="ec_map_canvas">
                                    <div v-html="currency.contact_iframe"></div>
                                </div>
                            </div>
                            <div class="ec_contact_info">
                                <h1 class="ec_contact_info_head">Contact us</h1>
                                <ul class="align-items-center">
                                    <li class="ec-contact-item">
                                        <i class="ecicon eci-map-marker" aria-hidden="true"></i>
                                        <span>Address :</span>
                                        {{ currency.company_address }}    
                                    </li>
                                    <li class="ec-contact-item align-items-center">
                                        <i class="ecicon eci-phone" aria-hidden="true"></i>
                                        <span>Call Us :</span>
                                        <a href="javascript:;"> {{ currency.contact_nos }}  </a>
                                    </li>
                                    <li class="ec-contact-item align-items-center">
                                        <i class="ecicon eci-envelope" aria-hidden="true"></i>
                                        <span>Email :</span>
                                        <a href="javascript:;">{{ currency.notification_emails }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
<script>
import { mapGetters } from 'vuex';
import AuthenticatedLayout from "@/Layouts/Main.vue";
import Form from 'vform';

export default {
    name: "Contact Us",
    props: {
       page:Object
    },
    components: {
        AuthenticatedLayout
    },
    data() {
        return {
            form: new Form({
                name:'',
                email:'',
                mobile:'',
                message:''
            })
        }
    },
    computed:{
        ...mapGetters(['currency']),
    },
    methods: {
        submit() {
            this.form.post('/api/submit-query').then((res) => {
                this.form.reset();
                this.$toast.success('Your query has been submitted successfully. One of our executive will contact you shortly.');
            }).catch(err => {
                this.$toast.error('Please fill all the fields and submit again.');
            })
        }
    }
};
</script>