<template>
    <div>
        <div class="row">

            <!-- Title -->
            <div class="small-12 medium-6 columns">
                <h1>Locations</h1>
            </div>

            <!-- Show or hide addtion form button -->
            <div class="small-12 medium-6 columns align-middle -text-right">
                <button type="submit" class="success button -margin-bottom-0" v-on:click="addEntry =! addEntry" v-if="!addEntry">
                    <span >
                        Add location
                    </span>
                </button>
            </div>

            <div class="columns">
                <div class="row" v-show="addEntry">
                    <div class="small-12 columns">
                        <!-- START Location input form -->
                        <label for="locationName">
                            Location name*
                            <input type="text" id="locationName" ref="locationInput" name="locationName" v-model="newLocation.name">
                        </label>

                        <label for="locationAddress">
                            Address*
                            <textarea ref="locationAddress" id="locationAddress" name="locationAddress" v-model="newLocation.address"></textarea>
                        </label>

                        <label for="locationCity">
                            City*
                            <select ref="locationCity" id="locationCity" name="locationCity" v-model="newLocation.city">
                                <option value=""></option>
                                <option v-for="city in cities" v-bind:value="city">
                                    {{ city }}
                                </option>
                            </select>
                        </label>

                        <label for="locationRegion">
                            Region*
                            <input type="text" id="locationRegion" ref="locationRegion" name="locationRegion" v-model="newLocation.region">
                        </label>

                        <label for="locationCode">
                            Code
                            <input type="text" id="locationCode" ref="locationCode" name="locationCode" v-model="newLocation.code">
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
                        <div class="small-12 columns">
                            <!-- Row Title -->
                            <button class="accordion__heading" v-on:click="accordionToggle(index, $event)">{{ location.name }}</button>
                            <!-- START Edit form -->
                            <div class="accordion__content">
                                <label for="editLocationName">
                                    Location name
                                    <input type="text" id="editLocationName" ref="editLocationInput" name="editLocationName" v-model="editLocation.name">
                                </label>

                                <label for="editLocationAddress">
                                    Address
                                    <textarea ref="editLocationAddress" id="editLocationAddress" name="editLocationAddress" v-model="editLocation.address"></textarea>
                                </label>

                                <label for="editLocationCity">
                                    City
                                    <select ref="editLocationCity" id="editLocationCity" name="editLocationCity" v-model="editLocation.city">
                                        <option value=""></option>
                                        <option v-for="city in cities" v-bind:value="city">
                                            {{ city }}
                                        </option>
                                    </select>
                                </label>

                                <label for="editLocationRegion">
                                    Region
                                    <input type="text" id="editLocationRegion" ref="editLocationRegion" name="editLocationRegion" v-model="editLocation.region">
                                </label>

                                <label for="editLocationCode">
                                    Code
                                    <input type="text" id="editLocationCode" ref="editLocationCode" name="editLocationCode" v-model="editLocation.code">
                                </label>

                                <button type="submit" class="success button" v-on:click="updateLocation" v-bind:disabled="loading">
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
                cities: [
                    "Atlantis",
                    "Bellville",
                    "Blue Downs",
                    "Brackenfell",
                    "Cape Town",
                    "Crossroads",
                    "Durbanville",
                    "Eerste River",
                    "Elsie's River",
                    "Fish Hoek",
                    "Goodwood",
                    "Gordon's Bay",
                    "Guguletu",
                    "Hout Bay",
                    "Khayelitsha",
                    "Kraaifontein",
                    "Kuils River",
                    "Langa",
                    "Macassar",
                    "Melkbosstrand",
                    "Mfuleni",
                    "Milnerton",
                    "Mitchell's Plain",
                    "Noordhoek",
                    "Nyanga",
                    "Observatory",
                    "Parow",
                    "Simon's Town",
                    "Somerset West",
                    "Southern Suburbs",
                    "Strand"
                ],
                editLocation: {
                    id: '',
                    name: '',
                    address: '',
                    city: '',
                    region: '',
                    code: '',
                    arrayIndex: '',
                },
                newLocation: {},
                addEntry: false,
            }
        },
        mounted() {
            this.locations = JSON.parse(this.propLocations);
            this.newLocation = this.initializeLocation();
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
                    this.addEntry = false;

                }, (err) => {

                    this.loading = false;

                    this.displayError(err);
                });

            },

            updateLocation: function() {

                this.loading = true;

                this.$http.patch(
                    '/locations/' + this.editLocation.id,
                    JSON.stringify(this.editLocation)
                ).then((response) => {

                    // If the response is successful, lets set the name to the edited object
                    this.loading = false;
                    this.locations[this.editLocation.index] = this.editLocation;
                    // To prevent reactivity from going accross, let's reassign the object.
                    this.createEditableObject(this.editLocation.index);
                    this.closeAllAccordions();

                }, (err) => {
                    this.loading = false;
                    // There is an error, let's display an alert.
                    this.displayError(err);
                });

            },

            accordionToggle: function(index, event) {
                event.preventDefault();
                let selectedElement = event.target;

                // Lets check the initial classes to see if we should close the current accordion or open a new one.
                let shouldClose = !selectedElement.classList.contains('accordion__heading--active');

                // Let's close all accordions fist
                this.closeAllAccordions();

                // If the button was open, we don't want to reopen it.
                if(shouldClose) {
                    selectedElement.classList.toggle("accordion__heading--active");
                    selectedElement.nextElementSibling.classList.toggle("accordion__content--active");
                }

                // Let's load up the editable object with the selected item
                // Let's wait for the previous accordion animation to finish then do this.
                setTimeout(() => {
                    // If we want to use vue with it's reactivity use the below
                    //this.editLocation = this.locations[index];
                    this.createEditableObject(index);
                }, 200)
            },

            closeAllAccordions() {
                if(this.$el.querySelector(".accordion__heading--active")) {
                    this.$el.querySelectorAll(".accordion__heading--active").forEach(function(element) {
                        element.classList.toggle("accordion__heading--active");
                    });

                    this.$el.querySelectorAll(".accordion__content--active").forEach(function(element) {
                        element.classList.toggle("accordion__content--active");
                    });
                }
            },

            createEditableObject(index) {
                 // If we want to assign a completely new object which will not update the other form due to
                 // reactivity, we must manually assign whatever is needed.
                 // We also need the array index so when we update successfully we know which index to update.

                 // TODO is this not better if we take the entire object and delete the obserable instead?
                /*this.editLocation = this.locations[index];
                delete this.editLocation.__ob__;*/
                //console.log("This edit item is", this.editLocation);
                this.editLocation = {};
                this.editLocation.index = index;
                this.editLocation.id = this.locations[index].id;
                this.editLocation.name = this.locations[index].name;
                this.editLocation.address = this.locations[index].address;
                this.editLocation.city = this.locations[index].city;
                this.editLocation.region = this.locations[index].region;
                this.editLocation.code = this.locations[index].code;
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

            initializeLocation() {
                return {
                        name: '',
                        address: '',
                        city: '',
                        region: '',
                        code: '',
                    };
            }

        }
    }

</script>
