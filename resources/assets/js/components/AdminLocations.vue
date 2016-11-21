<template>
    <div>
        <div class="row">
            <div class="small-12 columns">
                <h1>Locations</h1>
            </div>
            <div class="columns">
                <div class="row">
                    <div class="columns">
                        <label for="location">
                            Location
                            <input type="text" ref="locationInput" name="location">
                        </label>
                    </div>

                    <div class="shrink columns align-self-bottom">
                        <button type="submit" class="success button" v-on:click="addLocation" v-bind:disabled="loading">
                            <span v-if="loading">
                                <loading></loading>
                            </span>
                                <span v-else>
                                Add location
                            </span>
                        </button>
                    </div>
                </div>

                <!-- START List Section -->
                <div class="row">
                    <template v-for="(location, index) in locations" >
                        <div class="small-12 columns">
                            <!-- Row Title -->
                            <button class="accordion__heading" v-on:click="accordionToggle(index, $event)">{{ location.title }}</button>
                            <!-- Edit form -->
                            <div class="accordion__content">

                                <div class="row">
                                    <div class="columns">
                                        <label for="edit-location">
                                            Location
                                            <input type="text" name="edit-location" v-model="editItem.title">
                                        </label>
                                    </div>

                                    <div class="shrink columns align-self-bottom">
                                        <button type="submit" class="success button" v-on:click="editLocation" v-bind:disabled="loading">
                                            <span v-if="loading">
                                                <loading></loading>
                                            </span>
                                                            <span v-else>
                                                Edit location
                                            </span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </template>
                </div>
                <!-- END List Section -->
            </div>
        </div>
    </div>
</template>
<script>

    export default{
        props: ['propLocations'],
        data(){
            return{
                locations: [],
                loading: false,
                editItem: {
                    id: '',
                    title: '',
                    arrayIndex: '',
                },
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
            this.locations = locationData;
        },
        methods: {
            addLocation : function() {
                this.loading = true;

                this.$http.post(
                    '/add-location',
                    JSON.stringify(this.$refs.locationInput.value)
                ).then((response) => {
                    this.loading = false;
                }, (err) => {
                    // If the response is successful, let's take the ID of the response
                    // We then add it to the locations object.
                    let newLocation = {
                        id:     Math.floor((Math.random() * 100) + 1),
                        title:  this.$refs.locationInput.value
                    };
                    this.locations.push(newLocation);
                    this.$refs.locationInput.value = '';
                    this.loading = false;

                    console.log("The locations are ", this.locations);


                    // THere is an error, let's display an alert.
                    swal({
                      title: "Error!",
                      text: 'Some error',
                      type: "error",
                      confirmButtonText: "Ok"
                    });
                });

            },

            editLocation: function() {
                this.loading = true;

                this.$http.post(
                    '/edit-location/' + this.editItem.id,
                    JSON.stringify(this.editItem)
                ).then((response) => {
                    this.loading = false;
                }, (err) => {
                    // If the response is successful, lets set the name to the edited object
                    this.loading = false;
                    this.locations[this.editItem.index] = this.editItem;
                    // To prevent reactivity from going accross, let's reassign the object.
                    this.createEditableObject(this.editItem.index);

                    // THere is an error, let's display an alert.
                    swal({
                      title: "Error!",
                      text: 'Some error',
                      type: "error",
                      confirmButtonText: "Ok"
                    });
                });

            },

            accordionToggle: function(index, event) {
                event.preventDefault();
                let selectedElement = event.target;
                // Let's close all accordions fist
                if(this.$el.querySelector(".accordion__heading--active")) {
                    this.$el.querySelectorAll(".accordion__heading--active").forEach(function(element) {
                        element.classList.toggle("accordion__heading--active");
                    });

                    this.$el.querySelectorAll(".accordion__content--active").forEach(function(element) {
                        element.classList.toggle("accordion__content--active");
                    });
                }
                selectedElement.classList.toggle("accordion__heading--active");
                selectedElement.nextElementSibling.classList.toggle("accordion__content--active");

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
                this.editItem.title = this.locations[index].title;
            }

        }
    }

</script>
