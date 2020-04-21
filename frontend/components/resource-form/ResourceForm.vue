<template>
  <base-form #default="{form}" :item="itemData" @submit="onSubmit">
    <div
      :style="{
        opacity: form.isLoading ? 0.5 : 1,
        pointerEvents: form.isLoading ? 'none' : 'auto'
      }"
    >
      <component
        :is="`${field.type}Input`"
        v-for="field in resource.fields"
        :key="field.name"
        :field="field"
      />

      <div class="actions">
        <button type="submit" class="create-btn" :style="{}">
          {{ submitButtonLabel }}
        </button>
      </div>

      <pre>{{ form }}</pre>
    </div>
  </base-form>
</template>

<script>
import { reactive, provide } from "@vue/composition-api";
import { BaseForm } from "@amrnn/vue-form";

import axios from "@/axios";

export default {
  components: {
    BaseForm
  },
  props: {
    resource: { type: Object, required: true },
    item: { type: Object, default: null },
    locales: { type: Array, default: () => [] }
  },
  setup(props) {
    const resourcesFormLocales = reactive({
      locale: "en",
      locales: props.locales,
      setLocale
    });

    function setLocale(locale) {
      resourcesFormLocales.locale = locale;
    }

    provide("RESOURCE_FORM_LOCALES", resourcesFormLocales);
  },
  computed: {
    method() {
      return this.item ? "patch" : "post";
    },
    itemData() {
      return this.item
        ? this.item.fields.reduce((result, field) => {
            result[field.name] = field.data;
            return result;
          }, {})
        : null;
    },
    submitButtonLabel() {
      return this.item ? "Update" : "Create";
    },
    submitUrl() {
      return this.item
        ? this.item.api_urls.update
        : this.resource.api_urls.store;
    }
  },
  methods: {
    onSubmit({ data, onSuccess, onError }) {
      axios[this.method](this.submitUrl, data)
        .then(({ data }) => {
          onSuccess();
          this.$emit("success", data);
        })
        .catch(error => {
          onError(error.response.data.errors);
          this.$emit("error", error);
        });
    }
  }
};
</script>

<style scoped>
.actions {
  display: flex;
  justify-content: flex-end;
  margin-top: var(--sp-10);
}

.create-btn {
  background: var(--primary);
  color: var(--primary-10);
  border-radius: var(--br);
  padding: var(--sp-3) var(--sp-7);
  font-size: var(--fz-xs);
  font-weight: var(--fw-bold);
}
</style>
