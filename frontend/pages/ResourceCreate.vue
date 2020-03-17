<template>
  <div>
    <h1 class="resource-label">Create a new {{ resource.label }}</h1>
    <app-card style="margin-bottom: var(--sp-10)">
      <base-form
        #default="{form}"
        :validate="onTempValidate"
        @submit="onTempSubmit"
      >
        <base-form-field #default="{inputListeners, inputProps}" name="title">
          <input type="text" v-bind="inputProps" v-on="inputListeners" />
        </base-form-field>

        <base-form-field
          #default="{inputListeners, inputProps}"
          name="description"
          label="DescDesc"
        >
          <input type="text" v-bind="inputProps" v-on="inputListeners" />
        </base-form-field>

        <button type="submit">submit</button>
        <pre>{{ form }}</pre>
      </base-form>

      <!-- <div class="form-wrapper">
        <resource-form
          #default="{form}"
          :action="resource.api_urls.store"
          @error-focus="handleErrorFocus"
          @success="handleCreateSuccess"
        >
          <div
            :style="{
              opacity: form.isLoading ? 0.5 : 1,
              pointerEvents: form.isLoading ? 'none' : 'auto'
            }"
          >
            <resource-form-field
              v-for="field in resource.fields"
              #default="{on, props}"
              :key="field.name"
              :field="field"
            >
              <component :is="`${field.type}Input`" v-bind="props" v-on="on" />
            </resource-form-field>

            <div class="actions-wrapper">
              <button type="submit" class="create-btn" :style="{}">
                Create
              </button>
            </div>
            <pre>{{ form }}</pre>
          </div>
        </resource-form>
      </div> -->
    </app-card>
  </div>
</template>

<script>
import BaseForm from "@/components/base-form/BaseForm";
import BaseFormField from "@/components/base-form/BaseFormField";
// import ResourceForm from "@/components/resource-form/ResourceForm";
// import ResourceFormField from "@/components/resource-form/ResourceFormField";
// import BooleanInput from "@/components/fields/boolean/BooleanInput";
// import DateInput from "@/components/fields/date/DateInput";
// import ImageInput from "@/components/fields/image/ImageInput";
// import JsonInput from "@/components/fields/json/JsonInput";
// import JsonArrayInput from "@/components/fields/json_array/JsonArrayInput";
// import LongTextInput from "@/components/fields/long_text/LongTextInput";
// import ShortTextInput from "@/components/fields/short_text/ShortTextInput";

export default {
  components: {
    BaseForm,
    BaseFormField
    // ResourceForm,
    // ResourceFormField,
    // BooleanInput,
    // DateInput,
    // ImageInput,
    // JsonInput,
    // JsonArrayInput,
    // LongTextInput,
    // ShortTextInput
  },
  props: {
    resource: {
      type: Object,
      required: true
    }
  },
  data() {
    return {};
  },
  computed: {},
  created() {},
  methods: {
    onTempSubmit({ data, onError }) {
      setTimeout(() => {
        alert(JSON.stringify(data, null, 2));
        onError({ title: "yo bad" });
      }, 500);
    },
    onTempValidate(data) {
      return new Promise(resolve => {
        setTimeout(() => {
          const errors = {};
          if (data.title !== "shit") {
            errors.title = "must be 💩";
          }
          if (!data.description) {
            errors.description = "need something here";
          }
          resolve(errors);
        }, 1000);
      });
    },
    handleErrorFocus(errorComponents) {
      const inputName = errorComponents[0].name;
      const el = this.$el.querySelector(`[name="${inputName}"]`);
      if (el) {
        el.focus();
      }
    },
    handleCreateSuccess() {
      this.$flash("Item created successfully!");
      this.$router.push({ name: `${this.resource.name}.index` });
    }
  }
};
</script>

<style scoped lang="scss">
.resource-label {
  font-size: var(--fz-2xl);
  color: var(--grey-2);
  margin-bottom: var(--sp-6);
}

.form-wrapper {
  max-width: var(--sp-21);
  margin: 0 auto;
  // display: flex;
  // justify-content: center;
  padding: var(--sp-10) var(--sp-4);
}

.actions-wrapper {
  display: flex;
  justify-content: flex-end;
  margin-top: var(--sp-10);
}

.create-btn {
  // height: var(--sp-10);
  background: var(--primary);
  color: var(--primary-10);
  border-radius: var(--br);
  padding: var(--sp-3) var(--sp-7);
  font-size: var(--fz-xs);
  font-weight: var(--fw-bold);
}
</style>
