<script>
    import MainLayout from "@/Layouts/Main.vue";
    import { useForm } from '@inertiajs/inertia-vue3';
    import { Inertia } from '@inertiajs/inertia';

    export default {
        components: {
            MainLayout,
        },
        data() {
            return {
                submitBtn : true,
                form : useForm({
                    transaction_id:'',
                    user_id: '',
                    ids:[],
                    ratings : [],
                    comments : [],               
                }),
            };
        },
        props: {
          order:Object,
        },
        computed: {
            test(){
                // return this.order.sell_lines
                if(this.order.sell_lines.length > 0){
                    for(let i = 0; i < this.order.sell_lines.length; i++){
                        this.form.ids[i] = this.order.sell_lines[i]['product_id']
                        this.form.ratings[i] = 5
                        this.form.comments[i] = null
                    }
                    return this.form
                }
            }
        },
        methods: {
            submit_review(){
                this.form.transaction_id = this.order.id
                this.form.user_id = this.order.contact_id
                // this.submitBtn = false            
                // console.log(this.);    
                axios.post('/api/submit-order-review', {form : this.form}).then((response) => {
                    if(response.data.status == 'success'){
                        this.$toast.success('Your review has been submitted successfully.')
                        this.form.reset()
                        Inertia.reload()
                    }else{
                        this.$toast.warning('Please try after some time')
                    }
                })

            },
        },
        mounted(){
            
        }
    };
</script>
<template>
    <head title="'Review'" />
    <MainLayout>
          <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row ec_breadcrumb_inner">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="ec-breadcrumb-title">Review</h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <!-- ec-breadcrumb-list start -->
                                <ul class="ec-breadcrumb-list">
                                    <li class="ec-breadcrumb-item"><Link :href="route('home')">Home</Link></li>
                                    <li class="ec-breadcrumb-item active">Review</li>
                                </ul>
                                <!-- ec-breadcrumb-list end -->
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
                  <div class="section-title">
                      <h2 class="ec-bg-title">Review</h2>
                      <h2 class="ec-title">Review</h2>
                      <!-- <p class="sub-title mb-3">About our business Firm</p> -->
                  </div>
              </div>
              <div class="ec-common-wrapper">
                <table class="table ec-table">
                  <thead>
                      <tr>
                          <th scope="col">#</th>
                          <th scope="col">Product</th>
                          <th scope="col">Image</th>
                          <th scope="col">Rating</th>
                          <th scope="col">Review</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr v-for="(op, opk) in order.sell_lines" :key="op.id">
                          <th scope="row"><span>{{opk+1}}</span></th>
                          <td>
                              <span><label class="m-0">{{ (op.product)?op.product.name:'-' }}</label></span>
                              
                          </td>
                          <td><img style="height:100px;" :src="this.$media_url + op.product.image" /></td>
                          <td>
                            <div class="ec-t-review-rating">                                                                
                                <i v-for="f in 5" :key="'review-'+f" class="ecicon" :class="[(f <= form.ratings[opk]) ? 'eci-star fill' : 'eci-star-o']" @click="form.ratings[opk] = f"></i>
                            </div>
                          </td>
                          <td>
                            <textarea name="your-commemt" v-model="form.comments[opk]" placeholder="Enter Your Comment" ></textarea>
                          </td>
                      </tr>
                  </tbody>
                    <div class="ec-ratting-input form-submit">
                        <button class="btn btn-primary" type="button" @click="submit_review()" v-show="submitBtn">Submit</button>
                        <button class="btn btn-primary" type="button" disabled v-show="!submitBtn">Submiting...</button>
                    </div>
              </table>
              </div>
            </div>
          </div>
        </section>
    </MainLayout>
</template>