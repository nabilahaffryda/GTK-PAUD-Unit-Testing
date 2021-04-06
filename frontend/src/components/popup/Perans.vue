<template>
  <v-dialog v-model="dialog" scrollable max-width="400px" persistent>
    <v-card>
      <v-card-title class="headline">Ubah Peran Anda</v-card-title>
      <v-divider />
      <v-card-text>
        <v-list flat dense>
          <template v-for="(item, i) in roles">
            <v-list-item class="grey--text" :key="i" @click="onSwitch(item)">
              <v-list-item-icon><v-icon>mdi-swap-horizontal</v-icon></v-list-item-icon>
              <v-list-item-title class="grey--text text--darken-2">
                {{ item && item.label }}
              </v-list-item-title>
            </v-list-item>
          </template>
        </v-list>
      </v-card-text>
      <v-divider />
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click.native="dialog = false">
          Batal
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import { mapActions } from 'vuex';
export default {
  data() {
    return {
      dialog: false,
      roles: [
        { key: 'instansi', label: 'Admin Instansi' },
        { key: 'gtk', label: 'GTK' },
      ],
    };
  },
  methods: {
    ...mapActions('preferensi', ['getPreferensi']),

    open() {
      this.dialog = true;
    },

    onSwitch(role) {
      this.dialog = false;
      this.$store.commit('auth/SET_ROLE', role.key);
      window.location.href = '/';
    },
  },
};
</script>
