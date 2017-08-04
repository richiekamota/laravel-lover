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
                                    <div class="row" v-if="item.item_lease_dates.length > 0">
                                        <div class="small-4 medium-6 columns"><strong>Leased By</strong></div>
                                        <div class="small-3 medium-2 columns"><strong>Start Date</strong></div>
                                        <div class="small-3 medium-2 columns"><strong>End Date</strong></div>
                                        <div class="small-2 medium-2 columns"></div>
                                    </div>
                                    <div class="row column" v-else>
                                        <p>No lease dates found for this item.</p>
                                    </div>
                                    <div class="row" v-for="lease in item.item_lease_dates">
                                        <div class="small-4 medium-6 columns">{{lease.leasee_name}}</div>
                                        <div class="small-3 medium-2 columns">{{toNiceDate(lease.start_date)}}</div>
                                        <div class="small-3 medium-2 columns">{{toNiceDate(lease.end_date)}}</div>
                                        <div class="small-2 medium-2 columns"><a href="#">Remove</a></div>
                                    </div>
                                </div>
                    
                                <!-- END Edit form -->

                            </div>
                        </div>

                    </div>
                    <!-- END List Section -->
                </div>
            </div>
            <div class="medium-3 columns">
                <div class="--border-wrap">
                    <div class="stats-box">
                        <div class="row column clearfix">
                            <h3 class="stats-box__header --focused --mt0">Stats</h3>
                        </div>
                        Items: <span class="float-right">{{items.length}}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
    var moment = require('moment');
    export default {
        props: ['propItems', 'propUnitTypes'],
        data() {
            return {
                items: [],
                unitTypes: [],
                selectedUnitTypes: [],
                editSelectedUnitTypes: [],
                editingUnitTypes: [],
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
            this.items = JSON.parse(this.propItems);
            this.unitTypes = JSON.parse(this.propUnitTypes);
            this.newItem = this.initializeItem();

            console.log(this.items);
        },
        methods: {

            isEven: function (n) {
                return n % 2 == 0;
            },

            toNiceDate: (date) => {
                return moment(date).format("DD / MM / YY");
            },

            addItem: function () {

                this.loading = true;

                this.selectedUnitTypes.forEach((item) => {
                    this.newItem.unit_types.push(item.id);
                });

                this.$http.post(
                    '/items',
                    JSON.stringify(this.newItem)
                ).then((response) => {
                    this.loading = false;
                    // If the response is successful, let's take the ID of the response
                    // We then add it to the items object.
                    let newItemToAdd = response.data.data;
                    this.items.push(newItemToAdd);
                    // Reset the new location.
                    this.newItem = this.initializeItem();
                    this.addEntry = false;

                }, (err) => {

                    this.loading = false;

                    this.displayError(err);
                });

            },

            addSelectedUnitType: function (unit_type, index) {
                this.selectedUnitTypes.push(unit_type);
                this.unitTypes.splice(index, 1);
            },

            removeSelectedUnitType: function (unit_type, index) {
                this.selectedUnitTypes.splice(index, 1);
                this.unitTypes.push(unit_type);

            },

            addEditSelectedUnitType: function (unit_type, index) {
                this.editItem.unit_types.push(unit_type);
                this.editingUnitTypes.splice(index, 1);
            },

            removeEditSelectedUnitType: function (unit_type, index) {
                this.editItem.unit_types.splice(index, 1);
                this.editingUnitTypes.push(unit_type);

            },

            cancelAddItem: function () {
                this.newItem = this.initializeItem();
                this.addEntry = false;
            },

            updateItem: function () {

                this.loading = true;

                // save the editing items unit types out
                let editItemUnitTypes = this.editItem.unit_types;
                // reset to empty array
                this.editItem.unit_types = [];

                editItemUnitTypes.forEach((item) => {
                    this.editItem.unit_types.push(item.id);
                });

                this.$http.patch(
                    '/items/' + this.editItem.id,
                    JSON.stringify(this.editItem)
                ).then((response) => {

                    // If the response is successful, lets set the name to the edited object
                    this.loading = false;
                    this.items[this.editItem.index] = this.editItem;
                    this.items[this.editItem.index].unit_types = editItemUnitTypes;
                    // To prevent reactivity from going accross, let's reassign the object.
                    this.createEditableObject(this.editItem.index);
                    this.closeAllAccordions();

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
                this.closeAllAccordions();

                // If the button was open, we don't want to reopen it.
                if (shouldClose) {
                    selectedElement.classList.toggle("accordion__heading--active");
                    selectedElement.nextElementSibling.classList.toggle("accordion__content--active");
                }

                // Let's load up the editable object with the selected item
                // Let's wait for the previous accordion animation to finish then do this.
                setTimeout(() => {
                    // If we want to use vue with it's reactivity use the below
                    //this.editItem = this.items[index];
                    this.createEditableObject(index);
                }, 200)
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

            createEditableObject(index) {

                console.log(this.items[index]);

                // If we want to assign a completely new object which will not update the other form due to
                // reactivity, we must manually assign whatever is needed.
                // We also need the array index so when we update successfully we know which index to update.

                // Get an array featuring all the unit type id's
                let unitTypeIDArray = this.items[index].unit_types.map((unitType) => {
                    return unitType.id;
                });

                // If the selected items unit type id is in the array of unit type
                this.editingUnitTypes = this.unitTypes.filter((unitType) => {
                    return unitTypeIDArray.indexOf(unitType.id) < 0;
                });

                // TODO is this not better if we take the entire object and delete the obserable instead?
                /*this.editItem = this.items[index];
                 delete this.editItem.__ob__;*/
                //console.log("This edit item is", this.editItem);
                this.editItem = {};
                this.editItem.index = index;
                this.editItem.id = this.items[index].id;
                this.editItem.name = this.items[index].name;
                this.editItem.description = this.items[index].description;
                this.editItem.cost = this.items[index].cost;
                this.editItem.for_lease = this.items[index].for_lease;
                this.editItem.item_lease_dates = this.items[index].item_lease_dates;
                this.editItem.payment_type = this.items[index].payment_type;
                this.editItem.unit_types = this.items[index].unit_types;

                console.log(this.editItem);

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

            initializeItem() {
                return {
                    name: '',
                    description: '',
                    cost: '',
                    unit_types: [],
                    for_lease: 0,
                    payment_type: ''
                };
            }

        }
    }

</script>
