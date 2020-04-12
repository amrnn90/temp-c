<template>
  <div
    v-if="!translatableField"
    class="input-field-wrapper"
    :class="{ 'has-error': formField.hasError }"
  >
    <div v-if="showHeader" class="field-header">
      <label :for="formField.name" class="label">
        {{ formField.label }}
      </label>
      <select
        v-if="isTranslationField"
        :value="resourceFormLocales.locale"
        @input="e => resourceFormLocales.setLocale(e.target.value)"
      >
        <option
          v-for="locale in resourceFormLocales.locales"
          :key="locale"
          :value="locale"
          >{{ locale }}</option
        >
      </select>
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

  <div v-else>
    <resource-form-field
      #default="bindTranslatableField"
      :field="translatableField"
      :is-translation-field="true"
    >
      <slot v-bind="bindTranslatableField" />
    </resource-form-field>
  </div>
</template>

<script>
import { useFormField } from "@amrnn/vue-form";
import { computed, inject } from "@vue/composition-api";
import ResourceFormField from "@/components/resource-form/ResourceFormField";

export default {
  name: "resource-form-field",
  components: { ResourceFormField },
  props: {
    field: {
      type: Object,
      required: true
    },
    isTranslationField: {
      type: Boolean,
      default: false
    },
    showHeader: {
      type: Boolean,
      default: true
    },
    showResetButton: {
      type: Boolean,
      default: true
    },
    onFocus: {
      type: Function,
      default: null
    }
  },
  setup(props) {
    const resourceFormLocales = inject("RESOURCE_FORM_LOCALES");

    const fieldName = computed(() => props.field.name);

    const translatableField = computed(() => {
      return props.field.translatable
        ? {
            ...props.field,
            name: `${props.field.name}.${resourceFormLocales.locale}`,
            translatable: false
          }
        : null;
    });

    const formFieldOpts = {
      label: props.field.label,
      unsetIfNull: props.isTranslationField
    };

    if (props.onFocus) {
      formFieldOpts.onFocus = props.onFocus;
    }

    const formField = useFormField(fieldName, formFieldOpts);

    return { formField, resourceFormLocales, translatableField };
  }
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
