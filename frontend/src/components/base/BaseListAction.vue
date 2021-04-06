<template>
  <div>
    <v-menu bottom left v-if="$allow(actions.map((item) => item.akses).join('|'), data.policies)">
      <template v-slot:activator="{ on, attrs }">
        <v-btn v-bind="attrs" v-on="on" icon>
          <v-icon :color="`${menuColor} lighten-1`">mdi-dots-vertical</v-icon>
        </v-btn>
      </template>
      <v-list dense>
        <template v-for="(action, i) in actions">
          <v-list-item :key="i" @click="onAction(action, data, id)" v-if="allow(action, data)">
            <v-list-item-icon style="margin-right: 8px">
              <v-icon v-text="action.icon" />
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title v-html="action.title" />
            </v-list-item-content>
          </v-list-item>
        </template>
      </v-list>
    </v-menu>
    <template v-else>
      <p class="pa-5">&nbsp;</p>
    </template>
  </div>
</template>

<script>
export default {
  name: 'BaseListAction',
  props: {
    data: {
      type: Object,
      default: () => {},
    },
    id: {
      type: Number,
      default: null,
    },
    actions: {
      type: Array,
      default: () => [],
    },
    allow: {
      type: Function,
    },
    menuColor: {
      type: String,
      default: 'grey',
    },
  },
  methods: {
    onAction(aksi, data, id) {
      this.$emit('action', { event: aksi, data, id });
    },
  },
};
</script>

<style scoped></style>
