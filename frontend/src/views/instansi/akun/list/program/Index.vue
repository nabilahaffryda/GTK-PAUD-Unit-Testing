<template>
  <list-admin
    jenis="program"
    akses="admin-program-lpd"
    title="Akun Admin Program LPD"
    desc=""
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
