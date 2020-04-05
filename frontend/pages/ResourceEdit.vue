<template>
  <div>
    <h1 class="resource-label">Edit {{ resource.label }}</h1>
    <app-card style="margin-bottom: var(--sp-10);">
      <div class="form-wrapper">
        <resource-form
          v-if="item"
          :resource="resource"
          :item="item"
          :locales="locales"
          @success="onSubmitSuccess"
        />
      </div>
    </app-card>
  </div>
</template>

<script>
import axios from "@/axios";
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
  data() {
    return {
      item: null,
    };
  },
  computed: {
    locales() {
      return this.$store.state.structure.locales;
    },
  },
  created() {
    axios
      .get(`${this.resource.api_urls.base_path}/${this.$route.params.id}`)
      .then(({ data }) => {
        this.item = data;
      });
  },
  methods: {
    onSubmitSuccess() {
      this.$flash("Item updated successfully!");
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
