<template>
  <v-snackbar top right :timeout="timeout" :color="color" style="opacity: 0.8" v-model="active">
    <v-card flat color="transparent">
      <v-card-text class="pa-0">
        <v-list color="transparent" class="pa-0">
          <v-list-item>
            <v-list-item-icon v-if="icon.length > 0">
              <v-icon x-large dark>{{ icon }}</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <span class="white--text body-1" v-html="text"></span>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-card-text>
      <v-divider light></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          class="text-right mt-1"
          right
          text
          v-for="(item, i) in button"
          :key="i"
          @click="
            () => {
              dismiss();
              if (item.event && typeof item.event === 'function') item.event();
            }
          "
        >
          {{ item.label }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-snackbar>
</template>

<script>
export default {
  name: 'Toast',
  data() {
    return {
      active: false,
      text: '',
      icon: '',
      color: 'info',
      timeout: 4000,
      dismissible: true,
      button: [{ label: 'Tutup' }],
    };
  },
  methods: {
    reset() {
      this.text = '';
      this.icon = '';
      this.color = 'info';
      this.timeout = 4000;
      this.dismissible = true;
      this.button = [{ label: 'Tutup' }];
      this.event = null;
    },

    show(options = {}) {
      // reset
      this.reset();

      if (this.active) {
        this.close();
        this.$nextTick(() => this.show(options));
        return;
      }

      Object.keys(options).forEach(
        (field) =>
          (this[field] = options[field] !== false && options[field] !== undefined ? options[field] : this[field])
      );

      this.active = true;
    },

    close() {
      this.active = false;
    },

    dismiss() {
      if (this.dismissible) {
        this.active = false;
      }
    },
  },
};
</script>

<style scoped></style>
