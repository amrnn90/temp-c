<template>
  <div style="padding: var(--sp-6); border: 1px solid var(--primary-8)">
    <div v-for="(field, index) in nestedFields" :key="index">
      <m-form-field v-if="index < 100" #default="{on, props}" :field="field">
        <component :is="`${field.type}Input`" v-bind="props" v-on="on" />
        <button type="button" @click="() => remove(index)">remove</button>
      </m-form-field>
    </div>

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
    },
    remove(index) {
      const newValue = [
        ...this.value.slice(0, index),
        ...this.value.slice(index + 1)
      ];
      this.$emit("input", newValue);
    }
  }
};
</script>

<style lang="scss" scoped></style>
