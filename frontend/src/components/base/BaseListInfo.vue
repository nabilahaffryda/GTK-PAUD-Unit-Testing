<template>
  <v-list-item>
    <v-list-item-content>
      <v-container class="pa-0">
        <v-row no-gutters v-for="(items, i) in info" :key="i">
          <template v-for="(item, j) in items">
            <v-col cols="12" :md="item.size || 6" class="pt-0 pb-2" :key="`${i}_${j}`">
              <v-row v-if="tipe === 'row'">
                <v-col cols="12" md="3" sm="3">
                  <span class="caption">{{ item && item.label }}</span>
                </v-col>
                <v-col cols="12" md="1" sm="1" class="px-0">
                  <span class="mx-2">:</span>
                </v-col>
                <v-col cols="12" md="8" sm="8" class="px-0">
                  <span style="white-space: normal" v-html="item.value || ''"></span>
                </v-col>
              </v-row>
              <template v-else>
                <v-list-item-subtitle class="caption">
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
              </template>
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
    tipe: {
      type: String,
      default: '',
    },
  },
};
</script>
<style scoped></style>
