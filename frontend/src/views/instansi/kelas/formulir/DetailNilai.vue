<template>
  <v-card class="mx-auto" width="850" flat>
    <v-card-text>
      <div class="d-flex px-2" style="justify-content: space-between">
        <div class="mr-5">
          <h3>
            {{ $getDeepObj(detail, 'paud_diklat_luring.data.nama') }} -
            {{ $getDeepObj(detail, 'nama') }}
          </h3>
          <div class="body-2 my-2">
            <v-list-item class="px-0">
              <v-list-item-avatar color="primary">
                <v-icon dark>mdi-account</v-icon>
              </v-list-item-avatar>
              <v-list-item-content class="py-0">
                <v-row dense no-gutters>
                  <v-col cols="12" md="2" sm="2">
                    <div class="body-2 label--text"> Nama Peserta </div>
                    <div class="body-2 label--text"> Surel </div>
                  </v-col>
                  <v-col cols="12" md="6" sm="6">
                    <div class="body-2 black--text"
                    >:
                      {{
                        $getDeepObj(peserta, 'ptk.data.nama') ||
                        $getDeepObj(peserta, 'paud_peserta_nonptk.data.nama') ||
                        ''
                      }}
                    </div>
                    <div class="body-2 black--text"
                    >:
                      {{
                        $getDeepObj(peserta, 'ptk.data.email') ||
                        $getDeepObj(peserta, 'paud_peserta_nonptk.data.email') ||
                        ''
                      }}
                    </div>
                  </v-col>
                </v-row>
              </v-list-item-content>
            </v-list-item>
          </div>
        </div>
        <div>
          <v-chip class="pa-4" color="success">Sudah dinilai</v-chip>
        </div>
      </div>
      <v-tabs
        v-model="tab"
        grow
        color="secondary"
      >
        <v-tab
          v-for="item in items"
          :key="item"

        >
          {{ item }}
        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab">
        <v-tab-item
          v-for="item in items"
          :key="item"
        >
          <v-card
            flat
          >
            <v-simple-table>
              <template v-slot:default>
                <thead>
                <tr>
                  <th class="text-left">
                    <h3>Indikator Penilaian</h3>
                  </th>
                  <th class="text-right">
                    <h3>Nilai</h3>
                  </th>
                </tr>
                </thead>
                <tbody>
                <tr
                  v-for="(ins, i) in instruments.filter(instrument => Number($getDeepObj(instrument, 'm_instrumen_nilai_luring_paud.data.k_tahap_nilai_luring_paud') || 0) === Number(tab + 1))"
                  :key="i"
                >
                  <td>
                    {{ i + 1 }}. <span v-html="$getDeepObj(ins, 'm_instrumen_nilai_luring_paud.data.keterangan')" />
                  </td>
                  <td  class="text-right">
                    {{ form[ins.k_instrumen_nilai_luring_paud] }}
                  </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                  <td class="text-left">
                    <h4>Total Nilai</h4>
                  </td>
                  <td class="text-right">
                    <h4>{{ getTotal(tab + 1) }}</h4>
                  </td>
                </tr>
                </tfoot>
              </template>
            </v-simple-table>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-card-text>
  </v-card>
</template>
<script>
export default {
  props: {
    detail: {
      type: Object,
      default: () => {},
    },
    initValue: {
      type: Object,
      default: () => null,
    },
  },
  data() {
    return {
      instruments: [],
      peserta: {},
      form: {},
      tab: null,
      total: 0,
      items: [
        'Pendalaman Materi', 'Pelaksanaan Tugas Mandiri',
      ],
    };
  },
  computed: {
  },
  methods: {
    getTotal(tahap) {
      let result = 0;
      const intrument = this.instruments.filter(instrument => Number(this.$getDeepObj(instrument, 'm_instrumen_nilai_luring_paud.data.k_tahap_nilai_luring_paud') || 0) === Number(tahap))
      for (const item of intrument) {
        const nilai = item.nilai;
        const bobot = this.$getDeepObj(item, 'm_instrumen_nilai_luring_paud.data.n_bobot') || 0;
        result += Number(nilai * (bobot / 100));
      }
      return (result || 0).toFixed(2);
    },

    initForm(value) {
      if (!value) return;
      const { instruments, peserta } = value;
      this.$set(this, 'instruments', instruments || []);
      this.$set(this, 'peserta', peserta);
      this.form = {};

      instruments.forEach((item) => {
        this.$set(this.form, this.$getDeepObj(item, 'k_instrumen_nilai_luring_paud'), (item && item.nilai) || '');
      });
    },
  },
  watch: {
    initValue: 'initForm',
  },
};
</script>
