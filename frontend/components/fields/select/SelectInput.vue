<template>
  <resource-form-field
    #default="{inputProps, inputListeners, hasError}"
    :field="field"
  >
    <app-select-input
      :multiple="field.options.multiple"
      :input-class="`field-input ${hasError ? 'has-error' : ''}`"
      :items="field.options['is-async'] ? getItems : field.options.items"
      :label-by="field.options.label_by"
      :track-by="field.options.track_by"
      :searchable="field.options.searchable"
      v-bind="inputProps"
      v-on="inputListeners"
    />
  </resource-form-field>
</template>

<script>
import ResourceFormField from "@/components/resource-form/ResourceFormField";
import AppSelectInput from "@/components/inputs/AppSelectInput";
import axios from "@/axios";

export default {
  components: { ResourceFormField, AppSelectInput },
  props: {
    field: {
      type: Object,
      required: true
    }
  },
  methods: {
    getItems(query) {
      console.log("here", this.field);
      return axios
        .get(this.field.options.items_url, { params: { query } })
        .then(({ data }) => {
          return data;
        });
    }
  }
};
</script>

<style lang="scss" scoped></style>
