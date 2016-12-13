<template>

    <div class="pending-applications">

        <div class="row table">

            <div class="small-12 columns">
                <div class="table__row table__row--add">
                    <!-- Row Title -->
                    <div class="accordion__heading accordion__heading--add --white">
                        <h4 class="--white">Pending Applications</h4>
                    </div>

                </div>
            </div>

            <template v-for="(application, index) in pendingApplications">
                <div class="small-12 columns">
                    <div class="table__row" :class="{ even: isEven(index), first: index == 0, last: index == pendingApplications.length -1 }">

                        <!-- Row Title -->
                        <a v-bind:href="getUrl(application.id)">
                            <button class="accordion__heading">
                                {{application.user.first_name }} {{application.user.last_name}}
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
        props: ['propPendingApplications'],
        data(){
            return {
                pendingApplications: []
            }
        },
        mounted() {
            this.pendingApplications = JSON.parse(this.propPendingApplications);
            console.log('pendingApplications', this.pendingApplications);
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