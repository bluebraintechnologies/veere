<template>
    <div class="row">
        <div class="col-md-12">
            <div class="section-title">
                <h2 class="ec-title text-uppercase text-muted">Featured Products</h2>
            </div>
        </div>

        <div class="col-md-12 ec-pro-tab">
            <ul class="ec-pro-tab-nav nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link" :class="[(activecat == 'all')?'active':'']" @click="activecat = 'all'">All</a>
                </li>
                <li class="nav-item" v-for="(cat, ci) in categories" :key="'cat'+ci" @click="activecat = cat.slug">
                    <a class="nav-link" :class="[(activecat == cat.slug)?'active':'']" href="javascript:void(0)">{{ cat.category }}</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row margin-minus-b-15">
        <div class="col">
            <div class="tab-content">
                <div class="tab-pane fade" :class="[(activecat == 'all')?' show active':'']">
                    <div class="row">
                        <div class="ec-product-content" v-for="(pp, pk) in all_products" :key="'fpp'+pk">
                            <single-grid :product="pp"></single-grid>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" v-for="(p, k) in products" :key="'fp'+k" :class="[(activecat == p.slug)?'show active':'']">
                    <div class="row">
                        <div class="ec-product-content" v-for="(pp, pk) in p.products" :key="'fpp'+pk">
                            <single-grid :product="pp"></single-grid>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import SingleGrid from '@/Pages/Product/SingleGrid.vue'
export default {
    components: { SingleGrid },
    props: {
        products: [Array, Object],
        categories: [Array, Object],
    },
    computed: {
        all_products() {
            let pp = this.products.map( ele => ele.products);
            return  pp.flat();
        }
    },
    data() {
        return {
            activecat:'all'
        }
    }
}
</script>