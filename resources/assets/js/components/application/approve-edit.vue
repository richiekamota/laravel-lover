<template>
    <div class="application-approve-edit">
        <div class="row columns">
            <h2 class="--focused">CONTRACT EDIT APPROVAL | let's get this applicant an updated contract! </h2>
            <p class="--slate">
                It's time to select the items that will make up the new contract for this application. Using the options below you can select
                the items required and then generate a new edited contract that will be emailed to the applicant.
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
                            Leaseholder Name: {{application.first_name}} {{application.last_name}}
                        </div>
                        <div class="row column">
                            Leaseholder Email: {{application.email}}
                        </div>
                        <div class="row column">
                            Leaseholder Mobile: {{application.phone_mobile}}
                        </div>
                        <div class="row column">
                            Resident Name: {{application.resident_first_name}} {{application.resident_last_name}}
                        </div>
                        <div class="row column">
                            Resident Email: {{application.resident_email}}
                        </div>
                        <div class="row column">
                            Resident Mobile: {{application.resident_phone_mobile}}
                        </div>
                        <div class="row column">
                            Location: {{application.location.name}} - Type: {{application.unit_type.name}}
                        </div>
                    </div>

                </div>

                <hr class="--mt2 --mb2">

                <h4 class="--mb0">Contract Details | updating contract details</h4>
                <p class="--mt1">Contract request changes to any of the above details can be made in the input boxes below.
                </p>

                <form role="form" method="POST" v-on:submit.prevent ref="editForm">
                    <div class="row">
                        <div class="column">
                            <label for="leaseholder_email">
                               Leaseholder Email
                               <input type="text" name="leaseholder_email" v-model="editForm.leaseholder_email">
                            </label>
                        </div>
                        <div class="column">
                            <label for="leaseholder_mobile">
                               Leaseholder Mobile
                               <input type="text" name="leaseholder_mobile" v-model="editForm.leaseholder_mobile">
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="resident_email">
                               Resident Email
                               <input type="text" name="resident_email" v-model="editForm.resident_email">
                            </label>
                        </div>
                        <div class="column">
                            <label for="resident_mobile">
                               Resident Mobile
                               <input type="text" name="resident_mobile" v-model="editForm.resident_mobile">
                            </label>
                        </div>
                    </div>
                </form>

                <hr class="--mt2 --mb2">

                <h4 class="--mb0">Contract Timings | start and end date of the contract</h4>
                <p class="--mt1">These dates have been taken from the users contract requests but you can update them if needed.
                </p>

                <div class="row">
                    <div class="column">
                        <label for="unit_occupation_date">
                            Unit Occupation Date
                            <Flatpickr :options='{ altInput: true, altFormat: "d F Y" }' name="unit_occupation_date" v-model="unit_occupation_date" @change="filterUnits()"
                                @update='unit_occupation_date = $event' required/>
                        </label>
                    </div>
                    <div class="column">
                        <label for="unit_vacation_date">
                            Unit Vacation Date
                            <Flatpickr :options='{ altInput: true, altFormat: "d F Y" }' name="unit_vacation_date" v-model="unit_vacation_date" @change="filterUnits()"
                                @update='unit_vacation_date = $event' required/>
                        </label>
                    </div>
                </div>

                <hr class="--mt2 --mb2">

                <h4 class="--mb0">Contract Unit | the unit the tenant will be living in</h4>
                <p class="--mt1" >
                    Select the unit from the list.
                    The users CURRENT unit has been pre-selected for you.
                    The user's requested type is: {{application.unit.code}} - {{application.unit_type.name}}
                </p>


                <select class="styled-select" name="selectedUnit" v-model="selectedUnit" required v-if="filteredUnits.length != 0">

                    <option v-for="unit in filteredUnits" v-bind:value="unit.id">
                        {{ unit.code }} - {{unit.unit_type.name}}
                    </option>
                </select>
                <p v-else>
                    No units available for selected occupation date. Please select a different occupation date range.
                </p>

                <hr class="--mt2 --mb2">

                <!-- List of items, some are preselected, others can be added. -->

                <h4 class="--mb0">Contract Items | individual items that need to go on the contract</h4>

                <p class="--mt1">Previously selected items:</p>

                <div v-for="contractItem in application.contractItems" class="row column">
                            <span>{{contractItem["name"]}}</span>
                </div>
                <!-- Loop suggested items -->
                <p class="--mt1">Select or deselect items by clicking on them.</p>
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
                        <span class="selected-unit-types__name">{{item.name}}</span>
                        <span class="selected-unit-types__cost float-right">R{{item.cost.toFixed(2)}}</span>
                    </div>
                    <hr class="selected-unit-types__line">
                    <span class="selected-unit-types__name">
                        <b>Total Cost:</b>
                    </span>
                    <span class="float-right">R{{totalCost.toFixed(2)}}</span>
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
                                        Finalise Edit Approval
                                    </template>
                                </button>
                            </a>
                        </div>

                        <div class="row column" v-if="doubleCheck">
                            <p>Please double check the items you have selected, these are added to the contract and its automatically
                                generated and emailed to the applicant.</p>
                            <a>
                                <button id="pending-application" class="button button--focused --expanded" v-on:click="confirmApproved()">
                                    <span v-if="loading">
                                        <loading></loading>
                                    </span>
                                    <template v-if="!loading">Confirm</template>
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

    import VueFlatpickr from 'vue-flatpickr';

    Vue.use(VueFlatpickr);

    var moment = require('moment');

    export default {
        props: ['propApplication', 'propLocation', 'propSuggestedItems', 'propItems', 'propAvailableUnits'],
        data() {

            return {
                application: {},
                location: {},
                selectedItems: [],
                items: [],
                editForm: {
                    leaseholder_email: '',
                    leaseholder_mobile: '',
                    resident_email: '',
                    resident_mobile: '',
                },
                availableUnits: [],
                filteredUnits: [],
                unit_occupation_date: '',
                unit_vacation_date: '',
                unit_fee_split: '',
                selectedUnit: '',
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
            this.filteredUnits = JSON.parse(this.propAvailableUnits);

            this.unit_occupation_date = this.application.unit_occupation_date;

            this.unit_vacation_date = moment(this.unit_occupation_date).add(this.application.unit_lease_length, 'months');

            this.filterItems();
            this.filterUnits();

            this.selectedUnit = this.application.contract.unit_id;
        },

        watch: {
            // whenever question changes, this function will run
            unit_occupation_date: function () {
                this.filterUnits();
            },
            unit_vacation_date: function () {
                this.filterUnits();
            }

        },

        methods: {
            filterItems: function () {
                let selectedItemsId = [];
                let initialItems = JSON.parse(this.propItems);

                this.selectedItems.forEach((item) => {
                    selectedItemsId.push(item.id);
                });

                initialItems.forEach((item) => {
                    if (!selectedItemsId.includes(item.id)) {
                        this.items.push(item);
                    }
                });

                this.updateTotalCost();
            },

            filterUnits: function () {
                this.filteredUnits = [];

                for (var i = 0; i < this.availableUnits.length; i++) {

                    var unit = this.availableUnits[i];
                    var isValid = false;

                    if(this.application.contract.unit_id == unit.id){
                        isValid = true;
                    }else{
                        isValid = false;
                    }
                    if (unit.occupation_dates) {

                        if (unit.occupation_dates.length > 0) {

                            var ll = 0;

                            var inputStartDate = new Date(this.unit_occupation_date);
                            var inputEndDate = new Date(this.unit_vacation_date);

                            while (unit.occupation_dates.length > ll) {
                                // alert(inputStartDate + " > " + inputEndDate);
                                var unitStartDate = new Date(unit.occupation_dates[ll].start_date);
                                var unitEndDate = new Date(unit.occupation_dates[ll].end_date);
                                // alert(unitStartDate + " > " + unitEndDate);
                                if (inputStartDate != '' && inputEndDate != '') {

                                    if ((unitStartDate >= inputStartDate && unitStartDate <= inputEndDate) || (unitEndDate <= inputEndDate && unitEndDate >= inputStartDate)) {
                                        if (this.application.unit_fee_split) {
                                            isValid = true;
                                        }
                                    }

                                    ll++;
                                }
                            }
                        } else {
                            isValid = true;
                        }

                        if (isValid == true) {
                            this.filteredUnits.push(unit);
                        }
                    };


                }
            },

            toNiceDate: (date) => {
                return moment(date).format("dddd, MMMM Do YYYY");
            },

            addSelectedItem: function (item, index) {

                this.selectedItems.push(item);
                this.items.splice(index, 1);
                this.updateTotalCost();
            },

            removeSelectedItem: function (item, index) {
                this.selectedItems.splice(index, 1);
                this.items.push(item);
                this.updateTotalCost();
            },

            updateTotalCost: function () {
                let total = 0;
                this.selectedItems.forEach((item) => {
                    total += item.cost;
                });
                this.totalCost = total;
            },

            checkApprovedItems: function () {

                this.doubleCheck = true;

            },

            confirmApproved: function () {

                this.loading = true;

                if (this.selectedUnit == '') {
                    swal({
                        title: "Error!",
                        text: "Please select a unit from the list.",
                        type: "error",
                        confirmButtonText: "Ok",
                        allowOutsideClick: true
                    });

                    this.loading = false;
                    return false;
                }

                this.$http.post(
                    '/contracts/' + this.application.id + '/edit',
                    {
                        items: this.selectedItems,
                        user_id: this.application.user_id,
                        unit_id: this.selectedUnit,
                        unit_occupation_date: this.unit_occupation_date,
                        unit_vacation_date: this.unit_vacation_date,
                        leaseholder_email: this.editForm.leaseholder_email,
                        leaseholder_mobile: this.editForm.leaseholder_mobile,
                        resident_email: this.editForm.resident_email,
                        resident_mobile: this.editForm.resident_mobile
                    }
                ).then((response) => {
                    this.loading = false;
                    // Redirect user to dashboard
                    swal({
                        title: "Success!",
                        text: "The contract has been marked pending and the applicant has been emailed.",
                        type: "success",
                        confirmButtonText: "Ok"
                    },
                        function () {
                            location.href = '/dashboard';
                        });

                }, (err) => {

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
    };
</script>