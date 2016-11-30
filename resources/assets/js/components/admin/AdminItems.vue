<template>
    <div>
        <div class="row">

            <!-- Title -->
            <div class="small-12 medium-6 columns">
                <h1>Items</h1>
            </div>

            <!-- Show or hide addtion form button -->
            <div class="small-12 medium-6 columns align-middle -text-right">
                <button type="submit" name="showAddForm" class="success button -margin-bottom-0" v-on:click="addEntry =! addEntry" v-if="!addEntry">
                    <span>
                        Add item
                    </span>
                </button>
            </div>

            <div class="columns">
                <div class="row" v-show="addEntry">
                    <div class="small-12 columns">
                        <!-- START Item input form -->
                        <label for="itemName">
                            Item name*
                            <input type="text" id="itemName" name="itemName" v-model="newItem.name">
                        </label>

                        <label for="itemDescription">
                            Description*
                            <textarea ref="itemDescription" id="itemDescription" name="itemDescription" v-model="newItem.description"></textarea>
                        </label>

                        <label for="itemCost">
                            Cost
                            <input type="number" id="itemCost" ref="itemCost" name="itemCost" v-model="newItem.cost">
                        </label>
                    </div>

                    <div class="small-12 columns">
                        <button type="submit" name="addItem" class="success button" v-on:click="addItem" v-bind:disabled="loading">
                            <span v-if="loading">
                                <loading></loading>
                            </span>
                            <span v-else>
                                Add Item
                            </span>
                        </button>
                    </div>
                    <!-- END Item input form -->
                </div>

                <!-- START List Section -->
                <div class="row">
                    <template v-for="(item, index) in items" >
                        <div class="small-12 columns">
                            <!-- Row Title -->
                            <button class="accordion__heading" v-on:click="accordionToggle(index, $event)">{{ item.name }}</button>
                            <!-- START Edit form -->
                            <div class="accordion__content">

                                <label for="editItemName">
                                    Item name*
                                    <input type="text" id="editItemName" name="editItemName" v-model="editItem.name">
                                </label>

                                <label for="editItemDescription">
                                    Description*
                                    <textarea ref="editItemDescription" id="editItemDescription" name="editItemDescription" v-model="editItem.description"></textarea>
                                </label>

                                <label for="editItemCost">
                                    Cost
                                    <input type="number" id="editItemCost" ref="editItemCost" name="editItemCost" v-model="editItem.cost">
                                </label>

                                <button type="submit" class="success button" v-on:click="updateLocation" v-bind:disabled="loading">
                                    <span v-if="loading">
                                        <loading></loading>
                                    </span>
                                    <span v-else>
                                        Update Item
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
        props: ['propItems'],
        data(){
            return{
                items: [],
                loading: false,
                editItem: {
                    id: '',
                    name: '',
                    description: '',
                    value: '',
                },
                newItem: {},
                addEntry: false,
            }
        },
        mounted() {
            this.items = JSON.parse(this.propItems);
            this.newItem = this.initializeItem();
        },
        methods: {
            addItem : function() {

                this.loading = true;

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

            updateLocation: function() {

                this.loading = true;

                this.$http.patch(
                    '/items/' + this.editItem.id,
                    JSON.stringify(this.editItem)
                ).then((response) => {

                    // If the response is successful, lets set the name to the edited object
                    this.loading = false;
                    this.items[this.editItem.index] = this.editItem;
                    // To prevent reactivity from going accross, let's reassign the object.
                    this.createEditableObject(this.editItem.index);
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
                    //this.editItem = this.items[index];
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
                /*this.editItem = this.items[index];
                delete this.editItem.__ob__;*/
                //console.log("This edit item is", this.editItem);
                this.editItem = {};
                this.editItem.index = index;
                this.editItem.id = this.items[index].id;
                this.editItem.name = this.items[index].name;
                this.editItem.description = this.items[index].description;
                this.editItem.cost = this.items[index].cost;
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

            initializeItem() {
                return {
                        name: '',
                        description: '',
                        cost: '',
                    };
            }

        }
    }

</script>
