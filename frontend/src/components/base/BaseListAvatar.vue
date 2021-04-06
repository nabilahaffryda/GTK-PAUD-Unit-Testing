<template>
  <v-list>
    <v-list-item class="px-0">
      <v-list-item-avatar class="mr-1" :tile="tile" :color="color">
        <slot />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title class="link body-2" @click="onDetail" v-html="title"> </v-list-item-title>
        <v-list-item-subtitle class="caption" v-html="subtitle" />
      </v-list-item-content>
      <v-list-item-action v-if="useAction">
        <slot name="action" />
      </v-list-item-action>
    </v-list-item>
  </v-list>
</template>

<script>
export default {
  name: 'BaseListAvatar',
  props: {
    size: {
      type: Number,
      default: 32,
    },
    tile: {
      type: Boolean,
      default: false,
    },
    useAction: {
      type: Boolean,
      default: false,
    },
    color: {
      type: String,
      default: '',
    },
    caption: {
      type: String,
      default: '',
    },
    title: {
      type: String,
      required: true,
    },
    subtitle: {
      type: String,
      default: '',
    },
    detail: {
      type: Object,
      default: () => null,
    },
  },
  methods: {
    onDetail() {
      this.$emit('detail', this.detail);
    },
  },
};
</script>

<style scoped>
.link {
  white-space: normal;
  cursor: pointer;
}
</style>
