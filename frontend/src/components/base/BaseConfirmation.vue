<template>
  <v-row justify="center">
    <v-dialog v-model="modal" max-width="550" @keydown.esc="cancel" persistent>
      <v-card>
        <v-card-title :class="[tipe, 'title', myClass[tipe]]">
          <v-icon :class="['mr-2', myClass[tipe]]">{{ icon || myIcons[tipe] }}</v-icon>
          <span v-html="title"></span>
        </v-card-title>
        <v-container class="grey lighten-2" v-if="items.length">
          <template v-for="(item, index) in items">
            <v-list v-if="item.title" :key="item.title" expand>
              <v-list-item class="px-0 pb-0">
                <v-list-item-avatar :color="item.colorAvatar" class="text-center ml-3">
                  <template v-if="item.icon">
                    <v-icon :color="item.iconColor" :size="item.iconSize || 40">{{ item.icon }}</v-icon>
                  </template>
                  <v-img v-else :src="$imgUrl(item.foto)" aspect-ratio="1" class="grey lighten-2"></v-img>
                </v-list-item-avatar>
                <v-list-item-content>
                  <span class="info--text" v-html="item.title" style="line-height: 1.5rem"></span>
                  <template v-for="(item2, index2) in item.subtitles">
                    <v-list-item-subtitle
                      v-html="item2"
                      :key="index2"
                      class="mb-0"
                      style="line-height: 1rem"
                    ></v-list-item-subtitle>
                  </template>
                </v-list-item-content>
              </v-list-item>
            </v-list>
            <v-divider v-else :inset="item.inset" :key="index"></v-divider>
          </template>
        </v-container>
        <v-container grid-list-md v-if="form && form.render">
          <v-layout wrap class="pa-2">
            <span class="pa-1" v-html="form.desc"></span>
            <base-render :renderEl="form.render" :params="form.params"></base-render>
          </v-layout>
        </v-container>
        <v-card-text
          v-html="desc"
          :class="{
            'py-0': Object.keys(form).length,
            'py-2': !Object.keys(form).length,
          }"
        ></v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <template v-if="invert">
            <v-btn :color="lblConfirmColor" text @click="agree">
              {{ lblConfirmButton }}
            </v-btn>
            <v-btn v-if="confirmation" :color="lblCancelColor" text @click="cancel">
              {{ lblCancelButton }}
            </v-btn>
          </template>
          <template v-else>
            <v-btn v-if="confirmation" :color="lblCancelColor" text @click="cancel">
              {{ lblCancelButton }}
            </v-btn>
            <v-btn :color="lblConfirmColor" text @click="agree">
              {{ lblConfirmButton }}
            </v-btn>
          </template>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
import BaseRender from '@/components/base/BaseRender';
export default {
  components: { BaseRender },
  data() {
    return {
      title: null,
      desc: null,
      tipe: 'warning',
      lblCancelButton: 'Tidak',
      lblConfirmButton: 'Ya',
      lblConfirmColor: 'blue darken-1',
      lblCancelColor: 'grey darken-1',
      items: [],
      form: '',
      resolve: null,
      reject: null,
      modal: false,
      confirmation: true,
      invert: false,
      icon: '',
      myIcons: {
        warning: 'mdi-alert',
        error: 'mdi-close-circle',
        info: 'mdi-information',
        success: 'mdi-check-circle',
      },
      myClass: {
        warning: '',
        error: 'white--text',
        info: ' white--text',
        success: ' white--text',
      },
    };
  },
  methods: {
    open(message, title, options = {}) {
      this.title = title || 'Konfirmasi';
      this.desc = message;

      // apply keterangan jika ada
      if (options && this.$isObject(options)) {
        this.items = options.data || [];
        this.tipe = options.tipe || this.tipe;
        this.form = options.form || {};
        this.icon = options.icon || '';
        this.invert = options.invert || false;
        this.useReject = options.useReject || false;

        (this.lblCancelButton = options.lblCancelButton || 'Tidak'),
          (this.lblConfirmButton = options.lblConfirmButton || 'Ya'),
          (this.confirmation = options.confirmation !== undefined ? options.confirmation : true),
          (this.lblConfirmColor = options.lblConfirmColor !== undefined ? options.lblConfirmColor : 'blue darken-1'),
          (this.lblCancelColor = options.lblCancelColor !== undefined ? options.lblCancelColor : 'grey darken-1');
      }
      // open
      this.modal = true;
      return new Promise((resolve, reject) => {
        this.resolve = resolve;
        this.reject = reject;
      });
    },
    agree() {
      this.modal = false;
      this.resolve(true);
    },

    cancel() {
      this.modal = false;
      if (this.useReject) this.reject('Anda memilih Tidak!');
    },
  },
};
</script>
