<template>
  <delete-item #default="{trigger, isDisabled}" :item="item">
    <!-- <button
      @click="handleClick(isDisabled)"
      v-tooltip.top="{content: isDisabled ? '' : 'Delete Item',}"
      :disabled="isDisabled"
    >
      <icon name="trash-2" />
      <modal v-if="showModal" @dismiss="showModal = false">
        <div>
          <h2>Are you sure you want to delete this item?</h2>
          <button @click="handleConfirm(trigger)">Yes</button>
          <button @click="showModal = false">No</button>
        </div>
      </modal>
    </button> -->

    <v-popover
      :open="showModal"
      @show="showModal = true"
      @hide="showModal = false"
    >
      <button
        v-tooltip.top="{ content: isDisabled ? '' : 'Delete Item' }"
        :disabled="isDisabled"
        @click="handleClick(isDisabled)"
      >
        <app-icon name="trash-2" />
      </button>

      <template slot="popover">
        <div>
          <page-backdrop v-if="showModal" @escape="showModal = false" />
          <h2>Are you sure you want to delete this item?</h2>
          <button @click="handleConfirm(trigger)">Yes</button>
          <button @click="showModal = false">No</button>
        </div>
      </template>
    </v-popover>
  </delete-item>
</template>

<script>
import DeleteItem from "@/components/DeleteItem";
import PageBackdrop from "@/components/modals/PageBackdrop";

export default {
  components: {
    DeleteItem,
    PageBackdrop,
  },
  props: {
    item: {
      type: Object,
      required: true,
    },
  },
  inject: ["tableStoreNamespace"],

  data() {
    return {
      showModal: false,
      // show: false
    };
  },
  computed: {
    tableStore() {
      return this.$dynamicModuleStore(this.tableStoreNamespace);
    },
  },
  methods: {
    refreshTable() {
      this.tableStore.dispatch("refresh");
    },
    handleClick(isDisabled) {
      if (isDisabled) return;
      this.showModal = true;
    },
    handleConfirm(triggerDelete) {
      triggerDelete().then(() => {
        this.showModal = false;
        this.refreshTable();
        this.$flash("Item deleted successfully!");
      });
    },
  },
};
</script>

<style lang="scss" scoped>
button:disabled {
  opacity: 0.3;
  cursor: default;
}
</style>
