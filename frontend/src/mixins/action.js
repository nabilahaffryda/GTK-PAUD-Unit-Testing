import { mapActions } from 'vuex';
import BasePhotoProfil from '@/components/base/BasePhotoProfil';

export default {
  name: 'mixinAction',
  components: {
    BasePhotoProfil,
  },
  methods: {
    ...mapActions('preferensi', ['getPreferensi']),
    ...mapActions('profil', ['uploadFoto', 'ajuan', 'batalAjuan', 'mulaiTbs']),
    ...mapActions('cv', ['mulaiCv']),
    ...mapActions('esai', ['mulaiEsai']),
    ...mapActions('auth', ['logout']),

    onLogout() {
      this.$store.commit('SET_LOADING', true);
      this.logout().then(() => {
        window.location.href = process.env.VUE_APP_API_URL + `/auth/logout`;
      });
    },

    onAction({ action, title }) {
      switch (action) {
        case 'download':
          window.open(`https://files1.simpkb.id/berkas/sekolah-penggerak/Surat_Pernyataan_Calon_Peserta_SP_rev1.docx`);
          break;
        case 'cv':
        case 'esai':
        case 'tbs':
        case 'tbs-demo':
          if (action === this.$route.name) return;
          if (['cv', 'esai'].includes(action) && this.tutupPendaftaran) this.$router.push({ name: action });
          else
            this.$popupGoto(
              action,
              `Informasi Pengerjaan ${title}`,
              `${action}-masuk`,
              this.statusLengkap && this.statusLengkap[`is_mulai_${action}`]
            );
          break;
        case 'cv-masuk':
        case 'esai-masuk':
          if (!(this.statusLengkap && this.statusLengkap[`is_mulai_${action.split('-')[0]}`])) {
            this[`mulai${this.$titleCase(action.split('-')[0])}`](this.profilID).then(() => {
              this.onReload();
              this.$router.push({ name: action.split('-')[0] });
            });
            return;
          }
          this.$router.push({ name: action.split('-')[0] });
          break;
        case 'tbs-masuk':
          this.mulaiTbs({ id: this.profilID, tbsId: this.configTbs.psp_profil_tbs_id }).then(() => {
            window.location.href = this.configTbs.url_tbs;
          });
          break;
        case 'tbs-demo-masuk':
          this.mulaiTbs({ id: this.profilID, tbsId: this.configTbsDemo.psp_profil_tbs_id }).then(() => {
            window.location.href = this.configTbsDemo.url_tbs;
          });
          break;
        case 'microteaching':
          this.$router.push({ name: 'microteaching' });
      }
    },

    onReload() {
      this.getPreferensi(true);
    },

    onUploadFoto(data) {
      this.uploadFoto(data).then(() => {
        this.onReload();
      });
    },

    onAjuan() {
      if (this.tutupPendaftaran && this.kVerval !== 5) {
        const msg = `Pendaftaran <b>seleksi Tahap ${this.tahap}</b> sebagai
                    <b>${this.isKasek ? 'Peserta - Kepala Sekolah' : 'Pelatih Ahli'}</b>
                    Program Sekolah Penggerak telah berakhir`;
        this.$error(msg);
        return;
      }

      this.$confirm(`<h3>Anda yakin ingin mengajukan Verval sekarang?</h3>`, `Ajuan Verval`, {
        tipe: 'success',
      }).then(() => {
        this.ajuan(this.profilID).then(() => {
          this.$success(`Data Anda berhasil diajukan`);
          this.onReload();
        });
      });
    },

    onBatalAjuan() {
      if (this.tutupPendaftaran) {
        const msg = `Pendaftaran <b>seleksi Tahap ${this.tahap}</b> sebagai
                    <b>${this.isKasek ? 'Peserta - Kepala Sekolah' : 'Pelatih Ahli'}</b>
                    Program Sekolah Penggerak telah berakhir`;
        this.$error(msg);
        return;
      }

      this.$confirm(`<h3>Anda yakin ingin membatalkan Ajuan Verval?</h3>`, `Batal Ajuan Verval`, {
        tipe: 'error',
      }).then(() => {
        this.batalAjuan(this.profilID).then(() => {
          this.$success(`Ajuan Anda berhasil dibatalkan`);
          this.onReload();
        });
      });
    },
  },
};
