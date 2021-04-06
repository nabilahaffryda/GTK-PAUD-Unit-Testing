<template>
  <v-dialog
    v-model="dialog"
    :max-width="options.width ? options.width : 650"
    persistent
    scrollable
    transition="dialog-bottom-transition"
  >
    <v-card>
      <v-card-title class="pa-0" v-if="!(options && options.custom)">
        <v-toolbar dark :color="options.toolbarColor || 'secondary'" flat>
          <v-toolbar-title v-html="options.title || title"></v-toolbar-title>
          <v-spacer />
          <v-btn icon dark @click="onClose" v-if="!(options && options.persist)">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-toolbar>
      </v-card-title>
      <v-card-text :class="[options.custom ? 'border-top-studi' : '']">
        <slot name="content">
          <v-row class="my-5" dense no-gutters>
            <v-col cols="2" v-if="!options.noIcon">
              <v-avatar :color="options.avatarColor || '#FFE6E6'" size="64">
                <v-icon :color="options.iconColor || 'error'">
                  {{ options.icon || `mdi-alert` }}
                </v-icon>
              </v-avatar>
            </v-col>
            <v-col :cols="options.noIcon ? 12 : 10">
              <div v-html="message"></div>
            </v-col>
          </v-row>
        </slot>
      </v-card-text>
      <template v-if="(options && options.useBtn) || !((options && options.persist) || false)">
        <v-divider></v-divider>
        <v-card-actions>
          <slot name="action">
            <v-spacer></v-spacer>
            <v-btn :color="notif.color || '#26a69a'" dark @click="onAction" v-if="notif && notif.action">
              {{ notif.button }}
            </v-btn>
            <v-btn class="ma-2" :color="options.btnColor || '#26a69a'" dark @click="onClose" v-else>
              {{ (options && options.btnLabel) || 'OK' }}
            </v-btn>
          </slot>
        </v-card-actions>
      </template>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'PopupNotifikasi',
  data() {
    return {
      dialog: false,
      title: 'Pengumuman',
      message: '',
      options: {},
    };
  },
  props: {
    notif: {
      type: Object,
      default: () => {},
    },
  },
  methods: {
    open(message, title, options) {
      this.title = title || this.title;
      this.message = message || this.message;
      this.options = options || this.options;
      this.dialog = true;
    },
    onClose() {
      this.dialog = false;
      if (this.options && this.options.action) this.options && this.options.action();
    },
    onAction() {
      this.$nextTick(() => {
        this.dialog = false;
        this.$emit('action', this.notif.action);
      });
    },
  },
};
</script>

<style scoped>
.border-top-studi {
  border-top: 7px solid #00bfa5;
}
</style>
