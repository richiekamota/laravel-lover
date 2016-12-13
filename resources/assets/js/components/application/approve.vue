<template>
    <div class="application-approve">
        <div class="row columns">
            <h2 class="--focused">APPLICATION APPROVAL | lets get this applicant a contract! </h2>
            <p>
                It's time to select the items that will make up the contract for this applicaiton.
                Using the options below you can select the items required and then generate a contract
                that will be emailed to the applicant.
            </p>
        </div>

        <div class="row" v-if="application">
            <div class="small-12 medium-8 columns">

                <!-- User basic details -->
                <div class="row column" v-if="application.user">

                    <div class="accordion__heading accordion__heading--add">
                        <h4 id="heading-user-details" class="--white">Contract Details</h4>
                    </div>

                    <div class="table__row table__row--padded last">
                        <div class="row column">
                            {{application.user.first_name}} {{application.user.last_name}}
                        </div>
                        <div class="row column">
                            Location: {{application.location.name}} - Type: {{application.unit_type.name}}
                        </div>
                    </div>

                </div>


                <!-- List of items, some are preselected, others can be added. -->

                <h3>Items that need to be added to the contract | click to add / remove</h3>

                <!-- Loop suggested items -->

                <h4 class="--mt2 --mb1">Available Items</h4>
                <select multiple ref="items" id="items" name="items">
                    <option v-for="(item , index) in items" v-bind:value="item.id" v-on:click="addSelectedItem(item, index)">
                        {{ item.name }}
                    </option>
                </select>

                <hr class="selected-unit-types__line --mt2" v-if="selectedItems.length > 0">
                <div v-for="(item , index) in selectedItems" class="selected-unit-types clearfix" v-on:click="removeSelectedItem(item, index)">
                    <span class="selected-unit-types__name">{{item.name}}</span> <span class="float-right">R{{item.cost.toFixed(2)}}</span>
                </div>
                <hr class="selected-unit-types__line" v-if="selectedItems.length > 0">
                <span class="selected-unit-types__name"><b>Total Cost:</b></span> <span class="float-right">R{{totalCost.toFixed(2)}}</span>
                <hr class="selected-unit-types__line" v-if="selectedItems.length > 0">

            </div>

            <div class="medium-4 columns">

                <div class="--border-wrap">
                    <div class="stats-box">
                        <div class="row column clearfix">
                            <h3 class="stats-box__header --focused --mt0 --mb0">Actions</h3>
                        </div>

                        <div class="row column">
                            <p>Finalise this approval and email the applicant their contract</p>
                        </div>

                        <div class="row column">
                            <a >
                                <button id="pending-application" class="button button--approve" v-on:click="confirmApproval()">
                                    Finalise approval
                                </button>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>
<script>

    var moment = require('moment');

    export default {
        props: ['propApplication', 'propLocation', 'propSuggestedItems', 'propItems'],
        data(){
            return {
                application: {},
                location: {},
                selectedItems : [],
                items: [],
                totalCost: 0
            }
        },

        mounted() {

            this.application = JSON.parse(this.propApplication);
            this.location = JSON.parse(this.propLocation);
            this.selectedItems = JSON.parse(this.propSuggestedItems);
            this.items = [];

            this.filterItems();

        },

        methods: {

            filterItems: function(){
                let selectedItemsId = [];
                let initialItems = JSON.parse(this.propItems);

                this.selectedItems.forEach((item) =>{
                    selectedItemsId.push(item.id);
                });

                initialItems.forEach((item) =>{
                    if(!selectedItemsId.includes(item.id)){
                        this.items.push(item);
                    }
                });

                this.updateTotalCost();
            },

            toNiceDate: (date) => {
                return moment(date).format("dddd, MMMM Do YYYY");
            },

            addSelectedItem : function(item, index){
                console.log(this.items);
                this.selectedItems.push(item);
                this.items.splice(index,1);
                this.updateTotalCost();
            },

            removeSelectedItem: function(item, index){
                this.selectedItems.splice(index,1);
                this.items.push(item);
                this.updateTotalCost();
            },

            updateTotalCost: function(){
                let total = 0;
                this.selectedItems.forEach((item) =>{
                    total += item.cost;
                });
                this.totalCost = total;
            },

            confirmApproved: function () {

                this.$http.post(
                    '/application/' + this.application.id + '/approved',
                    {
                        // TODO get array of items for the contract
                    }
                ).then((response) => {

                    this.loading = false;

                    // Redirect user to dashboard
                    swal({
                        title: "Success!",
                        text: "The application has been marked pending and the applicant has been emailed.",
                        type: "success",
                        confirmButtonText: "Ok"
                    });

                }, (err) => {

                    this.loading = false;
                    this.displayError(err);
                });

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


        }

    }

</script>