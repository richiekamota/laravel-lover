<template>
    <div>

        <div class="row">
            <div class="medium-9 columns">
                <h2 class="--focused">OCCUPATIONS |</h2>
                <p ref="filterSummary">
                    {{filterSummary}}
                </p>
            </div>
        </div>

        <div class="row">

            <!-- Title -->
            <div class="small-12 medium-9 columns">

                <!-- START List Section -->
                <div class="row table">

                    <div class="small-12 columns">
                        <div class="table__row table__row--add">
                            <!-- Row Title -->
                            <button class="accordion__heading accordion__heading--add">
                                <h4 class="--white">Units</h4>
                            </button>


                        </div>
                    </div>

                    <!-- Repeat in here -->
                    <template v-if="filteredUnits.length == 0">
                        <div class="small-12 columns">
                            <div class="table__row last">
                                <button class="accordion__heading">No results found</button>
                            </div>
                        </div>
                    </template>

                    <template v-for="(filteredUnit, index) in filteredUnits">
                        <div class="small-12 columns" v-show="index >= pagination.from && index <= pagination.to">
                            <div class="table__row"
                                 :class="{ even: isEven(index), first: index == 0, last: index == filteredUnits.length -1 }">
                                <!-- Row Title -->
                                <button class="accordion__heading" v-on:click="accordionToggle(index, $event)">{{
                                    filteredUnit.code }}
                                </button>
                                <!-- START Edit form -->
                                <div class="accordion__content  --bg-calm">
                                    <div v-if="filteredUnit.occupation_dates.length == 0">

                                        <p>There are no occupations for this unit</p>

                                    </div>
                                    <template v-for="(occupationDate, index) in filteredUnit.occupation_dates">

                                        <div class="row column">
                                            <!-- START Unit input form -->
                                            <p><strong>Contract</strong>:
                                                {{ occupationDate.contract_id }}</p>

                                            <div class="col row">
                                                <div class="small-6 columns">
                                                    <p><strong>Start Date</strong>:
                                                        {{ occupationDate.start_date }}</p>
                                                </div>
                                                <div class="small-6 columns">
                                                    <p><strong>End Date</strong>:
                                                        {{ occupationDate.end_date }}</p>
                                                </div>
                                            </div>


                                            </label>
                                        </div>

                                        <hr/>
                                    </template>
                                </div>
                                <!-- END Edit form -->
                            </div>
                        </div>
                    </template>

                    <!-- START Pagination buttons -->
                    <div class="row">
                        <ul class="pagination text-center" role="navigation" aria-label="Pagination">
                            <!-- <li class="pagination-previous" v-on:click="pagination.currentPage = pagination.currentPage - 1; calculatePagination()" v-bind:class="{disabled: pagination.previousPage > 0}" >Previous</li> -->
                            <template v-for="n in pagination.maxPages">
                                <li class="current" v-show="n ==  pagination.currentPage"><span class="show-for-sr">You're on page</span>
                                    {{n}}
                                </li>
                                <li><a v-bind:aria-label="'Page ' + n"
                                       v-on:click="pagination.currentPage = n; calculatePagination()"
                                       v-show="n !=  pagination.currentPage"> {{n}}</a></li>
                            </template>
                            <!-- <li class="pagination-next" v-on:click="pagination.currentPage = pagination.currentPage + 1; calculatePagination()" v-show="pagination.nextPage <= pagination.maxPages"><a aria-label="Next page">Next</a></li> -->
                        </ul>
                    </div>
                    <!-- END Pagination buttons -->


                </div>

            </div>

            <div class="medium-3 columns">

                <!-- START Filter Section -->


                <h3></strong>Filter:</h3>
                <p class="error">{{filterMessage}}</p>
                <div class="row column">
                    <label for="filterLocation">
                        <select class="--mb0" ref="filterLocation" id="filterLocation" name="filterLocation"
                                v-model="filterLocation" placeholder="Filter by location">
                            <option value="">Select Location</option>
                            <option v-for="location in locations" v-bind:value="location.id">
                                {{ location.name }}
                            </option>
                        </select>
                    </label>
                    <br/>
                </div>
                <div class="row column">

                    <Flatpickr :options='{ altInput: true, altFormat: "d F Y" }' name="filterStartDate"
                               placeholder="Start Date"
                               v-model="filterStartDate"
                    />
                </div>
                <div class="row column">

                    <Flatpickr :options='{ altInput: true, altFormat: "d F Y" }' name="filterEndDate"
                               placeholder="End Date"
                               v-model="filterEndDate"
                    />
                </div>
                <div class="row column">
                    <input type="radio" id="Occupied" value="1" v-model="occupied">
                    <label for="Occupied">Occupied Units</label>
                    <br/>
                    <input type="radio" id="Unoccupied" value="0" v-model="occupied">
                    <label for="Unoccupied">Unoccupied Units</label>


                </div>
                <div class="row column">
                    <button v-on:click="filter" class="button">
                        Filter
                    </button>

                </div>

                <div class="row column --border-wrap">
                    <div class="stats-box">
                        <div class="row column clearfix">
                            <h3 class="stats-box__header --focused --mt0">Stats</h3>
                        </div>
                        Units: <span class="float-right">{{filteredUnits.length}}</span>
                        <br/><br/>
                        <form action="" method="post" target="_blank">
                            <input type="hidden" name="export_ids" id="export_ids" ref="export_ids" value="">
                        </form>
                        <button v-on:click="exportToCSV" class="button">
                            Export to CSV
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>
<script>

    const moment = require('moment');

    import VueFlatpickr from 'vue-flatpickr';

    Vue.use(VueFlatpickr);

    export default {
        props: ['propLocations', 'propUnits'],
        data(){
            return {
                units: [],
                locations: [],
                filteredUnits: [],
                loading: false,
                filterStartDate: '',
                filterEndDate: '',
                filterLocation: '',
                filterSummary: 'Showing all occupied units.',
                occupied: 1,
                filterMessage: '',
                pagination: {
                    total: 1,
                    from: 0,
                    to: 1,
                    per_page: 10,
                    currentPage: 1,
                    nextPage: 1,
                    previousPage: 1,
                    maxPages: 0
                }
            }
        },
        mounted() {
            this.locations = JSON.parse(this.propLocations);
            this.units = JSON.parse(this.propUnits);
            this.defaultFilter();
            this.calculatePagination();
        },
        methods: {

            isEven: function (n) {
                return n % 2 == 0;
            },

            defaultFilter: function () {
                this.filteredUnits = this.units.filter((unit) => {
                    if (this.filteredUnits.occupation_dates) {
                        if (this.filteredUnits.occupation_dates.length > 0) {
                            var isValid = true;
                            return isValid;
                        }
                    }
                });
                this.pagination.currentPage = 1;
                this.calculatePagination();
            },

            getTime: function (time) {
                return moment(time).format("MMMM Do YYYY");
            },

            accordionToggle: function (index, event) {
                event.preventDefault();
                let selectedElement = event.target;

                // Lets check the initial classes to see if we should close the current accordion or open a new one.
                let shouldClose = !selectedElement.classList.contains('accordion__heading--active');

                // Let's close all accordions fist
                if (this.$el.querySelector(".accordion__heading--active")) {
                    this.$el.querySelectorAll(".accordion__heading--active").forEach(function (element) {
                        element.classList.toggle("accordion__heading--active");
                    });

                    this.$el.querySelectorAll(".accordion__content--active").forEach(function (element) {
                        element.classList.toggle("accordion__content--active");
                    });
                }
                // If the button was open, we don't want to reopen it.
                if (shouldClose) {
                    selectedElement.classList.toggle("accordion__heading--active");
                    selectedElement.nextElementSibling.classList.toggle("accordion__content--active");
                }

                // Let's load up the editable object with the selected item
                // Let's wait for the previous accordion animation to finish then do this.
                setTimeout(() => {
                    // If we want to use vue with it's reactivity use the below
                    //this.editUnit = this.units[index];
                }, 200)
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


            filter() {
                // Let's get a fresh list before filter.
                this.filteredUnits = this.units;
                console.log(this.filteredUnits);
                this.filterMessage = '';

                if (this.filterLocation != '') {
                    this.filteredUnits = this.units.filter((unit) => {
                        var isValid = false;

                        if (this.filterLocation == unit.location_id) {
                            isValid = true;
                        }

                        return isValid;
                    });

                }

                if (this.filterStartDate != '' || this.filterEndDate != '') {
                    var inputStartDate = new Date(this.filterStartDate);
                    var inputEndDate = new Date(this.filterEndDate);

                    this.filteredUnits = this.units.filter((unit) => {

                        var isValid = false;

                        if (unit.occupation_dates.length && this.occupied == 1) {
                            var i = 0;
                            while (i < unit.occupation_dates.length) {

                                var unitStartDate = new Date(unit.occupation_dates[i].start_date);
                                var unitEndDate = new Date(unit.occupation_dates[i].end_date);


                                var curOccupationData = unit.occupation_dates[i];
                                unit.occupation_dates.splice(i, 1);

                                if (this.filterStartDate != '' && this.filterEndDate != '') {

                                    if ((unitStartDate >= inputStartDate && unitStartDate <= inputEndDate) || (unitEndDate <= inputEndDate && unitEndDate >= inputStartDate)) {
                                        if (this.occupied == 1) {
                                            isValid = true;
                                            unit.occupation_dates[i] = curOccupationData;
                                        }
                                    }
                                }

                                else if (this.filterStartDate != '' && this.filterEndDate == '') {

                                    if ((unitStartDate >= inputStartDate && unitEndDate <= inputStartDate)) {
                                        if (this.occupied == 1) {
                                            isValid = true;
                                            unit.occupation_dates[i] = curOccupationData;
                                        }
                                    }
                                }

                                else if (this.filterEndDate != '' && this.filterStartDate == '') {

                                    if ((unitEndDate >= inputEndDate && unitStartDate <= inputEndDate)) {
                                        if (this.occupied == 1) {
                                            isValid = true;
                                            unit.occupation_dates[$i] = curOccupationData;
                                        }
                                    }
                                }


                                i++;

                            }
                        }else{
                            if (this.occupied == 0 && !unit.occupation_dates.length) {
                                isValid = true;
                            }
                        }

                        return isValid;
                    });

                    this.pagination.currentPage = 1;

                }else{
                    this.filteredUnits = [];
                    this.filterMessage = 'Please select START and END dates for this filter.';

                }


                this.calculatePagination();
                this.filterSummary = 'Showing ' + this.filteredUnits.length + ' ' + (this.occupied == 1 ? 'Occupied' : 'Unoccupied') + ' units.';
                if (this.filterStartDate || this.filterEndDate) {
                    this.filterSummary = this.filterSummary + ' Between ' + this.filterStartDate + ' and ' + this.filterEndDate + '.';
                }
                if (this.filterLocation) {
                    this.filterSummary = this.filterSummary + (this.filterLocation != '' ? ' In ' + this.filterLocation + '.' : '');
                }

            },

            exportToCSV: function () {
                this.loading = true;

                var i = 0;
                var exportUnits = [];
                while (i < this.filteredUnits.length) {
                    exportUnits = exportUnits + ',' + this.filteredUnits[i].id;
                    i++;
                }

                var postArray = {
                    location: this.filterLocation,
                    start_date: this.filterStartDate,
                    end_date: this.filterEndDate,
                    occupation: this.occupied,
                    export_ids: exportUnits
                };

                this.$http.post(
                    '/occupations/export',
                    JSON.stringify(postArray)
                ).then((response) => {
                    swal({
                        title: "Export to CSV",
                        text: "Your CSV export will download shortly.",
                        type: "success",
                        confirmButtonText: "Ok",
                    });

                    var blob = response.body;
                    var base64data = '';

                    var reader = new window.FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function () {
                        base64data = reader.result;

                        var encodedUri = base64data;
                        var link = document.createElement("a");
                        link.setAttribute("href", encodedUri);
                        link.setAttribute("download", "export.csv");
                        document.body.appendChild(link); // Required for FF

                        link.click();

                    }

                    this.loading = false;

                }, (err) => {
                    console.log("An error occured", err);
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
                    swal({
                        title: "Error exporting data",
                        text: errorMessage,
                        type: "error",
                        confirmButtonText: "Ok"
                    });

                    this.loading = false;
                });
            },

            calculatePagination() {
                this.pagination.total = this.filteredUnits.length;
                // Since Arrays start from 0, we must subtract an additional 1.
                this.pagination.from = (this.pagination.currentPage - 1) * this.pagination.per_page;
                this.pagination.to = this.pagination.from + this.pagination.per_page - 1;
                this.pagination.nextPage = this.pagination.currentPage + 1;
                this.pagination.previousPage = this.pagination.currentPage - 1;
                // Since Arrays start at 0 we need to increment this value
                this.pagination.maxPages = Math.round((this.pagination.total / this.pagination.per_page));
            }

        }
    }

</script>
