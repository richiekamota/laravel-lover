<template>
    <div>
        <div class="row">

            <!-- Title -->
            <div class="small-12 medium-6 columns">
                <h1>Units - INCOMPLETE!!</h1>
            </div>

            <!-- Show or hide addtion form button -->
            <div class="small-12 medium-6 columns align-middle -text-right">
                <button type="submit" class="success button -margin-bottom-0" v-on:click="addEntry =! addEntry" v-if="!addEntry">
                    <span >
                        Add unit
                    </span>
                </button>
            </div>

            <div class="columns">
                <div class="row" v-show="addEntry">
                    <div class="small-12 columns">
                        <!-- START Location input form -->

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
                            <input type="text" ref="locationCode" name="locationCode" v-model="newUnit.code">
                        </label>

                        <label for="locationId">
                            Unit Type*
                            <select ref="locationId" id="locationId" name="locationId" v-model="newUnit.type_id">
                                <option value=""></option>
                                <option v-for="unitType in unitTypes" v-bind:value="unitType.id">
                                    {{ unitType.name }}
                                </option>
                            </select>
                        </label>
                    </div>

                    <div class="small-12 columns">
                        <button type="submit" class="success button" v-on:click="addLocation" v-bind:disabled="loading">
                            <span v-if="loading">
                                <loading></loading>
                            </span>
                            <span v-else>
                                Add unit
                            </span>
                        </button>
                    </div>
                    <!-- END Location input form -->
                </div>

            <!-- START List Section -->
                <div class="row">
                    <template v-for="(location, index) in locations" >
                        <div class="small-12 columns" v-show="index >= pagination.from && index <= pagination.to">
                            <!-- Row Title -->
                            <button class="accordion__heading" v-on:click="accordionToggle(index, $event)">{{ location.name }}</button>
                            <!-- START Edit form -->
                            <div class="accordion__content">

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
                                    <input type="text" ref="locationCode" name="locationCode" v-model="newUnit.code">
                                </label>

                                <label for="typeId">
                                    Unit Type*
                                    <select ref="typeId" id="typeId" name="typeId" v-model="newUnit.type_id">
                                        <option value=""></option>
                                        <option v-for="unitType in unitTypes" v-bind:value="unitType.id">
                                            {{ unitType.name }}
                                        </option>
                                    </select>
                                </label>

                                <button type="submit" class="success button" v-on:click="editLocation" v-bind:disabled="loading">
                                    <span v-if="loading">
                                        <loading></loading>
                                    </span>
                                    <span v-else>
                                        Update location
                                    </span>
                                </button>
                            </div>
                            <!-- END Edit form -->
                    </template>

                    <!-- START Pagination buttons -->
                    <button v-on:click="pagination.currentPage = pagination.currentPage - 1; calculatePagination()" v-show="pagination.previousPage > 0">Previous</button>
                    <template v-for="n in pagination.maxPages">
                        <button v-on:click="pagination.currentPage = n; calculatePagination()" v-show=" n !=  pagination.currentPage" > {{n}} </button>
                    </template>
                    <button v-on:click="pagination.currentPage = pagination.currentPage + 1; calculatePagination()" v-show="pagination.nextPage <= pagination.maxPages">Next</button>
                    <!-- END Pagination buttons -->
                </div>
                <!-- END List Section -->
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['propLocations', 'propUnitTypes'],
        data(){
            return{
                locations: [],
                unitTypes: [],
                units: [],
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
                    per_page: 2,
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
            this.newUnit = this.initializeUnit();
            this.calculatePagination();
        },
        methods: {
            addLocation : function() {
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
                    this.loading = false;
                }, (err) => {
                    this.loading = false;
                    // THere is an error, let's display an alert.
                    this.displayError(err);
                });

            },

            editLocation: function() {
                this.loading = true;

                this.$http.patch(
                    '/units/' + this.editUnit.id,
                    JSON.stringify(this.editUnit)
                ).then((response) => {
                    // If the response is successful, lets set the name to the edited object
                    this.loading = false;
                    this.units[this.editUnit.index] = this.editUnit;
                    // To prevent reactivity from going accross, let's reassign the object.
                    this.createEditableObject(this.editUnit.index);
                }, (err) => {
                    this.loading = false;
                    // THere is an error, let's display an alert.
                    this.displayError(err);
                });

            },

            accordionToggle: function(index, event) {
                event.preventDefault();
                let selectedElement = event.target;

                // Lets check the initial classes to see if we should close the current accordion or open a new one.
                let shouldClose = !selectedElement.classList.contains('accordion__heading--active');

                // Let's close all accordions fist
                if(this.$el.querySelector(".accordion__heading--active")) {
                    this.$el.querySelectorAll(".accordion__heading--active").forEach(function(element) {
                        element.classList.toggle("accordion__heading--active");
                    });

                    this.$el.querySelectorAll(".accordion__content--active").forEach(function(element) {
                        element.classList.toggle("accordion__content--active");
                    });
                }
                // If the button was open, we don't want to reopen it.
                if(shouldClose) {
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
                this.editUnit.id = this.units[index].id;
                this.editUnit.location_id = this.units[index].location_id;
                this.editUnit.code = this.units[index].code;
                this.editUnit.type_id  = this.units[index].type_id;
                this.editUnit.user_id = '';
                this.editUnit.contract_id = '';
            },

            displayError(err) {
                // There is an error, let's display an alert.
                let errorMessage = '';
                if(err.body.message) {
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

            calculatePagination() {
                this.pagination.total = this.locations.length;
                // Since Arrays start from 0, we must subtract an additional 1.
                this.pagination.from = (this.pagination.currentPage - 1) * this.pagination.per_page;
                this.pagination.to = this.pagination.from + this.pagination.per_page - 1;
                this.pagination.nextPage = this.pagination.currentPage + 1;
                this.pagination.previousPage = this.pagination.currentPage - 1;
                // Since Arrays start at 0 we need to increment this value
                this.pagination.maxPages = (this.pagination.total / this.pagination.per_page);
            }

        }
    }

</script>
