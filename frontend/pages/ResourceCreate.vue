<template>
  <div>
    <h1 class="resource-label">Create a new {{ resource.label }}</h1>
    <app-card style="margin-bottom: var(--sp-10);">
      <div class="form-wrapper">
        <resource-form
          :resource="resource"
          :locales="locales"
          @success="onSubmitSuccess"
        />
      </div>
    </app-card>
  </div>
</template>

<script>
import ResourceForm from "@/components/resource-form/ResourceForm";

export default {
  components: {
    ResourceForm,
  },
  props: {
    resource: {
      type: Object,
      required: true,
    },
  },
  computed: {
    locales() {
      return this.$store.state.structure.locales;
    },
  },
  created() {},
  methods: {
    onSubmitSuccess() {
      this.$flash("Item created successfully!");
      this.$router.push({ name: `${this.resource.name}.index` });
    },
  },
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
</style>
