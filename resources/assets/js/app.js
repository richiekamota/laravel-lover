/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

Vue.component('example', require('./components/Example.vue'));

// Loading utility.
Vue.component('loading', require('./components/Loading.vue'));

// Application form.
Vue.component('application-form', require('./components/ApplicationForm.vue'));

// Admin Location, unit and unit types form.
Vue.component('admin-locations', require('./components/admin/AdminLocations.vue'));
Vue.component('admin-unit-types', require('./components/admin/AdminUnitTypes.vue'));
Vue.component('admin-units', require('./components/admin/AdminUnits.vue'));

const app = new Vue({
    el: '#app'
});
