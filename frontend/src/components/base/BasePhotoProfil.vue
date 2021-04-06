<template>
  <div class="box-card">
    <v-btn id="edit-profpic" class="prof-pic grey--text text--darken-2" right icon v-if="isEdit">
      <v-icon large>mdi-camera</v-icon>
    </v-btn>
    <template>
      <v-img :src="image" :aspect-ratio="aspectRatio" />
    </template>
    <profil-picture
      v-if="isEdit"
      :uploadUrl="uploadUrl"
      :useBase64="useBase64"
      trigger="#edit-profpic"
      @upload="upload"
      @uploaded="uploaded"
    />
  </div>
</template>

<script>
import ProfilPicture from '../popup/ProfilPicture';
export default {
  name: 'BasePhotoProfil',
  components: { ProfilPicture },
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
    aspectRatio: {
      type: Number,
      default: 4 / 6,
    },
    useBase64: {
      type: Boolean,
      default: false,
    },
    photo: {
      type: String,
      required: true,
    },
    photodef: {
      type: String,
      default: 'avatar.png',
    },
    uploadUrl: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      image: '',
    };
  },
  mounted() {
    this.checkImage(this.photo);
  },
  methods: {
    upload(data) {
      this.$emit('upload', data);
    },
    uploaded() {
      this.$success('Unggah Foto berhasil disimpan');
      this.$emit('reload');
    },
    checkImage(imageSrc) {
      let img = new Image();
      img.onload = () => {
        this.image = imageSrc;
      };
      img.onerror = () => {
        this.image = this.$imgUrl(this.photodef);
      };
      img.src = imageSrc;
    },
  },
  watch: {
    photo: 'checkImage',
  },
};
</script>
<style scoped>
.box-card {
  position: relative;
}
.prof-pic {
  position: absolute;
  z-index: 1;
  right: 3%;
  top: 3%;
}
</style>
