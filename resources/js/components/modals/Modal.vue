<template>
  <portal to="modals">
    <transition name="modal" :duration="200" appear @afterLeave="afterModalLeave">
      <div class="modal-backdrop" @click="handleBackdropClick">
        <div class="modal" @click.stop>
          <slot />

          <div>{{scrollbarWidth}}</div>
        </div>
      </div>
    </transition>
  </portal>
</template>

<script>
export default {
  props: {
    dismissOnBackdropClick: { default: true, type: Boolean }
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
    },

    /* https://stackoverflow.com/a/13382873 */
    getScrollbarWidth() {
      // Creating invisible container
      const outer = document.createElement("div");
      outer.style.visibility = "hidden";
      outer.style.overflow = "scroll"; // forcing scrollbar to appear
      outer.style.msOverflowStyle = "scrollbar"; // needed for WinJS apps
      document.body.appendChild(outer);

      // Creating inner element and placing it in the container
      const inner = document.createElement("div");
      outer.appendChild(inner);

      // Calculating difference between container's full width and the child width
      const scrollbarWidth = outer.offsetWidth - inner.offsetWidth;

      // Removing temporary elements from the DOM
      outer.parentNode.removeChild(outer);

      return scrollbarWidth;
    },

    bodyIsOverflowing() {
      const windowHeight = document.documentElement.clientHeight;
      const scrollHeight = Math.max(
        document.body.scrollHeight,
        document.documentElement.scrollHeight,
        document.body.offsetHeight,
        document.documentElement.offsetHeight,
        document.body.clientHeight,
        document.documentElement.clientHeight
      );

      console.log('scrollHeight:', scrollHeight)
      console.log('windowHeight:', windowHeight)
      return scrollHeight > windowHeight;
    },

    afterModalLeave() {
      document.body.style.removeProperty("overflow");
      document.body.style.removeProperty("padding-right");
    }
  },
  mounted() {
    this.escapeListener = e => {
      if (e.key === "Escape") this.dismiss();
    };

    const bodyScrollbarWidth = this.getScrollbarWidth() + "px";

    window.addEventListener("keydown", this.escapeListener);

    // need to check if there's a body overflow first
    if (this.bodyIsOverflowing()) {
      document.body.style.setProperty("overflow", "hidden");
      document.body.style.setProperty("padding-right", bodyScrollbarWidth);
      this.$el.style.setProperty("padding-right", bodyScrollbarWidth);
    }
  },
  destroyed() {
    window.removeEventListener("keydown", this.escapeListener);
    /* Removing body styles is in a transition hook: afterModalLeave() */
  }
};
</script>

<style lang="scss" scoped>
@import "resources/sass/init";

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background: rgba(black, 0.5);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  // backdrop-filter: blur(4px);
}

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

// .modal-enter,
// .modal-leave-to {
//   opacity: 0;
// }

// .modal-enter-to,
// .modal-leave {
//   opacity: 1;
// }

// .modal-enter-active,
// .modal-leave-active {
//   transition: all .3s ease;
// }

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
  opacity: 0;
}

.modal-enter-to,
.modal-leave {
  opacity: 1;
}

.modal-enter-active,
.modal-leave-active {
  transition: all 0.2s ease;
}
</style>