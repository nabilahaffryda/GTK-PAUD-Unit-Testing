import { mapActions } from 'vuex';

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
  },
};
