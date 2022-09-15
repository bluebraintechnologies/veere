<script>
import { mapGetters } from "vuex";

export default {
    props: {
        imgid: [Number, String],
        className: [String],
    },
    computed: {
        ...mapGetters(["assetItems"]),
        img_link() {
            let iid = this.imgid;
            let imag = this.assetItems.filter((ele) => {
                return ele.id == iid;
            });
            if (imag.length >= 1) {
                return this.$media_url + imag[0].file_name;
            } else {
                return this.$local_media_url+"/media/placeholder.jpg";
            }
        },
    },
};
</script>
<template>
    <img :src="img_link" alt="swaariish image" :class="className" />
</template>