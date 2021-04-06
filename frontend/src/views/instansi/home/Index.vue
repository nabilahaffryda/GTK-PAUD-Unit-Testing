<template>
  <div class="home">
    <v-row>
      <v-col>
        <v-card tile flat class="my-5">
          <v-card-text class="pa-0">
            <v-row no-gutters>
              <v-col cols="2">
                <div class="bg-kiri"></div>
              </v-col>
              <v-col cols="10" class="pa-5">
                <h1 class="headline">DIKLAT BERJENJANG GTK PAUD</h1>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-row>
      <template v-for="(menu, i) in menus">
        <template v-if="menu.program">
          <v-col cols="12" md="12" lg="12" class="pa-3" :key="i" v-if="$akseses(menu, 'menu').length">
            <div class="headline" v-html="menu.program" />
            <v-divider class="py-3" />
            <v-row>
              <template v-for="(child, c) in menu.menu">
                <v-col cols="12" md="4" lg="4" :key="c" v-if="$allow(child.akses)">
                  <base-card-menus v-bind="child" @action="onAction" :key="c" />
                </v-col>
              </template>
            </v-row>
          </v-col>
        </template>
        <template v-else>
          <template v-for="(child, c) in menu.menu">
            <v-col cols="12" md="4" lg="4" :key="c" v-if="$allow(child.akses)">
              <base-card-menus v-bind="child" @action="onAction" :key="c" />
            </v-col>
          </template>
        </template>
      </template>
    </v-row>
  </div>
</template>

<script>
import BaseCardMenus from '@/components/base/BaseCardMenus';
export default {
  name: 'home',
  components: {
    BaseCardMenus,
  },
  computed: {
    menus() {
      return this.$store.state.menus || [];
    },
  },
  methods: {
    onAction({ action }) {
      switch (action) {
        default:
          break;
      }
    },
  },
};
</script>

<style scoped>
.bg-kiri {
  background: #bbdefb;
  height: 100%;
}
</style>
