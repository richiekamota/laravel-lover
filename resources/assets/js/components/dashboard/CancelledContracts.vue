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

            <div class="small-12 columns" v-if="cancelledContracts.length === 0">
                <div class="table__row even last">

                    <button class="accordion__heading">
                        No contracts cancelled...
                    </button>

                </div>
            </div>

            <template v-for="(cancelledContract, index) in cancelledContracts">
                <div class="small-12 columns">
                    <div class="table__row" :class="{ even: isEven(index), first: index == 0, last: index == cancelledContracts.length -1 }">

                        <!-- Row Title -->
                        <div v-show="cancelledContract.sa_id_number == cancelledContract.resident_sa_id_number || cancelledContract.passport_number == cancelledContract.resident_passport_number">
                            <button class="accordion__heading">
                                {{cancelledContract.created_at}} - {{cancelledContract.first_name}} {{cancelledContract.last_name}}
                            </button>
                        </div>

                        <div v-show="cancelledContract.sa_id_number != cancelledContract.resident_sa_id_number || cancelledContract.passport_number != cancelledContract.resident_passport_number">
                            <button class="accordion__heading">
                                {{cancelledContract.created_at}} - <strong>Leaseholder:</strong> {{cancelledContract.first_name}} {{cancelledContract.last_name}}  <strong>Tenant:</strong> {{cancelledContract.resident_first_name}} {{cancelledContract.resident_last_name}}
                            </button>
                        </div>
                    </div>
                </div>
            </template>

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
    },
    methods: {

        isEven: function (n) {
            return n % 2 == 0;
        },
    }
}

</script>