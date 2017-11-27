<template>
    <div class="page-items">
        <div class="row columns">
            <h2 class="--focused">ITEMS | things people pay for</h2>
            <p>
                Items are things people pay for as part of their contract. This can include parking bays, DSTV, beds etc. They are also added
                to a contract for reference.
            </p>
        </div>

        <div class="row">

            <div class="small-12 medium-9 columns">

                <!-- START List Section -->
                <div class="row table">
                    <div class="small-12 columns">
                        <div class="table__row table__row--add">
                            <!-- Row Title -->
                            <button class="accordion__heading accordion__heading--add --white" v-on:click="addEntry = !addEntry">
                                <h4 class="--white">Add new item</h4>
                            </button>
                            <!-- START Edit form -->
                            <div class="accordion__content --bg-calm" v-bind:class="{ 'accordion__content--active' : addEntry }" v-show="addEntry">

                                <div class="row column">
                                    <!-- START Location input form -->
                                    <label for="itemName">
                                        Name
                                        <input type="text" id="itemName" ref="itemInput" name="itemName" v-model="newItem.name">
                                    </label>

                                    <label for="itemDescription">
                                        Description
                                        <textarea ref="itemDescription" id="itemDescription" name="itemDescription" v-model="newItem.description"></textarea>
                                    </label>

                                    <label for="itemCost">
                                        Cost
                                        <input type="number" id="itemCost" ref="itemCost" name="editItemCost" v-model="newItem.cost">
                                    </label>

                                    <label for="itemPaymentType">
                                        Item Payment Type

                                        <select ref="itemPaymentType" id="itemPaymentType" name="itemPaymentType" v-model="newItem.payment_type">
                                            <option value="Rental">Monthly Rental Cost</option>
                                            <option value="Deposit">Deposit</option>
                                            <option value="Once-off">Once-off Contract Item</option>
                                            <option value="Monthly">Monthly Contract Item</option>
                                            <option value="Free">Free (Part of Contract)</option>
                                        </select>
                                    </label>

                                    <label for="itemForLease">
                                        Is this item stock limited?

                                        <select ref="itemForLease" id="itemForLease" name="itemForLease" v-model="newItem.for_lease">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </label>

                                    <label for="qty" v-if="newItem.for_lease == 1">
                                        Total Quantity

                                        <input type="number" id="qty" ref="qty" name="qty" v-model="newItem.qty">
                                    </label>

                                    <label for="itemUnitTypes">
                                        Unit Types* - (add and remove the required unit types)
                                    </label>

                                    <hr class="selected-unit-types__line" v-if="selectedUnitTypes.length > 0">
                                    <div v-for="(unit_type , index) in selectedUnitTypes" class="selected-unit-types clearfix">
                                        <span class="selected-unit-types__name">{{unit_type.name}}</span>
                                        <span class="selected-unit-types__remove float-right" v-on:click="removeSelectedUnitType(unit_type, index)">remove</span>
                                    </div>
                                    <hr class="selected-unit-types__line" v-if="selectedUnitTypes.length > 0">

                                    <select multiple ref="itemUnitTypes" id="itemUnitTypes" name="itemUnitTypes">
                                        <option v-for="(unit_type , index) in unitTypes" v-bind:value="unit_type.id" v-on:click="addSelectedUnitType(unit_type, index)">
                                            {{ unit_type.name }}
                                        </option>
                                    </select>

                                </div>
                                <!-- END Edit form -->

                                <div class="row column">
                                    <button type="submit" name="addItem" class="button focused --mt1" v-on:click="addItem" v-bind:disabled="loading">
                                        <span v-if="loading">
                                            <loading></loading>
                                        </span>
                                        <span v-else>Add item</span>
                                    </button>
                                    <button type="submit" name="cancelItem" class="button float-right --mt1" v-on:click="cancelAddItem" v-bind:disabled="loading">
                                        <span v-if="loading">
                                            <loading></loading>
                                        </span>
                                        <span v-else>Cancel</span>
                                    </button>
                                </div>

                            </div>

                        </div>
                    </div>

                    <template v-for="(item, index) in items">
                        <div class="small-12 columns">
                            <div class="table__row" :class="{ even: isEven(index), first: index == 0, last: index == items.length -1 }">

                                <!-- Row Title -->
                                <div class="row column">
                                <button class="accordion__heading" v-on:click="accordionToggle(index, $event)">{{ item.name }} : R{{item.cost}} ({{item.payment_type}})

                                <button class="button button--small float-right item_delete" v-on:click="deleteItem(item,index)" >Delete</button>
                                </button>
                                </div>
                                <!-- START Edit form -->
                                <div class="accordion__content --bg-calm">

                                    <label for="editItemName">
                                        Name
                                        <input type="text" id="editItemName" ref="editItemInput" name="editItemName" v-model="editItem.name">
                                    </label>

                                    <label for="editItemDescription">
                                        Description
                                        <textarea ref="editItemDescription" id="editItemDescription" name="editItemDescription" v-model="editItem.description"></textarea>
                                    </label>

                                    <label for="editItemCost">
                                        Cost
                                        <input type="number" id="editItemCost" ref="editItemCost" name="editItemCost" v-model="editItem.cost">
                                    </label>


                                    <label for="itemPaymentType">
                                        Item Payment Type

                                        <select ref="itemPaymentType" id="itemPaymentType" name="itemPaymentType" v-model="editItem.payment_type">
                                            <option value="Rental">Monthly Rental Cost</option>
                                            <option value="Deposit">Deposit</option>
                                            <option value="Once-off">Once-off Contract Item</option>
                                            <option value="Monthly">Monthly Contract Item</option>
                                            <option value="Free">Free (Part of Contract)</option>
                                        </select>
                                    </label>

                                    <label for="itemForLease">
                                        Is this item stock limited?

                                        <select ref="itemForLease" id="itemForLease" name="itemForLease" v-model="editItem.for_lease">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </label>

                                    <label for="qty" v-if="editItem.for_lease == 1">
                                        Total Quantity

                                        <input type="number" id="qty" ref="qty" name="qty" v-model="editItem.qty">
                                    </label>


                                    <label for="editItemUnitTypes">
                                        Unit Types* - (add and remove the required unit types)
                                    </label>

                                    <hr class="selected-unit-types__line">
                                    <div v-for="(unit_type , index) in editItem.unit_types" class="selected-unit-types clearfix">
                                        <span class="selected-unit-types__name">{{unit_type.name}}</span>
                                        <span class="selected-unit-types__remove float-right" v-on:click="removeEditSelectedUnitType(unit_type, index)">remove</span>
                                    </div>
                                    <hr class="selected-unit-types__line">
                                    <select multiple ref="editItemUnitTypes" id="editItemUnitTypes" name="editItemUnitTypes">
                                        <option v-for="(unit_type , index) in editingUnitTypes" v-bind:value="unit_type.id" v-on:click="addEditSelectedUnitType(unit_type, index)">
                                            {{ unit_type.name }}
                                        </option>
                                    </select>

                                    <button type="submit" class="success button" v-on:click="updateItem" v-bind:disabled="loading">
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
                        Items:
                        <span class="float-right">{{items.length}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

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
                    qty: 0,
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
                this.editItem.for_lease = this.items[index].for_lease;
                this.editItem.qty = this.items[index].qty;
                this.editItem.payment_type = this.items[index].payment_type;
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
                    unit_types: [],
                    for_lease: 0,
                    qty: 0,
                    payment_type: ''
                };
            },

            deleteItem: function (item,index,err) {
                swal({
                    title: "Are you sure?",
                    text: "Your will not be able to recover this item!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false,
                    animation: "slide-from-top"
                },  () => {
                    this.$http.delete(
                    '/items/' + this.items[index].id + '/delete')
                    .then((response) => {
                    this.items.splice(index, 1);

                        if (response.status == 200) {
                            swal("Item Successfuly Deleted!","The item has been deleted", "success");
                        }
                    }, function (err) {
                        if (err.status == 401){
                            swal("Not Authorised!","You are not authorised to make any changes", "error");
                        } else {
                            swal("Oh no!", "Delete Error!", "error");
                            this.displayError(err);
                        }
                    });
                });
            }
        }
    }
</script>
