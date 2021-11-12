<template>
  <list-diklat :jenis="jenis" :actions="actions" :akses="akses" />
</template>
<script>
import actions from '../diklat/actions';
import ListDiklat from '../diklat/Index';
export default {
  components: { ListDiklat },
  computed: {
    jenis() {
      return this.$route.meta.tipe;
    },

    actions() {
      return actions[this.jenis];
    },

    akses() {
      return this.jenis === 'daring' ? 'lpd-diklat' : 'lpd-luring-diklat';
    },
  },
};
</script>
