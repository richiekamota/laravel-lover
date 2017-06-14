<template>
    <div class="row">
        <div class="small-12 medium-8 columns">
            <div>

                <div class="row column">
                    <div class="page-application__header">
                        <h1 class="page-application__header-title">Application Form</h1>
                        <p class="page-application__header-sub">Thanks for choosing MyDomain as your student
                            accommodation
                            provider. To get started simply fill in the following application form.</p>
                        <p>The first step is to create an account with us. We use this account to
                            communicate with you
                            during
                            the application process and if successful this account will be your portal to the MyDomain
                            team.</p>
                    </div>
                </div>

                <form role="form" method="POST" v-bind:action="formAction" v-on:submit.prevent ref="appForm">

                    <div class="accordion">

                        <!-- START Step 1 -->
                        <button class="application-step__heading" v-on:click="accordionToggle(1, $event)"
                                ref="accordion1"
                                data-accordion="1">Step 1: Details of the leaseholder applying to rent the premises
                        </button>
                        <div class="application-step__content">

                            <template v-if="showStep >= 1">

                                <!-- TODO: Chekc if ID number has been filled in, make passport number not required and vice versa -->

                                <!-- Resident First name -->
                                <label for="first_name">
                                    First name *
                                    <input type="text" name="first_name" v-model="appForm.first_name" required>
                                </label>

                                <!-- Resident Last name -->
                                <label for="last_name">
                                    Last Name *
                                    <input type="text" name="last_name" v-model="appForm.last_name" required>
                                </label>

                                <!-- Leaseholder Email -->
                                <label for="email">
                                    Email Address *
                                    <input type="text" name="email" v-model="appForm.email" required>
                                </label>

                                <div class="row">
                                    <div class="column medium-5">
                                        <!-- SA ID number -->
                                        <label for="sa_id_number">
                                            South African ID Number
                                            <input type="text" name="sa_id_number" v-model="appForm.sa_id_number">
                                        </label>
                                    </div>
                                    <div class="column medium-2">
                                        <p>
                                            <strong>OR</strong>
                                        </p>
                                    </div>
                                    <div class="column medium-5">
                                        <!-- passport number -->
                                        <label for="passport_number">
                                            Passport number
                                            <input type="text" name="passport_number" v-model="appForm.passport_number">
                                        </label>
                                    </div>
                                </div>

                                <!-- Date of birth DOB -->
                                <label for="dob">
                                    Date Of Birth *
                                    <Flatpickr :options='{ altInput: true, altFormat: "d F Y" }' name="dob"
                                               v-model="appForm.dob" @update='appForm.dob = $event' required/>
                                </label>

                                <!-- Nationality -->
                                <div class="styled-select">
                                    <label for="nationality">
                                        Nationality *
                                        <select class="styled-select" name="nationality" v-model="appForm.nationality"
                                                v-on:change="roomInfo($event)" required>
                                            <option v-for="country in countries" v-bind:value="country.nationality">
                                                {{ country.nationality }}
                                            </option>
                                        </select>
                                    </label>
                                </div>

                                <!-- Cellphone Number -->
                                <label for="phone_mobile">
                                    Telephone (Mobile) *
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
                                    Address where you currently stay *
                                    <input type="text" name="current_address" v-model="appForm.current_address"
                                           required>
                                </label>

                                <!-- Marital Status -->
                                <div class="styled-select">
                                    <label for="marital_status">
                                        Marital Status *
                                        <select name="marital_status" v-model="appForm.marital_status" required>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Divorced">Divorced</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </label>
                                </div>

                                <!-- TODO: Use vbind to check if maritial status is check and then make this required. -->

                                <!-- Married Type -->
                                <div class="styled-select">
                                    <label for="married_type">
                                        If married, please select the appropriate option
                                        <select name="married_type" v-model="appForm.married_type"
                                                v-bind:required="appForm.marital_status == 'Married'">
                                            <option value=""></option>
                                            <option value="In community of property">In community of property</option>
                                            <option value="ANC">ANC</option>
                                            <option value="Accural System">Accural System</option>
                                        </select>
                                    </label>
                                </div>

                                <div v-if="appForm.step1" v-html="appForm.step1"></div>

                                <hr class="application-step__line --mt2">

                                <button type="submit" id="step1-save" class="button button--focused --mt2"
                                        v-on:click="submitStep(1)" v-bind:disabled="loading">
                                    <span v-if="loading"><loading></loading></span>
                                    <span v-else>Save and continue</span>
                                </button>

                            </template>

                        </div>
                        <!-- END Step 1 -->

                        <!-- START Step 2 -->
                        <button class="application-step__heading" v-on:click="accordionToggle(2, $event)"
                                ref="accordion2"
                                v-bind:class="{ '--disabled' : showStep <  2}" data-accordion="2">Step 2: Leaseholder
                            home
                            ownership
                        </button>
                        <div class="application-step__content">
                            <template v-if="showStep >= 2">

                                <!-- Current property Owner -->
                                <div class="row column">
                                    <input type="checkbox" name="current_property_owner" id="current_property_owner"
                                           v-model="appForm.current_property_owner"><label for="current_property_owner">Are
                                    you the owner of the property where you currently stay?
                                    <br/>
                                </label>

                                </div>

                                <template v-if="appForm.current_property_owner == false || appForm.current_property_owner == ''" >
                                    <!-- Rental Time -->
                                    <div class="styled-select">
                                        <label for="rental_time">
                                            How long have you rented there *
                                            <select name="rental_time" v-model="appForm.rental_time">
                                                <option value="12">12 Months</option>
                                                <option value="24">24 Months</option>
                                                <option value="36">36 Months</option>
                                                <option value="48">48 Months</option>
                                            </select>
                                        </label>
                                    </div>

                                    <!-- Rental Amount -->
                                    <label for="rental_amount">
                                        Monthly rental amount *
                                        <input type="number" name="rental_amount" v-model="appForm.rental_amount"
                                               required>
                                    </label>

                                    <!-- Rental Name -->
                                    <label for="rental_name">
                                        Name of the Rental Agent / Landlord *
                                        <input type="text" name="rental_name" v-model="appForm.rental_name" required>
                                    </label>

                                    <!-- Rental Phone Home -->
                                    <label for="rental_phone_home">
                                        Work phone number *
                                        <input type="text" name="rental_phone_home" v-model="appForm.rental_phone_home"
                                               required>
                                    </label>

                                    <!-- Rental Phone Mobile -->
                                    <label for="rental_phone_mobile">
                                        Mobile phone number *
                                        <input type="text" name="rental_phone_mobile"
                                               v-model="appForm.rental_phone_mobile"
                                               required>
                                    </label>
                                </template>

                                <div v-if="appForm.step2" v-html="appForm.step2"></div>

                                <hr class="application-step__line --mt2">

                                <button type="submit" id="step2-save" class="button button--focused --mt2"
                                        v-on:click="submitStep(2)" v-bind:disabled="loading">
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

                        <!-- START Step 3 -->
                        <button class=" application-step__heading" v-on:click="accordionToggle(3, $event)"
                                ref="accordion3"
                                v-bind:class="{ '--disabled' : showStep <  3}" data-accordion="3">Step 3: Employment
                            details
                        </button>
                        <div class="application-step__content">

                            <template v-if="showStep >= 3">

                                <!-- Self Employed -->

                                <div class="row column">
                                    <input type="checkbox" name="selfemployed" id="selfemployed"
                                           v-model="appForm.selfemployed"><label
                                        for="selfemployed">Self-employed</label>
                                </div>

                                <!-- Occupation -->
                                <label for="occupation">
                                    Occupation *
                                    <input type="text" name="occupation" v-model="appForm.occupation" required>
                                </label>

                                <!-- Current Monthly Expenses -->
                                <label for="current_monthly_expenses">
                                    Current monthly expenses *
                                    <input type="text" name="current_monthly_expenses"
                                           v-model="appForm.current_monthly_expenses" required>
                                </label>


                                <!-- If not selfemployed -->
                                <template v-if="appForm.selfemployed == false">

                                    <!-- Employer Company -->
                                    <label for="employer_company">
                                        Name of company *
                                        <input type="text" name="employer_company" v-model="appForm.employer_company"
                                               required>
                                    </label>

                                    <!-- Employer Name -->
                                    <label for="employer_name">
                                        Employer's name *
                                        <input type="text" name="employer_name" v-model="appForm.employer_name"
                                               required>
                                    </label>

                                    <!-- Employer phone work -->
                                    <label for="employer_phone_work">
                                        Employer's telephone number *
                                        <input type="text" name="employer_phone_work"
                                               v-model="appForm.employer_phone_work"
                                               required>
                                    </label>

                                    <!-- Employer Email -->
                                    <label for="employer_email">
                                        Employer's email address *
                                        <input type="text" name="employer_email" v-model="appForm.employer_email"
                                               required>
                                    </label>

                                    <!-- Employer Salary -->
                                    <label for="employer_salary">
                                        Gross monthly salary before deducations and tax (copy of pay slip to be
                                        attached) *
                                        <input type="text" name="employer_salary" v-model="appForm.employer_salary"
                                               required>
                                    </label>

                                </template>

                                <div v-if="appForm.step3" v-html="appForm.step3"></div>

                                <hr class="application-step__line --mt2">

                                <button type="submit" id="step3-save" class="button button--focused --mt2"
                                        v-on:click="submitStep(3)"
                                        v-bind:disabled="loading">
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

                        <!-- START Step 4 -->
                        <button class="application-step__heading" v-on:click="accordionToggle(4, $event)"
                                ref="accordion4"
                                v-bind:class="{ '--disabled' : showStep <  4}" data-accordion="4">Step 4: Details of the
                            resident applying to occupy the premises
                        </button>
                        <div class="application-step__content">

                            <template v-if="showStep >= 4">

                                <!-- Resident First name -->
                                <label for="resident_first_name">
                                    First name *
                                    <input type="text" name="resident_first_name" v-model="appForm.resident_first_name"
                                           required>
                                </label>

                                <!-- Resident Last name -->
                                <label for="resident_last_name">
                                    Last Name *
                                    <input type="text" name="resident_last_name" v-model="appForm.resident_last_name"
                                           required>
                                </label>

                                <!-- Resident ID Number-->
                                <label for="resident_sa_id_number">
                                    ID Number
                                    <input type="text" name="resident_sa_id_number"
                                           v-model="appForm.resident_sa_id_number">
                                </label>

                                <p>
                                    <strong>OR</strong>
                                </p>

                                <!-- Resident Passport Number -->
                                <label for="resident_passport_number">
                                    Passport number
                                    <input type="text" name="resident_passport_number"
                                           v-model="appForm.resident_passport_number">
                                </label>

                                <!-- Resident Date of birth DOB -->
                                <label for="resident_dob">
                                    Date Of Birth *
                                    <Flatpickr :options='{ altInput: true, altFormat: "d F Y" }' name="resident_dob"
                                               v-model="appForm.resident_dob" @update='appForm.resident_dob = $event'
                                               required/>
                                </label>

                                <!-- Resident Nationality -->
                                <label for="resident_nationality">
                                    Nationality *
                                    <select name="resident_nationality" v-model="appForm.resident_nationality" required
                                            v-on:change="roomInfo($event)" required>
                                        <option v-for="country in countries" v-bind:value="country.nationality">
                                            {{ country.nationality }}
                                        </option>
                                    </select>
                                </label>

                                <!-- Resident Mobile phone number -->
                                <label for="resident_phone_mobile">
                                    Telephone (Mobile) *
                                    <input type="text" name="resident_phone_mobile"
                                           v-model="appForm.resident_phone_mobile"
                                           required>
                                </label>

                                <!-- Resident Email Address -->
                                <label for="resident_email">
                                    Email Address *
                                    <input type="text" name="resident_email" v-model="appForm.resident_email" required>
                                </label>

                                <!-- Resident Current Address -->
                                <label for="resident_current_address">
                                    Address where you currently stay *
                                    <input type="text" name="resident_current_address"
                                           v-model="appForm.resident_current_address" required>
                                </label>

                                <!-- Resident Landlord -->
                                <label for="resident_landlord">
                                    Name of rental agent/ landlord
                                    <input type="text" name="resident_landlord" v-model="appForm.resident_landlord"
                                           required>
                                </label>

                                <!-- Resident Landlord work phone number -->
                                <label for="resident_landlord_phone_work">
                                    Landlord's work phone number *
                                    <input type="text" name="resident_landlord_phone_work"
                                           v-model="appForm.resident_landlord_phone_work" required>
                                </label>

                                <!-- Resident Landlord phone number -->
                                <label for="resident_landlord_phone_mobile">
                                    Landlord's mobile phone number *
                                    <input type="text" name="resident_landlord_phone_mobile"
                                           v-model="appForm.resident_landlord_phone_mobile" required>
                                </label>

                                <!-- Resident Study location-->
                                <label for="resident_study_location">
                                    Where will you be studying *
                                    <input type="text" name="resident_study_location"
                                           v-model="appForm.resident_study_location"
                                           required>
                                </label>

                                <!-- Resident Study year -->
                                <label for="resident_study_year">
                                    What year of study will you be in *
                                    <input type="text" name="resident_study_year" v-model="appForm.resident_study_year"
                                           required>
                                </label>

                                <!-- Employer Name -->
                                <label for="resident_gender">
                                    What is your gender *
                                    <select name="resident_gender" v-model="appForm.resident_gender" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Unlisted">Unlisted</option>
                                    </select>
                                </label>

                                <div v-if="appForm.step4" v-html="appForm.step4"></div>

                                <hr class="application-step__line --mt2">

                                <button type="submit" id="step4-save" class="button button--focused --mt2"
                                        v-on:click="submitStep(4)"
                                        v-bind:disabled="loading">
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

                        <!-- START Step 5 -->
                        <button class=" application-step__heading" v-on:click="accordionToggle(5, $event)"
                                ref="accordion5"
                                v-bind:class="{ '--disabled' : showStep <  5}" data-accordion="5">Step 5: Details of the
                            premises
                        </button>
                        <div class="application-step__content">

                            <!-- Unit Location -->
                            <label for="unit_location">
                                Unit Location *
                                <select name="unit_location" v-model="appForm.unit_location" required>
                                    <option></option>
                                    <option v-for="location in locations" v-bind:value="location.id">
                                        {{location.name}}
                                    </option>
                                </select>
                            </label>

                            <!-- Unit Type -->
                            <label for="unit_type">
                                Unit Type *
                                <select name="unit_type" v-model="appForm.unit_type" required
                                        v-on:change="roomInfo($event)">
                                    <option></option>
                                    <option v-for="unit in unitTypes" v-bind:value="unit.id"
                                            v-bind:data-description="unit.description"
                                            v-if="appForm.unit_location == unit.location_id">
                                        {{ unit.name }} - R{{ unit.cost }}
                                    </option>
                                </select>
                            </label>

                            <p v-show="unitTypeInfo">
                                Unit Description: <br>
                                {{ unitTypeInfo }}
                            </p>

                            <!-- Unit Lease Length -->
                            <label for="unit_lease_length">
                                Unit lease length *
                                <select name="unit_lease_length" v-model="appForm.unit_lease_length" required>
                                    <option value=""></option>
                                    <option value="3">3 months</option>
                                    <option value="6">6 months</option>
                                    <option value="9">9 months</option>
                                    <option value="12">12 months</option>

                                </select>
                            </label>

                            <!-- Unit Car parking -->
                            <div class="row column">
                                <input type="checkbox" name="unit_car_parking" id="unit_car_parking"
                                       v-model="appForm.unit_car_parking"><label for="unit_car_parking">Vehicle
                                Parking bay</label>
                            </div>

                            <!-- Unit Bike parking -->

                            <div class="row column">
                                <input type="checkbox" name="unit_bike_parking" id="unit_bike_parking"
                                       v-model="appForm.unit_bike_parking"><label for="unit_bike_parking">Motorcycle
                                Parking Bay</label>
                            </div>

                            <!-- Unit TV -->
                            <div class="row column">
                                <input type="checkbox" name="unit_tv" id="unit_tv"
                                       v-model="appForm.unit_tv"><label for="unit_tv">10 Channel DSTV Bouquet</label>
                            </div>

                            <!-- Unit Storeroom -->
                            <div class="row column">
                                <input type="checkbox" name="unit_storeroom" id="unit_storeroom"
                                       v-model="appForm.unit_storeroom"><label for="unit_storeroom">Storeroom</label>
                                <br/>
                                <br/>
                            </div>

                            <!-- Unit occupation date -->
                            <label for="unit_occupation_date">
                                Unit Occupation Date *
                                <Flatpickr :options='{ altInput: true, altFormat: "d F Y" }' name="unit_occupation_date"
                                           v-model="appForm.unit_occupation_date"
                                           @update='appForm.unit_occupation_date = $event' required/>
                            </label>

                            <div v-if="appForm.step5" v-html="appForm.step5"></div>

                            <hr class="application-step__line --mt2">

                            <button type="submit" id="step5-save" class="button button--focused  --mt2"
                                    v-on:click="submitStep(5)"
                                    v-bind:disabled="loading">
                    <span v-if="loading">
                        <loading></loading>
                    </span>
                                <span v-else>
                        Save and continue
                    </span>
                            </button>

                        </div>
                        <!-- END Step 5 -->

                        <!-- START Step 6 -->
                        <button class="application-step__heading" v-on:click="accordionToggle(6, $event)"
                                ref="accordion6"
                                v-bind:class="{ '--disabled' : showStep <  6}" data-accordion="6">Step 6: General
                            Details
                        </button>
                        <div class="application-step__content">
                            <!-- Judgements -->

                            <div class="row column">
                                <input type="checkbox" name="judgements" id="judgements"
                                       v-model="appForm.judgements"><label for="judgements">Judgements</label>
                            </div>

                            <!-- Unit Storeroom -->
                            <label for="judgements_details">
                                Judgement Details
                                <textarea name="judgements_details" v-model="appForm.judgements_details"></textarea>
                            </label>

                            <div v-if="appForm.step6" v-html="appForm.step6"></div>

                            <hr class="application-step__line --mt2">

                            <button type="submit" id="step6-save" class="button button--focused"
                                    v-on:click="submitStep(6)"
                                    v-bind:disabled="loading">
                    <span v-if="loading">
                        <loading></loading>
                    </span>
                                <span v-else>
                        Save and continue
                    </span>
                            </button>
                        </div>
                        <!-- END Step 6 -->

                        <!-- START Step 7 -->
                        <button class="application-step__heading" v-on:click="accordionToggle(7, $event)"
                                ref="accordion7"
                                v-bind:class="{ '--disabled' : showStep <  7}" data-accordion="7">Step 7: Required
                            supporting
                            documents
                        </button>
                        <div class="application-step__content">

                            <!-- Resident ID -->
                            <label for="resident_id">
                                Resident ID *
                                <div id="resident_id" name="resident_id" class="dropzone"></div>
                            </label>

                            <!-- Resident Study permit -->
                            <label for="resident_study_permit">
                                Resident Study Permit *
                                <div id="resident_study_permit" name="resident_study_permit" class="dropzone"></div>
                            </label>

                            <!-- Resident Acceptance -->
                            <label for="resident_acceptance">
                                Resident Acceptance *
                                <div id="resident_acceptance" name="resident_acceptance" class="dropzone"></div>
                            </label>

                            <!-- Resident Financial Aid -->
                            <label for="resident_financial_aid">
                                Resident Financial Aid *
                                <div id="resident_financial_aid" name="resident_financial_aid" class="dropzone"></div>
                            </label>

                            <!-- Leaseholder ID -->
                            <label for="leaseholder_id">
                                Leaseholder's ID *
                                <div id="leaseholder_id" name="leaseholder_id" class="dropzone"></div>
                            </label>

                            <!-- Leaseholder Address proof -->
                            <label for="leaseholder_address_proof">
                                Leaseholder's Proof of Address *
                                <div id="leaseholder_address_proof" name="leaseholder_address_proof"
                                     class="dropzone"></div>
                            </label>

                            <!-- Leaseholder's payslip -->
                            <label for="leaseholder_payslip">
                                Leaseholder's payslip *
                                <div id="leaseholder_payslip" name="leaseholder_payslip" class="dropzone"></div>
                            </label>

                            <div v-if="appForm.step7" v-html="appForm.step7"></div>

                            <hr class="application-step__line --mt2">

                            <button type="submit" id="step7-save" class="button button--focused --mt2"
                                    v-on:click="submitStep(7)" v-bind:disabled="loading">
                    <span v-if="loading">
                        <loading></loading>
                    </span>
                                <span v-else>
                        Save and continue
                    </span>
                            </button>

                        </div>
                        <!-- END Step 7 -->

                        <!-- START Step 8 -->
                        <button class="application-step__heading" v-on:click="accordionToggle(8, $event)"
                                ref="accordion8"
                                v-bind:class="{ '--disabled' : showStep <  8}" data-accordion="8">Step 8: Comments
                        </button>
                        <div class="application-step__content">
                            <!-- Comments -->
                            <label for="comments">
                                Comments
                                <textarea name="comments" v-model="appForm.comments"></textarea>
                            </label>

                            <!-- Confirm -->

                            <div class="row column">
                                <input type="checkbox" name="confirm" id="confirm"
                                       v-model="appForm.confirm"><label for="confirm">Confirm</label>
                                <input type="hidden" name="confirm_time" v-model="appForm.confirm_time">
                            </div>


                            <div v-if="appForm.step8" v-html="appForm.step8"></div>

                            <button type="submit" id="step8-save" class="button button--focused --mt2"
                                    v-on:click="submitStep(8)" v-bind:disabled="loading">
                                    <span v-if="loading">
                                        <loading></loading>
                                    </span>
                                <span v-else>
                                        Save
                                    </span>
                            </button>


                        </div>
                        <!-- END Step 8 -->

                    </div>

                </form>

            </div>
        </div>
        <div class="medium-4 columns">

            <div class="stats-box">
                <div class="row column clearfix">
                    <h3 class="stats-box__header --focused --mt0 --mb0">Actions</h3>
                </div>
                <div class="row column">
                    <p>Once complete, you can submit this application for approval. Alternatively, you can cancel this
                        application.</p>
                </div>
                <div class="row column">

                    <button id="cancel-application" class="button button--cancel --expanded"
                            v-on:click="submitForReview()" v-bind:disabled="loading">
                        Submit
                    </button>
                    <button id="cancel-application" class="button button--cancel --expanded"
                            v-on:click="submitForCancel()" v-bind:disabled="loading">
                        Cancel
                    </button>


                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import {Data} from '../data.js';

    import VueFlatpickr from 'vue-flatpickr';

    Vue.use(VueFlatpickr);


    export default {
        props: ['step', 'formApplicationId', 'propLocations', 'propUnitTypes', 'applicationFormData'],
        data: () => {
            let appForm = {
                // Step 1
                step1: '',
                myForm: '',
                first_name: '',
                last_name: '',
                email: '',
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
                step2: '',
                current_property_owner: false,
                rental_time: '',
                rental_amount: '',
                rental_name: '',
                rental_phone_home: '',
                rental_phone_mobile: '',
                // Step 3
                step3: '',
                selfemployed: false,
                occupation: '',
                current_monthly_expenses: '',
                employer_name: '',
                employer_company: '',
                employer_phone_work: '',
                employer_email: '',
                employer_salary: '',
                // Step 4
                step4: '',
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
                step5: '',
                unit_location: '',
                unit_type: '',
                unit_lease_length: '',
                unit_car_parking: false,
                unit_bike_parking: false,
                unit_tv: false,
                unit_storeroom: false,
                unit_occupation_date: '',
                // Step 6
                step6: '',
                judgements: false,
                judgements_details: '',
                // Step 7
                step7: '',
                resident_id: '',
                resident_study_permit: '',
                resident_acceptance: '',
                resident_financial_aid: '',
                leaseholder_id: '',
                leaseholder_address_proof: '',
                leaseholder_payslip: '',
                // Step 8
                comments: '',
                confirm: '',
                confirm_time: '',
            };
            let formAction = "/step-1";
            return {
                appForm: appForm,
                locations: [],
                unitTypes: [],
                formAction: formAction,
                showStep: '',
                unitTypeInfo: '',
                loading: false,
                countries: ''
            };
        },
        mounted() {
            this.showStep = this.step;

            // Toggle the accordion based on the parameter passed
            document.querySelector('[data-accordion="' + this.step + '"]').click();

            // Setup the countries which contains the nationalities
            // Instantiate our data class
            let getData = new Data;

            this.countries = getData.getCountries();
            this.locations = JSON.parse(this.propLocations);
            this.unitTypes = JSON.parse(this.propUnitTypes);
            this.appForm = JSON.parse(this.applicationFormData);

            // Add dropzones
            let residentIdDropzone = new Dropzone("#resident_id", {url: "/documents/application"});
            residentIdDropzone.on('sending', (file, xhr, data) => {
                data.append("document_type", "resident_id");
                data.append("id", this.appForm.id);
            });
            residentIdDropzone.on('addedfile', () => {
                if (residentIdDropzone.files[1] != null) {
                    residentIdDropzone.removeFile(residentIdDropzone.files[0]);
                }
            });

            let residentStudyDropzone = new Dropzone("#resident_study_permit", {url: "/documents/application"});
            residentStudyDropzone.on('sending', (file, xhr, data) => {
                data.append("document_type", "resident_study_permit");
                data.append("id", this.appForm.id);
            });

            let residentAcceptanceDropzone = new Dropzone("#resident_acceptance", {url: "/documents/application"});
            residentAcceptanceDropzone.on('sending', (file, xhr, data) => {
                data.append("document_type", "resident_acceptance");
                data.append("id", this.appForm.id);
            });

            let residentFinacialAidDropzone = new Dropzone("#resident_financial_aid", {url: "/documents/application"});
            residentFinacialAidDropzone.on('sending', (file, xhr, data) => {
                data.append("document_type", "resident_financial_aid");
                data.append("id", this.appForm.id);
            });

            let leaderHolderIdDropzone = new Dropzone("#leaseholder_id", {url: "/documents/application"});
            leaderHolderIdDropzone.on('sending', (file, xhr, data) => {
                data.append("document_type", "leaseholder_id");
                data.append("id", this.appForm.id);
            });

            let leaseholderAddressDropzone = new Dropzone("#leaseholder_address_proof", {url: "/documents/application"});
            leaseholderAddressDropzone.on('sending', (file, xhr, data) => {
                data.append("document_type", "leaseholder_address_proof");
                data.append("id", this.appForm.id);
            });

            let leaseholderPaySlipDropzone = new Dropzone("#leaseholder_payslip", {url: "/documents/application"});
            leaseholderPaySlipDropzone.on('sending', (file, xhr, data) => {
                data.append("document_type", "leaseholder_payslip");
                data.append("id", this.appForm.id);
            });

        },
        methods: {

            accordionToggle: function (step, event) {
                event.preventDefault();

                // We only want interaction on steps which have been completed and saved and the current field.
                if (this.showStep >= step) {
                    let selectedElement = event.target;

                    selectedElement.classList.toggle("application-step__heading--active");
                    selectedElement.nextElementSibling.classList.toggle("application-step__content--active");
                }
            },

            update (val) {
                this.msg = val
                console.log("Update", val);
            },

            roomInfo: function (event) {
                let sel = event.target;
                if (typeof sel.options[sel.selectedIndex] !== "undefined") {
                    this.unitTypeInfo = sel.options[sel.selectedIndex].getAttribute('data-description');
                } else {
                    this.unitTypeInfo = '';
                }
            },

            submitForReview: function () {
                this.loading = true;
                var stepMessage = 'submit';

                if (this.appForm.confirm == null || this.appForm.confirm == false || this.appForm.confirm == 0) {
                    swal({
                        title: "Error!",
                        text: "Please complete all steps of the Application and confirm before you can submit for review.",
                        type: "error",
                        confirmButtonText: "Ok"
                    });
                } else {

                    this.$http.post(
                        '/application-form/' + this.formApplicationId + '/submit',
                        JSON.stringify(this.formatStepData('submit'))
                    ).then((response) => {
                        swal({
                            title: "You're Done!",
                            text: "Thank your for your application submission. We will contact you will the results of your application review.",
                            type: "success",
                            confirmButtonText: "Ok",
                            allowOutsideClick: true
                        }, function () {
                            window.location.href = '/dashboard';
                        });
                        this.loading = false;
                        // If we are successful, there might not be any message to say so let's set it to default.
                        this.appForm[stepMessage] = 'Thank you. Your Application has been sent for review.';
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
                        this.appForm[stepMessage] = errorMessage.replace(/\r/g, "<br/>");
                        swal({
                            title: "Error!",
                            text: errorMessage,
                            type: "error",
                            confirmButtonText: "Ok"
                        });

                        this.loading = false;
                    });
                }
            },

            submitForCancel: function (event) {
                this.loading = true;
                var stepMessage = 'cancel';

                this.$http.get(
                    '/application-form/' + this.formApplicationId + '/cancel'
                ).then((response) => {
                    swal({
                        title: "Application Cancelled",
                        text: "Your application has been successfully cancelled.",
                        type: "success",
                        confirmButtonText: "Ok",
                    }, function () {
                        window.location.href = '/dashboard';
                    });
                    this.loading = false;
                    // If we are successful, there might not be any message to say so let's set it to default.
                    this.appForm[stepMessage] = 'Thank you. Your Application has been cancelled.';
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
                    this.appForm[stepMessage] = errorMessage.replace(/\r/g, "<br/>");
                    swal({
                        title: "Error!",
                        text: errorMessage,
                        type: "error",
                        confirmButtonText: "Ok"
                    });

                    this.loading = false;
                });
            },

            submitStep: function (step) {
                this.loading = true;
                var stepMessage = 'step' + step;

                this.$http.post(
                    '/step-' + step + '/' + this.formApplicationId,
                    JSON.stringify(this.formatStepData(step))
                ).then((response) => {
                    // 8 Is the last step.
                    if (step != 8) {
                        this.showStep = step + 1;

                        // Proceed to the next part of the accordion.
                        let currentAccordion = 'accordion' + step;
                        this.$refs[currentAccordion].click();

                        let nextAccordion = 'accordion' + (step + 1);
                        this.$refs[nextAccordion].click();
                    }else{
                        swal({
                                title: "Application Form Complete",
                                text: "You have successfully completed the Application Form, \n You can now submit it for processing.",
                                type: "success",
                                confirmButtonText: "Ok",
                                allowOutsideClick: true
                            },
                            function(isConfirm) {

                            });
                    }
                    this.loading = false;

                    // If we are successful, there might not be any message to say so let's set it to default.
                    this.appForm[stepMessage] = '';

                    if (response.body.error == "Unit Not Available" && step == 5) {
                        swal({
                            title: "Warning",
                            text: response.body.message,
                            type: "warning",
                            confirmButtonText: "Ok",
                            allowOutsideClick: true
                        });
                    }
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
                    this.appForm[stepMessage] = errorMessage.replace(/\r/g, "<br/>");
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

            formatStepData(step) {
                switch (step) {
                    case 1:
                        return {
                            first_name: this.appForm.first_name,
                            last_name: this.appForm.last_name,
                            email: this.appForm.email,
                            sa_id_number: this.appForm.sa_id_number,
                            passport_number: this.appForm.passport_number,
                            dob: this.appForm.dob,
                            nationality: this.appForm.nationality,
                            phone_mobile: this.appForm.phone_mobile,
                            phone_home: this.appForm.phone_home,
                            phone_work: this.appForm.phone_work,
                            current_address: this.appForm.current_address,
                            marital_status: this.appForm.marital_status,
                            married_type: this.appForm.married_type,
                        };
                        break;
                    case 2:
                        return {
                            current_property_owner: this.appForm.current_property_owner,
                            rental_time: this.appForm.rental_time,
                            rental_amount: this.appForm.rental_amount,
                            rental_name: this.appForm.rental_name,
                            rental_phone_home: this.appForm.rental_phone_home,
                            rental_phone_mobile: this.appForm.rental_phone_mobile,
                        };
                        break;
                    case 3:
                        return {
                            selfemployed: this.appForm.selfemployed,
                            occupation: this.appForm.occupation,
                            current_monthly_expenses: this.appForm.current_monthly_expenses,
                            employer_name: this.appForm.employer_name,
                            employer_company: this.appForm.employer_company,
                            employer_phone_work: this.appForm.employer_phone_work,
                            employer_email: this.appForm.employer_email,
                            employer_salary: this.appForm.employer_salary,
                        };
                        break;
                    case 4:
                        return {
                            resident_first_name: this.appForm.resident_first_name,
                            resident_last_name: this.appForm.resident_last_name,
                            resident_sa_id_number: this.appForm.resident_sa_id_number,
                            resident_passport_number: this.appForm.resident_passport_number,
                            resident_dob: this.appForm.resident_dob,
                            resident_nationality: this.appForm.resident_nationality,
                            resident_phone_mobile: this.appForm.resident_phone_mobile,
                            resident_email: this.appForm.resident_email,
                            resident_current_address: this.appForm.resident_current_address,
                            resident_landlord: this.appForm.resident_landlord,
                            resident_landlord_phone_work: this.appForm.resident_landlord_phone_work,
                            resident_landlord_phone_mobile: this.appForm.resident_landlord_phone_mobile,
                            resident_study_location: this.appForm.resident_study_location,
                            resident_study_year: this.appForm.resident_study_year,
                            resident_gender: this.appForm.resident_gender,
                        };
                        break;
                    case 5:
                        return {
                            unit_location: this.appForm.unit_location,
                            unit_type: this.appForm.unit_type,
                            unit_lease_length: this.appForm.unit_lease_length,
                            unit_car_parking: this.appForm.unit_car_parking,
                            unit_bike_parking: this.appForm.unit_bike_parking,
                            unit_tv: this.appForm.unit_tv,
                            unit_storeroom: this.appForm.unit_storeroom,
                            unit_occupation_date: this.appForm.unit_occupation_date,
                        };
                        break;
                    case 6:
                        return {
                            judgements: this.appForm.judgements,
                            judgements_details: this.appForm.judgements_details,
                        };
                        break;
                    case 7:
                        return {
                            resident_id: this.appForm.resident_id,
                            resident_study_permit: this.appForm.resident_study_permit,
                            resident_acceptance: this.appForm.resident_acceptance,
                            resident_financial_aid: this.appForm.resident_financial_aid,
                            leaseholder_address_proof: this.appForm.leaseholder_address_proof,
                            leaseholder_payslip: this.appForm.leaseholder_payslip,
                        };
                        break;
                    case 8:
                        return {
                            comments: this.appForm.comments,
                            confirm: this.appForm.confirm,
                            confirm_time: this.appForm.confirm_time,
                        };
                    case 'submit':
                        return {

                            first_name: this.appForm.first_name,
                            last_name: this.appForm.last_name,
                            email: this.appForm.email,
                            sa_id_number: this.appForm.sa_id_number,
                            passport_number: this.appForm.passport_number,
                            dob: this.appForm.dob,
                            nationality: this.appForm.nationality,
                            phone_mobile: this.appForm.phone_mobile,
                            phone_home: this.appForm.phone_home,
                            phone_work: this.appForm.phone_work,
                            current_address: this.appForm.current_address,
                            marital_status: this.appForm.marital_status,
                            married_type: this.appForm.married_type,

                            current_property_owner: this.appForm.current_property_owner,
                            rental_time: this.appForm.rental_time,
                            rental_amount: this.appForm.rental_amount,
                            rental_name: this.appForm.rental_name,
                            rental_phone_home: this.appForm.rental_phone_home,
                            rental_phone_mobile: this.appForm.rental_phone_mobile,

                            selfemployed: this.appForm.selfemployed,
                            occupation: this.appForm.occupation,
                            current_monthly_expenses: this.appForm.current_monthly_expenses,
                            employer_name: this.appForm.employer_name,
                            employer_company: this.appForm.employer_company,
                            employer_phone_work: this.appForm.employer_phone_work,
                            employer_email: this.appForm.employer_email,
                            employer_salary: this.appForm.employer_salary,

                            resident_first_name: this.appForm.resident_first_name,
                            resident_last_name: this.appForm.resident_last_name,
                            resident_sa_id_number: this.appForm.resident_sa_id_number,
                            resident_passport_number: this.appForm.resident_passport_number,
                            resident_dob: this.appForm.resident_dob,
                            resident_nationality: this.appForm.resident_nationality,
                            resident_phone_mobile: this.appForm.resident_phone_mobile,
                            resident_email: this.appForm.resident_email,
                            resident_current_address: this.appForm.resident_current_address,
                            resident_landlord: this.appForm.resident_landlord,
                            resident_landlord_phone_work: this.appForm.resident_landlord_phone_work,
                            resident_landlord_phone_mobile: this.appForm.resident_landlord_phone_mobile,
                            resident_study_location: this.appForm.resident_study_location,
                            resident_study_year: this.appForm.resident_study_year,
                            resident_gender: this.appForm.resident_gender,

                            unit_location: this.appForm.unit_location,
                            unit_type: this.appForm.unit_type,
                            unit_lease_length: this.appForm.unit_lease_length,
                            unit_car_parking: this.appForm.unit_car_parking,
                            unit_bike_parking: this.appForm.unit_bike_parking,
                            unit_tv: this.appForm.unit_tv,
                            unit_storeroom: this.appForm.unit_storeroom,
                            unit_occupation_date: this.appForm.unit_occupation_date,

                            judgements: this.appForm.judgements,
                            judgements_details: this.appForm.judgements_details,

                            resident_id: this.appForm.resident_id,
                            resident_study_permit: this.appForm.resident_study_permit,
                            resident_acceptance: this.appForm.resident_acceptance,
                            resident_financial_aid: this.appForm.resident_financial_aid,
                            leaseholder_address_proof: this.appForm.leaseholder_address_proof,
                            leaseholder_payslip: this.appForm.leaseholder_payslip,

                            comments: this.appForm.comments,
                            confirm: this.appForm.confirm,
                            confirm_time: this.appForm.confirm_time

                        };

                        break;
                }
            }

        }
    }


</script>