<template>
    <section class="section ec-category-section section-space-p">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center border-0">
                        <h2 class="ec-title text-uppercase">Top Categories</h2>
                    </div>
                </div>
            </div>
            <div class="margin-minus-b-15 margin-minus-t-15">
                <Carousel :settings='settings' :breakpoints='breakpoints' id="ec-cat-slider">
                    <Slide class="ec_cat_content" v-for="(sitem, sk) in slides" :key="'cat'+sk">
                        <div class="ec_cat_inner">
                            <div class="ec-category-image">
                                <img src="/images/icons/drink-7.svg" class="svg_img" :alt="sitem.name" />
                            </div>
                            <div class="ec-category-desc">
                                <h3> {{ sitem.name }} <span title="Category Items"></span></h3>
                                <Link :href="route('category', sitem.slug)" class="cat-show-all">
                                    Show All <i class="ecicon eci-angle-double-right" aria-hidden="true"></i>
                                </Link>
                            </div>
                        </div>
                    </Slide>
                </Carousel>
            </div>
        </div>
    </section>
</template>
<script>
import { Carousel, Slide } from 'vue3-carousel';
import 'vue3-carousel/dist/carousel.css';
export default {
    props: {
        slides:[Array, Object]
    },
    components: {
        Carousel,
        Slide,
    },
    data() {
        return {
            settings: {
                snapAlign: 'center',
                wrapAround:true,
                autoplay: 8000,
                transition:3000
            },
            breakpoints: {
                400: {
                    itemsToShow: 1,
                    snapAlign: 'center',
                },
                700: {
                    itemsToShow: 2,
                    snapAlign: 'center',
                },
                1024: {
                    itemsToShow: 3,
                    snapAlign: 'left',
                },
                1200: {
                    itemsToShow: 4,
                    snapAlign: 'left',
                },
                1920: {
                    itemsToShow: 5,
                    snapAlign: 'left',
                },
            }
        }
    }
}
</script>