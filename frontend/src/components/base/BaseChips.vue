<template>
  <v-chip class="ma-1 pr-4" :color="color ? color : M_WARNA[colorIdx || 0]" small>
    <v-avatar v-if="chip.icon">
      <v-icon small>{{ chip.icon }}</v-icon>
    </v-avatar>
    {{ chip.label }}
  </v-chip>
</template>

<script>
export default {
  name: 'Chips',
  props: {
    chip: {
      type: Object,
      required: true,
    },
    color: {
      type: String,
    },
    colorIdx: {
      type: Number,
    },
  },
  data() {
    return {
      M_WARNA: {
        0: 'error-box',
        1: 'success-box',
        2: 'warning-box',
        3: 'primary-box',
        4: 'second-box',
      },
    };
  },
};
</script>

<style scoped>
/* v-chip coloring */
.success-box {
  background-color: #bbf5bb !important;
  border-color: #99c995 !important;
  color: #19601c !important;
}
.warning-box {
  background-color: #ffeaa7 !important;
  border-color: #d9c483 !important;
  color: #8a6a2f !important;
}
.error-box {
  background-color: #ffcdd2 !important;
  border-color: #cb9ca1 !important;
  color: #7f0000 !important;
}
.primary-box {
  background-color: #e3f2fd !important;
  border-color: #bbdefb !important;
  color: #2196f3 !important;
}
.second-box {
  background-color: #fafafa !important;
  border-color: #f5f5f5 !important;
  color: #9e9e9e !important;
}
</style>
