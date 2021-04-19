<template>
  <v-dialog v-model="dialog" width="950" scrollable>
    <v-card flat>
      <v-toolbar color="secondary" dark flat>
        <v-toolbar-title v-html="title"></v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click.native="dialog = false">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-divider></v-divider>
      <v-card-text class="pa-0" style="height: 500px">
        <template v-if="type === 'pdf'">
          <div ref="viewer-pdf" id="pdf-viewer" style="height: 475px"></div>
        </template>
        <template v-else>
          <viewer :options="imgOpt" :images="images" @inited="inited" class="viewer" ref="viewer">
            <template slot-scope="scope">
              <template v-for="src in scope.images">
                <div :style="`transform: rotate(${rotate}deg) scale(${scale});`" :key="src">
                  <img :src="$imgUrl(src)" @click="show" width="900" />
                </div>
              </template>
            </template>
          </viewer>
        </template>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-text class="py-2 grey" style="overflow: hidden" v-if="type !== 'pdf'">
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn icon color="white" v-bind="attrs" v-on="on" @click="rotateLeft">
              <v-icon>mdi-rotate-left</v-icon>
            </v-btn>
          </template>
          <span>putar ke kiri</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn icon color="white" v-bind="attrs" v-on="on" @click="rotateRight">
              <v-icon>mdi-rotate-right</v-icon>
            </v-btn>
          </template>
          <span>putar ke kanan</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn icon color="white" v-bind="attrs" v-on="on" @click="zoomOut">
              <v-icon>mdi-magnify-minus-outline</v-icon>
            </v-btn>
          </template>
          <span>perkecil</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn icon color="white" v-bind="attrs" v-on="on" @click="zoomIn">
              <v-icon>mdi-magnify-plus-outline</v-icon>
            </v-btn>
          </template>
          <span>perbesar</span>
        </v-tooltip>
      </v-card-text>
      <v-card-actions>
        <slot></slot>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import PDFObject from 'pdfobject';
import { getUrlExtension } from '@utils/format';
export default {
  name: 'PopupPreviewDetil',
  props: {
    title: {
      type: String,
      default: 'Detil',
    },
    url: {
      type: String,
      required: true,
    },
  },
  components: {
    Viewer: () => import('v-viewer/src/component.vue'),
  },
  data() {
    return {
      dialog: false,
      currentPage: 1,
      rotate: 0,
      scale: 1,
      pageCount: 0,
      imgOpt: {
        navbar: 0,
        title: 0,
        toolbar: {
          zoomIn: {
            show: 1,
            size: 'large',
          },
          zoomOut: {
            show: 1,
            size: 'large',
          },
          oneToOne: {
            show: 1,
            size: 'large',
          },
          reset: {
            show: 1,
            size: 'large',
          },
          prev: 0,
          play: 0,
          next: 0,
          rotateLeft: {
            show: 1,
            size: 'large',
          },
          rotateRight: {
            show: 1,
            size: 'large',
          },
          flipHorizontal: 0,
          flipVertical: 0,
        },
      },
      $viewer: null,
    };
  },
  computed: {
    images() {
      return [this.url || `bg-404.png`];
    },
    type() {
      return getUrlExtension(this.url) || 'jpg';
    },
  },
  methods: {
    open() {
      this.currentPage = 1;
      this.rotate = 0;
      this.scale = 1;
      this.dialog = true;
      if (this.type === 'pdf') {
        this.$nextTick(() => {
          PDFObject.embed(this.url, '#pdf-viewer');
        });
      }
    },
    inited(viewer) {
      this.$viewer = viewer;
    },
    show() {
      this.$viewer.show();
    },
    newTab() {
      window.open(this.url, '_blank');
    },
    rotateLeft() {
      this.rotate -= 90;
    },
    rotateRight() {
      this.rotate += 90;
    },
    zoomOut() {
      if (this.scale > 0.5) this.scale -= 0.5;
    },
    zoomIn() {
      if (this.scale < 4) this.scale += 0.5;
    },
  },
};
</script>

<style scoped></style>
