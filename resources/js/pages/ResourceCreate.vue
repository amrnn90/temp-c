<template>
  <div>
    <h1 class="resource-label">Create a new {{resource.label}}</h1>
    <page-card style="margin-bottom: var(--sp-10)">
      <m-form :action="resource.api_urls.store" @error-focus="handleErrorFocus" #default="{form}">
        <div v-for="field in resource.fields" :key="field.name">
          <m-form-field :name="field.name" :label="field.label" #default="{on, props}">
            <component v-bind="props" v-on="on" :is="`${field.type}Input`" />
          </m-form-field>
        </div>

        <button type="submit">submit</button>
        <pre>{{form}}</pre>
      </m-form>
    </page-card>
    <page-card>
      <pre>{{resource}}</pre>
    </page-card>
  </div>
</template>

<script>
export default {
  props: ["resource"],
  data() {
    return {};
  },
  computed: {},
  methods: {
    handleErrorFocus(errorComponents) {
      const inputName = errorComponents[0].name;
      const el = this.$el.querySelector(`[name=${inputName}]`);
      if (el) {
        el.focus();
      }
    }
  },
  created() {}
};
</script>


<style scoped lang="scss">
@import "resources/sass/init";

.resource-label {
  font-size: var(--fz-2xl);
  color: var(--grey-2);
  margin-bottom: var(--sp-6);
}
</style>