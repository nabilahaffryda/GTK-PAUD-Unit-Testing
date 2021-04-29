<template>
  <v-card outlined :disabled="(selected && selected.disable) || false">
    <v-list-item @click="onClick">
      <v-list-item-content class="ma-0 pa-0">
        <template v-if="selected && selected !== null">
          <list-avatar
            :title="(selected && selected.nama) || ''"
            :subtitle="(selected && selected.desc) || ''"
            class="py-0"
          >
            <v-icon>{{ selected.icon }}</v-icon>
          </list-avatar>
        </template>
        <template v-else>
          <v-list-item-title class="text-capitalize" v-html="title"></v-list-item-title>
        </template>
      </v-list-item-content>
      <v-list-item-action>
        <v-btn icon>
          <v-icon color="grey lighten-1"> mdi-arrow-down-drop-circle-outline </v-icon>
        </v-btn>
      </v-list-item-action>
    </v-list-item>
  </v-card>
</template>

<script>
export default {
  name: 'SelectCard',
  props: {
    title: {
      type: String,
      default: 'Pilih selector',
    },
    selected: {
      type: Object,
    },
  },
  methods: {
    onClick() {
      this.$emit('click');
    },
  },
};
</script>

<style scoped></style>
