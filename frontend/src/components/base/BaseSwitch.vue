<template>
  <v-chip class="ma-2" large :color="`${isTutup ? '' : !isAktif ? 'error' : 'primary'} lighten-5`">
    <v-tooltip top>
      <template v-slot:activator="{ on }">
        <v-btn
          class="mr-2 elevation-0"
          :color="`${isTutup ? '' : !isAktif ? 'error' : 'primary'} ${model === '' ? 'darken-2' : 'lighten-3'}`"
          :disabled="isTutup"
          fab
          x-small
          dark
          v-on="on"
          @click="select('')"
        >
          <v-icon>mdi-cancel</v-icon>
        </v-btn>
      </template>
      <span>Belum Diset</span>
    </v-tooltip>
    <v-tooltip top>
      <template v-slot:activator="{ on }">
        <v-btn
          class="mr-2 elevation-0"
          :color="`${isTutup ? '' : !isAktif ? 'error' : 'primary'} ${model === '1' ? 'darken-2' : 'lighten-3'}`"
          :disabled="isTutup"
          fab
          x-small
          dark
          v-on="on"
          @click="select('1')"
        >
          <v-icon>mdi-check</v-icon>
        </v-btn>
      </template>
      <span>Set True</span>
    </v-tooltip>
    <v-tooltip top>
      <template v-slot:activator="{ on }">
        <v-btn
          class="mr-2 elevation-0"
          :color="`${isTutup ? '' : !isAktif ? 'error' : 'primary'} ${model === '0' ? 'darken-2' : 'lighten-3'}`"
          :disabled="isTutup"
          fab
          x-small
          dark
          v-on="on"
          @click="select('0')"
        >
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </template>
      <span>Set False</span>
    </v-tooltip>
  </v-chip>
</template>

<script>
export default {
  name: 'BaseSwitch',
  props: {
    value: {
      type: String,
      default: '',
    },
    isAktif: {
      type: Boolean,
      default: false,
    },
    isTutup: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      model: this.value || '',
    };
  },
  methods: {
    mutate() {
      this.$emit('input', this.model);
    },
    select(value) {
      this.model = value;
    },
  },
  watch: {
    value: function (value) {
      this.model = value;
    },
    model: 'mutate',
  },
};
</script>

<style scoped></style>
