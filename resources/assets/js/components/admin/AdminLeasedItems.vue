<template>
    <div class="page-items">
        <div class="row columns">
            <h2 class="--focused">LEASED ITEMS | Lease dates for items with limited stock</h2>
            <p>List of date ranges during which these items are under lease or unavailable.</p>
        </div>

        <div class="row">

            <div class="small-12 medium-9 columns">

                <!-- START List Section -->
                <div class="row table">

                    <div v-for="(item, index) in items" style="width:100%">
                        <div class="small-12 columns">
                            <div class="table__row"
                                 :class="{ even: isEven(index), first: index == 0, last: index == items.length -1 }">

                                <!-- Row Title -->
                                <button class="accordion__heading" v-on:click="accordionToggle(index, $event)">{{
                                    item.name }} : R{{item.cost}} ({{item.payment_type}})
                                </button>

                                <!-- START Edit form -->
                                <div class="accordion__content --bg-calm">
                                    <template v-if="!addEntry">
                                        <div class="row table-head" v-if="item.item_lease_dates.length > 0">
                                            <div class="small-3 medium-3 columns"><strong>Item Name</strong></div>
                                            <div class="small-3 medium-3 columns"><strong>Leased By</strong></div>
                                            <div class="small-2 medium-2 columns"><strong>Start Date</strong></div>
                                            <div class="small-2 medium-2 columns"><strong>End Date</strong></div>
                                            <div class="small-2 medium-2 columns"></div>
                                        </div>
                                        <div class="row column" v-else>
                                            <p>No lease dates found for this item.</p>
                                        </div>
                                        <div class="row table-row" v-for="(lease, index2) in item.item_lease_dates"
                                             :class="{ even: isEven(index2), first: index2 == 0, last: index2 == items.length -1 }">
                                            <div class="small-3 medium-3 columns">{{lease.item_name}}</div>
                                            <div class="small-3 medium-3 columns">{{lease.leasee_name}}</div>
                                            <div class="small-2 medium-2 columns">{{toNiceDate(lease.start_date)}}</div>
                                            <div class="small-2 medium-2 columns">{{toNiceDate(lease.end_date)}}</div>
                                            <div class="small-2 medium-2 columns"><a href="#"
                                                                                     v-on:click="removeItem(index, index2)">Remove</a>
                                            </div>
                                        </div>
                                        <button class="button lease-button" v-on:click="showAddForm()">Add Lease
                                        </button>
                                    </template>
                                    <template v-else>

                                        <div class="row column">
                                            <input type="hidden" id="item_id" ref="item_id"
                                                   name="item_id" v-model="newItem.item_id">

                                            <!-- START Location input form -->
                                            <label for="leasee_name">
                                                Item Name
                                                <input type="text" id="leasee_name" ref="leasee_name"
                                                       name="leasee_name" v-model="newItem.leasee_name">
                                            </label>

                                            <label for="item_name">
                                                Leasee Name
                                                <textarea ref="item_name" id="item_name"
                                                          name="item_name"
                                                          v-model="newItem.item_name"></textarea>
                                            </label>

                                            <label for="user_id">
                                                User Contract
                                                <select ref="user_id" id="user_id" name="user_id" v-model="newItem.user_id">
                                                    <option value=""></option>
                                                    <option v-for="user in users" v-bind:value="user.id">
                                                        {{ user.first_name }}  {{ user.last_name }}
                                                    </option>
                                                </select>
                                            </label>

                                            <label for="start_date">
                                                Start Date
                                                <Flatpickr :options='{ altInput: true, altFormat: "d F Y" }'
                                                           name="start_date"
                                                           v-model="newItem.start_date"
                                                           @update='newItem.start_date = $event'
                                                           required/>
                                            </label>

                                            <label for="end_date">
                                                End Date
                                                <Flatpickr :options='{ altInput: true, altFormat: "d F Y" }'
                                                           name="end_date"
                                                           v-model="newItem.end_date"
                                                           @update='newItem.end_date = $event'
                                                           required/>
                                            </label>

                                            <!--<label for="status">-->
                                            <!--Status-->

                                            <!--<select ref="status" id="status" name="status"-->
                                            <!--v-model="newItem.status">-->
                                            <!--<option value="Active">Active</option>-->
                                            <!--<option value="Inactive">Inactive</option>-->
                                            <!--</select>-->
                                            <!--</label>-->

                                        </div>
                                        <!-- END Edit form -->

                                        <div class="row column">
                                            <button type="submit" name="addItem"
                                                    class="button lease-button focused --mt1"
                                                    v-on:click="addItem" v-bind:disabled="loading">
                                                <span>Add</span>
                                            </button>
                                            <button type="submit" name="cancelItem"
                                                    class="button lease-button float-right --mt1"
                                                    v-on:click="cancelAddItem">
                                                <span>Cancel</span>
                                            </button>
                                        </div>

                                    </template>

                                    <!-- END Edit form -->


                                </div>
                            </div>


                        </div>

                    </div>
                    <!-- END List Section -->
                </div>
            </div>
            <div class="medium-3 columns">
                <!-- START Filter Section -->
                <div class="row column">
                    <input type="text" placeholder="Filter by everything" ref="filterInput">
                </div>

                <div class="row column">
                    <button v-on:click="filter" class="button">
                        Filter
                    </button>
                </div>
                <div class="--border-wrap">
                    <div class="stats-box">
                        <div class="row column clearfix">
                            <h3 class="stats-box__header --focused --mt0">Stats</h3>
                        </div>
                        Lease Count: <span class="float-right">{{total_leases}}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>

    import {Data} from '../../data.js';

    import VueFlatpickr from 'vue-flatpickr';

    Vue.use(VueFlatpickr);

    var moment = require('moment');
    export default {
        props: ['propItems', 'propUnitTypes', 'propUsers'],
        data() {
            return {
                users: [],
                total_leases: 0,
                items: [],
                index: 0,
                unitTypes: [],
                selectedUnitTypes: [],
                loading: false,
                editItem: {
                    id: '',
                    name: '',
                    description: '',
                    cost: '',
                    for_lease: 0,
                    item_lease_dates: [],
                    payment_type: '',
                    unit_types: [],
                    arrayIndex: '',
                },
                newItem: {},
                addEntry: false,
            }
        },
        mounted() {
            this.allItems = JSON.parse(this.propItems);
            this.items = JSON.parse(this.propItems);
            this.users = JSON.parse(this.propUsers);
            this.unitTypes = JSON.parse(this.propUnitTypes);
            this.total_leases = 0;
            this.items.forEach((item) => {
                this.total_leases = this.total_leases + item.item_lease_dates.length;
            });


        },
        methods: {

            isEven: function (n) {
                return n % 2 == 0;
            },

            toNiceDate: (date) => {
                return moment(date).format("DD / MM / YY");
            },

            showAddForm: function () {
                this.addEntry = true;
            },

            addItem: function () {

                this.loading = true;

                let formData = {
                    user_id: this.newItem.user_id,
                    leasee_name: this.newItem.leasee_name,
                    item_name: this.newItem.item_name,
                    item_id: this.editItem.id,
                    start_date: this.newItem.start_date,
                    end_date: this.newItem.end_date,
                    status: 'Active'
                };
                // this.loading = false;

                this.$http.post(
                    '/items/lease',
                    JSON.stringify(formData)
                ).then((response) => {
                    this.loading = false;
                    // Reset the new location.
                    this.addEntry = false;
                    // If the response is successful, let's take the ID of the response
                    // We then add it to the items object.

                    // this.items.push(response.body.data);

                    this.items[this.index].item_lease_dates.push(response.body.data);


                }, (err) => {

                    this.loading = false;
                    this.displayError(err);
                });

            },

            filter() {
                // Let's get a fresh list before filter.
                this.items = this.allItems;

                // If the input field is blank, let's not apply filter logic and return everything.
                if (this.$refs['filterInput'].value != '') {

                    this.items = this.items.filter((item) => {
                        // Let's iterate over the attributes of the unit.
                        var isValid = false;
                        var filteredInput = this.$refs['filterInput'].value;

                        Object.keys(item).forEach(function (key) {
                            let obj = item[key];
                            if (typeof obj === 'string' && obj.indexOf(filteredInput) !== -1 && obj !== null) {
                                isValid = item;
                            } else if (typeof obj === 'object' && obj !== null) {

                                obj.forEach(function (lease) {
                                    let obj2 = lease;
                                    if (typeof obj2 === 'string' && obj2.indexOf(filteredInput) !== -1) {

                                        isValid = item;

                                    } else if (typeof obj2 === 'object' && obj2 !== null) {

                                        let p = obj2;

                                        for (var key in p) {
                                            if (p.hasOwnProperty(key)) {
                                                console.log(key + " -> " + p[key]);
                                                if (typeof p[key] === 'string' && p[key].toLowerCase().indexOf(filteredInput.toLowerCase()) !== -1) {

                                                    isValid = item;
                                                }
                                            }
                                        }

                                        Array.from(obj2).forEach(child => {
                                            console.log(child)
                                        })

//                                        obj2.forEach(function (lease_val) {
//                                            let obj3 = lease_val;
//                                            console.log(typeof obj3);
//                                            if (typeof obj3 === 'string' && obj3.indexOf(filteredInput) !== -1) {
//
//                                                isValid = item;
//                                            }
//                                        });
                                    }
                                });
                            }
                        });
                        return isValid;
                    });
                }

                if (this.$el.querySelector(".accordion__heading--active")) {
                    this.$el.querySelectorAll(".accordion__heading--active").forEach(function (element) {
                        element.classList.toggle("accordion__heading--active");
                    });

                    this.$el.querySelectorAll(".accordion__content--active").forEach(function (element) {
                        element.classList.toggle("accordion__content--active");
                    });
                }

            },

            cancelAddItem: function () {
                this.addEntry = false;
            },

            removeItem: function (index, index2) {

                this.loading = true;
                this.$http.delete(
                    '/items/lease/' + this.items[index].item_lease_dates[index2].id,
                    JSON.stringify(this.items[index].item_lease_dates[index2])
                ).then((response) => {
                    this.loading = false;
                    this.items[index].item_lease_dates.splice(index2, 1);
                    this.displaySuccess(response);
                }, (err) => {
                    this.loading = false;
                    this.displayError(err);
                });

            },

            accordionToggle: function (index, event) {

                event.preventDefault();
                let selectedElement = event.target;

                // Lets check the initial classes to see if we should close the current accordion or open a new one.
                let shouldClose = !selectedElement.classList.contains('accordion__heading--active');

                // Let's close all accordions fist
                this.closeAllAccordions();

                this.editItem = this.items[index];
                this.index = index;

                // If the button was open, we don't want to reopen it.
                if (shouldClose) {
                    selectedElement.classList.toggle("accordion__heading--active");
                    selectedElement.nextElementSibling.classList.toggle("accordion__content--active");
                }
            },

            closeAllAccordions() {
                if (this.$el.querySelector(".accordion__heading--active")) {
                    this.$el.querySelectorAll(".accordion__heading--active").forEach(function (element) {
                        element.classList.toggle("accordion__heading--active");
                    });

                    this.$el.querySelectorAll(".accordion__content--active").forEach(function (element) {
                        element.classList.toggle("accordion__content--active");
                    });
                }
            },

            displayError(err) {
                // There is an error, let's display an alert.
                let errorMessage = '';
                if (err.body.message) {
                    errorMessage = err.body.message;
                } else {
                    // This should occur if there are any validation errors.
                    // Let's iterate over the list of errors.
                    Object.keys(err.body).forEach(function (key) {
                        let obj = err.body[key];
                        obj = obj.toString();
                        errorMessage = errorMessage + obj + '\r \n';
                    });
                }

                // THere is an error, let's display an alert.
                swal({
                    title: "Error!",
                    text: errorMessage,
                    type: "error",
                    confirmButtonText: "Ok"
                });
            },

            displaySuccess(msg) {
                // There is an error, let's display an alert.
                let message = '';
                if (msg.body.message) {
                    message = msg.body.message;
                } else {
                    // This should occur if there are any validation errors.
                    // Let's iterate over the list of errors.
                    Object.keys(msg.body).forEach(function (key) {
                        let obj = msg.body[key];
                        obj = obj.toString();
                        message = message + obj + '\r \n';
                    });
                }

                // THere is an error, let's display an alert.
                swal({
                    title: "Success!",
                    text: message,
                    type: "success",
                    confirmButtonText: "Ok"
                });
            },

        }
    }

</script>
