<script>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { mapActions } from 'vuex';
import { useForm } from '@inertiajs/inertia-vue3';
import axios from 'axios';
export default {
    name: "Dashboard",
    props: {
       user:Object,
    },
    components: {
        AuthenticatedLayout,
    },
    data() {
        return {
            form: useForm({
                name:'',
                phone:'',
                email: '',
                gender: '',
                password : '',
                new_password: '',
                confirm_password: '',
                avatar:'',
            }),
        }
    },
    methods: {
        ...mapActions(['getWishlistItems', 'getCartItems']),
        submit() {
            // if(this.form.new_password){
            //     if(this.form.password == ''){
            //         this.$toast.error('Please enter current password!!')
            //         return false;
            //     }
            // }
            // if(this.form.new_password != this.form.confirm_password) {
            //     this.$toast.error('New password and confirm password is not same');
            //     return false;
            // }
            axios.post('/api/password-check', {form : this.form, user: this.user}).then((response)=>{
                if(response.data.status == 'success'){
                    let existingObj = this;
                    const config = {
                        headers: {
                            'content-type': 'multipart/form-data'
                        }
                    }
                    let data = new FormData();
                    data.append('avatar', this.form.avatar);
                    data.append('name', this.form.name);
                    data.append('phone', this.form.phone);
                    this.form.post('update-profile', {
                        preserveScroll: true,
                        onSuccess: () => this.resetForm(),
                    })
                    
                }else{
                    this.$toast.warning('Password is incorrect!!')
                }
            })
            
        },
        changeImage() {
            
        },
        resetForm(){
            
            // this.form.name = this.user.name;
            // this.form.email = this.user.email;
            // this.form.password = ''
            // this.form.phone = this.user.mobile;
        }
    },
    mounted() {
        this.getWishlistItems();
        this.getCartItems();
        this.form.name = this.user.name;
        this.form.email = this.user.email;
        this.form.password = ''
        this.form.phone = this.user.mobile;
        this.form.gender = this.user.gender
    }
    
};
</script>
<template>
    <Head title="User Dashboard" />

    <AuthenticatedLayout>
        <div class="ec-vendor-dashboard-card ec-vendor-setting-card">
            <div class="ec-vendor-card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="ec-vendor-block-profile ec-check-login-form">
                            <div class="ec-vendor-block-img space-bottom-30">
                                <div class="ec-vendor-block-bg">

                                </div>
                                <div class="ec-vendor-block-detail">
                                    <img class="v-img cursor-pointer" :src="'/avatars/' + $page.props.user.image" alt="image" v-if="$page.props.user.image">
                                    <img class="v-img cursor-pointer" :src="$local_media_url+'1.jpg'" alt="image" v-else>
                                    <!-- <img class="v-img cursor-pointer" :src="$page.props.auth.user.avatar" alt="image" > -->
                                    <h5 class="name">{{ $page.props.auth.user.name }}</h5>
                                </div>
                                <p>Hello <span> {{ $page.props.auth.user.name }}!</span></p>
                                <p>From your account you can easily view and track orders. You can manage and change your account information like address, contact information and history of orders.</p>
                            </div>
                            <h5>Account Information</h5>
                            <form @submit.prevent="submit">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="ec-vendor-detail-block ec-vendor-block-email space-bottom-30">
                                            <h6>Profile Details 
                                                <a href="javasript:void(0)">
                                                    <img src="/images/icons/edit.svg" class="svg_img pro_svg" alt="edit" />
                                                </a>
                                            </h6>
                                            <ul>
                                                 <li>
                                                    <label class="text-uppercase">Profile Pic</label>
                                                    <input class="pic23" type="file" @input="form.avatar = $event.target.files[0]" id="inputFile"/>
                                                    <div class="error" v-if="form.errors.avatar">{{ form.errors.avatar }}</div>
                                                    <small class="error">Note : if you upload any unappropriate or nude image , your account will be terminated immediately.</small>
                                                </li>
                                                <li>
                                                    <label class="text-uppercase">name</label>
                                                    <input type="text" name="name" v-model="form.name" class="" autocomplete="off">
                                                </li>
                                                <li>
                                                    <label class="text-uppercase">email</label>
                                                    <input type="email" name="email" v-model="form.email" class="" autocomplete="off" >
                                                </li>
                                                <li>
                                                    <label class="text-uppercase">phone</label>
                                                    <input type="text" name="phone" v-model="form.phone" class="" autocomplete="off">
                                                </li>
                                                <li>
                                                    <label class="text-uppercase" >Gender</label><br>
                                                    <select class="" v-model="form.gender" style="border: 1px solid #e1e1e1  !important; padding: 10px;
    width: 100%;">
                                                        <option value="">--</option>
                                                        <option value="female">Female</option>
                                                        <option value="male">Male</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                    <!-- <input type="text" name="phone" v-model="form.phone" class="" autocomplete="off"> -->
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6 col-sm-12">
                                        <div class="ec-vendor-detail-block ec-vendor-block-address">
                                            <h6>Change Password
                                                <a href="javasript:void(0)" >
                                                    <img src="/images/icons/edit.svg" class="svg_img pro_svg" alt="edit" />
                                                </a>
                                            </h6>
                                            <ul>
                                                <li>
                                                    <label class="text-uppercase">Current password</label>
                                                    <input type="password" v-model="form.password" name="pass" placeholder="New Password" autocomplete="Off">
                                                </li>
                                                <li>
                                                    <label class="text-uppercase">new password</label>
                                                    <input type="password" v-model="form.new_password" name="npass" placeholder="New Password" autocomplete="Off">
                                                </li>
                                                <li>
                                                    <label class="text-uppercase">confirm password</label>
                                                    <input type="password" v-model="form.confirm_password" name="cpass" placeholder="Confirm Password" autocomplete="off">
                                                </li>
                                            </ul>
                                        </div>
                                    </div> -->
                                    <div class="col-md-12 col-sm-12">
                                        <button type="submit" class="btn btn-primary" :disabled="form.processing">Update Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
.pic23{
    line-height: 23px;
    cursor: pointer;
    padding-top: 9px;
    padding-left: 9px;
}
.error{
    color: red;
    font-size:small;
}
</style>