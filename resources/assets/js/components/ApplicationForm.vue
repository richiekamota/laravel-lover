<template>

    <form role="form" method="POST" v-bind:action="formAction" v-on:submit.prevent ref="appForm">

        <div class="accordion">

            <button class="accordion__heading" v-on:click="accordionToggle(1, $event)" ref="accordion1" data-accordion="1" >Step 1: Details of the leaseholder applying to rent the premises</button>

            <!-- START Step 1 -->

            <div class="accordion__content">

                <template v-if="showStep >= 1">

                    <h2>Step 1: Details of the leaseholder applying to rent the premises</h2>
                    <!-- First Name -->
                    <label for="first_name">
                        First Name
                        <input type="text" name="first_name" v-model="appForm.first_name" required autofocus>
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
                        <input type="tel" name="phone_mobile" v-model="appForm.phone_mobile" required>
                    </label>

                    <!-- Home telephone number -->
                    <label for="phone_home">
                        Telephone (Home)
                        <input type="tel" name="phone_home" v-model="appForm.phone_home">
                    </label>

                    <!-- Work telephone number -->
                    <label for="phone_work">
                        Telephone (Work)
                        <input type="tel" name="phone_work" v-model="appForm.phone_work">
                    </label>

                    <!-- Current Address -->
                    <label for="current_address">
                        Address where you currently stay
                        <input type="text" name="current_address" v-model="appForm.current_address">
                    </label>

                    <!-- Marital Status -->
                    <label for="marital_status">
                        Marital Status
                        <select name="marital_status" v-model="appForm.marital_status" required>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Widowed">Widowed</option>
                        </select>
                    </label>

                    <!-- TODO: Use vbind to check if maritial status is check and then make this required. -->

                    <!-- Married Type -->
                    <label for="married_type">
                        If married, please select the appropriate option
                        <select name="married_type" v-model="appForm.married_type" v-bind:required="appForm.marital_status == 'Married'">
                            <option value="In community of property">In community of property</option>
                            <option value="ANC">ANC</option>
                            <option value="Accural System">Accural System</option>
                        </select>
                    </label>

                    <button type="submit" class="success button" v-on:click="submitStep(1)" v-bind:disabled="loading">
                        <span v-if="loading">
                            <loading></loading>
                        </span>
                        <span v-else>
                            Save and continue
                        </span>
                    </button>

                </template>

            </div>

            <!-- END Step 1 -->

            <button class="accordion__heading" v-on:click="accordionToggle(2, $event)" ref="accordion2" data-accordion="2">Step 2</button>
            <!-- START Step 2 -->
            <div class="accordion__content">
                <template v-if="showStep >= 2">
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
                            <select name="rental_time" v-model="appForm.rental_time">
                                <option value="12">12 Months</option>
                                <option value="24">24 Months</option>
                                <option value="36">36 Months</option>
                                <option value="48">48 Months</option>
                            </select>
                        </label>

                        <!-- Rental Amount -->
                        <label for="rental_amount">
                            Monthly rental amount
                            <input type="number" name="rental_amount" v-model="appForm.rental_amount" required>
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

                    <button type="submit" class="success button" v-on:click="submitStep(2)" v-bind:disabled="loading">
                        <span v-if="loading">
                            <loading></loading>
                        </span>
                        <span v-else>
                            Save and continue
                        </span>
                    </button>
                </template>
            </div>
            <!-- END Step 2 -->

            <button class="accordion__heading" v-on:click="accordionToggle(3, $event)" ref="accordion3" data-accordion="3">Step 3: Employment details</button>
            <!-- START Step 3 -->

            <div class="accordion__content">

                <template v-if="showStep >= 3">
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

                    </template>

                    <button type="submit" class="success button" v-on:click="submitStep(3)" v-bind:disabled="loading">
                        <span v-if="loading">
                            <loading></loading>
                        </span>
                        <span v-else>
                            Save and continue
                        </span>
                    </button>

                </template>

            </div>

            <!-- END Step 3 -->

            <button class="accordion__heading" v-on:click="accordionToggle(4, $event)" ref="accordion4" data-accordion="4">Step 4:Details of the resident applying to occupy the premises</button>
            <!-- START Step 4 -->
            <div class="accordion__content">

                <template v-if="showStep >= 4">
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
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Unlisted">Unlisted</option>
                        </select>
                    </label>

                    <button type="submit" class="success button" v-on:click="submitStep(4)" v-bind:disabled="loading">
                        <span v-if="loading">
                            <loading></loading>
                        </span>
                        <span v-else>
                            Save and continue
                        </span>
                    </button>
                </template>
            </div>
            <!-- END Step 4 -->

            <button class="accordion__heading" v-on:click="accordionToggle(5, $event)" ref="accordion5" data-accordion="5">Step 5: Details of the premises</button>
            <!-- START Step 5 -->
            <div class="accordion__content">

                <!-- Unit Location -->
                <label for="unit_location">
                    Unit Location
                    <input type="text" name="unit_location" v-model="appForm.unit_location" required>
                </label>

                <!-- Unit Type -->
                <label for="unit_type">
                    Unit Type
                    <select name="unit_type" v-model="appForm.unit_type" required v-on:change="roomInfo($event)">
                        <option v-for="unit in unitTypes" v-bind:value="unit.id" v-bind:data-info="unit.info">
                            {{ unit.name }}
                        </option>
                    </select>
                </label>

                <p v-show="unitTypeInfo">
                    Unit Description: <br>
                    {{ unitTypeInfo }}
                </p>

                <!-- Unit Lease Length -->
                <label for="unit_lease_length">
                    Unit lease length
                    <select name="unit_lease_length" v-model="appForm.unit_lease_length" required>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                </label>

                <!-- Unit Car parking -->
                <label for="unit_car_parking">
                    Motor vehicle parking bay
                    <input type="checkbox" name="unit_car_parking" v-model="appForm.unit_car_parking">
                </label>

                <!-- Unit Bike parking -->
                <label for="unit_bike_parking">
                    Motorcycle vehicle parking bay
                    <input type="checkbox" name="unit_bike_parking" v-model="appForm.unit_bike_parking">
                </label>

                <!-- Unit TV -->
                <label for="unit_tv">
                    10 Channel DSTV bouqet
                    <input type="checkbox" name="unit_tv" v-model="appForm.unit_tv">
                </label>

                <!-- Unit Storeroom -->
                <label for="unit_storeroom">
                    Storeroom
                    <input type="checkbox" name="unit_storeroom" v-model="appForm.unit_storeroom">
                </label>

                <!-- Unity occupation date -->
                <label for="unit_occupation_date">
                    Date Of Birth
                    <input type="date" class="flatpickr" name="unit_occupation_date" v-model="appForm.unit_occupation_date" required>
                </label>

                <button type="submit" class="success button" v-on:click="submitStep(5)" v-bind:disabled="loading">
                        <span v-if="loading">
                            <loading></loading>
                        </span>
                    <span v-else>
                            Save and continue
                        </span>
                </button>

            </div>
            <!-- END Step 5 -->

            <button class="accordion__heading" v-on:click="accordionToggle(6, $event)" ref="accordion6" data-accordion="6">Step 6: General Details</button>
            <!-- START Step 6 -->
            <div class="accordion__content">
                <!-- Judgements -->
                <label for="judgements">
                    Judgements
                    <input type="checkbox" name="judgements" v-model="appForm.judgements">
                </label>

                <!-- Unit Storeroom -->
                <label for="judgements_details">
                    Judgement Details
                    <textarea name="judgements_details" v-model="appForm.judgements_details"></textarea>
                </label>

                <button type="submit" class="success button" v-on:click="submitStep(6)" v-bind:disabled="loading">
                        <span v-if="loading">
                            <loading></loading>
                        </span>
                    <span v-else>
                            Save and continue
                        </span>
                </button>
            </div>
            <!-- END Step 6 -->

            <button class="accordion__heading" v-on:click="accordionToggle(7, $event)" ref="accordion7" data-accordion="7">Step 7: Required supporting documents</button>
            <!-- START Step 7 -->
            <div class="accordion__content">

                <!-- Resident ID -->
                <label for="resident_id">
                    Resident ID
                    <input type="file" name="resident_id">
                </label>

                <!-- Resident Study permit -->
                <label for="resident_study_permit">
                    Resident Study Permit
                    <input type="file" name="resident_study_permit">
                </label>

                <!-- Resident Acceptance -->
                <label for="resident_acceptance">
                    Resident Acceptance
                    <input type="file" name="resident_acceptance">
                </label>

                <!-- Resident Financial Aid -->
                <label for="resident_financial_aid">
                    Resident Financial Aid
                    <input type="file" name="resident_financial_aid">
                </label>

                <!-- Resident Address proof -->
                <label for="leaseholder_address_proof">
                    Resident Proof of Address
                    <input type="file" name="leaseholder_address_proof">
                </label>

                <!-- Resident Address proof -->
                <label for="leaseholder_payslip">
                    Leaseholder's payslip
                    <input type="file" name="leaseholder_payslip">
                </label>

                <button type="submit" class="success button" v-on:click="submitStep(7)" v-bind:disabled="loading">
                        <span v-if="loading">
                            <loading></loading>
                        </span>
                    <span v-else>
                            Save and continue
                        </span>
                </button>

            </div>
            <!-- END Step 7 -->

            <button class="accordion__heading" v-on:click="accordionToggle(8, $event)" ref="accordion8" data-accordion="8">Step 8</button>
            <!-- START Step 8 -->
            <div class="accordion__content">
                <!-- Comments -->
                <label for="comments">
                    Resident Financial Aid
                    <textarea name="comments" v-model="appForm.comments"></textarea>
                </label>

                <!-- Confirm -->
                <label for="confirm">
                    Confirm
                    <input type="checkbox" name="confirm" v-model="appForm.confirm">
                </label>

                <button type="submit" class="success button" v-on:click="submitStep(8)" v-bind:disabled="loading">
                        <span v-if="loading">
                            <loading></loading>
                        </span>
                    <span v-else>
                            Save and continue
                        </span>
                </button>
            </div>
            <!-- END Step 8 -->
        </div>

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
                    resident_gender: '',
                // Step 5
                    unit_location: '',
                    unit_type: '',
                    unit_lease_length: '',
                    unit_car_parking: '',
                    unit_bike_parking: '',
                    unit_tv: '',
                    unit_storeroom: '',
                    unit_occupation_date: '',
                // Step 6
                    judgements: '',
                    judgements_details: '',
                // Step 7
                    resident_id: '',
                    resident_study_permit: '',
                    resident_acceptance: '',
                    resident_financial_aid: '',
                    leaseholder_address_proof: '',
                    leaseholder_payslip: '',
                // Step 8
                    comments: '',
                    confirm: '',
            };
            let unitTypes = [
                {
                    id: '1',
                    name: 'Studio',
                    rental: 'R3850.00 per month',
                    deposit: 'R7700 once-off (R3850 as security deposit and R3850 as damage deposit',
                    info: 'Open plan studio, furnished with single base & matteress, desk, wardrobe, kitchenette with prep bowl & induction hot plate, mini-refrigerator, bnathhroom pod'
                },
                {
                    id: '2',
                    name: 'Premium Studio',
                    rental: 'R5000.00 per month',
                    deposit: 'R10000 once-off (R5000 as security deposit and R3850 as damage deposit',
                    info: 'Premium studio description'
                }
            ];
            let formAction = "/step-1";
            return {
                appForm: appForm,
                formAction: formAction,
                showStep: '',
                unitTypes: unitTypes,
                unitTypeInfo: '',
                loading: false
            };
        },
        mounted() {
            console.log('Component ready.');

            this.showStep = this.step;

            // Toggle the accordion based on the parameter passed
            document.querySelector('[data-accordion="' + this.step + '"]').click();
        },
        methods:{

            accordionToggle: function(step, event) {
                event.preventDefault();

                // We only want interaction on steps which have been completed and saved and the current field.
                if(this.showStep >= step) {
                    let selectedElement = event.target;

                    selectedElement.classList.toggle("accordion__heading--active");
                    selectedElement.nextElementSibling.classList.toggle("accordion__content--active");
                }
            },

            roomInfo: function(event) {
                let sel = event.target;
                this.unitTypeInfo = sel.options[sel.selectedIndex].getAttribute('data-info');
            },

            submitStep: function(step) {

                console.log("Valid", this.$refs.appForm.checkValidity());
                this.loading = true;

                this.$http.post(
                    '/section-' + step,
                    JSON.stringify(this.appForm)
                 ).then((response) => {
                    console.log("Submitted", response);
                    this.loading = false;
                }, (err) => {
                    console.log("Submitted error", err);

                    // 8 Is the last step.
                    if(step != 8) {
                        this.showStep = step + 1;

                        // Proceed to the next part of the accordion.
                        let currentAccordion = 'accordion' + step;
                        this.$refs[currentAccordion].click();

                        let nextAccordion = 'accordion' + (step + 1);
                        this.$refs[nextAccordion].click();
                    }

                    this.loading = false;

                });

            }

        }
    }



</script>