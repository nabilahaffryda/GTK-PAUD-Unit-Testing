<template>
  <div class="text-center" style="width: 100%">
    <template v-if="useNumber">
      <v-pagination
        :circle="circle"
        color="secondary"
        v-model="page"
        :length="pageTotal"
        prev-icon="mdi-chevron-left"
        next-icon="mdi-chevron-right"
        :total-visible="5"
      >
      </v-pagination>
    </template>
    <template v-else>
      <v-btn
        fab
        color="secondary"
        class="ma-1"
        x-small
        :disabled="Number(page) === 1"
        :depressed="Number(page) === 1"
        @click="prev"
      >
        <v-icon>mdi-chevron-left</v-icon>
      </v-btn>
      <v-btn
        fab
        color="secondary"
        class="ma-1"
        x-small
        :disabled="Number(total) < 10"
        :depressed="Number(total) < 10"
        @click="next"
      >
        <v-icon>mdi-chevron-right</v-icon>
      </v-btn>
    </template>
  </div>
</template>

<script>
export default {
  name: 'BaseTableFooter',
  props: {
    pageTotal: {
      type: Number,
      default: 0,
    },
    total: {
      type: Number,
      default: 0,
    },
    circle: {
      type: Boolean,
      default: true,
    },
    useNumber: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      page: 1,
    };
  },
  methods: {
    next() {
      this.page = this.page + 1;
    },
    prev() {
      this.page = this.page > 1 ? this.page - 1 : this.page;
    },
    changePage() {
      this.$emit('changePage', this.page);
    },
  },
  watch: {
    page: 'changePage',
  },
};
</script>

<style scoped></style>
