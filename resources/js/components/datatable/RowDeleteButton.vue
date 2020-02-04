<template>
  <delete-item :item="item" #default="{trigger, isDisabled}">
    <i
      @click="handleClick(isDisabled)"
      class="icon lni-trash"
      :class="{'is-disabled': isDisabled}"
      v-tooltip.top="{content: isDisabled ? '' : 'Delete Item',}"
    >
      <modal v-if="showModal" @dismiss="showModal = false">
        <div>
          <h2>Are you sure you want to delete this item?</h2>
          <button @click="handleConfirm(trigger)">Yes</button>
          <button @click="showModal = false">No</button>
        </div>
      </modal>
    </i>
  </delete-item>
</template>

<script>
export default {
  props: ["item"],
  inject: ["refreshTable"],

  data() {
    return {
      showModal: false
    };
  },
  methods: {
    handleClick(isDisabled) {
      if (isDisabled) return;
      this.showModal = true;
    },
    handleConfirm(triggerDelete) {
      triggerDelete().then(() => {
        this.showModal = false;
        this.refreshTable();
        this.$flash('Item deleted successfully!');
      });
    }
  }
};
</script>

<style lang="scss" scoped>
@import "resources/sass/init";
.icon {
  font-size: 22px;
  color: $grey-4;
  cursor: pointer;

  &.is-disabled {
    opacity: 0.3;
    cursor: default;
  }
}
</style>