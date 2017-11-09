<template>
    <div>

        <div class="row">
            <div class="medium-9 columns">
                <h2 class="--focused">UNITS | the rooms people stay in</h2>
                <p>
                    A full list of all the units across all the locations. Use the filter option to narrow your results. A unit can be linked
                    to a tenant and have a current active contract.
                </p>
            </div>
        </div>

        <div class="row">

            <!-- Title -->
            <div class="small-12 medium-9 columns">

                <!-- START List Section -->
                <div class="row table">

                    <div class="small-12 columns">
                        <div class="table__row table__row--add">
                            <!-- Row Title -->
                            <button class="accordion__heading accordion__heading--add" v-on:click="addEntry = !addEntry">
                                <h4 class="--white">Add New Unit</h4>
                            </button>
                            <!-- START Edit form -->
                            <div class="accordion__content --bg-calm" v-bind:class="{ 'accordion__content--active' : addEntry }" v-show="addEntry">

                                <div class="row column">
                                    <!-- START Unit input form -->

                                    <label for="locationId">
                                        Location*
                                        <select ref="locationId" id="locationId" name="locationId" v-model="newUnit.location_id">
                                            <option value=""></option>
                                            <option v-for="location in locations" v-bind:value="location.id">
                                                {{ location.name }}
                                            </option>
                                        </select>
                                    </label>

                                    <label for="locationCode">
                                        Code
                                        <input type="text" id="locationCode" ref="locationCode" name="locationCode" v-model="newUnit.code">
                                    </label>

                                    <label for="unitTypeId">
                                        Unit Type*
                                        <select ref="unitTypeId" id="unitTypeId" name="unitTypeId" v-model="newUnit.type_id">
                                            <option value=""></option>
                                            <option v-for="unitType in unitTypes" v-bind:value="unitType.id">
                                                {{ unitType.name }}
                                            </option>
                                        </select>
                                    </label>
                                </div>
                                <!-- END Edit form -->

                                <div class="row column">
                                    <button type="submit" name="addUnit" class="button button--focused --mt1" v-on:click="addUnit" v-bind:disabled="loading">
                                        <span v-if="loading">
                                            <loading></loading>
                                        </span>
                                        <span v-else>Add unit</span>
                                    </button>
                                    <button type="submit" name="addLocation" class="button float-right --mt1" v-on:click="cancelUnit" v-bind:disabled="loading">
                                        <span v-if="loading">
                                            <loading></loading>
                                        </span>
                                        <span v-else>Cancel</span>
                                    </button>
                                </div>

                            </div>

                        </div>
                    </div>

                    <!-- Repeat in here -->

                    <template v-for="(filteredUnit, index) in filteredUnits">
                        <div class="small-12 columns" v-show="index >= pagination.from && index <= pagination.to">
                            <div class="table__row" :class="{ even: isEven(index), first: index == 0, last: index == units.length -1 }">
                                <!-- Row Title -->
                                <button class="accordion__heading" v-on:click="accordionToggle(index, $event)">{{ filteredUnit.code }} - {{filteredUnit.location.name}} </button>
                                <!-- START Edit form -->
                                <div class="accordion__content  --bg-calm">

                                    <label for="editLocationId">
                                        Location*
                                        <select class="--mb0" ref="editLocationId" id="editLocationId" name="editLocationId" v-model="editUnit.location_id">
                                            <option value=""></option>
                                            <option v-for="location in locations" v-bind:value="location.id">
                                                {{ location.name }}
                                            </option>
                                        </select>
                                    </label>

                                    <label for="editLocationCode">
                                        Code
                                        <input type="text" id="editLocationCode" ref="editLocationCode" name="editLocationCode" v-model="editUnit.code">
                                    </label>

                                    <label for="editTypeId">
                                        Unit Type*
                                        <select ref="editTypeId" id="editTypeId" name="editTypeId" v-model="editUnit.type_id">
                                            <option value=""></option>
                                            <option v-for="unitType in unitTypes" v-bind:value="unitType.id">
                                                {{ unitType.name }}
                                            </option>
                                        </select>
                                    </label>

                                    <button type="submit" class="success button" v-on:click="updateUnit" v-bind:disabled="loading">
                                        <span v-if="loading">
                                            <loading></loading>
                                        </span>
                                        <span v-else>
                                            Update unit
                                        </span>
                                    </button>
                                </div>
                                <!-- END Edit form -->
                            </div>
                        </div>
                    </template>

                    <!-- START Pagination buttons -->
                    <div class="row">
                        <ul class="pagination text-center" role="navigation" aria-label="Pagination">
                            <!-- <li class="pagination-previous" v-on:click="pagination.currentPage = pagination.currentPage - 1; calculatePagination()" v-bind:class="{disabled: pagination.previousPage > 0}" >Previous</li> -->
                            <template v-for="n in pagination.maxPages">
                                <li class="current" v-show="n ==  pagination.currentPage">
                                    <span class="show-for-sr">You're on page</span> {{n}} </li>
                                <li>
                                    <a v-bind:aria-label="'Page ' + n" v-on:click="pagination.currentPage = n; calculatePagination()"
                                        v-show="n !=  pagination.currentPage"> {{n}}</a>
                                </li>
                            </template>
                            <!-- <li class="pagination-next" v-on:click="pagination.currentPage = pagination.currentPage + 1; calculatePagination()" v-show="pagination.nextPage <= pagination.maxPages"><a aria-label="Next page">Next</a></li> -->
                        </ul>
                    </div>
                    <!-- END Pagination buttons -->

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

                <div class="row column --border-wrap">
                    <div class="stats-box">
                        <div class="row column clearfix">
                            <h3 class="stats-box__header --focused --mt0">Stats</h3>
                        </div>
                        Units:
                        <span class="float-right">{{units.length}}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>
<script>
    export default {
        props: ['propLocations', 'propUnitTypes', 'propUnits'],
        data() {
            return {
                locations: [],
                unitTypes: [],
                units: [],
                filteredUnits: [],
                loading: false,
                editUnit: {
                    id: '',
                    location_id: '',
                    code: '',
                    type_id: '',
                    user_id: '',
                    contract_id: '',
                },
                newUnit: {},
                pagination: {
                    total: 1,
                    from: 0,
                    to: 1,
                    per_page: 50,
                    currentPage: 1,
                    nextPage: 1,
                    previousPage: 1,
                    maxPages: 0
                },
                addEntry: false,
            }
        },
        mounted() {

            this.locations = JSON.parse(this.propLocations);
            this.unitTypes = JSON.parse(this.propUnitTypes);
            this.units = JSON.parse(this.propUnits);

            this.newUnit = this.initializeUnit();
            // Let's assign the units to the filtered units so later we can filter out what we don't need
            this.filteredUnits = this.units;
            this.calculatePagination();
        },
        methods: {

            isEven: function (n) {
                return n % 2 == 0;
            },

            addUnit: function () {
                this.loading = true;

                this.$http.post(
                    '/units',
                    JSON.stringify(this.newUnit)
                ).then((response) => {
                    this.loading = false;
                    // If the response is successful, let's take the ID of the response
                    // We then add it to the units object.
                    let newUnitToAdd = response.data.data;
                    this.units.push(newUnitToAdd);
                    // Reset the new unit.
                    this.newUnit = this.initializeUnit();
                    this.addEntry = false;
                }, (err) => {
                    this.loading = false;
                    // THere is an error, let's display an alert.
                    this.displayError(err);
                });

            },

            cancelUnit: function () {
                this.newUnit = this.initializeUnit();
                this.addEntry = false;
            },

            updateUnit: function () {

                this.loading = true;

                this.$http.patch(
                    '/units/' + this.editUnit.id,
                    JSON.stringify(this.editUnit)
                ).then((response) => {
                    // If the response is successful, lets set the name to the edited object
                    this.loading = false;
                    this.filteredUnits[this.editUnit.index] = this.editUnit;
                    this.units[this.editUnit.index] = this.editUnit;
                    // To prevent reactivity from going accross, let's reassign the object.
                    this.createEditableObject(this.editUnit.index);
                    if (this.$el.querySelector(".accordion__heading--active")) {
                        this.$el.querySelectorAll(".accordion__heading--active").forEach(function (element) {
                            element.classList.toggle("accordion__heading--active");
                        });

                        this.$el.querySelectorAll(".accordion__content--active").forEach(function (element) {
                            element.classList.toggle("accordion__content--active");
                        });
                    }
                }, (err) => {
                    this.loading = false;
                    // There is an error, let's display an alert.
                    this.displayError(err);
                });

            },

            accordionToggle: function (index, event) {
                event.preventDefault();
                let selectedElement = event.target;

                // Lets check the initial classes to see if we should close the current accordion or open a new one.
                let shouldClose = !selectedElement.classList.contains('accordion__heading--active');

                // Let's close all accordions fist
                if (this.$el.querySelector(".accordion__heading--active")) {
                    this.$el.querySelectorAll(".accordion__heading--active").forEach(function (element) {
                        element.classList.toggle("accordion__heading--active");
                    });

                    this.$el.querySelectorAll(".accordion__content--active").forEach(function (element) {
                        element.classList.toggle("accordion__content--active");
                    });
                }
                // If the button was open, we don't want to reopen it.
                if (shouldClose) {
                    selectedElement.classList.toggle("accordion__heading--active");
                    selectedElement.nextElementSibling.classList.toggle("accordion__content--active");
                }

                // Let's load up the editable object with the selected item
                // Let's wait for the previous accordion animation to finish then do this.
                setTimeout(() => {
                    // If we want to use vue with it's reactivity use the below
                    //this.editUnit = this.units[index];
                    this.createEditableObject(index);
                }, 200)
            },

            createEditableObject(index) {
                // If we want to assign a completly new object which will not update the other form due to
                // reactivity, we must manually assign whatever is needed.
                // We also need the array index so when we update succesfully we know which index to update.
                this.editUnit = {};
                this.editUnit.index = index;
                this.editUnit.id = this.filteredUnits[index].id;
                this.editUnit.location_id = this.filteredUnits[index].location_id;
                this.editUnit.code = this.filteredUnits[index].code;
                this.editUnit.type_id = this.filteredUnits[index].type_id;
                this.editUnit.user_id = '';
                this.editUnit.contract_id = '';
                this.editUnit.location = this.filteredUnits[index].location;
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

            initializeUnit() {
                return {
                    location_id: '',
                    code: '',
                    type_id: ''
                };
            },

            filter() {
                // Let's get a fresh list before filter.
                this.filteredUnits = this.units;

                // If the input field is blank, let's not apply filter logic and return everything.
                if (this.$refs['filterInput'].value != '') {
                    this.filteredUnits = this.filteredUnits.filter((unit) => {
                        // Let's iterate over the attributes of the unit.
                        var isValid = false;
                        var filteredInput = this.$refs['filterInput'].value;
                        Object.keys(unit).forEach(function (key) {
                            // If the key is the code
                            // TODO: This needs to take into account the location
                            // in the filter values and not just the code
                            let obj = unit[key];
                            if ((key === 'code') && obj && obj.indexOf(filteredInput) !== -1) {
                                isValid = true;
                            }
                        });
                        return isValid;
                    });

                    this.pagination.currentPage = 1;
                }

                if (this.$el.querySelector(".accordion__heading--active")) {
                    this.$el.querySelectorAll(".accordion__heading--active").forEach(function (element) {
                        element.classList.toggle("accordion__heading--active");
                    });

                    this.$el.querySelectorAll(".accordion__content--active").forEach(function (element) {
                        element.classList.toggle("accordion__content--active");
                    });
                }

                this.calculatePagination();
            },

            calculatePagination() {
                this.pagination.total = this.filteredUnits.length;
                // Since Arrays start from 0, we must subtract an additional 1.
                this.pagination.from = (this.pagination.currentPage - 1) * this.pagination.per_page;
                this.pagination.to = this.pagination.from + this.pagination.per_page - 1;
                this.pagination.nextPage = this.pagination.currentPage + 1;
                this.pagination.previousPage = this.pagination.currentPage - 1;
                // Since Arrays start at 0 we need to increment this value
                this.pagination.maxPages = Math.round((this.pagination.total / this.pagination.per_page));
            }

        }
    }

</script>