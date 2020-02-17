<template>
  <div class="input-field-wrapper">
    <label :for="name" class="label">{{inputLabel}}</label>

    <div class="input-wrapper">
      <slot :on="inputOn" :props="inputProps"></slot>
      <div class="field-input__error" role="alert">
        <span>{{error}}</span>
      </div>
    </div>

    <!-- <button
      type="button"
      v-tooltip="'reset'"
      style="display: inline"
      v-if="!valueEqualsInitialValue"
      @click="handleInput(initialValue)"
    >
      <icon name="rotate-cw" />
    </button> -->

  </div>
</template>

<script>
export default {
  inheritAttrs: false,
  props: ["name", "label"],
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
        class: {
          input: true,
          "input--error": this.sharedForm.errors[this.name]
        }
      };
    },
    value() {
      return this.sharedForm.fields[this.name];
    },
    initialValue() {
      return this.sharedForm.initialFields[this.name];
    },
    error() {
      return (
        this.sharedForm.errors[this.name] &&
        this.sharedForm.errors[this.name][0]
      );
    },
    inputLabel() {
      return this.label === undefined
        ? this.name.charAt(0).toUpperCase() + this.name.slice(1)
        : this.label;
    },
    valueEqualsInitialValue() {
      return this.initialValue == this.value;
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
    return {};
  },
  methods: {
    initValue() {
      const { sharedForm } = this;
      const current = sharedForm.fields[this.name];
      sharedForm.fields = {
        ...sharedForm.fields,
        [this.name]: Object.is(current, undefined) ? null : current
      };
    },
    handleInput(evOrValue) {
      let newValue;
      if (typeof evOrValue === "object" && evOrValue.target) {
        newValue = evOrValue.target.value;
      } else {
        newValue = evOrValue;
      }
      const { sharedForm } = this;
      sharedForm.fields = {
        ...sharedForm.fields,
        [this.name]: newValue
      };
      sharedForm.errors[this.name] = null;
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
  display: flex;
  margin-bottom: var(--sp-6);
  font-size: var(--fz-sm);
}

.input-wrapper {
  flex: 1;
}

.label {
  width: var(--sp-14);
  margin-right: var(--sp-11);
  color: var(--grey-6);
  font-weight: var(--fw-semibold);

  /* TO BE ALIGNED WITH INPUT TEXT */
  padding: calc(var(--input-border-size) + var(--input-vertical-padding)) 0;
  text-align: right;
}

.field-input__error {
  margin-top: var(--sp-1);
  color: var(--error-8);
  min-height: 1.5em;
  font-size: var(--fz-xs);
}
</style>