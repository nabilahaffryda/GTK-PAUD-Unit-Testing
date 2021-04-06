<template>
  <v-card
    outlined
    tile
    :disabled="disable"
    :to="to || undefined"
    :href="href || undefined"
    :target="target || undefined"
    @click="onAction"
  >
    <v-list-item :class="[disable ? 'grey' : color, deepColor]">
      <v-list-item-content style="margin-left: -17px; background: transparent" :disabled="false">
        <slot name="content"></slot>
      </v-list-item-content>
      <v-list-item-icon>
        <v-icon size="80" color="white" v-text="icon"></v-icon>
      </v-list-item-icon>
    </v-list-item>
    <v-list-item dark :class="[disable ? 'grey' : color, deepColor]">
      <v-list-item-content>
        <v-list-item-title class="title h mb-1" v-html="title"></v-list-item-title>
        <v-list-item-subtitle class="subtitle-1" v-html="subtitle"></v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>
    <v-card-text class="text--primary" style="min-height: 100px">
      <span v-html="desc"></span>
      <slot name="button"></slot>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: 'BaseCardMenus',
  props: {
    title: {
      type: String,
      required: true,
    },
    subtitle: {
      type: String,
      default: '',
    },
    desc: {
      type: String,
      required: true,
    },
    icon: {
      type: String,
      default: 'mdi-help-circle',
    },
    color: {
      type: String,
      required: true,
    },
    deepColor: {
      type: String,
      required: true,
    },
    akses: {
      default: true,
    },
    disable: {
      type: Boolean,
      default: false,
    },
    to: {
      type: Object,
      default: () => {},
    },
    href: {
      type: String,
      default: '',
    },
    target: {
      type: String,
      default: '',
    },
    action: {
      type: String,
      default: '',
    },
  },
  methods: {
    onAction() {
      this.$emit('action', { action: this.action, title: this.title });
    },
  },
};
</script>

<style scoped></style>
