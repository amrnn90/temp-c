<template>
  <div style="padding: var(--sp-6); border: 3px solid var(--grey-10)">
    <!-- <toggle-input
      :name="name"
      :id="id"
      :class="{'has-error': hasError}"
      :value="value"
      @input="(value) => $emit('input', value)"
    /> -->

    <div v-for="field in nestedFields" :key="field.name">
      <m-form-field :field="field" #default="{on, props}">
        <component v-bind="props" v-on="on" :is="`${field.type}Input`" />
      </m-form-field>
    </div>


    <!-- <pre>{{field}}</pre> -->
  </div>
</template>

<script>
export default {
  props: ["field", "value", "name", "id", "hasError"],
  computed: {
    nestedFields() {
      return this.field.fields.map(field => {
        return {
          ...field,
          name: `${this.name}.${field.name}`
        }
      })
    }
  }
};
</script>

<style lang="scss" scoped>
</style>