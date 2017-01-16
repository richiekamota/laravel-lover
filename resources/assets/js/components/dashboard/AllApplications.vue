<template>

    <div class="open-applications --mt2">

        <div class="row table">

            <div class="small-12 columns">
                <div class="table__row table__row--add">
                    <!-- Row Title -->
                    <div class="accordion__heading accordion__heading--add --white">
                        <h4 class="--white">Approved / Declined Applications</h4>
                    </div>

                </div>
            </div>

            <template v-for="(application, index) in allApplications">
                <div class="small-12 columns">
                    <div class="table__row" :class="{ even: isEven(index), first: index == 0, last: index == allApplications.length -1 }">

                        <!-- Row Title -->
                        <a v-bind:href="getUrl(application.id)">

                            <button class="accordion__heading">
                                {{application.created_at }} - {{application.user.first_name }} {{application.user.last_name }} <span class="float-right --caps-first">{{application.status}}</span>
                            </button>
                        </a>

                    </div>
                </div>
            </template>

        </div>

    </div>

</template>
<script>

    export default {
        props: ['propAllApplications'],
        data(){
            return {
                allApplications: []
            }
        },
        mounted() {
            this.allApplications = JSON.parse(this.propAllApplications);
            console.log('allApplications', this.allApplications);
        },
        methods: {

            isEven: function (n) {
                return n % 2 == 0;
            },

            getUrl: function (id) {
                return '/application/'+id+'/review';
            }

        }
    }

</script>