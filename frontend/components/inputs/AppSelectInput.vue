<template>
  <div
    ref="dropdownTrigger"
    :tabindex="dropDownTriggerTabIndex"
    class="dropdown-trigger"
    :class="inputClass"
    @click.stop
    @mousedown.prevent.stop="onMouseDown"
    @focus="onFocus"
    @keydown.tab="closeDropdown"
    @keydown.escape="closeDropdown"
    @keydown.down.prevent="highlightNext"
    @keydown.up.prevent="highlightPrev"
    @keydown.enter.prevent="selectHighlightedItem"
  >
    <div v-if="showPlaceholder" class="placeholder">Pick an item</div>
    <div v-if="showSelectedValue" class="selected-item">
      {{ itemLabel(value) }}
    </div>
    <input
      :id="id"
      type="text"
      style="display: none"
      :name="name"
      :value="value"
      @click="$refs.dropdownTrigger.focus()"
    />
    <v-popover
      popover-class="select-dropdown"
      :container="$refs.dropdownTrigger"
      :open="isOpen"
      :auto-hide="false"
      trigger="manual"
    >
      <div slot="popover" @mousedown.stop>
        <div v-if="showSearchInput" class="search-input-wrapper">
          <input
            ref="searchInput"
            v-model="searchQuery"
            class="search-input"
            type="text"
            @input="onSearchInput"
            @mousedown.stop
            @blur="onSearchInputBlur"
          />

          <app-icon name="search" stroke-width="1" class="search-input-icon" />
        </div>

        <div
          v-for="(item, index) in filteredItems"
          :key="itemKey(item)"
          class="select-dropdown-item"
          :class="{
            'is-highlighted': index === highlightedItemIndex,
            'is-selected': isSelectedItem(item)
          }"
          @click="selectItem(item)"
          @mouseenter="highlight(index)"
        >
          {{ itemLabel(item) }}
        </div>
      </div>
    </v-popover>
  </div>
</template>

<script>
// import _ from "@/lodash";

export default {
  props: {
    value: {
      type: null,
      default: null
    },
    name: {
      type: String,
      default: null
    },
    id: {
      type: String,
      default: null
    },
    items: {
      type: [Array, Function],
      required: true
    },
    multiple: {
      type: Boolean,
      default: false
    },
    searchable: {
      type: Boolean,
      default: true
    },
    inputClass: {
      type: String,
      default: ""
    },
    labelBy: {
      type: String,
      default: null
    },
    trackBy: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      isOpen: false,
      tabindex: 0,
      highlightedItemIndex: null,
      dropDownTriggerTabIndex: 0,
      filteredItems: [],
      searchQuery: ""
    };
  },
  computed: {
    showPlaceholder() {
      return !this.value;
    },
    showSelectedValue() {
      return this.value;
    },
    showSearchInput() {
      return this.searchable;
    }
  },
  watch: {
    isOpen(isOpen) {
      setTimeout(() => {
        this.dropDownTriggerTabIndex = isOpen
          ? this.showSearchInput
            ? null
            : 0
          : 0;
      }, 0);
    }
  },
  mounted() {
    this.windowClickListener = () => this.closeDropdown();
    window.addEventListener("mousedown", this.windowClickListener);

    this.onSearchInput();
  },
  destroyed() {
    window.removeEventListener("mousedown", this.windowClickListener);
  },
  methods: {
    itemLabel(item) {
      return this.labelBy ? item[this.labelBy] : item;
    },
    itemKey(item) {
      return this.trackBy ? item[this.trackBy] : item;
    },
    isSelectedItem(item) {
      if (!this.value) return false;
      return this.trackBy
        ? this.value[this.trackBy] === item[this.trackBy]
        : this.value === item;
    },
    selectItem(item) {
      if (!this.isOpen) return;
      // this.closeDropdown();
      if (this.isSelectedItem(item)) {
        return this.$emit("input", null);
      }
      this.$emit("input", item);
    },
    selectHighlightedItem() {
      if (!this.isOpen) return;
      if (this.highlightedItemIndex === null) {
        this.closeDropdown();
        return;
      }
      this.selectItem(this.filteredItems[this.highlightedItemIndex]);
    },
    highlightNext() {
      this.highlight(
        this.highlightedItemIndex === null ? 0 : this.highlightedItemIndex + 1
      );
    },
    highlightPrev() {
      this.highlight(
        this.highlightedItemIndex === null
          ? this.filteredItems.length - 1
          : this.highlightedItemIndex - 1
      );
    },
    highlight(index) {
      if (!this.isOpen) return;
      this.highlightedItemIndex = index;
      if (this.highlightedItemIndex < 0) {
        this.highlightedItemIndex = this.filteredItems.length - 1;
      }
      if (this.highlightedItemIndex > this.filteredItems.length - 1) {
        this.highlightedItemIndex = 0;
      }
    },
    openDropdown() {
      if (this.isOpen) return;
      this.isOpen = true;

      setTimeout(() => {
        this.searchable ? this.focusSearchInput() : this.focusDropdownTrigger();
        this.$emit("focus");
        this.$emit("open-dropdown");
      }, 100);
    },
    closeDropdown() {
      if (!this.isOpen) return;
      this.isOpen = false;
      this.highlightedItemIndex = null;
      this.$emit("blur");
      this.$emit("close-dropdown");
    },
    focusDropdownTrigger() {
      return this.$refs.dropdownTrigger && this.$refs.dropdownTrigger.focus();
    },
    focusSearchInput() {
      return this.$refs.searchInput && this.$refs.searchInput.focus();
    },
    toggleDropdown() {
      this.isOpen ? this.closeDropdown() : this.openDropdown();
    },
    onFocus() {
      console.log("focusing");
      this.openDropdown();
    },
    onMouseDown() {
      this.toggleDropdown();
    },
    onSearchInputBlur() {
      this.isOpen && this.focusSearchInput();
    },
    onSearchInput() {
      this.highlightedItemIndex = 0;
      if (typeof this.items === "function") {
        return this.items(this.searchQuery).then(filteredItems => {
          this.filteredItems = filteredItems;
        });
      }

      if (!this.searchQuery) {
        this.filteredItems = this.items;
      }

      this.filteredItems = this.items.filter(item =>
        this.itemLabel(item)
          .toLowerCase()
          .includes(this.searchQuery.toLowerCase())
      );
    }
  }
};
</script>

<style lang="scss">
.select-dropdown {
  top: 10px !important;
  width: 100%;
  cursor: default;
}

.select-dropdown .popover-inner {
  padding: var(--sp-3) !important;
  box-shadow: 0px 6px 15px hsla(var(--grey-v-9), 0.6) !important;
  background: white !important;
  border-radius: var(--br);
  overflow: hidden;
}
</style>

<style lang="scss" scoped>
.dropdown-trigger {
  position: relative;
  cursor: pointer;
}
.search-input {
  background: var(--grey-10);

  &:focus {
    outline: none;
  }
}

.select-dropdown-item {
  cursor: pointer;
  display: block;
  width: 100%;
  text-align: left;
  padding: var(--sp-3);
  border-radius: var(--br);
  color: var(--grey-5);
  font-size: var(--fz-sm);
  font-weight: var(--fw-medium);

  &:not(:last-child) {
    margin-bottom: var(--sp-1);
  }

  &.is-highlighted {
    /* background: var(--primary-10); */
    background: var(--grey-11);
  }

  &.is-selected {
    background: var(--primary);
    color: white;
  }

  &:focus {
    outline: none;
  }
}

.search-input-wrapper {
  position: relative;
  margin-bottom: var(--sp-2);
  .search-input-icon {
    position: absolute;
    top: 50%;
    left: var(--sp-3);
    transform: translateY(-50%);
  }
}

.search-input {
  // height: var(--sp-10);
  background: var(--grey-10);
  display: block;
  width: 100%;
  // color: var(--primary-10);
  border-radius: var(--br);
  padding: var(--sp-3) var(--sp-7);
  padding-left: var(--sp-11);
  font-size: var(--fz-xs);
  font-weight: var(--fw-semibold);

  &::placeholder {
    color: var(--grey-7);
  }
  & + .search-input-icon {
    color: var(--grey-7);
  }

  &:focus,
  &:focus + .search-input-icon {
    color: var(--grey-4);
  }
}

.search-input-wrapper {
  position: relative;
  .search-input-icon {
    position: absolute;
    top: 50%;
    left: var(--sp-3);
    transform: translateY(-50%);
  }
}
</style>
