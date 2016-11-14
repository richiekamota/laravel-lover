<template>

    <form role="form" method="POST" v-bind:action="formAction" v-on:submit.prevent>

        <!-- START Step 1 -->

        <template v-if="showStep == 1">
            <h2>Step 1</h2>
            <!-- First Name -->
            <label for="first_name">
                First Name
                <input type="text" name="first_name" v-model="appForm.first_name" v-on:keyup="testXHR" required autofocus>
            </label>

            <!-- Last Name -->
            <label for="last_name">
                Last Name
                <input type="text" name="last_name" v-model="appForm.last_name" required>
            </label>

            <!-- Email -->
            <label for="email">
                E-Mail Address
                <input id="email" type="email" name="email" v-model="appForm.email" required>
            </label>

            <!-- Password -->
            <label for="password">
                Password
                <input id="password" type="password" name="password" v-model="appForm.password" required>
            </label>

            <!-- SA ID number -->
            <label for="sa_id_number">
                South African ID Number
                <input type="text" name="sa_id_number" v-model="appForm.sa_id_number">
            </label>

            <!-- passport number -->
            <label for="passport_number">
                Passport number
                <input type="text" name="passport_number" v-model="appForm.passport_number">
            </label>

            <!-- Date of birth DOB -->
            <label for="dob">
                Date Of Birth
                <input type="date" class="flatpickr" name="dob" v-model="appForm.dob" required>
            </label>

            <!-- Nationality -->
            <label for="nationality">
                Nationality
                <input type="text" name="nationality" v-model="appForm.nationality" required>
            </label>

            <!-- Cellphone Number -->
            <label for="phone_mobile">
                Cellphone Number
                <input type="text" name="phone_mobile" v-model="appForm.phone_mobile" required>
            </label>

            <!-- Home telephone number -->
            <label for="phone_home">
                Home phone number
                <input type="text" name="phone_home" v-model="appForm.phone_home" required>
            </label>

            <!-- Work telephone number -->
            <label for="phone_work">
                Work phone numbera
                <input type="text" name="phone_work" v-model="appForm.phone_work" required>
            </label>

            <!-- Current Address -->
            <label for="current_address">
                Current Address
                <input type="text" name="current_address" v-model="appForm.current_address" required>
            </label>

            <!-- Marital Status -->
            <label for="marital_status">
                Marital Status
                <input type="text" name="marital_status" v-model="appForm.marital_status" required>
            </label>

            <!-- Married Type -->
            <label for="married_type">
                Marriage Type
                <input type="text" name="married_type" v-model="appForm.married_type" required>
            </label>

            <button type="submit" class="success button" v-on:click="submitStep(1, $event)">
                Save Step 1
            </button>

        </template>

        <!-- END Step 1 -->

        <!-- START Step 2 -->
        <template v-if="showStep == 2">
            <h2>Step 2</h2>
            <label for="current_property_owner">
                Are you the owner of the property where you currently stay?
                <input type="checkbox" name="current_property_owner" v-model="appForm.current_property_owner" required>
            </label>

            <template v-if="appForm.current_property_owner == false">
                <label for="rental_time">
                    How long have you rented there
                    <input type="text" name="rental_time" v-model="appForm.rental_time" required>
                </label>
            </template>
        </template>
        <!-- END Step 2 -->
    </form>

</template>

<script>
    const Flatpickr = require("flatpickr");

    export default {
        props: ['step'],
        data: () => {
            let appForm = {
                // Step 1
                    myForm: '',
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    sa_id_number: '',
                    passport_number: '',
                    dob: '',
                    nationality: '',
                    phone_mobile: '',
                    phone_home: '',
                    phone_work: '',
                    current_address: '',
                    marital_status: '',
                    married_type: '',
                // Step 2
                    current_property_owner: '',
                    rental_time: '',
                    rental_amount: '',
                    rental_name: '',
                    rental_phone_home: '',
                    rental_phone_mobile: ''
            };
            let formAction = "/step-1";
            return {
                appForm: appForm,
                formAction: formAction,
                showStep: ''
            };
        },
        mounted() {
            console.log('Component ready.');

            this.showStep = this.step;
        },
        methods:{
            testXHR: function() {
            },

            submitStep: function(step, event) {

            console.log("This mew", this.meow);

                this.$http.post(
                    this.formAction,
                    JSON.stringify(this.appForm)
                 ).then((response) => {
                    console.log("Submitted", response);
                }, (err) => {
                    console.log("Submitted error", err);
                    this.showStep = step + 1;
                });

            }

        }
    }



</script>