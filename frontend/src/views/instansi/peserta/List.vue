<template>
  <div>
    <!--notif jadwal-->
    <v-card tile flat class="my-5">
      <v-card-text class="pa-0">
        <v-row no-gutters>
          <v-col cols="2">
            <div class="bg-kiri"></div>
          </v-col>
          <v-col cols="10" class="pa-5">
            <h1 class="headline black--text" v-html="`Peserta Non Dapodik`"></h1>
            <p>
              <b>Peserta Non Dapodik adalah</b> peserta yang ditambahkan daru unsur guru yang tidak terdata dari Dapodik
              untuk kebutuhan diklat moda luring
            </p>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <v-card>
      <v-card-title class="pa-0">
        <base-table-header @search="onSearch" :btnAdd="true" @add="onAdd" @reload="onReload" @download="onDownload">
          <template v-slot:subtitle>
            <div class="subtitle-1 black--text">
              Daftar Peserta Luring <b>{{ total }} </b>
            </div>
          </template>
        </base-table-header>
      </v-card-title>
    </v-card>

    <base-modal-full
      ref="modal"
      colorBtn="primary"
      generalError
      :use-save="formulir.isValid"
      :title="formulir.title"
      @save="onSave"
    >
      <component
        ref="formulir"
        :is="formulir.component || 'FormPeserta'"
        :isEdit="formulir.isEdit"
        :initValue="formulir.init"
        :masters="masters"
      />
    </base-modal-full>
  </div>
</template>
<script>
import list from '@mixins/list';
import FormPeserta from './formulir/Peserta';
import { mapActions, mapState } from 'vuex';

export default {
  mixins: [list],
  components: { FormPeserta },
  data() {
    return {
      formulir: {},
    };
  },
  created() {
    this.getMasters({
      name: ['m_propinsi', 'm_kota', 'm_kualifikasi', 'm_golongan'].join(';'),
      filter: {
        0: {
          k_propinsi: {
            op: '<',
            val: 90,
          },
        },
      },
    });
  },
  computed: {
    ...mapState('master', ['masters']),
  },
  methods: {
    ...mapActions('master', ['getMasters']),

    onAdd() {
      this.$set(this.formulir, 'title', 'Tambah Peserta Non Dapodik');
      this.$set(this.formulir, 'isEdit', false);
      this.$set(this.formulir, 'isValid', false);
      this.$refs.modal.open();
      this.$nextTick(() => {
        this.$refs.formulir.reset();
      });
    },

    onSave() {}
  },
};
</script>
