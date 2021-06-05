<template>
  <div class="verval">
    <v-card tile flat class="my-5">
      <v-card-text class="pa-0">
        <v-row no-gutters>
          <v-col cols="2">
            <div class="bg-kiri"></div>
          </v-col>
          <v-col cols="10" class="pa-5">
            <h1 class="headline black--text"> <strong>Verval</strong> Profil</h1>
            <div> Modul ini digunakan untuk melakukan {{ $route.meta.title }} </div>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <!--daftar-->
    <v-card flat>
      <v-card-text>
        <base-table-header
          btn-filter
          @download="onDownloadList"
          @filter="onFilter"
          @search="onSearch"
          @reload="onReload"
        >
          <template v-slot:subtitle>
            <div class="body-1 black--text">
              <b>{{ total }}</b> Daftar Kandidat yang perlu di periksa
            </div>
          </template>
        </base-table-header>
        <v-divider />
        <base-list-filter
          ref="filter"
          title="Pilih Filter Status"
          :filtered="filtered"
          :filters="formFilter"
          :paramsFilter="filters"
          @save="filterSave"
        />
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
              <v-list-item class="px-0">
                <v-list-item-content>
                  <v-row no-gutters>
                    <v-col cols="12" md="4">
                      <v-list-item class="px-0" @click="onVerval(item)">
                        <v-list-item-icon class="mr-1 my-auto">
                          <v-icon color="primary" size="60">mdi-account-circle</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content class="py-0 px-1">
                          <p class="caption"> Nama {{ $titleCase(obj) }} </p>
                          <span class="body-1">
                            <strong>
                              {{ $getDeepObj(item, `${obj}.data.nama`) || '-' }}
                            </strong>
                          </span>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0">
                          <v-list-item-title>
                            <div class="label--text">Nomor HP </div>
                          </v-list-item-title>
                          <v-list-item-subtitle class="link black--text body-2">
                            <template>
                              {{
                                $getDeepObj(item, `${obj}.data.no_hp`) ||
                                $getDeepObj(item, `${obj}.data.no_telpon`) ||
                                '-'
                              }}
                            </template>
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0">
                          <v-list-item-title>
                            <div class="label--text">Alamat Email</div>
                          </v-list-item-title>
                          <v-list-item-subtitle class="link black--text body-2">
                            {{ $getDeepObj(item, `${obj}.data.email`) }}
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0">
                          <v-list-item-title>
                            <div class="label--text">Status</div>
                          </v-list-item-title>
                          <v-list-item-subtitle class="link black--text body-2">
                            <v-chip
                              :color="
                                getColor(
                                  jenis === 'petugas'
                                    ? $getDeepObj(item, 'paud_petugas_perans.data.0.k_verval_paud')
                                    : item.k_verval_paud
                                )
                              "
                              dark
                              small
                            >
                              {{
                                jenis === 'petugas'
                                  ? $getDeepObj(item, 'paud_petugas_perans.data.0.m_verval_paud.data.keterangan') || '-'
                                  : $getDeepObj(item, 'm_verval_paud.data.keterangan') || '-'
                              }}
                            </v-chip>
                            <span
                              style="cursor: pointer"
                              v-if="false"
                              class="group pa-2"
                              @click="
                                $info('Data sedang dalam proses perbaikan oleh pendaftar', {
                                  tipe: 'warning',
                                  data: '',
                                })
                              "
                            >
                              <v-icon dark color="warning" medium>mdi-pencil-circle</v-icon>
                            </span>
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    <v-col cols="12" md="2">
                      <v-list-item class="px-0">
                        <v-list-item-content class="py-0">
                          <v-list-item-title>
                            <div class="label--text">Aksi Selanjutnya</div>
                          </v-list-item-title>
                          <v-list-item-subtitle class="link black--text body-2">
                            <v-btn color="primary" small block @click="onVerval(item)">
                              {{
                                $allow(`${jenis}-verval.update`) &&
                                [2, 3].includes(
                                  Number(
                                    jenis === 'petugas'
                                      ? $getDeepObj(item, 'paud_petugas_perans.data.0.k_verval_paud')
                                      : item.k_verval_paud
                                  )
                                )
                                  ? 'Verval Ajuan'
                                  : 'LIHAT DETAIL'
                              }}
                            </v-btn>
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                  </v-row>
                </v-list-item-content>
                <v-list-item-action-text>
                  <base-list-action :data="item" :actions="actions" :allow="allow" @action="onAction" />
                </v-list-item-action-text>
              </v-list-item>
            </td>
          </template>
        </base-list-table>
      </v-card-text>
      <v-card-actions>
        <base-table-footer :pageTotal="pageTotal" :allow="!allow" @changePage="onChangePage"></base-table-footer>
      </v-card-actions>
    </v-card>
    <base-modal-full
      ref="modal"
      colorBtn="primary"
      :title="formulir['title']"
      :autoClose="formulir['autoClose']"
      :useSave="formulir['is_edit']"
      fluid
      @close="onClose"
      @save="onSave"
    >
      <form-berkas
        ref="formulir"
        :masters="Object.assign({}, masters)"
        :id="formulir['id']"
        :detail="formulir['detail']"
        :berkas="formulir['berkas']"
        :fungsi="formulir['fungsi']"
        :angkatan="Number(params.angkatan) > 1 ? 2 : 1"
        :statistik="formulir['statistik']"
        :isEdit="formulir['is_edit']"
        :isDisable="formulir['is_disable']"
        :initValue="formulir['init']"
        :jenis="jenis"
        :obj="obj"
      ></form-berkas>
    </base-modal-full>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex';
import list from '@mixins/list';
import actions from './actions';
import FormBerkas from '../formulir/Berkas';
export default {
  name: 'Index',
  mixins: [list],
  data() {
    return {
      actions: actions,
      formulir: {},
      listTimVerval: [],
      listLaporan: [],
    };
  },
  components: { FormBerkas },
  computed: {
    ...mapState('master', {
      masters: (state) => Object.assign({}, state.masters),
    }),

    ...mapState('preferensi', {
      akun: (state) => (state.data && state.data.akun) || {},
    }),

    configs() {
      const M_PROPINSI = (this.masters && this.masters['propinsi']) || {};
      const M_KOTA = (this.masters && this.masters['kota']) || {};
      return {
        selector: ['k_propinsi', 'k_kota'],
        required: [false, false],
        label: ['Provinsi', 'Kota/Kabupaten'],
        options: [M_PROPINSI, M_KOTA],
        grid: [{ cols: 12 }, { cols: 12 }],
        disabled: [],
      };
    },

    mapTimVerval() {
      let temp = {};
      for (const akun of this.listTimVerval) {
        temp[this.$getDeepObj(akun, 'akun.data.akun_id')] = this.$getDeepObj(akun, 'akun.data.nama');
      }
      return temp;
    },

    mapStatusVerval() {
      const masters = (this.masters && this.masters.m_verval_paud) || {};
      let temp = {};

      Object.keys(masters).forEach((item) => {
        if (![1, 3].includes(Number(item))) {
          this.$set(temp, item, masters[item]);
        }
      });

      return temp;
    },

    formFilter() {
      let master = Object.assign({}, this.masters[`m_berkas_${this.keyTipe}_paud`]);
      // delete kandidat
      delete master[1];
      return [
        // {
        //   label: 'Pilih Verifikator',
        //   model: 'akun_id',
        //   type: 'select',
        //   props: ['attach'],
        //   master: this.$mapForMaster(this.mapTimVerval),
        //   labelColor: 'secondary',
        //   grid: { cols: 12 },
        // },
        {
          label: 'Pilih Status Verval',
          default: true,
          type: 'checkbox',
          model: 'k_verval_paud',
          master: this.$mapForMaster(this.mapStatusVerval),
          props: ['attach', 'chips', 'deletable-chips', 'multiple', 'small-chips'],
        },
        // {
        //   label: 'Pilih Lokasi',
        //   default: true,
        //   type: 'cascade',
        //   configs: this.configs,
        //   labelColor: 'secondary',
        //   grid: { cols: 12, md: 6 },
        // },
      ];
    },

    filtersMaster() {
      return {
        k_verval_paud: this.mapStatusVerval,
        k_propinsi: this.masters && this.masters['propinsi'],
        k_kota: this.masters && this.masters['kota'],
        akun_id: this.mapTimVerval,
      };
    },

    obj() {
      return this.$route.meta.atribut;
    },

    keyTipe() {
      return this.$route.meta.paudkey;
    },

    jenis() {
      return this.$route.meta.tipe;
    },
  },
  created() {
    this.getMasters({
      name: [
        'm_berkas_petugas_paud',
        'm_berkas_lpd_paud',
        'm_kualifikasi',
        'tingkat_diklat_paud',
        'm_verval_paud',
      ].join(';'),
      filter: {
        0: {
          k_berkas_petugas_paud: {
            op: '<>',
            val: 2,
          },
        },
      },
    });
  },

  mounted() {
    Object.assign(this.attr, {
      tipe: this.$route.meta && this.$route.meta.tipe,
    });

    Object.assign(this.params, {
      angkatan: 1,
    });
  },
  methods: {
    ...mapActions('verval', ['fetch', 'getDetail', 'action', 'downloadList', 'getKinerja', 'getTimVerval']),
    ...mapActions('master', ['getMasters']),

    onClose() {
      if (!this.formulir['autoClose']) {
        const msg = [
          '<h3>Apakah Anda yakin ingin keluar dari halaman Verifikasi Berkas Peserta ?</h3>',
          'data yang tidak disimpan akan hilang jika Anda keluar dari Halaman ini',
        ].join(' ');
        this.$confirm(msg, 'Peringatan', { tipe: 'error', lblConfirmColor: 'red', lblCancelColor: 'grey' }).then(() => {
          this.$refs.modal.dialog = false;
        });
      }
    },

    onCall(item) {
      const url = `https://wa.me/62${Number(this.$getDeepObj(item, 'no_wa'))}`;
      window.open(url, '_blank');
    },

    isAllowAkses(policies) {
      let enable = false;
      let akses = ['psp-peserta-berkas.setuju', 'psp-peserta-berkas.tolak', 'psp-peserta-berkas.tolak-revisi'];

      akses.forEach((key) => {
        if (this.$allow(key, policies)) {
          enable = true;
          return;
        }
      });

      return enable;
    },

    async onVerval(item) {
      const kVerval = Number(
        this.jenis === 'petugas'
          ? this.$getDeepObj(item, 'paud_petugas_perans.data.0.k_verval_paud')
          : item.k_verval_paud
      );
      const status = {
        color: this.getColor(kVerval),
        keterangan:
          this.jenis === 'petugas'
            ? this.$getDeepObj(item, 'paud_petugas_perans.data.0.m_verval_paud.data.keterangan') || '-'
            : this.$getDeepObj(item, 'm_verval_paud.data.keterangan') || '-',
      };

      let init = {};
      let statistik = this.statistik;

      init = {
        pilihan: kVerval <= 3 ? null : kVerval === 4 ? 1 : kVerval === 6 ? 2 : 3,
      };

      this.$set(this.formulir, 'form', 'form-berkas');
      this.$set(this.formulir, 'title', 'Pemeriksaan Daftar Riwayat Hidup (CV)');
      this.$set(this.formulir, 'id', item.id);
      this.$set(this.formulir, 'statistik', statistik);
      this.$set(this.formulir, 'init', null);

      this.getDetail({ id: item.id, tipe: this.$route.meta.tipe }).then(({ data }) => {
        this.$set(this.formulir, 'is_disable', kVerval > 3);
        this.$set(this.formulir, 'is_edit', this.$allow(`${this.jenis}-verval.update`) && kVerval <= 3);
        this.$set(this.formulir, 'autoClose', kVerval > 3);

        this.$refs.modal.open();
        this.$nextTick(() => {
          this.$refs.formulir.reset();
          this.$set(this.formulir, 'init', init);
          this.$set(this.formulir, 'detail', data);
          this.$set(this.formulir, 'berkas', this.$getDeepObj(data, `paud_${this.keyTipe}_berkases.data`));
          this.$set(this.formulir.detail, 'status', status);
        });
      });
    },

    onSave() {
      const formulir = Object.assign({}, this.$refs.formulir.getValue());
      const status = formulir.pilihan;
      const alasan = formulir.alasan || '-';
      const id = formulir.id;

      if (status) {
        const aksi = status === 1 ? '4' : status === 2 ? '6' : '5';
        const html = `<span class="subtitle-1">Anda Yakin ingin <strong>${
          status === 1 ? 'menolak' : status === 2 ? 'menerima' : 'menyatakan perbaikan'
        }</strong> ajuan verval berikut ?</span>`;

        this.$confirm(html, 'Konfirmasi', { tipe: status === 1 ? 'error' : status === 2 ? 'success' : 'warning' }).then(
          () => {
            this.$refs.modal.loading = true;
            this.action({ id: id, jenis: this.jenis, params: { k_verval_paud: aksi, alasan } })
              .then(() => {
                this.$success('Data ajuan berhasil di ubah');
                this.$refs.modal.dialog = false;
                this.onReload();
              })
              .catch(() => {
                this.$refs.modal.loading = false;
              });
          }
        );
        this.$refs.modal.loading = false;
      } else {
        this.$error('Silakan tentukan hasil verifikasi data sebelum simpan');
        this.$refs.modal.loading = false;
      }
    },

    onKunci(data) {
      this.id = data.psp_profil_id;
      this.$confirm(`Anda yakin ingin mengunci Ajuan di atas agar bisa diverifikasi?`, `Kunci Ajuan`, {
        tipe: 'warning',
        data: this.confirmHtml(data),
      }).then(() => {
        this.action({ id: this.id, type: 'kunci' }).then(() => {
          this.$success(`Ajuan Peserta berhasil dikunci`);
          this.onReload();
        });
      });
    },

    onBatalKunci(data) {
      this.$confirm(`Anda yakin ingin membatalkan Kunci Verifikasi Ajuan Peserta di atas?`, `Batal Kunci Ajuan`, {
        tipe: 'error',
        data: this.confirmHtml(data),
      }).then(() => {
        this.action({ id: data.psp_profil_id, type: 'batal-kunci' }).then(() => {
          this.$success(`Batal kunci Ajuan Peserta berhasil`);
          this.onReload();
        });
      });
    },

    onBatalVerval(data) {
      this.$confirm(`Anda yakin ingin membatalkan VerVal Ajuan di atas?`, `Batal Verval`, {
        tipe: 'error',
        data: this.confirmHtml(data),
      }).then(() => {
        this.action({ id: data.psp_profil_id, type: 'batal-verval' }).then(() => {
          this.$success(`Batal Verval Ajuan Peserta berhasil`);
          this.onReload();
        });
      });
    },

    confirmHtml(data) {
      return [
        {
          icon: 'mdi-account-circle',
          iconSize: 60,
          iconColor: 'secondary',
          title: this.$getDeepObj(data, 'ptk.data.nama') || '',
          subtitles: [`<span class="text--primary">${this.$getDeepObj(data, 'ptk.data.no_ukg')}</span>`],
        },
      ];
    },

    async onKinerja() {
      const params = {
        angkatan: this.params.angkatan,
        count: 100,
      };

      const resp = await this.getKinerja({ params });
      const nilais = (resp && resp.data) || [];
      const statistik = this.statistik || {};
      const limit = Number(this.$getDeepObj(resp, 'meta.total')) || 0;

      const items = nilais.map((key, index) => {
        return {
          no: index + 1,
          nama: key.nama,
          diproses: key.diproses,
          disetujui: key.disetujui,
          revisi: key.revisi,
          ditolak: key.ditolak,
          jumlah: Number(key.diproses) + Number(key.disetujui) + Number(key.revisi) + Number(key.ditolak),
        };
      });

      const headers = [
        {
          text: 'NO',
          align: 'start',
          sortable: false,
          value: 'no',
        },
        { text: 'Tim Verval', value: 'nama' },
        { text: 'Diproses', value: 'diproses' },
        { text: 'Disetujui', value: 'disetujui' },
        { text: 'Revisi', value: 'revisi' },
        { text: 'Ditolak', value: 'ditolak' },
        { text: 'Jumlah', value: 'jumlah' },
      ];

      this.$refs.kinerja.open();

      this.$nextTick(() => {
        this.$refs.formkinerja.reset();
        this.$refs.formkinerja.headers = headers;
        this.$refs.formkinerja.data = (resp && resp.nilais) || [];
        this.$refs.formkinerja.items = items || [];
        this.$refs.formkinerja.kinerja = (resp && resp.kinerja) || null;
        this.$refs.formkinerja.statistik = statistik;
        this.$refs.formkinerja.limit = limit;
      });
    },

    onUnduhKinerja() {
      const params = {};
      this.unduhKinerja(params).then((url) => {
        window.open(url, '_blank');
      });
    },
  },
};
</script>

<style scoped>
.bg-kiri {
  background: #ffab91;
  height: 100%;
}
.sc-notif {
  background-color: #c8e6c9;
}
</style>
