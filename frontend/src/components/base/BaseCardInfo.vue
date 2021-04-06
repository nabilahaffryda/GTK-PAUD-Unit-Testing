<template>
  <v-card flat outlined class="my-2">
    <v-list-item>
      <v-list-item-avatar :size="size" color="primary">
        <v-icon v-if="icon" dark v-text="icon"></v-icon>
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title v-html="title"></v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-card-text class="pt-0">
      <span class="ml-12 headline blue--text" v-html="info"></span>
      <slot></slot>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: 'CardInfo',
  props: {
    size: {
      type: Number,
      default: 28,
    },
    icon: {
      type: String,
      default: '',
    },
    title: {
      type: String,
      default: '',
    },
    info: {
      type: String,
      default: '',
    },
  },
};
</script>

<style scoped></style>
