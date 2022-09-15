<script>
import GuestLayout from "@/Layouts/Guest.vue";
import Categories from './Categories.vue';
import MainSlider from './MainSlider.vue';
import ProductTab from './ProductTab.vue';
import Testimonial from './Testimonial.vue';
import Blogs from './Blogs.vue';


export default {
    components: {
      GuestLayout,
      Categories,
      MainSlider,
      ProductTab,
      Testimonial,
      Blogs,
    },
    data() {
        return {
          sideCartStatus: false,
        };
    },
    methods: {
      displaySideCart() {
        this.sideCartStatus = !this.sideCartStatus;
      },
      
    },
    created(){
      
    }
};
</script>
<template>
  <Head title="Home" />
  <GuestLayout>
      <Categories />
      <MainSlider />
      <ProductTab />
      <Testimonial />
      <Blogs />
  </GuestLayout>  
</template>
