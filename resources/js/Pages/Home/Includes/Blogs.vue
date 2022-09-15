<template>
     <section class="section ec-blog-section section-space-p">
        <div class="container">
            <Carousel :settings='settings' :breakpoints='breakpoints' class="ec-blog-slider">
                <Slide class="ec-blog-block">
                    <div class="ec-blog-inner">
                        <div class="ec-blog-image">
                            <a href="blog-detail-left-sidebar.html">
                                <img class="blog-image" src="/images/blog/17.jpg"
                                    alt="Blog" />
                            </a>
                        </div>
                        <div class="ec-blog-content">
                            <div class="ec-blog-cat"><a href="blog-left-sidebar.html">Vegetarian</a></div>
                            <h5 class="ec-blog-title"><a
                                    href="blog-detail-left-sidebar.html">Curbside Grocery Trends: How to Win the Pickup Battle.</a></h5>

                            <div class="ec-blog-date">By<span>Mr Robin</span> / Jan 18, 2022</div>
                        </div>
                    </div>
                </Slide>
                <Slide class="ec-blog-block">
                    <div class="ec-blog-inner">
                        <div class="ec-blog-image">
                            <a href="blog-detail-left-sidebar.html">
                                <img class="blog-image" src="/images/blog/18.jpg"
                                    alt="Blog" />
                            </a>
                        </div>
                        <div class="ec-blog-content">
                            <div class="ec-blog-cat"><a href="blog-left-sidebar.html">Organic</a></div>
                            <h5 class="ec-blog-title"><a
                                    href="blog-detail-left-sidebar.html">Grocery Retail KPIs 2021 Guide for Grocery Executives.</a></h5>

                            <div class="ec-blog-date">By<span>Mr Admin</span> / Apr 06, 2022</div>
                        </div>
                    </div>
                </Slide>
                <Slide class="ec-blog-block">
                    <div class="ec-blog-inner">
                        <div class="ec-blog-image">
                            <a href="blog-detail-left-sidebar.html">
                                <img class="blog-image" src="/images/blog/19.jpg"
                                    alt="Blog" />
                            </a>
                        </div>
                        <div class="ec-blog-content">
                            <div class="ec-blog-cat"><a href="blog-left-sidebar.html">Fresh Fruits</a></div>
                            <h5 class="ec-blog-title"><a
                                    href="blog-detail-left-sidebar.html">EBT Grocers: Claim Your Share of SNAP Online Revenue.</a></h5>

                            <div class="ec-blog-date">By<span>Mr Selsa</span> / Feb 10, 2022</div>
                        </div>
                    </div>
                </Slide>
                <Slide class="ec-blog-block">
                    <div class="ec-blog-inner">
                        <div class="ec-blog-image">
                            <a href="blog-detail-left-sidebar.html">
                                <img class="blog-image" src="/images/blog/20.jpg"
                                    alt="Blog" />
                            </a>
                        </div>
                        <div class="ec-blog-content">
                            <div class="ec-blog-cat"><a href="blog-left-sidebar.html">Dessert</a></div>
                            <h5 class="ec-blog-title"><a
                                    href="blog-detail-left-sidebar.html">Curbside Grocery Trends: How to Win the Pickup Battle.</a></h5>

                            <div class="ec-blog-date">By<span>Mr Pawar</span> / Mar 15, 2022</div>
                        </div>
                    </div>
                </Slide>
                <Slide class="ec-blog-block">
                    <div class="ec-blog-inner">
                        <div class="ec-blog-image">
                            <a href="blog-detail-left-sidebar.html">
                                <img class="blog-image" src="/images/blog/21.jpg"
                                    alt="Blog" />
                            </a>
                        </div>
                        <div class="ec-blog-content">
                            <div class="ec-blog-cat"><a href="blog-left-sidebar.html">Soups</a></div>
                            <h5 class="ec-blog-title"><a
                                    href="blog-detail-left-sidebar.html">6 Food Retail Industry Digital Strategies for 2021.</a></h5>

                            <div class="ec-blog-date">By<span>Mr Natly</span> / Jun 02, 2022</div>
                        </div>
                    </div>
                </Slide>
                <Slide class="ec-blog-block">
                    <div class="ec-blog-inner">
                        <div class="ec-blog-image">
                            <a href="blog-detail-left-sidebar.html">
                                <img class="blog-image" src="/images/blog/22.jpg"
                                    alt="Blog" />
                            </a>
                        </div>
                        <div class="ec-blog-content">
                            <div class="ec-blog-cat"><a href="blog-left-sidebar.html">Organics</a></div>
                            <h5 class="ec-blog-title"><a
                                    href="blog-detail-left-sidebar.html">Why Should be Concerned About Instacart Patents.</a></h5>

                            <div class="ec-blog-date">By<span>Mr Admin</span> / Feb 10, 2022</div>
                        </div>
                    </div>
                </Slide>
                <template #addons>
                    <Navigation />
                </template>
            </Carousel>
        </div>
    </section>
</template>

<script>
import { Carousel, Navigation, Slide } from 'vue3-carousel';
import 'vue3-carousel/dist/carousel.css';
export default {
    components: {
        Carousel,
        Slide,
        Navigation,
    },
    data() {
        return {
            settings: {
                itemsToShow: 4,
                snapAlign: 'center',
                wrapAround:true,
                transition:3000
            },
            breakpoints: {
                700: {
                    itemsToShow: 1,
                    snapAlign: 'center',
                },
                1024: {
                    itemsToShow: 2,
                    snapAlign: 'start',
                },
                1200: {
                    itemsToShow: 3,
                    snapAlign: 'start',
                },
                1600: {
                    itemsToShow: 4,
                    snapAlign: 'start',
                },
            }
        }
    },
};
</script>