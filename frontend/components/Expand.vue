<template>
  <transition
    @enter="handleEnter"
    @leave="handleLeave"
    @enterCancelled="handleCancel"
    @leaveCancelled="handleCancel"
  >
    <div v-show="show" class="expand-wrapper">
      <slot></slot>
    </div>
  </transition>
</template>

<script>
import anime from "animejs";

export default {
  props: {
    show: {
      type: Boolean,
      required: true
    }
  },
  watch: {},
  methods: {
    handleEnter(el, done) {
      this.done = done;
      if (this.animation) {
        return;
      }
      const height = el.offsetHeight;
      this.animation = anime({
        targets: el,
        height: [0, height],
        duration: 400,
        easing: "easeOutElastic(1, .5)",
        complete: () => this.complete(el)
      });
    },
    handleLeave(el, done) {
      this.done = done;
      if (this.animation) {
        return;
      }
      this.animation = anime({
        targets: el,
        height: 0,
        duration: 400,
        easing: "easeInElastic(1, .5)",
        complete: () => this.complete(el)
      });
    },
    handleCancel() {
      this.done();
      this.done = null;
      this.animation.reverse();
    },
    complete(el) {
      this.animation = null;
      el.style.removeProperty("height");
      this.done();
      this.done = null;
      this.animation = null;
    }
  }
};
</script>

<style scoped>
.expand-wrapper {
  overflow-y: hidden;
}
</style>
