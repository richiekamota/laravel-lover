<template>
    <div>

        <div class="row">
            <div class="medium-9 columns">
                <h2 class="--focused">UNITS | users currently signed up for the portal</h2>
                <p>
                    A full list of all the all users.
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
                                <h4 class="--white row">
                                    <div class="small-8 medium-8 columns">Users</div>

                                    <div class="small-4 medium-4 columns">Tenant Code</div>
                                </h4>
                            </button>


                        </div>
                    </div>

                    <!-- Repeat in here -->

                    <template v-for="(filteredUser, index) in filteredUsers">
                        <div class="small-12 columns" v-show="index >= pagination.from && index <= pagination.to">
                            <div class="table__row" :class="{ even: isEven(index), first: index == 0, last: index == filteredUsers.length -1 }">
                                <!-- Row Title -->
                                <button class="accordion__heading" v-on:click="accordionToggle(index, $event)">

                                    {{ filteredUser.first_name }} {{ filteredUser.last_name }} - {{ filteredUser.email }}
                                    <span class="--right">{{ filteredUser.tenant_code }}</span>

                                </button>
                                <!-- START Edit form -->
                                <div class="accordion__content  --bg-calm">
                                    <!-- <label for="userFirstName">
                                         First Name
                                         <input type="text" id="userFirstName" ref="userFirstName"
                                                name="userFirstName" v-model="editUser.first_name" readonly="readonly">
                                     </label>
                                     <label for="userLastName">
                                         Last Name
                                         <input type="text" id="userLastName" ref="userLastName"
                                                name="userLastName" v-model="editUser.last_name" readonly="readonly">
                                     </label>
                                     <label for="userEmail">
                                         Email
                                         <input type="text" id="userEmail" ref="userEmail"
                                                name="userEmail" v-model="editUser.email" readonly="readonly">
                                     </label>-->
                                    <label for="userTenantCode" class="row">

                                        <div class="small-6 medium-6 column"> Tenant Code
                                            <input type="text" id="userTenantCode" ref="userTenantCode" name="userTenantCode"
                                                v-model="editUser.tenant_code">
                                        </div>
                                        <div class="small-6 medium-6 column">&nbsp;
                                            <br/>
                                            <button type="submit" class="success button" v-on:click="updateUser" v-bind:disabled="loading">
                                                <span v-if="loading">
                                                    <loading></loading>
                                                </span>

                                                <span v-else>
                                                    Update User
                                                </span>
                                            </button>
                                        </div>

                                    </label>

                                    <hr/>
                                    <template v-if="filteredUser.contracts.length == 0">
                                        <p>There are no contracts for this user</p>
                                    </template>
                                    <template v-else>
                                        <div class="row">

                                            <div class="small-6 medium-6 column">
                                                <p>
                                                    <strong>CONTRACT UNIT</strong>
                                                </p>
                                            </div>
                                            <div class="small-3 medium-3 column">
                                                <p>
                                                    <strong>STATUS</strong>
                                                </p>
                                            </div>
                                            <div class="small-3 medium-3 column">
                                                <p>
                                                    <strong>ACTIONS</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-for="(filteredUsersContract, index) in filteredUser.contracts">
                                        <div class="row">

                                            <div class="small-6 medium-6 column">
                                                <p>Unit {{filteredUsersContract.unit_code}}
                                                </p>
                                            </div>
                                            <div class="small-3 medium-3 column">
                                                <p>{{filteredUsersContract.status}}</p>
                                            </div>
                                            <div class="small-3 medium-3 column" v-if="filteredUsersContract.status != 'cancelled'">
                                                <p>
                                                    <button type="submit" class="success" v-on:click="submitForRenew(filteredUsersContract.application_id)" v-bind:disabled="loading">RENEW
                                                    </button>
                                                    |
                                                    <button v-on:click="submitForCancel(filteredUsersContract.application_id)" type="submit" class="error" v-bind:disabled="loading">CANCEL
                                                    </button>
                                                </p>
                                            </div>
                                        </div>

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
                                <li class="current" v-show="n ==  pagination.currentPage">
                                    <span class="show-for-sr">You're on page</span>
                                    {{n}}
                                </li>
                                <li>
                                    <a v-bind:aria-label="'Page ' + n" v-on:click="pagination.currentPage = n; calculatePagination()"
                                        v-show="n !=  pagination.currentPage"> {{n}}</a>
                                </li>
                            </template>
                            <!-- <li class="pagination-next" v-on:click="pagination.currentPage = pagination.currentPage + 1; calculatePagination()" v-show="pagination.nextPage <= pagination.maxPages"><a aria-label="Next page">Next</a></li> -->
                        </ul>
                    </div>
                    <!-- END Pagination buttons -->

                </div>

            </div>

            <div class="medium-3 columns">

                <!-- START Filter Section -->
                <div class="row column">
                    <input type="text" placeholder="Filter by everything" ref="filterInput">
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
                        Users:
                        <span class="float-right">{{users.length}}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>
<script>
    export default {
        props: ['propUsers'],
        data() {
            return {
                users: [],
                filteredUsers: [],
                editUser: {
                    id: '',
                    tenant_code: ''
                },
                loading: false,
                pagination: {
                    total: 1,
                    from: 0,
                    to: 1,
                    per_page: 50,
                    currentPage: 1,
                    nextPage: 1,
                    previousPage: 1,
                    maxPages: 0
                }
            }
        },
        mounted() {
            this.users = JSON.parse(this.propUsers);

            this.filteredUsers = this.users;
            this.calculatePagination();
        },
        methods: {

            isEven: function (n) {
                return n % 2 == 0;
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
                    this.createEditableObject(index);
                }, 200)
            },

            updateUser: function () {
                console.log('edit user');
                this.loading = true;

                this.$http.patch(
                    '/users/' + this.editUser.id,
                    JSON.stringify(this.editUser)
                ).then((response) => {

                    // If the response is successful, lets set the name to the edited object
                    this.loading = false;
                    this.filteredUsers[this.editUser.index].tenant_code = this.editUser.tenant_code;
                    this.users[this.editUser.index].tenant_code = this.editUser.tenant_code;

                    // To prevent reactivity from going accross, let's reassign the object.
                    this.createEditableObject(this.editUser.index);
                }, (err) => {
                    this.loading = false;
                    // There is an error, let's display an alert.
                    this.displayError(err);
                });

            },

            submitForCancel: function (app_id) {
                this.loading = true;

                this.$http.post(
                    '/application/' + app_id + '/cancel'
                ).then((response) => {
                    swal({
                        title: "Application Cancelled",
                        text: "Your application has been successfully cancelled.",
                        type: "success",
                        confirmButtonText: "Ok",
                    });
                    this.loading = false;
                    // If we are successful, there might not be any message to say so let's set it to default.
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
                        title: "Error!",
                        text: errorMessage,
                        type: "error",
                        confirmButtonText: "Ok"
                    });

                    this.loading = false;
                });
            },

            submitForRenew: function (app_id) {
                this.loading = true;

                this.$http.post(
                    '/application/' + app_id + '/renew'
                ).then((response) => {
                    swal({
                        title: "Application Cancelled",
                        text: "The application has been successfully renewed.",
                        type: "success",
                        confirmButtonText: "Ok",
                    });
                    this.loading = false;
                    // If we are successful, there might not be any message to say so let's set it to default.
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
                        title: "Error!",
                        text: errorMessage,
                        type: "error",
                        confirmButtonText: "Ok"
                    });

                    this.loading = false;
                });
            },


            createEditableObject(index) {
                // If we want to assign a completly new object which will not update the other form due to
                // reactivity, we must manually assign whatever is needed.
                // We also need the array index so when we update succesfully we know which index to update.
                this.editUser = {};
                this.editUser.index = index;
                this.editUser.id = this.filteredUsers[index].id;
                this.editUser.first_name = this.filteredUsers[index].first_name;
                this.editUser.last_name = this.filteredUsers[index].last_name;
                this.editUser.email = this.filteredUsers[index].email;
                this.editUser.tenant_code = this.filteredUsers[index].tenant_code;
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
                this.filteredUsers = this.users;

                // If the input field is blank, let's not apply filter logic and return everything.
                if (this.$refs['filterInput'].value != '') {
                    this.filteredUsers = this.filteredUsers.filter((user) => {
                        // Let's iterate over the attributes of the unit.
                        var isValid = false;
                        var filteredInput = this.$refs['filterInput'].value;
                        Object.keys(user).forEach(function (key) {
                            let obj = user[key];
                            if (key == 'contracts' && user[key].length > 0) {
                                var contracts = user[key];
                                var i = 0;
                                while (i < contracts.length) {
                                    let contractObj = contracts[i];
                                    Object.keys(contractObj).forEach(function (ckey) {
                                        //alert(contractObj[ckey]);
                                    });
                                    i++;
                                }
                            } else {
                                if (obj && obj.indexOf(filteredInput) !== -1) {
                                    isValid = true;
                                }
                            }


                        });
                        return isValid;
                    });

                    this.pagination.currentPage = 1;
                }

                this.calculatePagination();
            },

            calculatePagination() {
                this.pagination.total = this.filteredUsers.length;
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