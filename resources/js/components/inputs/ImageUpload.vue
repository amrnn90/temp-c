<template>
  <div class="image-upload">
    <div class="image-upload-wrapper">
      <div class="image-holder" v-for="image in images" :key="image.name">
        <img class="image-preview" :src="imagePreview(image)" />
      </div>
      <div class="image-upload-actions">
        <button v-if="images.length == 0" type="button" @click="triggerFileInput">
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
      @click
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
      // pendingImage: null,
      images: [],
      previews: {}
    };
  },
  computed: {
    // image() {
    //   return this.pendingImage ? this.pendingImage : this.value;
    // }
  },
  watch: {
    value: {
      immediate: true,
      handler(value) {
        value = value || [];
        this.images = [
          ...value.map(image => ({
            name: image.originalName,
            state: "persisted"
          })),
          ...this.images.filter(
            image => image.state != "done" && image.state != "persisted"
          )
        ];
      }
    },
  },
  methods: {
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    imagePreview(image) {
      return this.previews[image.name];
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
        name: file.name,
        state: "pending"
      };

      this.images.push(image);

      const reader = new FileReader();

      reader.onload = ev => {
        this.resize(ev.target.result, 127, dataURL => {
          this.previews = { ...this.preview, [image.name]: dataURL };
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
          const index = this.images.findIndex(i => i === image);

          image.state = "done";

          this.images = [
            ...this.images.slice(index),
            image,
            ...this.images.slice(index + 1)
          ];

          /* A PREVIEW SHOULD ALREADY EXIST IF THIS WAS UPLOADED JUST NOW, NO NEED TO REPLACE IT */
          if (!this.previews[data.value.originalName]) {
            this.previews[data.value.originalName] = data.preview;
          }

          this.$emit("input", [...(this.value || []), data.value]);
          console.log("success", data);
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
  justify-content: center;
  transition: all 0.5s ease;

  height: var(--sp-15);
  min-width: var(--sp-15);
}

.image-upload-actions {
  // margin-left: auto;
}

.image-holder {
  margin-right: var(--sp-5);
}

.image-preview {
  width: var(--sp-15);
  height: var(--sp-15);
  border-radius: 50%;
  object-fit: cover;
  border: 1px solid var(--grey-9);
  box-shadow: 1px 1px 4px hsla(var(--primary-v-6), 0.1);
}
</style>