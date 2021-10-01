/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


window.Vue = require('vue');

import Toasted from 'vue-toasted';
import 'vue-select/dist/vue-select.css';
import vSelect from 'vue-select';
import ClipLoader from 'vue-spinner/src/ClipLoader.vue';

import Fragment from 'vue-fragment';

Vue.use(Fragment.Plugin);

Vue.use(Toasted);

Vue.component('v-select', vSelect);
Vue.component('clip-loader', ClipLoader);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


Vue.component('Padministrativo', require('./components/Padministrativo.vue').default);
Vue.component('Pobrero', require('./components/Pobrero.vue').default);
Vue.component('Escritorio', require('./components/Escritorio.vue').default);
Vue.component('Pdocente', require('./components/Pdocente.vue').default);
Vue.component('Beneficios', require('./components/Beneficios.vue').default);
Vue.component('Descuentos', require('./components/Descuentos.vue').default);
Vue.component('Tabuladores', require('./components/Tabuladores.vue').default);
Vue.component('Roles', require('./components/Roles.vue').default);
Vue.component('Usuarios', require('./components/Usuarios.vue').default);
Vue.component('Nominas', require('./components/Nominas.vue').default);
    Vue.component('InfoNomina', require('./components/InfoNomina.vue').default);
Vue.component('salarios', require('./components/salarios.vue').default);
Vue.component('banco', require('./components/Rbanco.vue').default);
Vue.component('Bancos', require('./components/Banco.vue').default);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data:{
    	menu: 0
    }
});
 