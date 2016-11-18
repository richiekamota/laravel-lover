<template>
    <div>
        <div class="row">
            <div class="columns">

                <!-- Location Section -->
                <template v-if="showType == 'location'">
                    Location 2
                    <input type="text" ref="locationInput">

                    <div class="row column">
                        <button type="submit" class="success button" v-on:click="addLocation" v-bind:disabled="loading">
                            <span v-if="loading">
                                <loading></loading>
                            </span>
                                <span v-else>
                                Add location
                            </span>
                        </button>
                    </div>

                    <!-- START List Section -->
                    <div class="row">
                        <template v-for="(location, index) in locations" >
                            <!-- Row Title -->
                            <div class="small-12 columns" v-on:click="editLocationForm(index)">
                                {{ location.title }}
                            </div>
                            <!-- Edit form -->
                            <div class="small-12 columns">
                                <ApplicationFormEdit :editable-object="location" @update='location.title = $event' />
                            </div>
                        </template>
                    </div>
                    <!-- END List Section -->

                </template>

                <!-- Unit Section -->
                <template v-if="showType == 'unit'">
                    Units
                </template>

                <!-- Unit Type section -->
                <template v-if="showType == 'unitType'">
                    Unit Type
                </template>
            </div>
        </div>
    </div>
</template>
<script>

    import ApplicationFormEdit from './ApplicationFormEdit.vue';

    export default{
        props: ['propType','propLocations', 'propUnits', 'propUnitTypes'],
        components:{
            ApplicationFormEdit
        },
        data(){
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

            return{
                showType: '',
                locations: [],
                units: [],
                unitTypes:[],
                loading: false
            }
        },
        mounted() {
            console.log("Mounted");
            this.showType = 'location';
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

                    // THere is an error, let's display an alert.
                    swal({
                      title: "Error!",
                      text: 'Some error',
                      type: "error",
                      confirmButtonText: "Ok"
                    });
                });

            },

            editLocationForm: function(index) {
                console.log("Clicked item is", this.locations[index]);
            }

        }
    }

</script>
