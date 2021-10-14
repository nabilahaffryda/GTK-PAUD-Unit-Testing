import { mapActions } from 'vuex';

export default {
  data() {
    return {
      paramsInstasi: {},
      cacheInstansi: {},
    };
  },
  methods: {
    ...mapActions('petugas', ['fetch', 'getDetail']),

    ...mapActions('master', ['getMasters']),

    onDetail(detail) {
      const id = (detail && detail.id) || '';
      const tipe = this.$getDeepObj(this.attr, 'tipe');
      if (!id) return;

      this.getDetail({ id, tipe })
        .then(({ data }) => {
          this.$set(this.formulir, 'title', `Detail ${this.title}`);
          this.$set(this.formulir, 'detail', data);
          this.$refs.modal.open();
        })
        .catch(() => {
          this.$refs.modal.loading = false;
        });
    },

    filterStatus(filters) {
      // set filter
      this.filters = filters;
      Object.assign(this.params, { page: 1 });
      this.fetchData();
      this.$refs.filter.close();
    },
  },
};
