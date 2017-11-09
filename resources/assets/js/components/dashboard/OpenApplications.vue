<template>

    <div class="open-applications">

        <div class="row table">

            <div class="small-12 columns">
                <div class="table__row table__row--add">
                    <!-- Row Title -->
                    <div class="accordion__heading accordion__heading--add --white">
                        <h4 class="--white">Open Applications</h4>
                    </div>

                </div>
            </div>

            <template v-for="(application, index) in openApplications">
                <div class="small-12 columns">
                    <div class="table__row" :class="{ even: isEven(index), first: index == 0, last: index == openApplications.length -1 }">

                        <!-- Row Title -->
                        <a v-bind:href="getUrl(application.id)">

                            <button class="accordion__heading">
                                {{application.created_at }} - {{application.user.first_name }} {{application.user.last_name }} - {{application.location.name }}
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
    props: ['propOpenApplications'],
    data(){
        return {
            openApplications: []
        }
    },
    mounted() {
        this.openApplications = JSON.parse(this.propOpenApplications);
        console.log(this.openApplications);
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