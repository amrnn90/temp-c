<template>
  <svg class="icon" :style="styles" viewBox="0 0 32 32">
    <use :xlink:href="iconUrl" />
  </svg>
</template>

<script>
export default {
  components: {},
  props: {
    name: { type: String, required: true },
    size: { type: [Number, String], default: 24 },
    stroke: { type: String, default: "currentColor" },
    strokeWidth: { type: [Number, String], default: 1 },
    strokeLinecap: { type: String, default: "round" },
    strokeLinejoin: { type: String, default: "round" }
  },
  computed: {
    iconUrl() {
      return "#" + this.name;
    },
    styles() {
      return {
        width: parseInt(this.size) + "px",
        height: parseInt(this.size) + "px",
        stroke: this.stroke,
        strokeWidth: this.strokeWidth,
        strokeLinecap: this.strokeLinecap,
        strokeLinejoin: this.strokeLinejoin,
        fill: "none",
        display: "inline-block",
        boxSizing: "content-box"
      };
    }
  }
};
</script>

<style scoped></style>
