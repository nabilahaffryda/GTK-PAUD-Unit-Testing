import { mapActions } from 'vuex';

export default {
  methods: {
    ...mapActions('master', ['getMasters']),
    ...mapActions('kelas', ['fetch', 'getDetail', 'getMapels']),

    async onDetail(item) {
      const data = await this.getDetail({ diklat_id: this.diklatId, id: item.id }).then(({ data }) => data);
      this.$set(this.formulir, 'title', 'Detail Diklat');
      this.$set(this.formulir, 'form', 'detail-kelas');
      this.$set(this.formulir, 'useSave', false);
      this.$refs.modal.open();

      this.$nextTick(() => {
        this.$refs.formulir.reset();
        this.$refs.formulir.kelas = data || {};
      });
    },

    listMapels() {
      this.getMapels().then(({ data }) => {
        this.mapels = data || [];
      });
    },
  },
};
