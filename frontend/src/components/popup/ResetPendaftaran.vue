<template>
  <v-dialog v-model="dialog" max-width="650" persistent transition="dialog-bottom-transition" scrollable>
    <v-card>
      <v-card-title class="pa-0">
        <v-toolbar flat dark color="error">
          <v-toolbar-title>Informasi Batal Pendaftaran</v-toolbar-title>
          <v-spacer />
          <v-btn right icon dark @click="close">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-toolbar>
      </v-card-title>
      <v-card-text class="pb-0 black--text my-5">
        <v-alert
          v-if="error"
          dense
          icon="mdi-information"
          close-text="Tutup"
          border="left"
          type="error"
          colored-border
          class="bg-error ma-0 mb-3"
        >
          <div class="black--text">{{ error }}</div>
        </v-alert>
        <div class="subtitle-1 black--text mb-2">
          Apakah Anda yakin akan melakukan
          <b>Batal Pendaftaran</b> sebagai <strong>Peserta - Kepala Sekolah</strong> {{ tahap }}
        </div>
        <div class="subtitle-1 black--text mb-2">
          Pastikan Anda telah memahami apa yang terjadi setelah melakukan proses Batal Pendaftaran:
          <ul>
            <li>
              Semua Data Pendaftaran yang telah diisikan akan dihapus dari sistem Sekolah Penggerak dan tidak bisa
              dikembalikan
            </li>
            <li>Status Anda akan dikembalikan seperti sebelum mendaftar.</li>
          </ul>
        </div>
        <div class="subtitle-1 black--text mb-4">
          <span class="px-0 body-2 black--text" style="height: 24px">Konfirmasi Kata Sandi</span>
          <v-text-field
            :append-icon="showPass ? 'mdi-eye' : 'mdi-eye-off'"
            :type="showPass ? 'text' : 'password'"
            @click:append="showPass = !showPass"
            ref="passwd"
            v-model="passwd"
            label="Konfirmasi Kata Sandi"
            required
            placeholder="Masukkan Kata Sandi Anda"
            filled
            dense
            outlined
            single-line
            hide-details
          />
        </div>
        <v-checkbox class="mt-2 mb-3" v-model="isChecked" hide-details>
          <template v-slot:label>
            <div class="black--text">
              Saya paham dan menyetujui Batal Pendaftaran Program Sekolah Penggerak
            </div>
          </template>
        </v-checkbox>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <div class="text-right">
          <v-btn text class="mr-2" :color="isChecked ? 'black' : 'grey'" left @click="onReset" :disabled="!isChecked">
            BATAL PENDAFTARAN
          </v-btn>
          <v-btn color="error" right @click="close" elevation="0">
            BATAL
          </v-btn>
        </div>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions } from 'vuex';
export default {
  name: 'PopupResetPendaftaran',
  props: {
    tahap: String,
  },
  data() {
    return {
      dialog: false,
      isChecked: false,
      error: null,
      showPass: false,
      passwd: null,
    };
  },
  methods: {
    ...mapActions('profil', ['batalDaftar']),

    open() {
      this.$set(this, 'error', null);
      this.$set(this, 'passwd', null);
      this.$set(this, 'isChecked', false);
      this.dialog = true;
    },

    close() {
      this.dialog = false;
    },

    onReset() {
      if (!this.passwd) {
        this.error = 'Pastikan Anda telah memasukan kata sandi';
        return;
      }

      const params = { password: this.passwd };
      this.batalDaftar({ params }).then(({ data }) => {
        if (data.message) {
          this.error = data.message;
          return;
        }

        this.$emit('reset');
      });
    },
  },
};
</script>
