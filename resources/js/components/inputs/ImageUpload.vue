<template>
  <div class="image-upload">
    <div class="image-upload-wrapper">
      <div class="image-holder" v-for="(image, index) in images" :key="index">
        <img class="image-preview" :src="image.preview" />
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
      multiple
    />
  </div>
</template>

<script>
import { genId } from "@/utils";

export default {
  props: ["value", "name", "id", "uploadUrl", "multiple"],
  data() {
    return {
      previews: {}
    };
  },
  computed: {
    images() {
      return (this.value || []).map(image => ({
        ...image,
        preview: this.previews[image.id] || image.preview
      }));
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
        state: "uploading"
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
  }
};
</script>

<style scoped lang="scss">
@import "resources/sass/init";

.image-upload {
  width: auto;
  display: inline-block;
}

.image-upload-wrapper {
  align-items: center;
  display: inline-flex;
  // justify-content: center;
  transition: all 0.5s ease;

  flex-wrap: wrap;
  justify-content: space-between;
  min-height: var(--sp-15);
  min-width: var(--sp-15);

  margin-bottom: calc(-1 * var(--sp-4));
}

// .images {
//   display: flex;
//   flex-wrap: wrap;
//   width: 100%;
// }

.image-upload-actions {
  // margin-left: auto;
  width: var(--sp-15);
  height: var(--sp-15);
  margin-bottom: var(--sp-4);
  // margin-right: auto;
  flex-grow: 1;
  button {
    width: 100%;
    height: 100%;
    width: var(--sp-15);
    height: var(--sp-15);
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid var(--grey-9);
    border-radius: var(--br);
  }
}

.image-holder {
  margin-right: var(--sp-5);
  margin-bottom: var(--sp-4);
}

.image-preview {
  width: var(--sp-15);
  height: var(--sp-15);
  border-radius: var(--br);
  object-fit: cover;
  border: 1px solid var(--grey-9);
  box-shadow: 1px 1px 4px hsla(var(--primary-v-6), 0.1);
}
</style>