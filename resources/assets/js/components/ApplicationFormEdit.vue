<template>
    <div>
        Edit form here. {{msg}}
    </div>
</template>
<script>
    export default{
        props: ['editableObject'],
        data(){
            return{
                msg:'hello vue' + Math.floor((Math.random() * 100) + 1)
            }
        },
        mounted() {
            console.log("Editable object is", this.editableObject);
            this.doUpdate();
        },
        methods: {
            doUpdate: function() {
                this.$emit('update', 'woof woof');
            }
        }
    }
</script>
