<template>
    <div class="page-items">
        <div class="row columns">
            <h2 class="--focused">APPLICATION REVIEW</h2>

        </div>

        <div class="row" v-if="application">
            <div class="small-12 medium-12 columns">

                <!-- Application details -->

                <div class="row column">

                    <div class="stats-box">
                        <div class="row column clearfix">
                            <h3 class="stats-box__header --focused --mt0">Application status</h3>
                        </div>
                        <div class="row">
                            <div class="column">
                                Current status:
                            </div>
                            <div class="column --caps-first">
                                {{application.status}}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- User details -->

                <div class="row column" v-if="application.user">

                    <div class="accordion__heading accordion__heading--add">
                        <h4 id="heading-user-details" class="--white">User Details</h4>
                    </div>

                    <div class="table__row table__row--padded">
                        <div class="row">
                            <div class="column">
                                First name:
                            </div>
                            <div class="column">
                                {{application.user.first_name}}
                            </div>
                        </div>

                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Last name:
                            </div>
                            <div class="column">
                                {{application.user.last_name}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Email:
                            </div>
                            <div class="column">
                                {{application.user.email}}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Leaseholder details -->

                <div class="row column">

                    <div class="accordion__heading accordion__heading--add">
                        <h4 id="heading-leaseholder" class="--white">Leaseholder details</h4>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Email:
                            </div>
                            <div class="column">
                                {{application.email}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                SA ID:
                            </div>
                            <div class="column">
                                {{application.sa_id_number}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Passport number:
                            </div>
                            <div class="column">
                                {{application.passport_number}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Date of birth:
                            </div>
                            <div class="column">
                                {{toNiceDate(application.dob)}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Nationality:
                            </div>
                            <div class="column">
                                {{application.nationality}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Phone - mobile:
                            </div>
                            <div class="column">
                                {{application.phone_mobile}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Phone - home:
                            </div>
                            <div class="column">
                                {{application.phone_home}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Phone - work:
                            </div>
                            <div class="column">
                                {{application.phone_work}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Current address:
                            </div>
                            <div class="column">
                                {{application.current_address}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Marital status:
                            </div>
                            <div class="column">
                                {{application.marital_status}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Married type:
                            </div>
                            <div class="column">
                                {{application.married_type}}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Leaseholder home ownership -->

                <div class="row column">

                    <div class="accordion__heading accordion__heading--add">
                        <h4 id="heading-leaseholder-home-ownership" class="--white">Leaseholder home ownership</h4>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Current property owner:
                            </div>
                            <div class="column">
                                {{application.current_property_owner ? 'True' : 'False'}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even" v-if="!application.current_property_owner">
                        <div class="row">
                            <div class="column">
                                Rental length:
                            </div>
                            <div class="column">
                                {{application.rental_time}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded" v-if="!application.current_property_owner">
                        <div class="row">
                            <div class="column">
                                Monthly rental amount:
                            </div>
                            <div class="column">
                                {{application.rental_amount}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even" v-if="!application.current_property_owner">
                        <div class="row">
                            <div class="column">
                                Name of the Rental Agent / Landlord:
                            </div>
                            <div class="column">
                                {{application.rental_name}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded" v-if="!application.current_property_owner">
                        <div class="row">
                            <div class="column">
                                Work phone number:
                            </div>
                            <div class="column">
                                {{application.rental_phone_home}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even" v-if="!application.current_property_owner">
                        <div class="row">
                            <div class="column">
                                Mobile phone number:
                            </div>
                            <div class="column">
                                {{application.rental_phone_mobile}}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Employment status -->

                <div class="row column">

                    <div class="accordion__heading accordion__heading--add">
                        <h4 id="heading-employment-status" class="--white">Employment status</h4>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Self employed:
                            </div>
                            <div class="column">
                                {{application.selfemployed ? 'True' : 'False'}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Occupation:
                            </div>
                            <div class="column">
                                {{application.occupation}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Current monthly expenses:
                            </div>
                            <div class="column">
                                {{application.current_monthly_expenses}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Name of company:
                            </div>
                            <div class="column">
                                {{application.employer_company}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Employer's name:
                            </div>
                            <div class="column">
                                {{application.employer_name}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Employer's telephone number:
                            </div>
                            <div class="column">
                                {{application.employer_phone_work}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Employer's email address:
                            </div>
                            <div class="column">
                                {{application.employer_email}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Gross monthly salary before deducations and tax:
                            </div>
                            <div class="column">
                                {{application.employer_salary}}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Resident details -->

                <div class="row column">

                    <div class="accordion__heading accordion__heading--add">
                        <h4 id="heading-resident-details" class="--white">Resident details</h4>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                First name:
                            </div>
                            <div class="column">
                                {{application.resident_first_name}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Last Name:
                            </div>
                            <div class="column">
                                {{application.resident_last_name}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                ID Number:
                            </div>
                            <div class="column">
                                {{application.resident_sa_id_number}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Passport number:
                            </div>
                            <div class="column">
                                {{application.resident_passport_number}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Date Of Birth:
                            </div>
                            <div class="column">
                                {{toNiceDate(application.resident_dob)}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Nationality:
                            </div>
                            <div class="column">
                                {{application.resident_nationality}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Telephone (Mobile):
                            </div>
                            <div class="column">
                                {{application.resident_phone_mobile}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Email Address:
                            </div>
                            <div class="column">
                                {{application.resident_email}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Address where you currently stay:
                            </div>
                            <div class="column">
                                {{application.resident_current_address}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Name of rental agent/ landlord:
                            </div>
                            <div class="column">
                                {{application.resident_landlord}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Landlord's work phone number:
                            </div>
                            <div class="column">
                                {{application.resident_landlord_phone_work}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Landlord's mobile phone number:
                            </div>
                            <div class="column">
                                {{application.resident_landlord_phone_mobile}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Where will you be studying:
                            </div>
                            <div class="column">
                                {{application.resident_study_location}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                What year of study will you be in:
                            </div>
                            <div class="column">
                                {{application.resident_study_year}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                What is your gender:
                            </div>
                            <div class="column">
                                {{application.resident_gender}}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Premises -->

                <div class="row column">

                    <div class="accordion__heading accordion__heading--add">
                        <h4 id="heading-premises" class="--white">Details of the premises</h4>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Unit Location:
                            </div>
                            <div class="column">
                                {{application.unit_location}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Unit Type:
                            </div>
                            <div class="column">
                                {{application.unit_type}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Unit lease length:
                            </div>
                            <div class="column">
                                {{application.unit_lease_length}}
                            </div>
                        </div>
                    </div>

                     <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Room mate name:
                            </div>
                            <div class="column">
                                {{application.unit_room_mate}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Motor vehicle parking bay:
                            </div>
                            <div class="column">
                                {{application.unit_car_parking}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Motorcycle parking bay:
                            </div>
                            <div class="column">
                                {{application.unit_bike_parking}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                DSTV:
                            </div>
                            <div class="column">
                                {{application.unit_tv}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Storeroom
                            </div>
                            <div class="column">
                                {{application.unit_storeroom}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Unit Occupation Date
                            </div>
                            <div class="column">
                                {{toNiceDate(application.unit_occupation_date)}}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- General -->

                <div class="row column">

                    <div class="accordion__heading accordion__heading--add">
                        <h4 id="heading-general" class="--white">General</h4>
                    </div>

                    <div class="table__row table__row--padded">

                        <div class="row">
                            <div class="column">
                                Judgements:
                            </div>
                            <div class="column">
                                {{application.judgements}}
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded even">

                        <div class="row">
                            <div class="column">
                                Judgement Details:
                            </div>
                            <div class="column">
                                {{application.judgements_details}}
                            </div>
                        </div>
                    </div>

                </div>

                <!--Supporting Documents -->

                <div class="row column">

                    <div class="accordion__heading accordion__heading--add">
                        <h4 id="heading-supporting" class="--white">Supporting documents</h4>
                    </div>

                    <div class="table__row table__row--padded even" v-if="application.resident_id">

                        <div class="row">
                            <div class="column">
                                Resident ID:
                            </div>
                            <div class="column">
                                <a v-bind:href="getDocumentDownloadLink(application.resident_id.id)">
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded odd" v-if="application.resident_study_permit">

                        <div class="row">
                            <div class="column">
                                Resident Study Permit:
                            </div>
                            <div class="column">
                                <a v-bind:href="getDocumentDownloadLink(application.resident_study_permit.id)">
                                    Download
                                </a>
                            </div>

                        </div>
                    </div>

                    <div class="table__row table__row--padded even" v-if="application.resident_acceptance">

                        <div class="row">
                            <div class="column">
                                Resident Acceptance:
                            </div>
                            <div class="column">
                                <a v-bind:href="getDocumentDownloadLink(application.resident_acceptance.id)">
                                    Download
                                </a>
                            </div>

                        </div>
                    </div>

                    <div class="table__row table__row--padded odd" v-if="application.resident_financial_aid">

                        <div class="row">
                            <div class="column">
                                Resident Financial Aid:
                            </div>
                            <div class="column">
                                <a v-bind:href="getDocumentDownloadLink(application.resident_financial_aid.id)">
                                    Download
                                </a>
                            </div>

                        </div>
                    </div>

                    <div class="table__row table__row--padded even" v-if="application.leaseholder_id">
                        <div class="row">
                            <div class="column">
                                Leaseholder ID:
                            </div>
                            <div class="column">
                                <a v-bind:href="getDocumentDownloadLink(application.leaseholder_id.id)">
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table__row table__row--padded odd" v-if="application.leaseholder_address_proof">

                        <div class="row">
                            <div class="column">
                                Resident Proof of Address:
                            </div>
                            <div class="column">
                                <a v-bind:href="getDocumentDownloadLink(application.leaseholder_address_proof.id)">
                                    Download
                                </a>
                            </div>

                        </div>
                    </div>

                    <div class="table__row table__row--padded even" v-if="application.leaseholder_payslip">

                        <div class="row">
                            <div class="column">
                                Leaseholder's payslip:
                            </div>
                            <div class="column">
                                <a v-bind:href="getDocumentDownloadLink(application.leaseholder_payslip.id)">
                                    Download
                                </a>
                            </div>

                        </div>
                    </div>


                </div>

                <!-- Comments -->

                <div class="row column">

                    <div class="accordion__heading accordion__heading--add">
                        <h4 id="heading-comments" class="--white">Comments</h4>
                    </div>

                    <div class="table__row table__row--padded last">

                        <div class="row">
                            <div class="column">
                                Comments:
                            </div>
                            <div class="column">
                                {{application.comments}}
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</template>
<script>

    var moment = require('moment');

    export default {
        props: ['propApplication'],
        data(){
            return {
                application: {},
                declineInput: false,
                loading: false
            }
        },
        mounted() {

            this.application = JSON.parse(this.propApplication);
            this.declineInput = false;

        },

        methods: {

            toNiceDate: (date) => {
                return moment(date).format("dddd, MMMM Do YYYY");
            },

            getDocumentDownloadLink: (id) => {

                return '/documents/' + id;
            },

            getApproveUrl: (id) => {
                return "/application/"+id+"/approve";
            },
            getPendingUrl: (id) => {
                return "/application/"+id+"/pending";
            },

            decline: function() {
                this.declineInput = !this.declineInput;
            },

            submitDecline: function(){

                this.loading = true;

                this.$http.post(
                    '/application/' + this.application.id + '/decline',
                    {
                        'reason' : this.declineReason
                    }
                ).then((response) => {

                    this.loading = false;
                    // If the decline call is successful then update the shown application.
                    this.application = response.data.data;

                    swal({
                        title: "Success!",
                        text: "The application has been declined and the applicant has been emailed.",
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