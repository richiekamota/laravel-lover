<template>

    <div class="contracts --mt2">

        <div class="row table">

            <div class="small-12 columns">
                <div class="table__row table__row--add">
                    <!-- Row Title -->
                    <div class="accordion__heading accordion__heading--add --white">
                        <h4 class="--white">Contracts</h4>
                    </div>

                </div>
            </div>

            <template v-for="(contract, index) in contracts">
                <div class="small-12 columns">
                    <div class="table__row" :class="{ even: isEven(index), first: index == 0, last: index == contracts.length -1 }">

                        <a v-bind:href="getUrl(contract.secure_link)" v-if="!contract.approved && contract.status != 'cancelled'">
                            <button class="accordion__heading">
                                {{getTime(contract.created_at)}} <span class="float-right --caps-first">{{contract.status}}</span>
                            </button>
                        </a>

                        <a v-bind:href="getDownloadUrl(contract.document_id)" v-else-if="contract.approved">
                            <button class="accordion__heading">
                                {{getTime(contract.created_at)}} <span class="float-right --caps-first">{{contract.status}}</span>
                            </button>
                        </a>

                        <span v-else>
                            <button class="accordion__heading">
                                {{getTime(contract.created_at)}} <span class="float-right --caps-first">{{contract.status}}</span>
                            </button>
                        </span>


                    </div>
                </div>
            </template>


                <div class="small-12 columns" v-if="contracts.length === 0">
                    <div class="table__row even last">

                            <button class="accordion__heading">
                                No contracts found...
                            </button>



                    </div>
                </div>


        </div>

    </div>

</template>
<script>

    const moment = require('moment');

    export default {
        props: ['propContracts'],
        data(){
            return {
                contracts: []
            }
        },
        mounted() {
            this.contracts = JSON.parse(this.propContracts);
            
        },
        methods: {

            isEven: function (n) {
                return n % 2 == 0;
            },

            getUrl: function (secure_link) {
                return "/contracts/secure/"+secure_link;
            },

            getDownloadUrl: function(document_id){
                return "/documents/" + document_id;
            },

            getTime: function(time){
                return moment(time).format("dddd, MMMM Do YYYY, h:mm:ss a");
            },

            isApproved: function(approved){
                return (approved) ? "Approved" : "Pending" ;
            }
        }
    }

</script>