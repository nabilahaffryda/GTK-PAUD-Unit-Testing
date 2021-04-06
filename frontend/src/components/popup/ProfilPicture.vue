<template>
  <avatar-cropper
    :cropper-options="options"
    :labels="labels"
    :trigger="trigger"
    :upload-form-name="nama"
    :upload-url="useBase64 ? '' : uploadUrl"
    :upload-handler="useBase64 ? onUpload : false"
    @uploaded="onUploaded"
    @changed="onChange"
  />
</template>

<script>
import AvatarCropper from 'vue-avatar-cropper';
export default {
  name: 'ProfilPicture',
  components: { AvatarCropper },
  props: {
    title: {
      type: String,
      default: '',
    },
    trigger: {
      type: String,
      default: '',
      required: true,
    },
    uploadUrl: {
      type: String,
      default: '',
    },
    useBase64: {
      type: Boolean,
      default: false,
    },
    outputMime: {
      type: String,
      default: 'image/jpeg',
    },
    nama: {
      type: String,
      default: 'foto',
    },
    limit: {
      type: Number,
      default: 2048,
    },
  },
  data() {
    return {
      labels: { submit: 'Simpan', cancel: 'Batal' },
      options: {
        aspectRatio: 4 / 6,
        autoCropArea: 4 / 6,
        viewMode: 1,
        movable: true,
        zoomable: true,
      },
    };
  },
  methods: {
    onUpload(cropper) {
      let imgBase64 = cropper.getCroppedCanvas().toDataURL(this.outputMime);
      const uploaddata = new FormData();
      uploaddata.append(this.nama, imgBase64);

      if (!imgBase64.match(/data:image\/(gif|jpeg|png);base64,(.*)/i)) {
        this.$error('Format file yang diunggah tidak sesuai');
        return;
      }

      this.$emit('upload', uploaddata);
    },
    onUploaded(resp) {
      this.$emit('uploaded', resp);
    },
    onChange(file) {
      if (file && file.size >= this.limit * 1000)
        this.$error(
          `Ukuran File gambar yang dipilih melebihi ketentuan yang diperbolehkan.
         Pilih file dengan ukuran kurang dari ${Math.round(this.limit / 1024)} MB`
        );
    },
  },
};
</script>

<style>
.avatar-cropper-overlay {
  z-index: 999 !important;
}
</style>
