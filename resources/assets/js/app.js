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

// Loading utility.
Vue.component('loading', require('./components/Loading.vue'));

// Support Button
Vue.component('support-button', require('./components/SupportButton.vue'));

// Application form.
Vue.component('application-form', require('./components/ApplicationForm.vue'));
Vue.component('application-user-review', require('./components/application/userReview.vue'));

// Admin Location, unit and unit types form.
Vue.component('admin-locations', require('./components/admin/AdminLocations.vue'));
Vue.component('admin-unit-types', require('./components/admin/AdminUnitTypes.vue'));
Vue.component('admin-units', require('./components/admin/AdminUnits.vue'));
Vue.component('admin-items', require('./components/admin/AdminItems.vue'));
Vue.component('admin-leased-items', require('./components/admin/AdminLeasedItems.vue'));
Vue.component('admin-users', require('./components/admin/AdminUsers.vue'));
Vue.component('admin-occupation-dates', require('./components/admin/AdminOccupationDates.vue'));

Vue.component('open-applications', require('./components/dashboard/OpenApplications.vue'));
Vue.component('cancelled-contracts', require('./components/dashboard/CancelledContracts.vue'));
Vue.component('all-applications', require('./components/dashboard/AllApplications.vue'));
Vue.component('tenant-applications', require('./components/dashboard/TenantApplications.vue'));
Vue.component('tenant-contracts', require('./components/dashboard/TenantContracts.vue'));

Vue.component('application-review', require('./components/application/review.vue'));
Vue.component('application-pending', require('./components/application/pending.vue'));
Vue.component('application-request-changes', require('./components/application/request-changes.vue'));
Vue.component('application-approve', require('./components/application/approve.vue'));
Vue.component('application-approve-edit', require('./components/application/approve-edit.vue'));

Vue.component('contract-approve', require('./components/contract/approve.vue'));

const app = new Vue({
    el: '#app'
});
