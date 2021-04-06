<template>
  <v-list-item>
    <v-list-item-content>
      <v-container class="pa-0">
        <v-row no-gutters v-for="(items, i) in info" :key="i">
          <template v-for="(item, j) in items">
            <v-col cols="12" :md="item.size || 6" class="pt-0 pb-2" :key="`${i}_${j}`">
              <v-list-item-subtitle class="caption grey--text">
                {{ item && item.label }}
              </v-list-item-subtitle>
              <v-list-item-title class="black--text">
                <template v-if="item.type === 'chips'">
                  <base-chips
                    :chip="{
                      label: item && item.value,
                      icon: item && item.icon,
                    }"
                    :color="item && item.color"
                  ></base-chips>
                </template>
                <span style="white-space: normal" v-else v-html="item.value || ''" />
              </v-list-item-title>
            </v-col>
          </template>
        </v-row>
      </v-container>
    </v-list-item-content>
  </v-list-item>
</template>

<script>
export default {
  name: 'BaseListInfo',
  props: {
    info: {
      type: Array,
      required: true,
    },
  },
};
</script>
<style scoped></style>
