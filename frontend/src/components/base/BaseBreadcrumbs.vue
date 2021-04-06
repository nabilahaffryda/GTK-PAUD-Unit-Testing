<template>
  <v-breadcrumbs large :items="items" divider="/">
    <template v-if="useIcon" v-slot:divider>
      <v-icon>{{ icon }}</v-icon>
    </template>
    <template v-slot:item="{ item }">
      <v-breadcrumbs-item :class="[{ link: item.to }, { 'secondary--text': item.to }]">
        <div v-if="item.to" @click="toLink(item)" style="cursor: pointer">
          {{ item.text }}
        </div>
        <div v-else style="cursor: default">{{ item.text }}</div>
      </v-breadcrumbs-item>
    </template>
  </v-breadcrumbs>
</template>

<script>
export default {
  props: {
    items: {
      type: Array,
      default: () => [],
    },
    icon: {
      type: String,
      default: 'mdi-chevron-right',
    },
    useIcon: {
      type: Boolean,
      default: true,
    },
  },
  methods: {
    toLink(item) {
      this.$router.push({
        name: item.to,
        params: Object.assign({}, item.params),
      });
    },
  },
};
</script>
