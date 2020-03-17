<template>
  <base-form-field-renderless
    #default="{name, label, error, isTouched, inputProps, inputListeners}"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <div class="input-field-wrapper" :class="{ 'has-error': !!error }">
      <label :for="name" class="label">{{ label }}</label>

      <div class="input-wrapper">
        <slot
          :input-listeners="inputListeners"
          :input-props="inputProps"
        ></slot>
        <div class="field-input__error" role="alert">
          <span>{{ error }}</span>
        </div>
      </div>
    </div>
  </base-form-field-renderless>
</template>

<script>
import BaseFormFieldRenderless from "./BaseFormFieldRenderless";

export default {
  components: {
    BaseFormFieldRenderless
  },
  methods: {
    focus() {
      // debugger;
      this.$scopedSlots.default()[0].context.focus();
    }
  },
  inheritAttrs: false
};
</script>

<style></style>
