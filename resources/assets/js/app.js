
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('categoria-component', require('./components/CategoriaComponent.vue'));
Vue.component('articulo-component', require('./components/ArticuloComponent.vue'));
Vue.component('cliente-component', require('./components/ClienteComponent.vue'));
Vue.component('proveedor-component', require('./components/ProveedorComponent.vue'));
Vue.component('rol-component', require('./components/RolComponent.vue'));
Vue.component('user-component', require('./components/UserComponent.vue'));
Vue.component('ingreso-component', require('./components/IngresoComponent.vue'));
Vue.component('venta-component', require('./components/VentaComponent.vue'));

const app = new Vue({
    el: '#app',
    data:{
        menu : 0
    }
});
