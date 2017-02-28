<template>
    <div class="page-items">
        <div class="row columns">
            <h2 class="--focused">ITEMS | things people pay for</h2>
            <p>
                Items are things people pay for as part of their contract. This can include parking bays, DSTV, beds
                etc.
                They are also added to a contract for reference.
            </p>
        </div>

        <div class="row">

            <div class="small-12 medium-9 columns">

                <!-- START List Section -->
                <div class="row table">
                    <div class="small-12 columns">
                        <div class="table__row table__row--add">
                            <!-- Row Title -->
                            <button class="accordion__heading accordion__heading--add --white"
                                    v-on:click="addEntry = !addEntry">
                                <h4 class="--white">Add new item</h4>
                            </button>
                            <!-- START Edit form -->
                            <div class="accordion__content --bg-calm"
                                 v-bind:class="{ 'accordion__content--active' : addEntry }" v-show="addEntry">

                                <div class="row column">
                                    <!-- START Location input form -->
                                    <label for="itemName">
                                        Name
                                        <input type="text" id="itemName" ref="itemInput"
                                               name="itemName" v-model="newItem.name">
                                    </label>

                                    <label for="itemDescription">
                                        Description
                                        <textarea ref="itemDescription" id="itemDescription"
                                                  name="itemDescription" v-model="newItem.description"></textarea>
                                    </label>

                                    <label for="itemCost">
                                        Cost
                                        <input type="number" id="itemCost" ref="itemCost"
                                               name="editItemCost" v-model="newItem.cost">
                                    </label>

                                    <label for="itemMonthlyPayment">
                                        <input type="number" id="itemMonthlyPayment" ref="itemMonthlyPayment"
                                               name="editItemMonthlyPayment" v-model="newItem.monthly_payment">
                                        Monthly Payment

                                    </label>

                                    <label for="itemUnitTypes">
                                        Unit Types* - (add and remove the required unit types)
                                    </label>

                                    <hr class="selected-unit-types__line" v-if="selectedUnitTypes.length > 0">
                                    <div v-for="(unit_type , index) in selectedUnitTypes"
                                         class="selected-unit-types clearfix">
                                        <span class="selected-unit-types__name">{{unit_type.name}}</span> <span
                                            class="selected-unit-types__remove float-right"
                                            v-on:click="removeSelectedUnitType(unit_type, index)">remove</span>
                                    </div>
                                    <hr class="selected-unit-types__line" v-if="selectedUnitTypes.length > 0">

                                    <select multiple ref="itemUnitTypes" id="itemUnitTypes" name="itemUnitTypes">
                                        <option v-for="(unit_type , index) in unitTypes" v-bind:value="unit_type.id"
                                                v-on:click="addSelectedUnitType(unit_type, index)">
                                            {{ unit_type.name }}
                                        </option>
                                    </select>

                                </div>
                                <!-- END Edit form -->

                                <div class="row column">
                                    <button type="submit" name="addItem" class="button focused --mt1"
                                            v-on:click="addItem" v-bind:disabled="loading">
                                        <span v-if="loading"><loading></loading></span>
                                        <span v-else>Add item</span>
                                    </button>
                                    <button type="submit" name="cancelItem" class="button float-right --mt1"
                                            v-on:click="cancelAddItem" v-bind:disabled="loading">
                                        <span v-if="loading"><loading></loading></span>
                                        <span v-else>Cancel</span>
                                    </button>
                                </div>

                            </div>

                        </div>
                    </div>

                    <template v-for="(item, index) in items">
                        <div class="small-12 columns">
                            <div class="table__row"
                                 :class="{ even: isEven(index), first: index == 0, last: index == items.length -1 }">

                                <!-- Row Title -->
                                <button class="accordion__heading" v-on:click="accordionToggle(index, $event)">{{
                                    item.name }} - {{item.address}}
                                </button>
                                <!-- START Edit form -->
                                <div class="accordion__content --bg-calm">
                                    <div class="row column">
                                        <input type="checkbox" id="editMonthlyPayment" ref="editMonthlyPayment"
                                               name="editItemMonthlyPayment" v-model="editItem.monthly_payment" />
                                        <label for="editMonthlyPayment"> This is a recurring / monthly payment item<br/></label>

                                    </div>
                                    <label for="editItemName">
                                        Name
                                        <input type="text" id="editItemName" ref="editItemInput"
                                               name="editItemName" v-model="editItem.name">
                                    </label>

                                    <label for="editItemDescription">
                                        Description
                                        <textarea ref="editItemDescription" id="editItemDescription"
                                                  name="editItemDescription" v-model="editItem.description"></textarea>
                                    </label>

                                    <label for="editItemCost">
                                        Cost
                                        <input type="number" id="editItemCost" ref="editItemCost"
                                               name="editItemCost" v-model="editItem.cost">
                                    </label>


                                    <label for="editItemUnitTypes">
                                        Unit Types* - (add and remove the required unit types)
                                    </label>

                                    <hr class="selected-unit-types__line">
                                    <div v-for="(unit_type , index) in editItem.unit_types"
                                         class="selected-unit-types clearfix">
                                        <span class="selected-unit-types__name">{{unit_type.name}}</span> <span
                                            class="selected-unit-types__remove float-right"
                                            v-on:click="removeEditSelectedUnitType(unit_type, index)">remove</span>
                                    </div>
                                    <hr class="selected-unit-types__line">
                                    <select multiple ref="editItemUnitTypes" id="editItemUnitTypes"
                                            name="editItemUnitTypes">
                                        <option v-for="(unit_type , index) in editingUnitTypes"
                                                v-bind:value="unit_type.id"
                                                v-on:click="addEditSelectedUnitType(unit_type, index)">
                                            {{ unit_type.name }}
                                        </option>
                                    </select>

                                    <button type="submit" class="success button" v-on:click="updateItem"
                                            v-bind:disabled="loading">
                                        <span v-if="loading">
                                            <loading></loading>
                                        </span>
                                        <span v-else>
                                            Update item
                                        </span>
                                    </button>
                                </div>
                                <!-- END Edit form -->

                            </div>
                        </div>
                    </template>
                </div>
                <!-- END List Section -->
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

    export default {
        props: ['propItems', 'propUnitTypes'],
        data(){
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
                    monthly_payment: '',
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
        },
        methods: {

            isEven: function (n) {
                return n % 2 == 0;
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
                this.editItem.monthly_payment = this.items[index].monthly_payment;
                this.editItem.unit_types = this.items[index].unit_types;

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
                    unit_types: []
                };
            }

        }
    }

</script>
