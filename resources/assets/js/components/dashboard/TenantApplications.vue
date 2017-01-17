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
                    <div class="table__row" :class="{ even: isEven(index), first: index == 0, last: index == applications.length -1 }">

                        <button class="accordion__heading">
                            {{getTime(application.created_at)}} <span class="float-right --caps-first">{{application.status}}</span>
                        </button>

                    </div>
                </div>
            </template>

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
                return '/application/'+id+'/review';
            },

            getTime: function(time){
                return moment(time).format("dddd, MMMM Do YYYY, h:mm:ss a");
            }
        }
    }

</script>