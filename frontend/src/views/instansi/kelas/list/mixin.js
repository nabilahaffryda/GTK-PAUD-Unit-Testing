import { mapActions } from 'vuex';
import { today } from '../../../../utils/format';

export default {
  methods: {
    ...mapActions('master', ['getMasters']),
    ...mapActions('petugasKelas', ['fetch', 'getDetail']),

    async onDetail(item) {
      const data = await this.getDetail({ id: item.id }).then(({ data }) => data);
      this.$set(this.formulir, 'title', 'Detail Diklat');
      this.$set(this.formulir, 'form', 'detail-kelas');
      this.$set(this.formulir, 'useSave', false);
      this.$refs.modal.open();

      this.$nextTick(() => {
        this.$refs.formulir.reset();
        this.$refs.formulir.kelas = data || {};
      });
    },

    isEndDiklat(item) {
      const now = new Date(today());
      const wkt_selesai = new Date(this.$getDeepObj(item, 'paud_diklat_luring.data.tgl_selesai'));
      return wkt_selesai >= now;
    },

    onView(item) {
      this.$router.push({ name: 'kelas-luring-peserta', params: { kelas_id: item.id } });
    },
  },
};
