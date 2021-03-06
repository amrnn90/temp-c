<template>
  <app-card class="table-row">
    <div class="table-row-upper">
      <div class="table-row-pre">
        <checkbox-input :value="isSelectedRow" @input="toggleSelectRow" />
      </div>
      <div class="table-row-cells table-cells">
        <div
          v-for="field in resource.fields"
          :key="field.name"
          class="table-row-cell"
          :class="`cell-${field.name}`"
          style="flex-grow: 1;"
          @click="isExpanded = !isExpanded"
        >
          <component
            :is="`${field.type}Index`"
            :field="row.fields.find(f => f.name == field.name)"
            :item="row"
            :is-title-field="resource.title_field == field.name"
          />
        </div>
      </div>
      <div class="table-row-post">
        <app-icon name="eye" />
        <router-link
          :to="{ name: `${resource.name}.edit`, params: { id: row.id } }"
        >
          <app-icon v-tooltip="'Edit'" name="edit-2" />
        </router-link>
        <row-delete-button :item="row" />
      </div>
    </div>
    <div class="table-row-expand">
      <expand :show="isExpanded">hello</expand>
    </div>
  </app-card>
</template>

<script>
import RowDeleteButton from "./RowDeleteButton";
import Expand from "@/components/Expand";
import CheckboxInput from "@/components/inputs/CheckboxInput";

export default {
  components: {
    RowDeleteButton,
    Expand,
    CheckboxInput
  },
  props: {
    row: {
      type: Object,
      required: true
    },
    resource: {
      type: Object,
      required: true
    }
  },
  inject: ["tableStoreNamespace"],
  data() {
    return {
      isExpanded: false
    };
  },
  computed: {
    rowId() {
      return this.row.id;
    },
    tableStore() {
      return this.$dynamicModuleStore(this.tableStoreNamespace);
    },
    isSelectedRow() {
      return this.tableStore.getters("isSelectedRow", this.rowId);
    }
  },
  methods: {
    toggleSelectRow() {
      this.tableStore.dispatch("toggleSelectRow", this.rowId);
    }
  }
};
</script>

<style scoped lang="scss">
.table-row {
  padding-top: var(--sp-3);
  padding-bottom: var(--sp-3);

  &:not(:last-child) {
    margin-bottom: var(--sp-2);
  }

  transition: all 0.2s ease;
  // &:hover {
  //   // background: hsla(var(--primary-v-6), .02);
  // }
}

.table-row-upper {
  display: flex;
  align-items: center;
}

.table-row-pre {
  width: var(--table-row-pre-width);
  display: flex;
  justify-content: center;
  align-items: center;
  flex-shrink: 0;
}

.table-row-post {
  width: var(--table-row-post-width);
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-left: auto;
}

.table-row-cells {
  flex-grow: 1;
  display: flex;
  align-items: center;
  cursor: pointer;
  margin: 0 var(--table-row-cells-horizontal-margin);
}

.table-row-cell {
  font-size: var(--fz-xs);
  font-weight: var(--fw-semibold);
  color: var(--grey-5);
}
</style>
