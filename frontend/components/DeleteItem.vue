<script>
import axios from "@/axios";
import _ from "@/lodash";

export default {
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  computed: {
    isDisabled() {
      return !_.get(this.item, "abilities.force_delete");
    }
  },
  methods: {
    handleDelete() {
      return axios.delete(this.item.api_urls["delete"]);
    }
  },
  render() {
    return this.$scopedSlots.default({
      trigger: () => this.handleDelete(),
      isDisabled: this.isDisabled
    });
  }
};
</script>

<style></style>
