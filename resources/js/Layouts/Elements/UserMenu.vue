<template>
    <div class="ec-sidebar-wrap">
        <div class="ec-sidebar-block">
            <div class="ec-vendor-block">
                <div class="ec-vendor-block-bg"></div>
                <div class="ec-vendor-block-detail">
                    <img class="v-img" :src="'/avatars/' + $page.props.user.image" alt="image" v-if="$page.props.user.image">
                    <img class="v-img" :src="$local_media_url+'1.jpg'" alt="image" v-else>
                    <h5>{{  $page.props.auth.user.name }}</h5>
                </div> 
                <div class="ec-vendor-block-items">
                    <ul>
                        <li :class="[(activemenu == 'dashboard')?'active':'']" >
                            <Link :href="route('dashboard')">Dashbaord</Link>
                        </li>
                        <li :class="[(activemenu == 'profile')?'active':'']" >
                            <Link :href="route('profile')">User Profile</Link>
                        </li>
                        <li :class="[(activemenu == 'addresses')?'active':'']" >
                            <Link :href="route('addresses')">Address Book</Link>
                        </li>
                        <li :class="[(activemenu == 'orders')?'active':'']" >
                            <Link :href="route('orders')">Orders</Link>
                        </li>
                        <li :class="[(activemenu == 'wishlist')?'active':'']" >
                            <Link :href="route('wishlist')">Wishlist</Link>
                        </li>
                        <li>
                            <!-- <Link :href="route('logout')">Logout</Link> -->
                            <a href="javascript:void(0)" @click="logoutUser()">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        activemenu:String,        
    },
    methods:{
        logoutUser(){
            axios.post('/user/logout-user').then((response) => {
                location.reload()
            })
        }
    }
}
</script>