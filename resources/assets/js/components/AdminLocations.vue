<template>
    <div>
        <div>this is template body</div>
    </div>

    <div class="row">

    </div>
</template>
<script>

    export default{
        props: ['locations', 'units', 'unitTypes'],
        data(){
            return{
                location: [],
                unit: [],
                unitType:[]
            }
        },
        mounted() {
            console.log("Mounted");
        }
    }

</script>
