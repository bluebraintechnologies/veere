<template>
  <Head title="Home" />
  <GuestLayout>
    <!-- Ec breadcrumb start -->
    <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row ec_breadcrumb_inner">
                        <div class="col-md-6 col-sm-12">
                            <h2 class="ec-breadcrumb-title">Cart</h2>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <!-- ec-breadcrumb-list start -->
                            <ul class="ec-breadcrumb-list">
                                <li class="ec-breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="ec-breadcrumb-item active">Cart</li>
                            </ul>
                            <!-- ec-breadcrumb-list end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Ec breadcrumb end -->

    <!-- Ec cart page -->
    <section class="ec-page-content section-space-p">
        <div class="container">
            <div class="row">
                <div class="ec-cart-leftside col-lg-8 col-md-12 ">
                    <!-- cart content Start -->
                    <div class="ec-cart-content">
                        <div class="ec-cart-inner">
                            <div class="row">
                                <form action="#">
                                    <div class="table-content cart-table-content">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Price</th>
                                                    <th style="text-align: center;">Quantity</th>
                                                    <th>Total</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="item in cartItems" :key="'cart-item-' + item.id">
                                                    <td data-label="Product" class="ec-cart-pro-name">
                                                      <a href="product-left-sidebar.html">
                                                        <img class="ec-cart-pro-img mr-4" src="assets/images/product-image/1.jpg" alt="" />
                                                        {{ item.product }}
                                                      </a>
                                                    </td>
                                                    <td data-label="Price" class="ec-cart-pro-price">
                                                      <span class="amount">{{ item.price }}</span>
                                                    </td>
                                                    <td data-label="Quantity" class="ec-cart-pro-qty" style="text-align: center;">
                                                        <div class="cart-qty-plus-minus">
                                                            <input class="cart-plus-minus" type="text" name="cartqtybutton" :value="cartItems.cart[item.id]" />
                                                        </div>
                                                    </td>
                                                    <td data-label="Total" class="ec-cart-pro-subtotal">{{ cartItems.cart[item.id]*item._price }}</td>
                                                    <td data-label="Remove" class="ec-cart-pro-remove">
                                                        <a href="#"><i class="ecicon eci-trash-o"></i></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="ec-cart-update-bottom">
                                                <a href="#">Continue Shopping</a>
                                                <button class="btn btn-primary">Check Out</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--cart content End -->
                </div>
                <!-- Sidebar Area Start -->
                <div class="ec-cart-rightside col-lg-4 col-md-12">
                    <div class="ec-sidebar-wrap">
                        <!-- Sidebar Summary Block -->
                        <div class="ec-sidebar-block">
                            <div class="ec-sb-title">
                                <h3 class="ec-sidebar-title">Summary</h3>
                            </div>
                            <div class="ec-sb-block-content">
                                <h4 class="ec-ship-title">Estimate Shipping</h4>
                                <div class="ec-cart-form">
                                    <p>Enter your destination to get a shipping estimate</p>
                                    <form action="#" method="post">
                                        <span class="ec-cart-wrap">
                                            <label>Country *</label>
                                            <span class="ec-cart-select-inner">
                                                <select name="ec_cart_country" id="ec-cart-select-country"
                                                    class="ec-cart-select">
                                                    <option selected="" disabled="">United States</option>
                                                    <option value="1">Country 1</option>
                                                    <option value="2">Country 2</option>
                                                    <option value="3">Country 3</option>
                                                    <option value="4">Country 4</option>
                                                    <option value="5">Country 5</option>
                                                </select>
                                            </span>
                                        </span>
                                        <span class="ec-cart-wrap">
                                            <label>State/Province</label>
                                            <span class="ec-cart-select-inner">
                                                <select name="ec_cart_state" id="ec-cart-select-state"
                                                    class="ec-cart-select">
                                                    <option selected="" disabled="">Please Select a region, state
                                                    </option>
                                                    <option value="1">Region/State 1</option>
                                                    <option value="2">Region/State 2</option>
                                                    <option value="3">Region/State 3</option>
                                                    <option value="4">Region/State 4</option>
                                                    <option value="5">Region/State 5</option>
                                                </select>
                                            </span>
                                        </span>
                                        <span class="ec-cart-wrap">
                                            <label>Zip/Postal Code</label>
                                            <input type="text" name="postalcode" placeholder="Zip/Postal Code">
                                        </span>
                                    </form>
                                </div>
                            </div>

                            <div class="ec-sb-block-content">
                                <div class="ec-cart-summary-bottom">
                                    <div class="ec-cart-summary">
                                        <div>
                                            <span class="text-left">Sub-Total</span>
                                            <span class="text-right">$80.00</span>
                                        </div>
                                        <div>
                                            <span class="text-left">Delivery Charges</span>
                                            <span class="text-right">$80.00</span>
                                        </div>
                                        <div>
                                            <span class="text-left">Coupan Discount</span>
                                            <span class="text-right"><a class="ec-cart-coupan">Apply Coupan</a></span>
                                        </div>
                                        <div class="ec-cart-coupan-content">
                                            <form class="ec-cart-coupan-form" name="ec-cart-coupan-form" method="post"
                                                action="#">
                                                <input class="ec-coupan" type="text" required=""
                                                    placeholder="Enter Your Coupan Code" name="ec-coupan" value="">
                                                <button class="ec-coupan-btn button btn-primary" type="submit"
                                                    name="subscribe" value="">Apply</button>
                                            </form>
                                        </div>
                                        <div class="ec-cart-summary-total">
                                            <span class="text-left">Total Amount</span>
                                            <span class="text-right">$80.00</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Sidebar Summary Block -->
                    </div>
                </div>
            </div>
        </div>
    </section>      
  </GuestLayout>  
</template>

<script>
import { mapGetters } from "vuex";
import GuestLayout from "@/Layouts/Main.vue";
// import 'assets/js/vendor/jquery.magnific-popup.min.js"';
// import 'assets/js/main.js';
// import {asd} from '@assets/css/style.css';
// import '/assets/css/responsive.css';

export default {
    components:{
      GuestLayout,
    },
    props: {
      
      },
    computed: {
      ...mapGetters(["cartItems"]),
    },
    methods: {
      displaySideCart() {
        this.sideCartStatus = !this.sideCartStatus;
      },
    },
    setup(props, { emit }) {
    },
};
</script>
<style >
    .ec-breadcrumb {
      padding: 15px 0;
      background-color: #f7f7f7;
    }
    .ec-breadcrumb .ec-breadcrumb-title {
      text-decoration: none;
      color: #444444;
      display: block;
      font-size: 15px;
      font-family: "Montserrat";
      line-height: 22px;
      font-weight: 700;
      margin: 0 auto;
      text-transform: capitalize;
    }
    .ec-breadcrumb-list {
      text-align: right;
    }
    .ec-breadcrumb-list li {
      display: inline-block;
      font-size: 14px;
      font-weight: 300;
      letter-spacing: 0.02rem;
      line-height: 1.2;
      text-transform: capitalize;
    }
    .ec-breadcrumb-list li a {
      position: relative;
      color: #444444;
    }
    .ec-breadcrumb-list li.active {
      color: #3474d4;
    }
    .ec-breadcrumb-list .ec-breadcrumb-item.active::before {
      color: #3474d4;
    }
    .ec-breadcrumb-item {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
    }
    .ec-breadcrumb-item + .ec-breadcrumb-item {
      padding-left: 7px;
    }
    .ec-breadcrumb-item + .ec-breadcrumb-item::before {
      display: inline-block;
      padding-right: 7px;
      color: #444444;
      content: "";
      font-family: "EcIcons";
      font-size: 15px;
    }
    .section-space-p {
    padding: 50px 0;
  }
  .ec-cart-content .table-content table {
    border: 1px solid #ebebeb;
    width: 100%;
    background-color: #fff;
  }
  .ec-cart-content .table-content table thead > tr {
    background-color: #ffffff;
    border: 1px solid #ebebeb;
  }
  .ec-cart-content .table-content table thead > tr > th {
    border-top: medium none;
    color: #444444;
    font-size: 15px;
    font-weight: 500;
    padding: 15px 14px 12px;
    text-align: left;
    text-transform: capitalize;
    vertical-align: middle;
    white-space: nowrap;
    line-height: 1;
    letter-spacing: 0;
  }
  .ec-cart-content .table-content table tbody > tr {
    border-bottom: 1px solid #ebebeb;
  }
  .ec-cart-content .table-content table tbody > tr td {
    color: #444444;
    font-size: 16px;
    padding: 15px 14px;
    text-align: left;
  }
  .ec-cart-content .table-content table tbody > tr td .ec-cart-pro-img {
    width: 61px;
  }
  .ec-cart-content .table-content table tbody > tr td .cart-qty-plus-minus {
    border: 1px solid #ededed;
    display: inline-block;
    height: 35px;
    overflow: hidden;
    padding: 0;
    position: relative;
    width: 84px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
    -webkit-box-pack: justify;
        -ms-flex-pack: justify;
            justify-content: space-between;
    margin: 0 auto;
  }
  .ec-cart-content .table-content table tbody > tr td .cart-qty-plus-minus input {
    background: transparent none repeat scroll 0 0;
    border: medium none;
    color: #444444;
    float: left;
    font-size: 14px;
    height: auto;
    margin: 0;
    padding: 0;
    text-align: center;
    width: 40px;
    outline: none;
    font-weight: 600;
    line-height: 38px;
  }
  .ec-cart-content .table-content table tbody > tr td .cart-qty-plus-minus .ec_cart_qtybtn {
    color: #444444;
    float: left;
    font-size: 20px;
    height: auto;
    margin: 0;
    padding: 0;
    width: 40px;
    height: 38px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
        -ms-flex-direction: column;
            flex-direction: column;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
    -webkit-box-pack: center;
        -ms-flex-pack: center;
            justify-content: center;
  }
  .ec-cart-content .table-content table tbody > tr td .cart-qty-plus-minus .ec_qtybtn {
    width: 100%;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
    -webkit-box-pack: center;
        -ms-flex-pack: center;
            justify-content: center;
    cursor: pointer;
    font-size: 0;
    color: #444444;
    height: 19px;
    position: relative;
  }
  .ec-cart-content .table-content table tbody > tr td .cart-qty-plus-minus .ec_qtybtn:before {
    width: 100%;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
    -webkit-box-pack: center;
        -ms-flex-pack: center;
            justify-content: center;
    cursor: pointer;
    font-size: 20px;
    color: #444444;
    height: 19px;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    content: "";
    font-family: "EcIcons";
    overflow: hidden;
  }
  .ec-cart-content .table-content table tbody > tr td .cart-qty-plus-minus .dec.ec_qtybtn:before {
    padding-bottom: 4px;
    content: "";
  }
  .ec-cart-content .table-content table tbody > tr td .cart-qty-plus-minus .inc.ec_qtybtn:before {
    padding-top: 4px;
    content: "";
  }
  .ec-cart-content .table-content table tbody > tr td.ec-cart-pro-name {
    width: 40%;
  }
  .ec-cart-content .table-content table tbody > tr td.ec-cart-pro-name a {
    color: #444444;
    font-weight: 400;
    text-decoration: none;
    font-size: 14px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    line-height: 1.5;
    letter-spacing: 0.6px;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
  }
  .ec-cart-content .table-content table tbody > tr td.ec-cart-pro-subtotal {
    color: #555;
    font-weight: 500;
    font-size: 15px;
  }
  .ec-cart-content .table-content table tbody > tr td.ec-cart-pro-price {
    color: #555;
    font-weight: 500;
    font-size: 15px;
  }
  .ec-cart-content .table-content table tbody > tr td.ec-cart-pro-qty .qty-plus-minus {
    display: inline-block;
    height: 40px;
    padding: 0;
    position: relative;
    width: 110px;
  }
  .ec-cart-content .table-content table tbody > tr td.ec-cart-pro-remove {
    width: 90px;
    text-align: right;
  }
  .ec-cart-content .table-content table tbody > tr td.ec-cart-pro-remove a {
    font-size: 22px;
    margin: 0 auto;
    color: #555;
  }
  .ec-cart-content .ec-cart-update-bottom {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
        -ms-flex-pack: justify;
            justify-content: space-between;
    padding: 30px 0 0;
  }
  .ec-cart-content .table-content table tbody > tr td.ec-cart-pro-name {
    width: 40%;
  }
  .ec-cart-content .table-content table tbody > tr td.ec-cart-pro-name a {
    color: #444444;
    font-weight: 400;
    text-decoration: none;
    font-size: 14px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    line-height: 1.5;
    letter-spacing: 0.6px;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
  }
  .ec-cart-content .table-content table tbody > tr td .ec-cart-pro-img {
    width: 61px;
  }
  .ec-cart-update-bottom > a {
    color: #444444;
    display: inline-block;
    text-decoration: underline;
    font-size: 15px;
    line-height: 20px;
    font-weight: 500;
    letter-spacing: 0.8px;
  }
  .ec-cart-update-bottom .btn {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    height: 40px;
    line-height: 40px;
    text-align: center;
    font-size: 16px;
    font-weight: 500;
    text-transform: uppercase;
    -webkit-box-pack: center;
        -ms-flex-pack: center;
            justify-content: center;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
    min-width: 150px;
  }

</style>
