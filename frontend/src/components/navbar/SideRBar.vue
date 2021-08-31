<template>
  <v-navigation-drawer v-model="drawer" right temporary fixed width="250">
    <v-card flat>
      <v-img
        :aspect-ratio="16 / 9"
        class="white--text"
        src="https://penggerak-cdn.siap.id/s3/sekolahpenggerak/img/bg-parallax.png"
        app
      >
        <v-layout pa-2 justify-center align-center column fill-height class="lightbox white--text">
          <v-spacer></v-spacer>
          <v-flex>
            <div class="body-2 text-xs-center">
              <v-avatar class="mb-1" color="white" :size="80">
                <v-img :src="$imgUrl(avatar)" :aspect-ratio="4 / 6" class="grey lighten-2"></v-img>
              </v-avatar>
            </div>
          </v-flex>
        </v-layout>
      </v-img>
      <v-card-title class="py-2" style="font-size: 1.1rem; font-weight: 400; line-height: 1.4rem">
        <div>
          <h4 class="mb-0">
            {{ username }}
          </h4>
          <span class="grey--text body-2 font-italic">
            {{ useremail }}
          </span>
          <div>
            <template v-if="role === 'instansi'">
              <v-chip v-for="(item, i) in currRole" class="mx-md-1" color="primary" x-small :key="i">
                {{ item }}
              </v-chip>
            </template>
            <template v-else>
              <v-chip class="mx-md-1" color="primary" x-small>
                {{ currRole }}
              </v-chip>
            </template>
          </div>
        </div>
      </v-card-title>
      <v-divider></v-divider>
      <v-list flat dense v-if="role === 'instansi'">
        <v-subheader style="height: 20px" class="purple--text">Ganti Instansi</v-subheader>
        <v-list-item-group color="primary">
          <v-list-item @click="popup('instansi')">
            <v-list-item-icon>
              <v-icon>mdi-account-switch</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <span class="caption">{{ instansi && instansi.nama }}</span>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
      <v-divider></v-divider>
      <v-list flat dense>
        <v-subheader class="purple--text">Akun</v-subheader>
        <v-list-item-group color="primary">
          <v-list-item @click="popup('role')" v-if="isMultiRole">
            <v-list-item-icon>
              <v-icon>mdi-account-switch</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Ubah Peran</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item v-if="$allow('guru-penggerak-profil.update')" @click="onProfil">
            <v-list-item-icon>
              <v-icon>mdi-account-edit</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Profil
                <v-chip class="ml-1" color="success" dark small>Baru</v-chip>
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item @click="toAkun">
            <v-list-item-icon>
              <v-icon>mdi-lock</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Ubah Kata Sandi</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item @click="onReset" v-if="isMenuReset">
            <v-list-item-icon>
              <v-icon>mdi-account-convert</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Reset Pendaftaran</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item @click="onLogout">
            <v-list-item-icon>
              <v-icon>mdi-logout-variant</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Keluar</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
      <!-- popup -->
      <popup-instansi ref="instansi"></popup-instansi>
      <popup-peran ref="role"></popup-peran>
      <popup-reset-pendaftaran :tahap="tahap" ref="reset" @reset="onSaveReset"></popup-reset-pendaftaran>
    </v-card>
  </v-navigation-drawer>
</template>

<script>
import { mapState, mapActions } from 'vuex';
import { fkata } from '@utils/format';
export default {
  props: ['value'],
  components: {
    PopupPeran: () => import('../popup/Perans'),
    PopupInstansi: () => import('../popup/Switch'),
    PopupResetPendaftaran: () => import('../popup/ResetPendaftaran'),
  },
  data() {
    return {
      drawer: false,
      isMulti: false,
    };
  },
  computed: {
    ...mapState('auth', {
      role: (state) => state.role ?? '',
      env: (state) => state.env ?? '',
    }),

    ...mapState('preferensi', {
      ptk: (state) => state?.data?.ptk ?? {},
      akun: (state) => state?.data?.akun ?? {},
      kasek: (state) => state?.data?.kasek ?? {},
      peserta: (state) => state?.data?.peserta ?? {},
      peserta_status: (state) => state?.data?.peserta_status ?? {},
      instansi: (state) => state?.data?.instansi ?? {},
      instruktur: (state) => state?.data?.instruktur ?? {},
      groups: (state) => state?.data?.groups ?? {},
    }),

    tahap() {
      return `${(this.peserta && this.peserta.gelombang) || 1}`;
    },

    isKasek() {
      return !!Object.keys(this.kasek || {}).length;
    },

    isMultiRole() {
      return false;
    },

    currRole() {
      const role =
        this.role === 'instansi'
          ? Object.values(this.groups)
          : this.isKasek
          ? 'Peserta - Kepala Sekolah'
          : 'Pelatih Ahli';
      return role;
    },

    isMenuReset() {
      return this.env !== 'production' && this.isKasek && this.$isObject(this.peserta);
    },

    username() {
      return this.role === 'instansi' ? this.akun?.nama ?? 'Admin' : this.ptk?.nama ?? 'Gtk';
    },

    useremail() {
      return this.role === 'instansi' ? this.akun?.email ?? '' : this.ptk?.email ?? '';
    },

    avatar() {
      return this.role === 'instansi' ? this.akun?.foto_url ?? 'avatar.png' : this.ptk?.foto_url ?? 'avatar.png';
    },
  },
  methods: {
    ...mapActions('auth', ['logout']),
    ...mapActions('preferensi', ['getPreferensi']),

    fkata(array) {
      return fkata(array);
    },

    to(obj) {
      return this.role === 'instansi'
        ? Object.assign({}, obj, {
            params: { id: this.instansiId },
          })
        : obj;
    },
    popup(jenis) {
      this.$refs[jenis].open();
    },
    onSwitch() {
      this.drawer = false;
    },
    onLogout() {
      this.$store.commit('SET_LOADING', true);
      this.logout().then(() => {
        window.location.href = process.env.VUE_APP_API_URL + `/auth/logout`;
      });
    },

    onReset() {
      if (this.peserta_status && this.peserta_status.tutup) {
        this.$error(`Aksi Batal Pendaftaran tidak diizinkan karena Pendaftaran Sekolah Penggerak telah berakhir`);
        return;
      }

      if (this.peserta && this.peserta.k_verval_psp > 1) {
        this.$error(`Aksi Batal Pendaftaran tidak diizinkan karena Anda terdeteksi telah melakukan Pengajuan Berkas`);
        return;
      }

      this.$refs.reset.open();
    },

    onSaveReset() {
      this.$refs.reset.close();
      this.$success(`Data Pendaftaran berhasil direset`);
      window.location.reload();
    },

    onProfil() {
      this.$router.push({ name: 'profil-asesor' });
    },

    toAkun() {
      window.open(`${process.env.VUE_APP_AKUN_URL}/profil`);
    },
  },
  watch: {
    value(val) {
      this.drawer = val;
    },
    drawer(val) {
      this.$emit('input', val);
    },
  },
};
</script>
