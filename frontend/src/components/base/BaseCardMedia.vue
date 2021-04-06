<template>
  <v-list color="transparent">
    <v-list-item :href="url">
      <v-list-item-content class="pa-0 ma-0">
        <v-card flat :class="['align-center text-center', { 'py-4': !noPadding }]" :color="color">
          <v-card-text :class="{ 'pa-0': noPadding }">
            <v-avatar :size="size" :color="avatarColor" :tile="tile">
              <slot />
            </v-avatar>
          </v-card-text>
        </v-card>
      </v-list-item-content>
    </v-list-item>
    <v-list-item class="text-center">
      <v-list-item-content :class="{ 'pa-0': noPadding, 'py-1': !noPadding }">
        <v-list-item-title class="font-weight-bold title" v-html="title" />
        <p class="body-2 grey--text" v-html="desc" />
        <p class="caption font-weight-bold my-2 align-bottom">
          <slot name="status" />
        </p>
      </v-list-item-content>
    </v-list-item>
  </v-list>
</template>

<script>
export default {
  name: 'CardMedia',
  props: {
    noPadding: {
      type: Boolean,
      default: false,
    },
    tile: {
      type: Boolean,
      default: false,
    },
    avatarColor: {
      type: String,
      default: 'yellow lighten-3',
    },
    color: {
      type: String,
      default: '',
    },
    size: {
      type: Number,
      default: 95,
    },
    title: {
      type: String,
      default: '',
    },
    desc: {
      type: String,
      default: '',
    },
    url: {
      type: String,
      default: '',
    },
  },
};
</script>

<style scoped></style>
