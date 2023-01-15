<script>
import GuestLayout from "@/Layouts/Main.vue";
import { mapGetters } from "vuex";
import moment from 'moment';
import Form from "vform";

export default {
    name: "Category",
    components: {
        GuestLayout,
    },
    data() {
        return {
            form: new Form({
                name: '',
                email:'',
                mobile:'',
                message: '',
            }),
            successMessage : '',
            errorMessage : '',
            message : '',
        };
    },
    props: {
        
    },
    computed: {
        ...mapGetters(["navItems"]),        
    },
    methods: {
        saveEnquiry(){
            this.form.post('/api/save-enquiry').then((response) => {
                if(response.data.status == 'success'){
                    this.successMessage = true
                    this.form.reset()
                    this.$toast.success('Your enquiry has been saved, one of our team member will contact you soon')
                    // this.message = 
                }else{
                    this.errorMessage = true
                    this.message = 'Please try again'
                }
                
            })
        }
    },
    created(){
        
    },
};
</script>

<template>
    <Head title="Contact Us" />

    <GuestLayout>
        <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row ec_breadcrumb_inner">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="ec-breadcrumb-title">Contact Us </h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <ul class="ec-breadcrumb-list">
                                    <li class="ec-breadcrumb-item">
                                        <Link :href="route('home')">Home</Link>
                                    </li>
                                     <li class="ec-breadcrumb-item">
                                        Contact Us
                                    </li>
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
                    <div class="col-md-12 text-center">
                        <div class="section-title mb-0">
                            <h4 class="text-center">Contact Us</h4>
                            <!-- <h2 class="text-center">47 Years of Experience</h2> -->
                        </div>
                    </div>
                    <div class="ec-common-wrapper mt-3">                        
                        <div class="contact-us-info row text-center">    
                            <div class="col-xs-12 col-xl-4">                                
                                <dl class="contact-us-info-item">
                                    <dt>
                                        <svg width="36" height="36" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3 9H17V15C17 17.2091 15.2091 19 13 19H7C4.79086 19 3 17.2091 3 15V9Z" stroke="#5060C9" stroke-width="2"></path>
                                            <path d="M8 15C8 13.8954 8.89543 13 10 13C11.1046 13 12 13.8954 12 15V19H8V15Z" stroke="#5060C9" stroke-width="2"></path>
                                            <path d="M18.983 8.95825C18.9791 8.96158 18.9744 8.96507 18.9689 8.96857C18.9494 8.98086 18.9083 9 18.8332 9H1.16678C1.09175 9 1.05062 8.98086 1.03115 8.96857C1.02561 8.96508 1.02094 8.96158 1.01702 8.95825L9.84388 1.05036C9.87037 1.02663 9.92375 1 10 1C10.0763 1 10.1296 1.02663 10.1561 1.05036L10.8182 0.311349L10.1561 1.05036L18.983 8.95825Z" stroke="#5060C9" stroke-width="2"></path>
                                        </svg>                                
                                        <div class="contact-us-info-title">Address</div>
                                    </dt>
                                    <dd>
                                        <div class="contact-us-info-description">1st Floor, #137-138 Plot no. P-2, Best Business Park, Netaji Subhash Place, Shakurpur, Delhi - 110034</div>
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-xs-12 col-xl-4">
                                <dl class="contact-us-info-item">
                                    <dt>
                                        <svg width="36" height="36" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M18.9617 14.9467C18.9108 14.7933 18.5869 14.5673 17.9901 14.2693C17.8285 14.1753 17.5981 14.0477 17.3001 13.8857C17.0018 13.7237 16.731 13.5745 16.4883 13.4381C16.245 13.3018 16.0171 13.1697 15.8042 13.042C15.7702 13.0165 15.6636 12.942 15.4847 12.8181C15.3055 12.6947 15.1544 12.603 15.0306 12.5432C14.9071 12.4838 14.7855 12.4539 14.6662 12.4539C14.4958 12.4539 14.2829 12.5754 14.0273 12.8181C13.7717 13.0612 13.5372 13.3252 13.3243 13.611C13.1112 13.8966 12.8853 14.1606 12.6469 14.4035C12.4082 14.6465 12.212 14.7679 12.0588 14.7679C11.9818 14.7679 11.8859 14.7464 11.771 14.7041C11.6561 14.6615 11.5687 14.625 11.5086 14.5956C11.4492 14.5656 11.3471 14.5061 11.2021 14.4163C11.0569 14.3268 10.9761 14.2778 10.9591 14.2693C9.79146 13.6214 8.79007 12.8799 7.95472 12.045C7.11963 11.2095 6.378 10.2083 5.73028 9.0406C5.72177 9.02354 5.67264 8.94254 5.58322 8.79774C5.49366 8.6528 5.43397 8.55066 5.40415 8.49084C5.37433 8.43124 5.3381 8.34384 5.29556 8.22881C5.25302 8.11378 5.23166 8.01796 5.23166 7.94117C5.23166 7.78785 5.35315 7.59173 5.59607 7.35308C5.83895 7.11455 6.10324 6.88861 6.38866 6.6757C6.67431 6.46279 6.93832 6.22834 7.18129 5.97276C7.42418 5.71704 7.54562 5.50409 7.54562 5.33367C7.54562 5.21443 7.51579 5.09286 7.45619 4.96932C7.39655 4.84552 7.30493 4.69444 7.18129 4.51538C7.05761 4.33641 6.98306 4.22998 6.95744 4.19572C6.82973 3.98281 6.69777 3.75486 6.56124 3.51195C6.42471 3.26908 6.27568 2.99836 6.11367 2.70011C5.9518 2.40199 5.82404 2.17179 5.73019 2.00988C5.43205 1.41342 5.20623 1.08942 5.05277 1.03846C4.99308 1.0129 4.90352 1 4.78432 1C4.55407 1 4.25369 1.04254 3.88296 1.12788C3.5121 1.21309 3.22028 1.30246 3.00717 1.39636C2.58101 1.57524 2.12933 2.09509 1.65198 2.95574C1.21731 3.75669 1 4.54946 1 5.33344C1 5.56337 1.01491 5.78707 1.04473 6.00459C1.07456 6.22189 1.12784 6.46695 1.20464 6.73973C1.2813 7.01242 1.34314 7.21499 1.38989 7.3469C1.43668 7.4789 1.52405 7.71536 1.65194 8.05638C1.77965 8.3973 1.85644 8.606 1.88201 8.68271C2.18029 9.51796 2.53399 10.2636 2.94305 10.9199C3.61608 12.0107 4.53454 13.1381 5.6979 14.3015C6.8613 15.4647 7.98848 16.3831 9.07948 17.0564C9.73571 17.4653 10.4817 17.819 11.3169 18.1175C11.3936 18.1429 11.6023 18.2195 11.9431 18.3477C12.2841 18.4754 12.5207 18.5628 12.6527 18.6097C12.7847 18.6566 12.9873 18.7185 13.2598 18.7953C13.5329 18.8721 13.7777 18.9254 13.995 18.9554C14.2125 18.9848 14.4363 19 14.6662 19C15.4502 19 16.2431 18.7826 17.0442 18.348C17.9048 17.8708 18.4246 17.4191 18.6036 16.9926C18.6977 16.7798 18.7868 16.4879 18.8721 16.117C18.9575 15.7464 19 15.4461 19 15.2159C19.0003 15.0962 18.9874 15.0069 18.9617 14.9467Z" stroke="#5060C9" stroke-width="2"></path>
                                        </svg>
                                        <div class="contact-us-info-title">Phones</div>
                                    </dt>
                                    <dd>
                                        <div class="contact-us-info-description">
                                            <a href="tel:7838224424">(+91) 7838224424</a> 
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-xs-12 col-xl-4">
                                <dl class="contact-us-info-item">
                                    <dt>
                                        <svg width="36" height="36" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1" y="3" width="18" height="14" rx="4" stroke="#5060C9" stroke-width="2"></rect>
                                            <path d="M2 4L10 11L18 4" stroke="#5060C9" stroke-width="2" stroke-linejoin="round"></path>
                                        </svg>
                                        <div class="contact-us-info-title">E-mail</div>
                                    </dt>
                                    <dd>
                                        <div class="contact-us-info-description contact-us-info-description-email">
                                            <a href="mailto: support@veeere.in"> support@veeere.in</a>
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="contact-us-info row">
                            <div class="section-title mb-3 mt-3">
                                <h4 class="text-center">Write Us</h4>                                
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1" class="form-label">Name*</label>
                                    <input type="text" v-model="form.name" class="form-control">
                                    <div class="form-err-msg" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1" class="form-label">Email*</label>
                                    <input type="email" v-model="form.email" class="form-control">
                                    <div class="form-err-msg" v-if="form.errors.has('email')" v-html="form.errors.get('email')" />
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1" class="form-label">Phone Number*</label>
                                    <input type="text" v-model="form.mobile" class="form-control">
                                    <div class="form-err-msg" v-if="form.errors.has('mobile')" v-html="form.errors.get('mobile')" />
                                </div>
                                <div col-md-12>
                                    <label for="exampleInputEmail1" class="form-label">What’s on your mind?*</label>
                                    <textarea  v-model="form.message" class="form-control"></textarea>
                                    <div class="form-err-msg" v-if="form.errors.has('message')" v-html="form.errors.get('message')" />
                                </div>
                                
                                <div class="form-group mt-3">
                                    <button type="button" class="btn btn-primary" @click="saveEnquiry()">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </section>
    </GuestLayout>
</template>

