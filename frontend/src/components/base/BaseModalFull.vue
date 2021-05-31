<template>
  <v-dialog v-model="dialog" fullscreen hide-overlay persistent transition="dialog-bottom-transition">
    <v-card color="#efefef">
      <validation-observer ref="observer" v-slot="{ errors }">
        <v-toolbar dark color="secondary" style="height: 64px; position: fixed; width: 100%; z-index: 99">
          <v-btn icon dark @click="close">
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title v-html="altTitle || title" />
          <v-spacer />
          <template v-if="mode === 'form'">
            <v-btn
              v-if="useSave"
              class="white--text mr-5"
              :color="colorBtn || `primary`"
              dark
              @click="save()"
              :loading="loading && !(error && error.show)"
              :disabled="loading && !(error && error.show)"
              >{{ lblBtn }}</v-btn
            >
          </template>
        </v-toolbar>
        <v-container :fluid="fluid" class="py-12">
          <v-tabs v-if="tabHeader.length" v-model="tabs" background-color="transparent" grow centered>
            <v-tab v-for="item in tabHeader" :key="item">
              {{ item }}
            </v-tab>
          </v-tabs>

          <div style="margin-top: 60px">
            <v-alert
              class="mt-7 mb-2"
              type="error"
              v-if="(!valid && showError(errors).length) || (error && error.show)"
            >
              <template v-if="generalError"> Pastikan Anda telah melengkapi semua isian data yang diwajibkan </template>
              <template v-else>
                <v-row align="center">
                  <v-col class="grow">
                    <ul v-if="showError(errors) && showError(errors).length">
                      <li v-for="(item, i) in showError(errors)" :key="i">
                        {{ item }}
                      </li>
                    </ul>
                    <p v-else-if="error && error.desc" v-html="error.desc"></p>
                  </v-col>
                </v-row>
              </template>
            </v-alert>
            <slot></slot>
          </div>
        </v-container>
      </validation-observer>
    </v-card>
  </v-dialog>
</template>

<script>
import { arrayFlat } from '@utils/format';
import { ValidationObserver } from 'vee-validate';
export default {
  name: 'ModalFull',
  components: { ValidationObserver },
  props: {
    title: {
      type: String,
      default: 'Modal',
    },
    tabHeader: {
      type: Array,
      default: () => [],
    },
    mode: {
      type: String,
      default: 'form',
    },
    generalError: {
      type: Boolean,
      default: false,
    },
    useSave: {
      type: Boolean,
      default: true,
    },
    colorBtn: {
      type: String,
      default: 'primary',
    },
    lblBtn: {
      type: String,
      default: 'Simpan',
    },
    autoClose: {
      type: Boolean,
      default: true,
    },
    fluid: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      dialog: false,
      loading: false,
      altTitle: null,
      valid: true,
      error: null,
      tabs: 0,
    };
  },
  methods: {
    open(title) {
      this.dialog = true;
      this.loading = false;
      this.error = {};
      if (this.$refs.observer) this.$refs.observer.reset();
      if (title) this.altTitle = title;
    },
    close() {
      if (this.autoClose) {
        this.dialog = false;
      }

      this.loading = false;
      this.$emit('close');
    },
    showError(errors) {
      const listError = Object.values(errors).filter((errors) => errors.length);
      return arrayFlat(listError);
    },
    setError(msg) {
      this.error.show = true;
      this.error.desc = msg;
    },
    async save() {
      this.error = {};
      this.valid = await this.$refs.observer.validate();
      if (this.valid) {
        this.loading = true;
        this.$emit('save');
      }
    },
    changeTabs(value) {
      this.$emit('tab', value);
    },
    async onValidate() {
      return await this.$refs.observer.validate();
    },
  },
  watch: {
    tabs: 'changeTabs',
  },
};
</script>
