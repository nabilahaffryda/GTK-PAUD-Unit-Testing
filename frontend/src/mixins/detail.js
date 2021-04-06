export default {
  data() {
    return {
      included: [],
      data: {},
    };
  },
  mounted() {
    this.onReload();
  },
  methods: {
    async onReload() {
      // get detail
      const { data, included } = await this.detail(this.detailId).then((data) => data);
      this.data = data;
      this.included = included;
    },
  },
};
