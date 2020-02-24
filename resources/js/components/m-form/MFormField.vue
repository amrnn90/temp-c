<template>
  <div
    class="input-field-wrapper"
    :class="{'has-error': hasError}"
    @mouseenter="isHovered = true"
    @mouseleave="isHovered = false"
  >
    <div class="field-header">
      <label :for="name" class="label">{{label}}</label>
      <div class="actions">
        <button
          type="button"
          style="display: inline; font-size: var(--fz-xs); color: var(--grey-8); "
          v-if="!valueEqualsInitialValue"
          @click="reset"
        >reset</button>
        <span
          v-if="!valueEqualsInitialValue && (typeof value === 'string' && value.length > 0)"
          style="display: inline; font-size: var(--fz-xs); color: var(--grey-8);"
        >|</span>
        <button
          type="button"
          style="display: inline; font-size: var(--fz-xs); color: var(--grey-8); "
          v-if="valueHasLength"
          @click="handleInput(typeof value == 'array' ? [] : '')"
        >clear</button>
      </div>
    </div>

    <div class="input-wrapper">
      <slot :on="inputOn" :props="inputProps"></slot>
      <div class="field-input__error" role="alert">
        <span>{{error}}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
  props: ["field"],
  computed: {
    inputOn() {
      return {
        input: this.handleInput
      };
    },
    inputProps() {
      return {
        id: this.name,
        name: this.name,
        value: this.value,
        field: this.field,
        hasError: this.hasError
      };
    },
    name() {
      return _.get(this.field, "name");
    },
    label() {
      return _.get(this.field, "label");
    },
    value() {
      return _.get(this.sharedForm.fields, this.name);
    },
    initialValue() {
      return _.get(this.sharedForm.initialFields, this.name);
    },
    valueHasLength() {
      return _.get(this.value, "length") > 0;
    },
    error() {
      const errors = this.sharedForm.errors[this.name];
      return errors && errors[0];
    },

    hasError() {
      return !!this.error;
    },

    valueEqualsInitialValue() {
      return _.isEqual(this.initialValue, this.value);
    }
  },
  watch: {
    error(newState) {
      if (newState) {
        this.sharedForm.errorComponents = [
          ...this.sharedForm.errorComponents,
          { name: this.name }
        ];
      }
    }
  },
  data() {
    return {
      isHovered: false
    };
  },
  methods: {
    initValue() {
      const { sharedForm } = this;
      const current = this.value;
      const newFields = {...sharedForm.fields};

      _.set(newFields, this.name, Object.is(current, undefined) ? null : current);

      sharedForm.fields = newFields;
    },
    handleInput(evOrValue) {
      let newValue;

      if (evOrValue && typeof evOrValue === "object" && evOrValue.target) {
        newValue = evOrValue.target.value;
      } else {
        newValue = evOrValue;
      }

      newValue = newValue === "" || newValue === undefined ? null : newValue;

      const { sharedForm } = this;

      const newFields = { ...sharedForm.fields };
      _.set(newFields, this.name, newValue);
      sharedForm.fields = newFields;
  
      sharedForm.errors = {
        ...sharedForm.errors,
        [this.name]: null
      };
    },
    reset() {
      this.handleInput(_.cloneDeep(this.initialValue));
    }
  },
  mounted() {
    this.initValue();
  },
  inject: ["sharedForm"]
};
</script>

<style lang="scss" scoped>
.input-field-wrapper {
  // display: flex;
  width: 100%;
  margin-bottom: var(--sp-5);
  font-size: var(--fz-sm);
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