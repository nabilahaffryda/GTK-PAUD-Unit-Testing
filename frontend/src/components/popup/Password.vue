<template>
  <v-dialog v-model="dialog" width="350" persistent>
    <v-card flat>
      <v-toolbar color="secondary" dark flat>
        <v-toolbar-title>Ganti Kata Sandi</v-toolbar-title>
      </v-toolbar>
      <v-divider />
      <v-card-text class="pa-0 elevation-0" style="min-height: 150px">
        <v-container fluid class="py-1">
          <v-row>
            <v-col cols="12">
              <v-form ref="form" v-model="valid" lazy-validation>
                <v-text-field
                  :append-icon="pass1 ? 'mdi-eye-off' : 'mdi-eye'"
                  :rules="[(v) => !!v || 'Kata Sandi Lama wajib diisi']"
                  :type="pass1 ? 'text' : 'password'"
                  @click:append="pass1 = !pass1"
                  label="Kata Sandi Lama"
                  required
                  outlined
                  v-model="params.password_lama"
                />
                <v-text-field
                  :append-icon="pass2 ? 'mdi-eye-off' : 'mdi-eye'"
                  :rules="[(v) => !!v || 'Kata Sandi Barus wajib diisi']"
                  :type="pass2 ? 'text' : 'password'"
                  @click:append="pass2 = !pass2"
                  label="Kata Sandi Baru"
                  required
                  outlined
                  v-model="params.password"
                />
                <v-text-field
                  :append-icon="pass3 ? 'mdi-eye-off' : 'mdi-eye'"
                  :rules="rules"
                  :type="pass3 ? 'text' : 'password'"
                  @click:append="pass3 = !pass3"
                  label="Ulangi Kata Sandi Baru"
                  required
                  outlined
                  v-model="params.password_confirmation"
                />
              </v-form>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-divider />
      <v-card-actions>
        <v-btn color="blue darken-1" text @click.native="dialog = false"> Batal </v-btn>
        <v-spacer />
        <v-btn color="red darken-1" right :disabled="!valid" text @click="submit"> Simpan </v-btn>
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
      valid: true,
      pass1: false,
      pass2: false,
      pass3: false,
      rules: [
        (v) => !!v || 'Konfirmasi Kata Sandi Baru wajib diisi!',
        (v) => (v && v === this.params.password) || 'Konfirmasi Kata Sandi Baru tidak sama!',
      ],
      params: {},
    };
  },
  methods: {
    ...mapActions('profil', ['password']),

    open() {
      this.dialog = true;
      this.clear();
    },
    submit() {
      if (this.$refs.form.validate()) {
        this.$confirm('<p>Anda yakin ingin GANTI KATA SANDI Anda diatas?</p>', 'Ganti Kata Sandi', {
          tipe: 'error',
          data: '',
        }).then(() => {
          this.password(this.params).then(() => {
            this.$success('Perubahan Kata Sandi Anda berhasil disimpan!');
            this.dialog = false;
          });
        });
      }
    },
    clear() {
      if (this.$refs.form) {
        this.$refs.form.reset();
      }
    },
  },
};
</script>
