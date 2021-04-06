<template>
  <v-main id="layout" class="fill-height">
    <v-container class="fill-height main" fluid>
      <router-view :key="$route.fullPath" />
    </v-container>
  </v-main>
</template>

<style scoped>
.main {
  font-family: 'Roboto Slab', serif;
  background: linear-gradient(to bottom right, #7ff2ff, #549dff);
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  height: 100%;
}
</style>
