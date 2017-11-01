<template>
  <button class="button button--small support-button" v-on:click="supportSubmit">Log Issue</button>
</template>

<script>
  export default {
    mounted() {},
    methods: {
      supportSubmit: function () {
        swal({
          title: "What happened?",
          text: "Please tell us what you were doing when you encounted the error.",
          showCancelButton: true,
          confirmButtonText: "Submit",
          closeOnConfirm: false,
          type: 'input',
          animation: "slide-from-top"
        }, (inputValue) => {
          this.$http.post('/submit-admin-issue', JSON.stringify({'user_issue': inputValue, 'location' : window.location.href })
          ).then((response) => {
            swal("Thanks!",
              "Your issue has been logged.",
              "success");
          }, (err) => {
              let errorMessage = '';
              if (err.body.message) {
                  errorMessage = err.body.message;
              }
              swal({
                  title: "Error!",
                  text: errorMessage,
                  type: "error",
                  confirmButtonText: "Ok"
              });
          });
        });

      }
    }
  }
</script>
