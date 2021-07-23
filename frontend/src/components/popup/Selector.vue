<template>
  <v-dialog v-model="dialog" scrollable max-width="700px">
    <v-card>
      <v-card-title class="headline">
        <span class="text-capitalize" v-html="title"></span>
      </v-card-title>
      <v-divider />
      <v-card-text>
        <base-list-table
          :hideHeader="true"
          :loading="loading"
          :item="data"
          :total="total"
          :usePaging="false"
          @fetch="fetchData"
        >
          <template slot-scope="{ item }">
            <td>
              <slot :item="item"></slot>
            </td>
          </template>
        </base-list-table>
      </v-card-text>
      <v-divider />
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" @click.native="onSave">Simpan</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import mixin from '@mixins/list';

export default {
  name: 'popupSelector',
  mixins: [mixin],
  props: {
    title: {
      type: String,
      default: 'Pilih Selector',
    },
    fetch: {
      type: Function,
    },
    filters: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      dialog: false,
    };
  },
  methods: {
    open() {
      this.dialog = true;
    },

    close() {
      this.dialog = false;
    },

    onSave() {
      this.$emit('save');
    },
  },
};
</script>
