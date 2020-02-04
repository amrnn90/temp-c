<template>
  <div @click="handleClick">
      {{message.content}}
  </div>
</template>

<script>
export default {
    props: ['message'],
    methods: {
        handleClick() {
            clearTimeout(this.timeoutHandle);
            this.dismiss();
        },
        dismiss() {
            this.$emit('dismiss');
        }
    },
    created() {
        this.timeoutHandle = setTimeout(() => {
            this.dismiss();
        }, 4000);
    }

}
</script>

<style>

</style>