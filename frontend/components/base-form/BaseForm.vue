<template>
  <form @submit.prevent="onSubmit">
    <slot :form="sharedForm"></slot>
  </form>
</template>

<script>
import _ from "@/lodash";
import lastPromise from "@/utils/last-promise";

export default {
  props: {
    item: { type: Object, default: null },
    validate: { type: Function, default: null }
  },
  data() {
    return {
      sharedForm: {
        // locale: "en",
        // locales: this.$store.state.structure.locales,
        fields: _.cloneDeep(this.item),
        initialFields: _.cloneDeep(this.item),
        touched: {},
        errors: {},
        // errorComponents: [],
        // focusTarget: null,
        waitingForErrorFocus: false,
        isValidating: false,
        isSubmitting: false,
        hasSubmitted: false,
        runValidate: this.runValidate
      }
    };
  },
  computed: {
    form() {
      return this.sharedForm.fields;
    },
    hasErrors() {
      return (
        Object.values(this.sharedForm.errors).filter(error => !!error).length >
        0
      );
    }
  },
  watch: {},
  mounted() {
    this.lastValidatePromise = lastPromise();
  },
  methods: {
    onSubmit() {
      this.sharedForm.hasSubmitted = true;
      this.clearErrors();

      const runSubmit = () => {
        this.sharedForm.isSubmitting = true;
        const onSuccess = () => {
          this.sharedForm.isSubmitting = false;
        };

        const onError = errors => {
          this.setErrors(errors);
          this.sharedForm.isSubmitting = false;
          this.waitForErrorFocus();
        };

        this.$emit("submit", {
          data: this.form,
          onSuccess,
          onError
        });
      };

      this.runValidate().then(() => {
        if (this.hasErrors) {
          this.waitForErrorFocus();
          return;
        }
        runSubmit();
      });
    },
    setErrors(errors) {
      errors = { ...(errors || {}) };
      this.sharedForm.errors = this.sharedForm.hasSubmitted
        ? errors
        : _.pick(errors, Object.keys(this.sharedForm.touched));
    },
    clearErrors() {
      this.sharedForm.errors = {};
    },
    waitForErrorFocus() {
      this.sharedForm.waitingForErrorFocus = true;
      setTimeout(() => {
        this.sharedForm.waitingForErrorFocus = false;
      }, 500);
    },
    runValidate() {
      this.sharedForm.isValidating = true;

      return this.lastValidatePromise(
        this.validate ? this.validate(this.form, this.errors) : this.errors
      ).then(errors => {
        this.sharedForm.isValidating = false;
        this.setErrors(errors);
        return errors;
      });
    }
  },
  provide() {
    return {
      sharedForm: this.sharedForm
    };
  }
};
</script>
