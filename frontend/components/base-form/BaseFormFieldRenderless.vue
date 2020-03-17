<script>
import _ from "@/lodash";
export default {
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
  computedOnSteroids() {
    return {
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
          value: this.value
        };
      },
      fieldLabel() {
        return this.label || this.name;
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
        return errors && (Array.isArray(errors) ? errors[0] : errors);
      },
      hasError() {
        return !!this.error;
      },
      isTouched() {
        return !!this.sharedForm.touched[this.name];
      },
      valueEqualsInitialValue() {
        return _.isEqual(this.initialValue, this.value);
      }
    };
  },
  watch: {
    error(newState) {
      if (newState) {
        if (!this.sharedForm.focusTarget) {
          this.sharedForm.focusTarget = this.name;
        }
      }
    }
  },
  mounted() {
    this.initValue();
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

      newValue = this.isTranslatable
        ? { ...(this.value || {}), [this.sharedForm.locale]: newValue }
        : newValue;

      const { sharedForm } = this;

      const newFields = { ...sharedForm.fields };
      _.set(newFields, this.name, newValue);
      sharedForm.fields = newFields;

      sharedForm.errors = {
        ..._.omit(sharedForm.errors, [this.name])
      };
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
    }
  },
  render() {
    return this.$scopedSlots.default({
      name: this.name,
      label: this.fieldLabel,
      inputProps: this.inputProps,
      inputListeners: this.inputListeners,
      error: this.error,
      isTouched: this.isTouched
    });
  }
};
</script>
