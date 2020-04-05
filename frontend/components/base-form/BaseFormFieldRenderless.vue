<script>
import _ from "@/lodash";

export default {
  inheritAttrs: false,

  props: {
    name: {
      type: String,
      required: true
    },
    label: {
      type: String,
      default: null
    }
  },
  inject: ["sharedForm"],
  computed: {
    inputListeners() {
      return {
        input: this.onInput,
        blur: this.onBlur
      };
    },
    inputProps() {
      return {
        id: this.name,
        name: this.name,
        value: this.passedValue
      };
    },
    fieldLabel() {
      return this.label || this.name;
    },
    value() {
      return _.get(this.sharedForm.fields, this.name);
    },
    // Overridable
    passedValue() {
      return this.value;
    },
    initialValue() {
      return _.get(this.sharedForm.initialFields, this.name);
    },
    valueHasLength() {
      return _.get(this.value, "length") > 0;
    },
    error() {
      const errors = this.sharedForm.errors[this.name];
      return errors && (Array.isArray(errors) ? errors[0] : errors);
    },
    hasError() {
      return !!this.error;
    },
    hasDescendentsError() {
      return (
        Object.keys(this.sharedForm.errors).filter(key =>
          key.startsWith(this.name + ".")
        ).length > 0
      );
    },
    hasErrorOrHasDescendentsError() {
      return this.hasError || this.hasDescendentsError;
    },
    isTouched() {
      return !!this.sharedForm.touched[this.name];
    },
    valueEqualsInitialValue() {
      return _.isEqual(this.initialValue, this.value);
    }
  },
  watch: {
    "sharedForm.waitingForErrorFocus": {
      immediate: true,
      handler(newState) {
        if (newState && this.hasErrorOrHasDescendentsError) {
          this.focus();
          if (!this.hasDescendentsError) {
            this.sharedForm.waitingForErrorFocus = false;
          }
        }
      }
    }
  },
  mounted() {
    this.initValue();
    this.$el.dataset.formField = this.name;
  },
  methods: {
    initValue() {
      const { sharedForm } = this;
      const current = this.value;
      const newFields = { ...sharedForm.fields };

      _.set(
        newFields,
        this.name,
        Object.is(current, undefined) ? null : current
      );

      sharedForm.fields = newFields;
    },
    onInput(evOrValue) {
      let newValue;

      if (evOrValue && typeof evOrValue === "object" && evOrValue.target) {
        newValue = evOrValue.target.value;
      } else {
        newValue = evOrValue;
      }

      newValue = newValue === "" || newValue === undefined ? null : newValue;

      newValue = this.committedValue(newValue);

      // newValue = this.isTranslatable
      //   ? { ...(this.value || {}), [this.sharedForm.locale]: newValue }
      //   : newValue;

      const { sharedForm } = this;

      const newFields = { ...sharedForm.fields };
      _.set(newFields, this.name, newValue);
      sharedForm.fields = newFields;

      sharedForm.errors = {
        ..._.omit(sharedForm.errors, [this.name])
      };
    },
    // Overridable
    committedValue(value) {
      return value;
    },
    onBlur() {
      const sharedForm = this.sharedForm;
      sharedForm.runValidate();
      sharedForm.touched = {
        ...sharedForm.touched,
        [this.name]: true
      };
    },
    reset() {
      this.onInput(_.cloneDeep(this.initialValue));
    },
    // Overridable
    focus() {
      const input = this.$el.querySelector(
        "input:not([type=hidden]):not([disabled]),textarea:not([disabled]),select:not([disabled]), [tabindex], [contenteditable]"
      );
      if (!input) return;
      input.focus();
      input.scrollIntoView({ block: "nearest" });
    }
  },
  render() {
    return this.$scopedSlots.default({
      name: this.name,
      label: this.fieldLabel,
      inputProps: this.inputProps,
      inputListeners: this.inputListeners,
      error: this.error,
      hasError: this.hasError,
      hasDescendentsError: this.hasDescendentsError,
      hasErrorOrHasDescendentsError: this.hasErrorOrHasDescendentsError,
      isTouched: this.isTouched
    });
  }
};
</script>
