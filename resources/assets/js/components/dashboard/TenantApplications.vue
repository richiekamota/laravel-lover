<template>

    <div class="open-applications --mt2">

        <div class="row table">

            <div class="small-12 columns">
                <div class="table__row table__row--add">
                    <!-- Row Title -->
                    <div class="accordion__heading accordion__heading--add --white">
                        <h4 class="--white">Applications</h4>
                    </div>

                </div>
            </div>

            <template v-for="(application, index) in applications">
                <div class="small-12 columns">
                    <div class="table__row"
                         :class="{ even: isEven(index), first: index == 0, last: index == applications.length -1 }"
                         v-if="application.status === 'draft'">

                        <a v-bind:href="getUrl(application.id)">

                            <button class="accordion__heading">
                                {{getTime(application.created_at)}} <span class="float-right --caps-first">{{application.status}}</span>
                            </button>
                        </a>

                    </div>
                    <div class="table__row"
                         :class="{ even: isEven(index), first: index == 0, last: index == applications.length -1 }"
                         v-else-if="application.status != 'draft' && application.status != 'cancelled'" >


                        <a v-bind:href="getReviewUrl(application.id)">

                            <button class="accordion__heading">
                                {{getTime(application.created_at)}} <span class="float-right --caps-first">{{application.status}}</span>
                            </button>
                        </a>



                    </div>
                </div>
            </template>

                <div class="small-12 columns" v-if="applications.length === 0">
                    <div class="table__row even last">


                            <button class="accordion__heading">
                               No applications found...
                            </button>


                    </div>

                </div>


        </div>

    </div>

</template>
<script>

    const moment = require('moment');

    export default {
        props: ['propApplications'],
        data(){
            return {
                applications: []
            }
        },
        mounted() {
            this.applications = JSON.parse(this.propApplications);
            console.log('applications', this.applications);
        },
        methods: {

            isEven: function (n) {
                return n % 2 == 0;
            },

            getUrl: function (id) {
                return '/application-form/' + id + '/edit';
            },

            getReviewUrl: function (id) {
                return '/application-form/' + id;
            },

            getTime: function (time) {
                return moment(time).format("dddd, MMMM Do YYYY, h:mm:ss a");
            },
            warn: function (message, event) {
                // now we have access to the native event
                if (event) event.preventDefault()
                alert(message)
            }
        }
    }

</script>