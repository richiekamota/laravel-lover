<template>

    <div class="cancelled-contracts --mt2">

        <div class="row table">

            <div class="small-12 columns">
                <div class="table__row table__row--add">
                    <!-- Row Title -->
                    <div class="accordion__heading accordion__heading--add --white">
                        <h4 class="--white">Cancelled Contracts</h4>
                    </div>

                </div>
            </div>

            <template v-for="(cancelledContract, index) in cancelledContracts">
                <div class="small-12 columns">
                    <div class="table__row" :class="{ even: isEven(index), first: index == 0, last: index == cancelledContract.length -1 }">

                        <!-- Row Title -->

                            <button class="accordion__heading">
                                <!-- cancelledContract.application.user.first_name -->
                                {{cancelledContract.application_id}}
                            </button>
                    </div>
                </div>
            </template>

            <div class="small-12 columns" v-if="cancelledContracts.length === 0">
                    <div class="table__row even last">

                        <button class="accordion__heading">
                            No contracts cancelled...
                        </button>
                    </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    props: ['propCancelledContracts'],
    data(){
        return {
            cancelledContracts: []
        }
    },
    mounted() {
        this.cancelledContracts = JSON.parse(this.propCancelledContracts);
        console.log(this.propCancelledContracts);
    },
    methods: {

        isEven: function (n) {
            return n % 2 == 0;
        },
    }
}

</script>