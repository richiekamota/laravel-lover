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

            <!-- Current property Owner -->
            <label for="current_property_owner">
                Are you the owner of the property where you currently stay?
                <input type="checkbox" name="current_property_owner" v-model="appForm.current_property_owner">
            </label>

            <template v-if="appForm.current_property_owner == false">
                <!-- Rental Time -->
                <label for="rental_time">
                    How long have you rented there
                    <input type="text" name="rental_time" v-model="appForm.rental_time" required>
                </label>

                <!-- Rental Amount -->
                <label for="rental_amount">
                    Monthly rental amount
                    <input type="text" name="rental_amount" v-model="appForm.rental_amount" required>
                </label>

                <!-- Rental Name -->
                <label for="rental_name">
                    Name of the Rental Agent / Landlord
                    <input type="text" name="rental_name" v-model="appForm.rental_name" required>
                </label>

                <!-- Rental Phone Home -->
                <label for="rental_phone_home">
                    Work phone number
                    <input type="text" name="rental_phone_home" v-model="appForm.rental_phone_home" required>
                </label>

                <!-- Rental Phone Mobile -->
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
            <h2>Step 3: Employment details</h2>

            <!-- Self Employed -->
            <label for="selfemployed">
                Self-employed
                <input type="checkbox" name="selfemployed" v-model="appForm.selfemployed">
            </label>

            <!-- Occupation -->
            <label for="occupation">
                Occupation
                <input type="text" name="occupation" v-model="appForm.occupation" required>
            </label>

            <!-- Current Monthly Expenses -->
            <label for="current_monthly_expenses">
                Current monthly expenses
                <input type="text" name="current_monthly_expenses" v-model="appForm.current_monthly_expenses" required>
            </label>


            <!-- If not selfemployed -->
            <template v-if="appForm.selfemployed == false">

                <!-- Employer Company -->
                <label for="employer_company">
                    Name of company
                    <input type="text" name="employer_company" v-model="appForm.employer_company" required>
                </label>

                <!-- Employer Name -->
                <label for="employer_name">
                    Employer's name
                    <input type="text" name="employer_name" v-model="appForm.employer_name" required>
                </label>

                <!-- Employer phone work -->
                <label for="employer_phone_work">
                    Employer's telephone number
                    <input type="text" name="employer_phone_work" v-model="appForm.employer_phone_work" required>
                </label>

                <!-- Employer Email -->
                <label for="employer_email">
                    Employer's email address
                    <input type="text" name="employer_email" v-model="appForm.employer_email" required>
                </label>

                <!-- Employer Salary -->
                <label for="employer_salary">
                    Gross monthly salary before deducations and tax (copy of pay slip to be attached)
                    <input type="text" name="employer_salary" v-model="appForm.employer_salary" required>
                </label>

                <button type="submit" class="success button" v-on:click="submitStep(3)">
                    Save Step 3
                </button>
            </template>

        </template>

        <!-- END Step 3 -->

        <!-- START Step 4 -->

        <template v-if="showStep == 4">
            <h2>Step 4:Details of the resident applying to occupy the premises</h2>

            <!-- Resident First name -->
            <label for="resident_first_name">
                First name
                <input type="text" name="resident_first_name" v-model="appForm.resident_first_name" required>
            </label>

            <!-- Resident Last name -->
            <label for="resident_last_name">
                Last Name
                <input type="text" name="resident_last_name" v-model="appForm.resident_last_name" required>
            </label>

            <!-- Resident ID Number-->
            <label for="resident_sa_id_number">
                ID Number
                <input type="text" name="resident_sa_id_number" v-model="appForm.resident_sa_id_number">
            </label>

            <p>
                <strong>OR</strong>
            </p>

            <!-- Resident Passport Number -->
            <label for="resident_passport_number">
                Passport number
                <input type="text" name="resident_passport_number" v-model="appForm.resident_passport_number">
            </label>

            <!-- Resident Date of birth DOB -->
            <label for="resident_dob">
                Date Of Birth
                <input type="date" class="flatpickr" name="resident_dob" v-model="appForm.resident_dob" required>
            </label>

            <!-- Resident Nationality -->
            <label for="resident_nationality">
                Nationality
                <input type="text" name="resident_nationality" v-model="appForm.resident_nationality" required>
            </label>

            <!-- Resident Mobile phone number -->
            <label for="resident_phone_mobile">
                Telephone (Mobile)
                <input type="text" name="resident_phone_mobile" v-model="appForm.resident_phone_mobile" required>
            </label>

            <!-- Resident Email Address -->
            <label for="resident_email">
                Email Address
                <input type="text" name="resident_email" v-model="appForm.resident_email" required>
            </label>

            <!-- Resident Current Address -->
            <label for="resident_current_address">
                Address where you currently stay
                <input type="text" name="resident_current_address" v-model="appForm.resident_current_address" required>
            </label>

            <!-- Resident Landlord -->
            <label for="resident_landlord">
                Name of rental agent/ landlord
                <input type="text" name="resident_landlord" v-model="appForm.resident_landlord" required>
            </label>

            <!-- Resident Landlord work phone number -->
            <label for="resident_landlord_phone_work">
                Landlord's work phone number
                <input type="text" name="resident_landlord_phone_work" v-model="appForm.resident_landlord_phone_work" required>
            </label>

            <!-- Resident Landlord phone number -->
            <label for="resident_landlord_phone_mobile">
                Landlord's mobile phone number
                <input type="text" name="resident_landlord_phone_mobile" v-model="appForm.resident_landlord_phone_mobile" required>
            </label>

            <!-- Resident Study location-->
            <label for="resident_study_location">
                Where will you be studying
                <input type="text" name="resident_study_location" v-model="appForm.resident_study_location" required>
            </label>

            <!-- Resident Study year -->
            <label for="resident_study_year">
                What year of study will you be in
                <input type="text" name="resident_study_year" v-model="appForm.resident_study_year" required>
            </label>

            <!-- Employer Name -->
            <label for="resident_gender">
                What is your gender
                <select name="resident_gender" v-model="appForm.resident_gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </label>

            <button type="submit" class="success button" v-on:click="submitStep(4)">
                Save Step 4
            </button>
        </template>

        <!-- END Step 4 -->
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
                    rental_phone_mobile: '',
                // Step 3
                    selfemployed: '',
                    occupation: '',
                    current_monthly_expenses: '',
                    employer_company: '',
                    employer_phone_work: '',
                    employer_email: '',
                    employer_salary: '',
                // Step 4
                    resident_first_name: '',
                    resident_last_name: '',
                    resident_sa_id_number: '',
                    resident_passport_number: '',
                    resident_dob: '',
                    resident_nationality: '',
                    resident_phone_mobile: '',
                    resident_email: '',
                    resident_current_address: '',
                    resident_landlord: '',
                    resident_landlord_phone_work: '',
                    resident_landlord_phone_mobile: '',
                    resident_study_location: '',
                    resident_study_year: '',
                    resident_gender: ''

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