<template>
  <div style="padding: var(--sp-6); border: 1px solid var(--primary-8)">
    <m-form-field
      :field="field"
      #default="{on, props}"
      v-for="(field, index) in nestedFields"
      :key="index"
    >
      <component v-bind="props" v-on="on" :is="`${field.type}Input`" />
    </m-form-field>

    <button type="button" @click="add">add</button>

    <!-- <pre>{{field}}</pre> -->
  </div>
</template>

<script>
export default {
  props: ["field", "value", "name", "id", "hasError"],
  computed: {
    nestedFields() {
      return (this.value || []).map((_, index) => {
        return {
          ...this.field.template_field,
          name: `${this.name}.${index}`
        };
      });
    }
  },
  methods: {
    add() {
      const newValue = [...(this.value || []), null];
      this.$emit("input", newValue);
    }
  }
};
</script>

<style lang="scss" scoped>
</style>