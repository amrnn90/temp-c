<template>
  <div class="image-upload">
    <div
      ref="upload-wrapper"
      class="image-upload-wrapper"
      :class="inputClass"
      :style="{'width': wrapperWidth}"
    >
      <div class="image-holder" v-for="(image, index) in images" :key="index">
        <img class="image-preview" :src="image.preview" />
        <span
          class="upload-progress"
          v-if="image.state == 'uploading'"
          :style="{width: image.progress + '%'}"
        ></span>
      </div>
      <div class="image-upload-actions">
        <button v-if="multiple || images.length == 0" type="button" @click="triggerFileInput">
          <icon name="plus" stroke="var(--grey-6)" strokeWidth="2" size="32" />
        </button>
        <button v-else type="button" @click="$emit('input', null)">
          <icon name="x" stroke="var(--grey-6)" strokeWidth="2" size="32" />
        </button>
      </div>
    </div>
    <input
      ref="fileInput"
      accept="image/*"
      @change="handleFileChange"
      type="file"
      :name="name"
      :id="id"
      style="display: none"
      :multiple="multiple"
    />
  </div>
</template>

<script>
import { genId } from "@/utils";

export default {
  props: ["value", "name", "id", "uploadUrl", "multiple", "inputClass"],
  data() {
    return {
      previews: {},
      hasMounted: false
    };
  },
  computed: {
    images() {
      return (this.value || []).map(image => ({
        ...image,
        preview: this.previews[image.id] || image.preview
      }));
    },
    wrapperWidth() {
      /* https://stackoverflow.com/q/60346823/4765497 */

      /* HACK TO FORCE RECOMPUTATION */
      this.hasMounted;

      const wrapper = this.$refs["upload-wrapper"];
      if (!wrapper) return 0;

      const itemsCount = this.images.length + 1;
      const wrapperOuterWidth =
        (parseFloat(window.getComputedStyle(wrapper).paddingLeft) +
          parseFloat(window.getComputedStyle(wrapper).borderLeftWidth)) *
        2;
      const gridGap = 20;
      const minItemWidth = 100;
      return (
        itemsCount * minItemWidth +
        wrapperOuterWidth +
        (itemsCount - 1) * gridGap +
        "px"
      );
    }
  },
  watch: {
    value: {
      immediate: true,
      handler(value) {
        let modValue = (value || []).map(image => ({
          ...image,
          id: image.id || genId(),
          state: image.state || "persisted"
        }));

        if (!_.isEqual(value, modValue)) {
          this.$emit("input", modValue);
        }
      }
    }
  },
  methods: {
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    resize(src, maxWidth, callback) {
      var img = document.createElement("img");
      img.src = src;
      img.onload = () => {
        var oc = document.createElement("canvas");
        var ctx = oc.getContext("2d");
        // resize to [maxWidth] px
        var scale = maxWidth / img.width;
        oc.width = img.width * scale;
        oc.height = img.height * scale;
        ctx.drawImage(img, 0, 0, oc.width, oc.height);
        // convert canvas back to dataurl
        callback(oc.toDataURL());
      };
    },
    handleFileChange() {
      const files = this.$refs.fileInput.files;

      for (let i = 0; i < files.length; i++) {
        this.upload(files[i]);
      }
      this.$refs.fileInput.value = "";
    },
    upload(file) {
      if (!file.type.startsWith("image/")) {
        // continue;
        return;
      }

      const formData = new FormData();

      formData.append(this.name, file);

      const image = {
        id: genId(),
        state: "uploading",
        progress: 0
      };

      const reader = new FileReader();

      reader.onload = ev => {
        this.resize(ev.target.result, 127, dataURL => {
          this.previews[image.id] = dataURL;
          this.$emit("input", [...(this.value || []), image]);
        });
      };

      reader.readAsDataURL(file);

      axios
        .post(this.uploadUrl, formData, {
          headers: {
            "Content-Type": "multipart/formdata"
          },
          onUploadProgress: (progressEvent) => {
            const percentCompleted = Math.round(
              (progressEvent.loaded * 100) / progressEvent.total
            );
            image.progress = percentCompleted;
          }
        })
        .then(({ data }) => {
          const index = this.value.findIndex(img => img.id == image.id);

          image.state = "success";

          this.$emit("input", [
            ...this.value.slice(0, index),
            { ...data, ...image },
            ...this.value.slice(index + 1)
          ]);
        })
        .catch(error => {
          image.state = "error";
          console.log("error", error);
        });
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.hasMounted = true;
    });
  }
};
</script>

<style scoped lang="scss">
@import "resources/sass/init";

.image-upload {
  // width: 100%;
  // display: inline-flex;
  // display: inline-flex;
  width: 100%;
}

.image-upload-wrapper {
  display: grid;
  max-width: 100%;
  grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
  gap: 20px;

  min-height: var(--sp-15);
}

.image-upload-actions {
  display: flex;
  justify-content: center;
  align-items: center;

  button {
    width: 100px;
    height: 100px;
    // width: var(--sp-15);
    // height: var(--sp-15);
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid var(--grey-9);
    border-radius: var(--br);
  }
}

.image-holder {
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;

  .upload-progress {
    position: absolute;
    left: 0;
    bottom: 0;
    height: 100%;
    background: var(--primary-4);
    opacity: .5;
    border-radius: var(--br);
    // width: 100%;
  }
}

.image-preview {
  // max-width: 100%;
  border-radius: var(--br);
  // object-fit: cover;
  border: 1px solid var(--grey-9);
  box-shadow: 1px 1px 4px hsla(var(--primary-v-6), 0.1);
}
</style>