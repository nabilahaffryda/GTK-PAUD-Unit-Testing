<template>
  <div>
    <v-card class="mx-auto" flat v-if="uimodel === 'card'">
      <v-card-title v-if="useHeader" class="pa-0">
        <v-toolbar color="secondary" dark flat>
          <v-toolbar-title>Unggah {{ (detail && detail.title) || '' }}</v-toolbar-title>
        </v-toolbar>
      </v-card-title>
      <v-card-text class="px-0 mt-3">
        <v-container>
          <v-row>
            <v-col cols="12" class="py-0">
              <v-alert text type="info" dense v-if="detail && detail.pesan">
                <span v-html="detail.pesan || ''" />
              </v-alert>
              <slot name="extra"></slot>
              <v-subheader :class="[`px-0 my-3 body-2 ${labelColor}--text`]" style="height: 24px">
                {{ title }}*
              </v-subheader>
              <template v-if="form.berkas && form.berkas.length">
                <v-chip
                  color="deep-purple accent-4"
                  dark
                  label
                  close
                  :href="form.berkas[0] && form.berkas[0]['url_berkas']"
                  target="_blank"
                  @click:close="onRemoveFile"
                >
                  {{ form.berkas[0] && form.berkas[0]['file_berkas'] }}
                </v-chip>
              </template>
              <template v-else>
                <validation-provider
                  mode="passive"
                  :name="`${title}`"
                  :rules="{ required: (rules && rules.required) || true }"
                  v-slot="{ errors }"
                >
                  <v-file-input
                    v-model="form.file"
                    :error-messages="errors"
                    :placeholder="`Pilih Berkas ${title} (${min} KB - ${roundDecimal(max / 1000)} MB)`"
                    append-icon="mdi-paperclip"
                    prepend-icon=""
                    :accept="accept"
                    :hint="
                      `Jenis file unggahan ${format || 'JPG/JPEG/PNG/GIF/PDF'} (${min} KB - ${roundDecimal(
                        max / 1000
                      )} MB). ${format ? '' : 'Untuk berkas multi halaman gunakan format PDF'}`
                    "
                    :rules="[
                      (value) =>
                        !value ||
                        (value && value.size < roundDecimal(max * 1000)) ||
                        'Berkas yang Anda upload melebihi kapasitas maksimum!',
                    ]"
                    persistent-hint
                    show-size
                    outlined
                    dense
                    single-line
                    @change="onCheck"
                  ></v-file-input>
                </validation-provider>
              </template>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <slot></slot>
      </v-card-actions>
    </v-card>
    <v-row v-else>
      <v-col cols="12" class="py-0">
        <v-alert text type="info" dense v-if="detail && detail.pesan">
          <span v-html="detail.pesan || ''" />
        </v-alert>
        <slot name="extra"></slot>
        <v-subheader :class="[`px-0 my-3 body-2 ${labelColor}--text`]" style="height: 24px"> {{ title }}* </v-subheader>
        <template v-if="form.berkas && form.berkas.length">
          <v-chip
            color="deep-purple accent-4"
            dark
            label
            close
            :href="form.berkas[0] && form.berkas[0]['url_berkas']"
            target="_blank"
            @click:close="onRemoveFile"
          >
            {{ form.berkas[0] && form.berkas[0]['file_berkas'] }}
          </v-chip>
        </template>
        <template v-else>
          <validation-provider
            mode="passive"
            :name="`${title}`"
            :rules="{ required: (rules && rules.required) || true }"
            v-slot="{ errors }"
          >
            <v-file-input
              v-model="form.file"
              :error-messages="errors"
              :placeholder="`Pilih Berkas ${title} (${min} KB - ${roundDecimal(max / 1000)} MB)`"
              append-icon="mdi-paperclip"
              prepend-icon=""
              :accept="accept"
              :hint="
                `Jenis file unggahan ${format || 'JPG/JPEG/PNG/GIF/PDF'} (${min} KB - ${roundDecimal(
                  max / 1000
                )} MB). ${format ? '' : 'Untuk berkas multi halaman gunakan format PDF'}`
              "
              :rules="[
                (value) =>
                  !value ||
                  (value && value.size < roundDecimal(max * 1000)) ||
                  'Berkas yang Anda upload melebihi kapasitas maksimum!',
              ]"
              persistent-hint
              show-size
              outlined
              dense
              single-line
              @change="onCheck"
            ></v-file-input>
          </validation-provider>
        </template>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { ValidationProvider } from 'vee-validate';
import { roundDecimal } from '@utils/format';
export default {
  name: 'FormUnggah',
  components: { ValidationProvider },
  props: {
    initValue: {
      type: Object,
      default: () => null,
    },
    title: {
      type: String,
      default: '',
    },
    uimodel: {
      type: String,
      default: '',
    },
    type: {
      type: String,
      default: '',
    },
    format: {
      type: String,
      default: '',
    },
    rules: {
      type: Object,
      default: () => {},
    },
    labelColor: {
      type: String,
      default: 'secondary',
    },
    detail: {
      type: Object,
      default: () => {},
    },
    min: {
      type: Number,
      // dalam KB
      default: 20,
    },
    max: {
      type: Number,
      // dalam KB
      default: 1500,
    },
    program: {
      type: String,
      default: 'PPG',
    },
    useHeader: {
      type: Boolean,
      default: true,
    },
  },
  computed: {
    accept() {
      let result = [];

      if (/PDF|pdf/.test(this.rules && this.rules.format)) {
        result.push('.pdf');
      }

      if (/JPG|JPEG|PNG/.test(this.rules && this.rules.format)) {
        result.push('image/*');
      }

      if (/xlxs|XLXS|XLS|xls/.test(this.rules && this.rules.format)) {
        result.push('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      }

      return result;
    },
  },
  data() {
    return {
      form: {},
    };
  },
  methods: {
    reset() {
      this.$set(this, 'form', {});
    },
    initForm(value) {
      const formulir = [{ name: 'berkas' }, { name: 'file' }];
      for (const item of formulir) {
        this.$set(this.form, item.name, this.$getDeepObj(value, item.name) || '');
      }
    },
    roundDecimal(value) {
      return roundDecimal(value);
    },
    onRemoveFile() {
      this.form.berkas = [];
    },
    onCheck(value) {
      const rules = {
        pdf: 'application/pdf',
        image: 'image',
      };
      if (value && value.type) {
        if (this.rules && this.rules.format && this.rules.format.toLowerCase() === 'pdf') {
          if (value.type !== rules[this.rules.format.toLowerCase()]) {
            this.form = Object.assign({}, {});
          }
        }
      }
    },
  },
  watch: {
    initValue: 'initForm',
  },
};
</script>
