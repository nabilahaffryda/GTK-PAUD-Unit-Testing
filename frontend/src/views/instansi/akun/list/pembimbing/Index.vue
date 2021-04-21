<template>
  <list-admin
    jenis="kelas"
    akses="pembimbing-praktik"
    title="Akun Pembimbing Praktik"
    desc="Lorem Ipsum"
    :actions="actions"
  ></list-admin>
</template>

<script>
import ListAdmin from '../base/Main';
import Actions from './actions';
export default {
  name: 'Program',
  components: { ListAdmin },
  data() {
    return {
      actions: Actions,
    };
  },
};
</script>

<style scoped></style>
