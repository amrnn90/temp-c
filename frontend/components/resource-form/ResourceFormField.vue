<template>
  <div class="input-field-wrapper" :class="{ 'has-error': formField.hasError }">
    <div v-if="showHeader" class="field-header">
      <label :for="formField.name" class="label">
        {{ formField.label }}
      </label>
      <!-- <select
          v-if="field.translatable"
          id=""
          v-model="sharedForm.locale"
          name=""
        >
          <option
            v-for="locale in sharedForm.locales"
            :key="locale"
            :value="locale"
            >{{ locale }}</option
          >
        </select> -->
      <div class="actions">
        <button
          v-if="showResetButton && formField.isUpdated"
          type="button"
          style="
            display: inline;
            font-size: var(--fz-xs);
            color: var(--grey-8);
          "
          @click="formField.reset"
        >
          reset
        </button>
      </div>
    </div>

    <div class="input-wrapper">
      <slot v-bind="formField"></slot>

      <div class="field-input__error" role="alert">
        <span>{{ formField.error }}</span>
      </div>
    </div>

    <!-- <pre>{{ formField }}</pre> -->
  </div>
</template>

<script>
import { useFormField } from "@amrnn/vue-form";
import { reactive, computed, inject } from "@vue/composition-api";
import _ from "@/lodash";
export default {
  props: {
    field: {
      type: Object,
      required: true,
    },
    showHeader: {
      type: Boolean,
      default: true,
    },
    showResetButton: {
      type: Boolean,
      default: true,
    },
  },
  setup(props, { attrs }) {
    const state = reactive({
      isTranslatable: computed(() => !!props.field.translatable),
      resourceFormLocales: inject("RESOURCE_FORM_LOCALES"),
    });

    state.formField = useFormField(props.field.name, {
      getValue,
      setValue,
      label: props.field.label,
      ...attrs,
    });

    function getValue(value) {
      return state.isTranslatable
        ? _.get(value, state.resourceFormLocales.locale)
        : value;
    }

    function setValue(newValue, oldValue) {
      return state.isTranslatable
        ? { ...(oldValue || {}), [state.resourceFormLocales.locale]: newValue }
        : newValue;
    }

    return state;
  },
};
</script>

<style lang="scss" scoped>
.input-field-wrapper {
  // display: flex;
  width: 100%;
  font-size: var(--fz-sm);
  &:not(:last-child) {
    margin-bottom: var(--sp-5);
  }
}

.input-wrapper {
  width: 100%;
}

.field-header {
  margin-bottom: var(--sp-2);
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  border-bottom: 1px solid var(--primary-10);
  padding-bottom: var(--sp-1);
}

.label {
  color: var(--grey-6);
  font-weight: var(--fw-semibold);
  cursor: pointer;
}

.has-error .label {
  color: var(--danger-8);
}

.field-input__error {
  margin-top: var(--sp-1);
  color: var(--danger-8);
  min-height: 1.5em;
  font-size: var(--fz-xs);
}
</style>
