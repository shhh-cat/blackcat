/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('@fortawesome/fontawesome-free/js/all')
window.Vue = require('vue');

Vue.config.debug = true;
Vue.config.devtools = true;
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
import VueConfirmDialog from 'vue-confirm-dialog';
import VueSweetalert2 from 'vue-sweetalert2';
import VModal from 'vue-js-modal';
// import '@sweetalert2/theme-bootstrap-4/bootstrap-4.css';
import 'animate.css';
import 'sweetalert2/src/sweetalert2.scss'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import InfiniteLoading from 'vue-infinite-loading';
import CoolLightBox from 'vue-cool-lightbox'
import 'vue-cool-lightbox/dist/vue-cool-lightbox.min.css'
import Carousel3d from 'vue-carousel-3d';




global.jQuery = require('jquery');
var $ = global.jQuery;
window.$ = $;


const loading_options = {
    backgroundColor: '#000000',
    color: '#ffffff',
};


const swal_options = {
    showClass: {
        popup: 'animate__animated animate__bounceIn animate__faster',
      },
    hideClass: {
        popup: 'animate__animated animate__bounceOut animate__faster'
      },
  };
Vue.use(InfiniteLoading, {
    props: {
        spinner: 'spiral',
        /* other props need to configure */
    }, });
Vue.use(Loading, loading_options);
Vue.use(VueSweetalert2, swal_options);
Vue.use(VueConfirmDialog);
Vue.use(VModal);
Vue.use(CoolLightBox)


Vue.use(Carousel3d);

//MIXINS
//import responseHelper from './mixins/responseHelper'



Vue.component('vue-confirm-dialog', VueConfirmDialog.default)
Vue.component('add-to-cart', require('./components/shop/AddToCart.vue').default);
Vue.component('review-button', require('./components/shop/ReviewButton.vue').default);
Vue.component('review-image', require('./components/shop/ReviewImage.vue').default);
//Vue.component('change-qty', require('./components/ChangeQuantity.vue').default);
//Vue.component('cart-count', require('./components/CartCount.vue').default);
Vue.component('cart-view', require('./components/shop/CartView.vue').default);
Vue.component('checkout-view', require('./components/shop/CheckoutView.vue').default);
Vue.component('search-input', require('./components/shop/SearchInput.vue').default);
Vue.component('search-view', require('./components/shop/SearchView.vue').default);
Vue.component('cart-view-total-amount', require('./components/shop/CartViewTotalAmount.vue').default);
Vue.component('orders', require('./components/shop/Orders.vue').default);
Vue.component('summary-cart', require('./components/shop/Summary.vue').default);
Vue.component('product-image', require('./components/shop/ProductImage.vue').default);
Vue.filter('toCurrency', function (value) {
    if (typeof value !== "number") {
        return value;
    }
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    });
    return formatter.format(value);
});
//GLOBAL DATA
Vue.prototype.$csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
Vue.prototype.$bearerAPITOKEN = {
                'Accept' : 'application/json',
                'Authorization' : document.querySelector("meta[name='api-token']").getAttribute('content').length == 0 ? '' :'Bearer ' + document.querySelector("meta[name='api-token']").getAttribute('content'),
                };



//MIXINS
//import responseHelper from './mixins/responseHelper'



const app = new Vue({
	mode: 'history'
}).$mount('#shop')



jQuery("#search-filter-input").on('keypress',function (e) {
   if (e.keyCode == 13) {
       window.location.replace("/search/"+$("#search-filter-input").val())
   }
});
