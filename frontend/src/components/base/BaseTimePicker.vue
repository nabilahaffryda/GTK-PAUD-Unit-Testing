<template>
  <v-row dense no-gutters>
    <v-col cols="12" md="4" sm="6">
      <v-select
        v-model="date.hour"
        placeholder="jam"
        :outlined="outlined"
        :disabled="disabled"
        :dense="dense"
        :items="hours"
        required
      />
    </v-col>
    <v-col class="mx-md-1" cols="12" md="4" sm="6">
      <v-select
        v-model="date.minute"
        placeholder="menit"
        :outlined="outlined"
        :disabled="disabled"
        :dense="dense"
        :items="minutes"
        required
      />
    </v-col>
    <v-col class="mt-3 mx-2" cols="12" md="2" sm="2"> WIB </v-col>
  </v-row>
  <!--  <v-dialog ref="dialog" v-model="modal" :return-value.sync="date" persistent width="290px">-->
  <!--    <template v-slot:activator="{ on }">-->
  <!--      <v-text-field-->
  <!--        :label="label"-->
  <!--        :prepend-icon="useicon ? 'mdi-calendar' : ''"-->
  <!--        readonly-->
  <!--        :value="date"-->
  <!--        v-on="on"-->
  <!--        :disabled="disabled"-->
  <!--        :dense="dense"-->
  <!--        :single-line="singleLine"-->
  <!--        :outlined="outlined"-->
  <!--        :error-messages="errors"-->
  <!--      />-->
  <!--    </template>-->
  <!--    <v-time-picker scrollable v-model="date" format="24hr">-->
  <!--      <v-spacer />-->
  <!--      <v-btn text color="primary" @click="modal = false">Cancel</v-btn>-->
  <!--      <v-btn text color="primary" @click="$refs.dialog.save(date)">OK</v-btn>-->
  <!--    </v-time-picker>-->
  <!--  </v-dialog>-->
</template>
<script>
export default {
  name: 'TimePicker',
  props: ['value', 'label', 'min', 'max', 'errors', 'disabled', 'useicon', 'dense', 'singleLine', 'outlined'],
  data() {
    return {
      modal: false,
      date: {
        hour: this.value ? this.value.split(':')[0] : '',
        minute: this.value ? this.value.split(':')[1] : '',
      },
    };
  },
  computed: {
    formatDate() {
      return this.date.hour || this.date.minute ? `${this.date.hour}:${this.date.minute || '00'}` : null;
    },

    hours() {
      let temp = [];
      for (let i = 1; i <= 23; i++) {
        if (i < 10) temp.push(`0${i}`);
        else temp.push(`${i}`);
      }
      return temp;
    },

    minutes() {
      let temp = [];
      for (let i = 0; i <= 55; i++) {
        if (i === 0 || i % 5 === 0) {
          if (i < 10) temp.push(`0${i}`);
          else temp.push(`${i}`);
        }
      }

      return temp;
    },
  },
  methods: {
    mutate() {
      this.$emit('input', this.formatDate);
    },
    setForm(value) {
      this.$set(this.date, 'hour', value ? value.split(':')[0] : '');
      this.$set(this.date, 'minute', value ? value.split(':')[1] : '');
    },
  },
  watch: {
    date: {
      handler() {
        this.mutate();
      },
      deep: true,
    },
    value: 'setForm',
  },
};
</script>
