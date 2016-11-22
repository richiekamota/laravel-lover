<template>
    <div>
        <div class="row">

            <!-- Title -->
            <div class="small-12 medium-6 columns">
                <h1>Unit Types</h1>
            </div>

            <!-- Show or hide addtion form button -->
            <div class="small-12 medium-6 columns align-middle -text-right">
                <button type="submit" class="success button -margin-bottom-0" v-on:click="addEntry =! addEntry" v-if="!addEntry">
                    <span >
                        Add Unit Type
                    </span>
                </button>
            </div>

            <div class="columns">
                <div class="row" v-show="addEntry">
                    <div class="small-12 columns">
                        <!-- START Location input form -->
                        <label for="unitTypeName">
                            Unit type name*
                            <input type="text" ref="unitTypeName" id="unitTypeName" name="unitTypeName" v-model="newUnitType.name">
                        </label>

                        <label for="unitTypeDescription">
                            Description*
                            <textarea ref="unitTypeDescription" id="unitTypeDescription" name="unitTypeDescription" v-model="newUnitType.description"></textarea>
                        </label>

                        <label for="locationId">
                            Location*
                            <select ref="locationId" id="locationId" name="locationId" v-model="newUnitType.location_id">
                                <option value=""></option>
                                <option v-for="location in locations" v-bind:value="location.id">
                                    {{ location.name }}
                                </option>
                            </select>
                        </label>

                        <label for="unitTypeCost">
                            Unit type Cost*
                            <input type="number" ref="unitTypeCost" id="unitTypeCost" name="unitTypeCost" v-model="newUnitType.cost">
                        </label>

                        <label for="unitTypeWifi">
                            Wifi
                            <input type="checkbox" ref="unitTypeWifi" id="unitTypeWifi" name="unitTypeWifi" v-model="newUnitType.wifi">
                        </label>

                        <label for="unitTypeElectricity">
                            Electricity
                            <input type="checkbox" ref="unitTypeElectricity" id="unitTypeElectricity" name="unitTypeElectricity" v-model="newUnitType.electricity">
                        </label>

                        <label for="unitTypeDstv">
                            Dstv
                            <input type="checkbox" ref="unitTypeDstv" id="unitTypeDstv" name="unitTypeDstv" v-model="newUnitType.dstv">
                        </label>

                        <label for="unitTypeParkingCar">
                            Car parking
                            <input type="checkbox" ref="unitTypeParkingCar" id="unitTypeParkingCar" name="unitTypeParkingCar" v-model="newUnitType.parking_car">
                        </label>

                        <label for="unitTypeParkingBike">
                            Bike parking
                            <input type="checkbox" ref="unitTypeParkingBike" id="unitTypeParkingBike" name="unitTypeParkingBike" v-model="newUnitType.parking_bike">
                        </label>

                        <label for="unitTypeStoreroom">
                            Storeroom
                            <input type="checkbox" ref="unitTypeStoreroom" id="unitTypeStoreroom" name="unitTypeStoreroom" v-model="newUnitType.storeroom">
                        </label>
                    </div>

                    <div class="small-12 columns">
                        <button type="submit" class="success button" v-on:click="addUnitType" v-bind:disabled="loading">
                            <span v-if="loading">
                                <loading></loading>
                            </span>
                            <span v-else>
                                Add Unit Type
                            </span>
                        </button>
                    </div>
                    <!-- END Location input form -->
                </div>

                <!-- START List Section -->
                <div class="row">
                    <template v-for="(unitType, index) in unitTypes" >
                        <div class="small-12 columns">
                            <!-- Row Title -->
                            <button class="accordion__heading" v-on:click="accordionToggle(index, $event)">{{ unitType.name }}</button>
                            <!-- START Edit form -->
                            <div class="accordion__content">

                                <label for="editUnitTypeName">
                                    Unit type name*
                                    <input type="text" id="editUnitTypeName" ref="editUnitTypeName" name="editUnitTypeName" v-model="editUnitType.name">
                                </label>

                                <label for="editUnitTypeDescription">
                                    Description*
                                    <textarea ref="editUnitTypeDescription" id="editUnitTypeDescription" name="editUnitTypeDescription" v-model="editUnitType.description"></textarea>
                                </label>

                                <label for="editLocationId">
                                    Location*
                                    <select ref="editLocationId" id="editLocationId" name="editLocationId" v-model="editUnitType.location_id">
                                        <option value=""></option>
                                        <option v-for="location in locations" v-bind:value="location.id">
                                            {{ location.name }}
                                        </option>
                                    </select>
                                </label>

                                <label for="edutUnitTypeCost">
                                    Unit type Cost*
                                    <input type="number" id="edutUnitTypeCost" ref="edutUnitTypeCost" name="edutUnitTypeCost" v-model="editUnitType.cost">
                                </label>

                                <label for="editUnitTypeWifi">
                                    Wifi
                                    <input type="checkbox" id="editUnitTypeWifi" ref="editUnitTypeWifi" name="editUnitTypeWifi" v-model="editUnitType.wifi">
                                </label>

                                <label for="editUnitTypeElectricity">
                                    Electricity
                                    <input type="checkbox" id="editUnitTypeElectricity" ref="editUnitTypeElectricity" name="editUnitTypeElectricity" v-model="editUnitType.electricity">
                                </label>

                                <label for="unitTypeDstv">
                                    Dstv
                                    <input type="checkbox" id="unitTypeDstv" ref="unitTypeDstv" name="unitTypeDstv" v-model="editUnitType.dstv">
                                </label>

                                <label for="unitTypeParkingCar">
                                    Car parking
                                    <input type="checkbox" id="unitTypeParkingCar" ref="unitTypeParkingCar" name="unitTypeParkingCar" v-model="editUnitType.parking_car">
                                </label>

                                <label for="unitTypeParkingBike">
                                    Bike parking
                                    <input type="checkbox" id="unitTypeParkingBike" ref="unitTypeParkingBike" name="unitTypeParkingBike" v-model="editUnitType.parking_bike">
                                </label>

                                <label for="unitTypeStoreroom">
                                    Storeroom
                                    <input type="checkbox" id="unitTypeStoreroom" ref="unitTypeStoreroom" name="unitTypeStoreroom" v-model="editUnitType.storeroom">
                                </label>

                                <button type="submit" class="success button" v-on:click="updateUnitType" v-bind:disabled="loading">
                                    <span v-if="loading">
                                        <loading></loading>
                                    </span>
                                    <span v-else>
                                        Update unit type
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
        props: ['propLocations','propUnitTypes'],
        data(){
            return{
                locations: [],
                unitTypes: [],
                loading: false,
                editUnitType: {
                    id: '',
                    name: '',
                    description: '',
                    location_id: '',
                    cost: '',
                    wifi: '',
                    electricity: '',
                    dstv: '',
                    parking_car: '',
                    parking_bike: '',
                    storeroom: ''
                },
                newUnitType: {},
                addEntry: false,
            }
        },
        mounted() {
            this.locations = JSON.parse(this.propLocations);
            this.newUnitType = this.initializeUnitType();
            this.unitTypes = JSON.parse(this.propUnitTypes);
            console.log("The unit types are ", this.unitTypes);
        },
        methods: {
            addUnitType : function() {

                this.loading = true;

                this.$http.post(
                    '/unit-types',
                    JSON.stringify(this.newUnitType)
                ).then((response) => {

                    this.loading = false;
                    // If the response is successful, let's take the ID of the response
                    // We then add it to the locations object.
                    let newUnitTypeToAdd = response.data.data;
                    this.unitTypes.push(newUnitTypeToAdd);
                    // Reset the new location.
                    this.newUnitType = this.initializeUnitType();
                    this.addEntry = false;

                }, (err) => {
                    this.loading = false;
                    this.displayError(err);
                });

            },

            updateUnitType: function() {

                this.loading = true;

                this.$http.patch(
                    '/unit-types/' + this.editUnitType.id,
                    JSON.stringify(this.editUnitType)
                ).then((response) => {

                    // If the response is successful, lets set the name to the edited object
                    this.loading = false;
                    this.unitTypes[this.editUnitType.index] = this.editUnitType;
                    // To prevent reactivity from going accross, let's reassign the object.
                    this.createEditableObject(this.editUnitType.index);
                    this.closeAllAccordions();

                }, (err) => {
                    this.loading = false;
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
                    //this.editUnitType = this.locations[index];
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
                /*this.editUnitType = this.locations[index];
                delete this.editUnitType.__ob__;*/
                //console.log("This edit item is", this.editUnitType);
                this.editUnitType = {};
                this.editUnitType.index = index;
                this.editUnitType.id = this.unitTypes[index].id;
                this.editUnitType.name = this.unitTypes[index].name;
                this.editUnitType.description = this.unitTypes[index].description;
                this.editUnitType.location_id = this.unitTypes[index].location_id;
                this.editUnitType.cost = this.unitTypes[index].cost;
                this.editUnitType.wifi = this.unitTypes[index].wifi;
                this.editUnitType.electricity = this.unitTypes[index].electricity;
                this.editUnitType.dstv = this.unitTypes[index].dstv;
                this.editUnitType.parking_car = this.unitTypes[index].parking_car;
                this.editUnitType.parking_bike = this.unitTypes[index].parking_bike;
                this.editUnitType.storeroom = this.unitTypes[index].storeroom;
            },

            initializeUnitType() {
                return {
                        name: '',
                        description: '',
                        location_id: '',
                        cost: '',
                        wifi: '',
                        electricity: '',
                        dstv: '',
                        parking_car: '',
                        parking_bike: '',
                        storeroom: ''
                    };
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
            }

        }
    }

</script>
