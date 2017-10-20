<template>
    <div class="contract-approve">

        <!-- Input for name -->
        <p>Please enter your name in UPPER CASE <br>this must match the user profile name used to create your application</p>
        <label for="upperName">
            <span class="row">
                <span class="small-6 column">
                    <input type="text" name="upperName" v-model="upperName">

                </span>
            </span>
        </label>

        <p>Contract Items Acceptance</p>
        <!-- List of items with tick box -->
        <ul class="--plain">
            <li v-for="(item, index) in items">
                <input v-model="item.checked" :id="'item'+index" type="checkbox"><label :for="'item'+index">I approve {{item.name}} to be added to the contract.</label>
            </li>
        </ul>

        <!-- Contract accept tick box -->
        <p>Contract Acceptance</p>
        <input id="contract-check" type="checkbox" v-model="contractAccept"><label for="contract-check">I {{contractUser.first_name}} {{contractUser.last_name}} approve this contract.</label>

        <!-- Submit button -->

        <div class="row column">
            <button type="submit" id="contract-approve" class="button button--focused --mt2" v-on:click="accept()">
                <span v-if="loading">
                    <loading></loading>
                </span>

                <template v-if="!loading">
                    Accept Contract
                </template>
            </button>
        </div>


    </div>
</template>

<script>

    // The user must input an uppercase version of their name, matching their profile name,
    // They must tick next to each item on the contract
    // They must tick next to the contract approve input
    // They must submit

    export default {

        props: ['propContract'],

        data() {
            return {
                test: "test",
                upperName: "",
                items: [],
                contractUser: {},
                contractAccept: false,
                loading: false
            }

        },

        mounted() {
            this.contract = JSON.parse(this.propContract);
            this.items = this.contract.items;
            this.contractUser = this.contract.user;

            
            this.items = this.items.map(function(item){
                item.checked = false;
                return item;
            });
        },

        methods: {

            accept: function (){

                let name = this.contractUser.first_name + " " + this.contractUser.last_name;

                if(this.upperName != name.toUpperCase()){
                    swal({
                        title: "Error!",
                        text: "The entered name does not match that of the user who created this application.",
                        type: "error",
                        confirmButtonText: "Ok"
                    });
                    return;
                }

                Promise.resolve()
                    .then(() => {

                        this.items.forEach(function(item){
                            if(!item.checked){
                                swal({
                                    title: "Error!",
                                    text: "You have not approved "+ item.name +". You need to approve this item before accepting.",
                                    type: "error",
                                    confirmButtonText: "Ok"
                                });
                                throw null;
                            }
                        });

                    })
                    .then( () => {

                        // Check if the checkbox is checked
                        if(!this.contractAccept){
                            swal({
                                title: "Error!",
                                text: "You have not approved the contract.",
                                type: "error",
                                confirmButtonText: "Ok"
                            });
                            throw null;
                        }

                    })
                    .then( () => {

                        // Sweet alert
                        swal({
                            title: "Confirm!",
                            text: "You are about to approve the contract, please confirm this is the correct action.",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Ok"
                        },
                            () => {
                                this.submitApproval();
                            }
                        );
                    })
                    .catch(()=>{});

            },

            submitApproval: function (){

                this.loading = true;

                this.$http.post(
                    '/contracts/' + this.contract.id + '/approved',
                    {
                        'user_id' : this.contractUser.id,
                        'contractApproved' : this.contractAccept,
                        'items' : this.items
                    }

                ).then((response) => {

                    this.loading = false;

                    // Redirect user to dashboard
                    swal({
                        title: "Success!",
                        text: "Your contract will now be sent for final processing.",
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
