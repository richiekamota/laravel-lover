<template>
    <div class="application-approve">
        <div class="row columns">
            <h2 class="--focused">APPLICATION APPROVAL | lets get this applicant a contract! </h2>
            <p class="--slate">
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

                <hr class="--mt2 --mb2">

                <h4 class="--mb0">Contract Timings | start and end date of the contract</h4>
                <p class="--mt1">These dates has been taken from the users contract requests but you can update them if needed.</p>

                <div class="row">
                    <div class="column">
                        <label for="unit_occupation_date">
                            Unit Occupation Date
                            <Flatpickr :options='{ altInput: true, altFormat: "d F Y" }' name="unit_occupation_date" v-model="unit_occupation_date" @update='unit_occupation_date = $event' required />
                        </label>
                    </div>
                    <div class="column">
                        <label for="unit_vacation_date">
                            Unit Vacation Date
                            <Flatpickr :options='{ altInput: true, altFormat: "d F Y" }' name="unit_vacation_date" v-model="unit_vacation_date" @update='unit_vacation_date = $event' required />
                        </label>
                    </div>
                </div>

                <hr class="--mt2 --mb2">

                <h4 class="--mb0">Contract Unit | the unit the tenant will be living in</h4>
                <p class="--mt1">Select the unit from the list, these are the available units matching the applicants requested type and location.</p>

                <select class="styled-select" name="selectedUnit" v-model="selectedUnit" required>
                    <option v-for="unit in availableUnits" v-bind:value="unit.id">
                        {{ unit.code }}
                    </option>
                </select>

                <hr class="--mt2 --mb2">

                <!-- List of items, some are preselected, others can be added. -->

                <h4 class="--mb0">Contract Items | individual items that need to go on the contract</h4>
                <p class="--mt1">Select or deselect items by clicking on them.</p>

                <!-- Loop suggested items -->

                <div class="title-bar --mt1">
                    <h4>Available Items</h4>
                </div>
                <select multiple ref="items" id="items" name="items" class="available-items">
                    <option v-for="(item , index) in items" v-bind:value="item.id" v-on:click="addSelectedItem(item, index)">
                        {{ item.name }}
                    </option>
                </select>

                <div class="title-bar --mt0 --mb1">
                    <h4>Selected Items</h4>
                </div>
                <div v-if="selectedItems.length > 0">
                    <div v-for="(item , index) in selectedItems" class="selected-unit-types clearfix" v-on:click="removeSelectedItem(item, index)">
                        <span class="selected-unit-types__name">{{item.name}}</span> <span class="selected-unit-types__cost float-right">R{{item.cost.toFixed(2)}}</span>
                    </div>
                    <hr class="selected-unit-types__line">
                    <span class="selected-unit-types__name"><b>Total Cost:</b></span> <span class="float-right">R{{totalCost.toFixed(2)}}</span>
                    <hr class="selected-unit-types__line">
                </div>

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
                            <a>
                                <button id="double-check" class="button button--approve --expanded" v-on:click="checkApprovedItems()">
                                    <span v-if="loading">
                                        <loading></loading>
                                    </span>

                                    <template v-if="!loading">
                                        Finalise approval
                                    </template>
                                </button>
                            </a>
                        </div>

                        <div class="row column" v-if="doubleCheck">
                            <p>Please double check the items you have selected, these are added to the contract
                                and its automatically generated and emailed to the applicant.</p>

                            <a>
                                <button id="pending-application" class="button button--focused --expanded" v-on:click="confirmApproved()">
                                    <span v-if="loading">
                                        <loading></loading>
                                    </span>

                                    <template v-if="!loading">
                                        Confirm
                                    </template>
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

    import Flatpickr  from 'vue-flatpickr';

    var moment = require('moment');

    export default {
        components: {
            Flatpickr
        },
        props: ['propApplication', 'propLocation', 'propSuggestedItems', 'propItems', 'propAvailableUnits'],
        data(){
            return {
                application: {},
                location: {},
                selectedItems : [],
                items: [],
                availableUnits: [],
                unit_occupation_date: {},
                unit_vacation_date: {},
                selectedUnit: {},
                totalCost: 0,
                loading: false,
                doubleCheck: false
            }
        },

        mounted() {

            this.application = JSON.parse(this.propApplication);
            this.location = JSON.parse(this.propLocation);
            this.selectedItems = JSON.parse(this.propSuggestedItems);
            this.items = [];
            this.availableUnits = JSON.parse(this.propAvailableUnits);

            this.unit_occupation_date = this.application.unit_occupation_date;

            this.unit_vacation_date = moment(this.unit_occupation_date).add(this.application.unit_lease_length, 'months');

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

            checkApprovedItems: function(){

                this.doubleCheck = true;

            },

            confirmApproved: function () {

                this.loading = true;

                console.log(this.selectedUnit);

                this.$http.post(
                    '/application/' + this.application.id + '/approve',
                    {
                        items: this.selectedItems,
                        user_id: this.application.user_id,
                        unit_id: this.selectedUnit,
                        unit_occupation_date: this.unit_occupation_date,
                        unit_vacation_date: this.unit_vacation_date
                    }
                ).then((response) => {

                    this.loading = false;

                    // Redirect user to dashboard
                    swal({
                        title: "Success!",
                        text: "The application has been marked pending and the applicant has been emailed.",
                        type: "success",
                        confirmButtonText: "Ok"
                    },
                    function(){
                        location.href = '/dashboard';
                    });

                }, (err) => {
                    console.log("An error occured", err);
                    let errorMessage = '';
                    if (err.message) {
                        errorMessage = err.message;
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
                        confirmButtonText: "Ok",
                        allowOutsideClick: true
                    });

                    this.loading = false;
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