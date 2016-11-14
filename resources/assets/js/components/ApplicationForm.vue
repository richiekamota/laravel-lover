<template>

    <form role="form" method="POST" v-bind:action="formAction" v-on:submit.prevent>

        <!-- START Step 1 -->

        <template v-if="showStep == 1">
            <h2>Step 1: Details of the leaseholder applying to rent the premises</h2>
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

            <!-- TODO: Chekc if ID number has been filled in, make passport number not required and vice versa -->

            <!-- SA ID number -->
            <label for="sa_id_number">
                South African ID Number
                <input type="text" name="sa_id_number" v-model="appForm.sa_id_number">
            </label>

            <p>
                <strong>OR</strong>
            </p>

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
                Telephone (Mobile)
                <input type="text" name="phone_mobile" v-model="appForm.phone_mobile" required>
            </label>

            <!-- Home telephone number -->
            <label for="phone_home">
                Telephone (Home)
                <input type="text" name="phone_home" v-model="appForm.phone_home">
            </label>

            <!-- Work telephone number -->
            <label for="phone_work">
                Telephone (Work)
                <input type="text" name="phone_work" v-model="appForm.phone_work">
            </label>

            <!-- Current Address -->
            <label for="current_address">
                Address where you currently stay
                <input type="text" name="current_address" v-model="appForm.current_address">
            </label>

            <!-- Marital Status -->
            <label for="marital_status">
                Marital Status
                <input type="text" name="marital_status" v-model="appForm.marital_status">
            </label>

            <!-- TODO: Use vbind to check if maritial status is check and then make this required. -->

            <!-- Married Type -->
            <label for="married_type">
                If married, please select the appropriate option
                <select name="married_type" v-model="appForm.married_type">
                    <option value="In community of property">In community of property</option>
                    <option value="ANC">ANC</option>
                    <option value="Accrual System">Accrual System</option>
                </select>
            </label>

            <button type="submit" class="success button" v-on:click="submitStep(1)">
                Save Step 1
            </button>

        </template>

        <!-- END Step 1 -->

        <!-- START Step 2 -->
        <template v-if="showStep == 2">
            <h2>Step 2</h2>
            <label for="current_property_owner">
                Are you the owner of the property where you currently stay?
                <input type="checkbox" name="current_property_owner" v-model="appForm.current_property_owner">
            </label>

            <template v-if="appForm.current_property_owner == false">
                <label for="rental_time">
                    How long have you rented there
                    <input type="text" name="rental_time" v-model="appForm.rental_time" required>
                </label>

                <label for="rental_amount">
                    Monthly rental amount
                    <input type="text" name="rental_amount" v-model="appForm.rental_amount" required>
                </label>

                <label for="rental_name">
                    Name of the Rental Agent / Landlord
                    <input type="text" name="rental_name" v-model="appForm.rental_name" required>
                </label>

                <label for="rental_phone_home">
                    Work phone number
                    <input type="text" name="rental_phone_home" v-model="appForm.rental_phone_home" required>
                </label>

                <label for="rental_phone_mobile">
                    Mobile phone number
                    <input type="text" name="rental_phone_mobile" v-model="appForm.rental_phone_mobile" required>
                </label>
            </template>

            <button type="submit" class="success button" v-on:click="submitStep(2)">
                Save Step 2
            </button>
        </template>
        <!-- END Step 2 -->

        <!-- START Step 3 -->

        <template v-if="showStep == 3">
            <h2>Step 3</h2>
        </template>

        <!-- END Step 3 -->
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

            submitStep: function(step) {

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