<template>
  <page-backdrop
    v-if="show"
    :transition="{ name: 'modal', duaration: 200, appear: true }"
    @click="handleBackdropClick"
    @escape="dismiss"
  >
    <div class="modal" @click.stop>
      <slot />
    </div>
  </page-backdrop>
</template>

<script>
import PageBackdrop from "./PageBackdrop";
export default {
  components: { PageBackdrop },
  props: {
    dismissOnBackdropClick: { type: Boolean, default: true },
    show: { type: Boolean, default: false }
  },
  data() {
    return {
      scrollbarWidth: null
    };
  },
  methods: {
    dismiss() {
      this.$emit("dismiss");
    },
    handleBackdropClick() {
      if (this.dismissOnBackdropClick) {
        this.dismiss();
      }
    }
  }
};
</script>

<style lang="scss">
.modal {
  flex: 1;
  background: white;
  border-radius: 5px;
  max-width: 600px;
  min-height: 300px;
  overflow: auto;

  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.07), 0 2px 4px rgba(0, 0, 0, 0.07),
    0 4px 8px rgba(0, 0, 0, 0.07), 0 8px 16px rgba(0, 0, 0, 0.07),
    0 16px 32px rgba(0, 0, 0, 0.07), 0 32px 64px rgba(0, 0, 0, 0.07);
}

.modal-enter .modal,
.modal-leave-to .modal {
  // opacity: 0;
  // transform: scale(.8);
  transform: translateY(-30px);
}

.modal-enter-to .modal,
.modal-leave .modal {
  // opacity: 1;
  // transform: scale(1);
  transform: translateY(0);
}

.modal-enter-active .modal,
.modal-leave-active .modal {
  transition: all 0.2s ease;
}

//

.modal-enter,
.modal-leave-to {
  // transform: translateY(-50px);

  opacity: 0;
}

.modal-enter-to,
.modal-leave {
  // transform: translateY(0);

  opacity: 1;
}

.modal-enter-active,
.modal-leave-active {
  transition: all 0.2s ease;
}
</style>
