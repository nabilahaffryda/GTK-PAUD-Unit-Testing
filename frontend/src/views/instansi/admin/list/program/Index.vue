<template>
  <list-admin jenis="program" title="Akun Admin Program" desc="Lorem Ipsum" :action="action"></list-admin>
</template>

<script>
import ListAdmin from '../Main';
import Action from './actions';
export default {
  name: 'Program',
  components: { ListAdmin },
  data() {
    return {
      action: Action,
    };
  },
};
</script>

<style scoped></style>
