<template>
  <v-container fluid class="px-4">
    <v-card flat>
      <v-container v-if="location && location.lat && location.lng">
        <v-toolbar elevation="0" dense>
          <v-list class="pb-0">
            <v-list-item class="pa-0">
              <v-list-item-content class="pa-0">
                <v-list-item-title class="title">
                  <h3>{{ title }}</h3>
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-toolbar>
        <div class="px-4 pb-2">
          <GmapMap
            ref="mapRef"
            :options="{
              zoomControl: true,
              mapTypeControl: false,
              scaleControl: false,
              streetViewControl: false,
              rotateControl: false,
              fullscreenControl: true,
              disableDefaultUi: false,
              gestureHandling: 'cooperative',
            }"
            :center="location"
            :zoom="zoom"
            :style="`width: ${width}; height: ${height}`"
          >
            <gmap-marker
              v-for="(m, index) in markers"
              :icon="$imgUrl(icon)"
              :position="m"
              :key="index"
              :clickable="true"
              @click="onInfoWindow(m)"
            />
            <GmapInfoWindow
              :options="{ maxWidth: 300 }"
              :position="iWindow.position"
              :opened="iWindow.open"
              @closeclick="iWindow.open = false"
            >
              <div v-html="iWindow.template"></div>
            </GmapInfoWindow>
          </GmapMap>
        </div>
      </v-container>
    </v-card>
  </v-container>
</template>

<script>
export default {
  name: 'ListMap',
  props: {
    title: {
      type: String,
      default: '',
    },
    location: {
      type: Object,
      default: () => {},
    },
    zoom: {
      type: Number,
      default: 13,
    },
    width: {
      type: String,
      default: '100%',
    },
    height: {
      type: String,
      default: '300px',
    },
    markers: {
      type: Array,
      default: () => [],
    },
    icon: {
      type: String,
      default: 'icn-map-lokasi.png',
    },
  },
  data() {
    return {
      iWindow: { open: false },
    };
  },
  methods: {
    onInfoWindow() {},
  },
};
</script>

<style scoped>
.link {
  white-space: normal;
  cursor: pointer;
}
</style>
