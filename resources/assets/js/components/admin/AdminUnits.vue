<template>
    <div>
        <div class="row">

            <!-- Title -->
            <div class="small-12 medium-6 columns">
                <h1>Units - INCOMPLETE!!</h1>
            </div>

            <!-- Show or hide addtion form button -->
            <div class="small-12 medium-6 columns align-middle -text-right">
                <button type="submit" class="success button -margin-bottom-0" v-on:click="addEntry =! addEntry">
                    <span v-if="!addEntry">
                        Show addition form
                    </span>
                    <span v-else>
                        Hide addition form
                    </span>
                </button>
            </div>

            <div class="columns">
                <div class="row" v-show="addEntry">
                    <div class="small-12 columns">
                        <!-- START Location input form -->
                        <label for="locationName">
                            Location name*
                            <input type="text" ref="locationInput" name="locationName" v-model="newLocation.name">
                        </label>

                        <label for="locationAddress">
                            Address*
                            <textarea ref="locationAddress" name="locationAddress" v-model="newLocation.address"></textarea>
                        </label>

                        <label for="locationCity">
                            City*
                            <select ref="locationCity" name="locationCity" v-model="newLocation.city">
                                <option value=""></option>
                                <option v-for="city in cities" v-bind:value="city">
                                    {{ city }}
                                </option>
                            </select>
                        </label>

                        <label for="locationRegion">
                            Region*
                            <input type="text" ref="locationRegion" name="locationRegion" v-model="newLocation.region">
                        </label>

                        <label for="locationCode">
                            Code
                            <input type="text" ref="locationCode" name="locationCode" v-model="newLocation.code">
                        </label>
                    </div>

                    <div class="small-12 columns">
                        <button type="submit" class="success button" v-on:click="addLocation" v-bind:disabled="loading">
                            <span v-if="loading">
                                <loading></loading>
                            </span>
                            <span v-else>
                                Add location
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
                                <label for="editLocationName">
                                    Location name
                                    <input type="text" ref="editLocationInput" name="editLocationName" v-model="editItem.name">
                                </label>

                                <label for="editLocationAddress">
                                    Address
                                    <textarea ref="editLocationAddress" name="editLocationAddress" v-model="editItem.address"></textarea>
                                </label>

                                <label for="editLocationCity">
                                    City
                                    <select ref="editLocationCity" name="editLocationCity" v-model="editItem.city">
                                        <option value=""></option>
                                        <option v-for="city in cities" v-bind:value="city">
                                            {{ city }}
                                        </option>
                                    </select>
                                </label>

                                <label for="editLocationRegion">
                                    Region
                                    <input type="text" ref="editLocationRegion" name="editLocationRegion" v-model="editItem.region">
                                </label>

                                <label for="editLocationCode">
                                    Code
                                    <input type="text" ref="editLocationCode" name="editLocationCode" v-model="editItem.code">
                                </label>

                                <button type="submit" class="success button" v-on:click="editLocation" v-bind:disabled="loading">
                                    <span v-if="loading">
                                        <loading></loading>
                                    </span>
                                    <span v-else>
                                        Edit location
                                    </span>
                                </button>
                            </div>
                            <!-- END Edit form -->
                    </template>

                    <!-- START Pagination buttons -->
                    <button v-on:click="pagination.currentPage = pagination.currentPage - 1; calculatePagination()" v-show="pagination.previousPage > 0">Previous</button>
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
        props: ['propLocations'],
        data(){
            return{
                locations: [],
                loading: false,
                cities: [],
                editItem: {
                    id: '',
                    name: '',
                    address: '',
                    city: '',
                    region: '',
                    code: '',
                    arrayIndex: '',
                },
                newLocation: {
                    name: '',
                    address: '',
                    city: '',
                    region: '',
                    code: '',
                },
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
            let locationData = [
                {
                    id: '1',
                    title: 'Woodstock'
                },
                {
                    id: '2',
                    title: 'Obz'
                },
                {
                    id: '3',
                    title: 'Muizenberg'
                },
            ];
            this.locations = JSON.parse(this.propLocations);
            this.calculatePagination();
        },
        methods: {
            addLocation : function() {
                this.loading = true;

                this.$http.post(
                    '/locations',
                    JSON.stringify(this.newLocation)
                ).then((response) => {
                    this.loading = false;
                    // If the response is successful, let's take the ID of the response
                    // We then add it to the locations object.
                    let newLocationToAdd = response.data.data;
                    this.locations.push(newLocationToAdd);
                    // Reset the new location.
                    this.newLocation = this.initializeLocation();
                    this.loading = false;
                }, (err) => {
                    this.loading = false;
                    // THere is an error, let's display an alert.

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

                    swal({
                      title: "Error!",
                      text: errorMessage,
                      type: "error",
                      confirmButtonText: "Ok"
                    });
                });

            },

            editLocation: function() {
                this.loading = true;

                this.$http.patch(
                    '/locations/' + this.editItem.id,
                    JSON.stringify(this.editItem)
                ).then((response) => {
                    // If the response is successful, lets set the name to the edited object
                    this.loading = false;
                    this.locations[this.editItem.index] = this.editItem;
                    // To prevent reactivity from going accross, let's reassign the object.
                    this.createEditableObject(this.editItem.index);
                }, (err) => {
                    this.loading = false;
                    // THere is an error, let's display an alert.
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
                    //this.editItem = this.locations[index];
                    this.createEditableObject(index);
                }, 200)
            },

            createEditableObject(index) {
                 // If we want to assign a completly new object which will not update the other form due to
                 // reactivity, we must manually assign whatever is needed.
                 // We also need the array index so when we update succesfully we know which index to update.
                this.editItem = {};
                this.editItem.index = index;
                this.editItem.id = this.locations[index].id;
                this.editItem.name = this.locations[index].name;
                this.editItem.address = this.locations[index].address;
                this.editItem.city = this.locations[index].city;
                this.editItem.region = this.locations[index].region;
                this.editItem.code = this.locations[index].code;
            },

            initializeLocation() {
                return {
                        name: '',
                        address: '',
                        city: '',
                        region: '',
                        code: '',
                    };
            },

            calculatePagination() {
                this.pagination.total = this.locations.length;
                // Since Arrays start from 0, we must subtract an additional 1.
                this.pagination.from = this.pagination.currentPage - (this.pagination.per_page -1);
                this.pagination.to = this.pagination.currentPage;
                this.pagination.nextPage = this.pagination.currentPage + 1;
                this.pagination.previousPage = this.pagination.currentPage - 1;
                // Since Arrays start at 0 we need to increment this value
                this.pagination.maxPages = (this.pagination.total / this.pagination.per_page) + 1;
                console.log("Pagination is ",this.pagination);
            }

        }
    }

</script>
