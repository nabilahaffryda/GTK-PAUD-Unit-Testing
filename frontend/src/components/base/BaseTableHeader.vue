<template>
  <v-card flat width="100%">
    <v-card-text :class="!searchInput ? 'py-1' : ''">
      <v-row no-gutters>
        <v-col cols="12" md="3" class="my-auto">
          <v-row no-gutters>
            <v-col>
              <v-list-item class="pa-0">
                <v-list-item-content class="pa-0">
                  <v-list-item-title class="title">
                    <slot name="title" />
                  </v-list-item-title>
                  <v-list-item-subtitle>
                    <slot name="subtitle" />
                  </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col class="hidden-md-and-up">
              <v-toolbar flat dense>
                <slot name="toolbar" />
                <v-spacer></v-spacer>
                <v-btn small icon @click="add" v-if="btnAdd">
                  <v-icon color="secondary">mdi-plus</v-icon>
                </v-btn>
                <v-btn small icon @click="download" v-if="btnDownload">
                  <v-icon color="secondary">mdi-download</v-icon>
                </v-btn>
                <v-btn small icon @click="filter" v-if="btnFilter">
                  <v-icon color="secondary">mdi-filter-variant</v-icon>
                </v-btn>
                <v-btn small icon @click="reload" v-if="btnReload">
                  <v-icon color="secondary">mdi-reload</v-icon>
                </v-btn>
              </v-toolbar>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="12" md="9">
          <div class="hidden-md-and-up">
            <v-text-field
              v-if="searchInput"
              v-model="keyword"
              :label="searchLabel"
              dense
              single-line
              outlined
              hide-details
              class="mt-1 mr-1"
              append-icon="mdi-magnify"
              @keyup.enter="search"
              @click:append="search"
            >
            </v-text-field>
          </div>
          <div class="hidden-sm-only hidden-xs-only">
            <v-toolbar flat>
              <v-spacer></v-spacer>
              <v-text-field
                v-if="searchInput"
                v-model="keyword"
                :label="searchLabel"
                dense
                single-line
                outlined
                hide-details
                class="my-auto mr-1"
                append-icon="mdi-magnify"
                @keyup.enter="search"
                @click:append="search"
              >
              </v-text-field>
              <div style="min-width: 8%">
                <v-tooltip top>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      class="button-action"
                      outlined
                      depressed
                      small
                      width="40px"
                      height="40px"
                      v-bind="attrs"
                      v-on="on"
                      @click="upload"
                      v-if="btnUpload"
                    >
                      <v-icon color="primary">mdi-upload</v-icon>
                    </v-btn>
                  </template>
                  <span>Unggah</span>
                </v-tooltip>
                <v-tooltip top>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      class="button-action"
                      outlined
                      depressed
                      small
                      width="40px"
                      height="40px"
                      v-bind="attrs"
                      v-on="on"
                      @click="filter"
                      v-if="btnFilter"
                    >
                      <v-icon color="primary">mdi-filter-variant</v-icon>
                    </v-btn>
                  </template>
                  <span>Saring</span>
                </v-tooltip>
                <v-tooltip top>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      class="button-action"
                      outlined
                      depressed
                      small
                      width="40px"
                      height="40px"
                      v-bind="attrs"
                      v-on="on"
                      @click="download"
                      v-if="btnDownload"
                    >
                      <v-icon color="primary">mdi-download</v-icon>
                    </v-btn>
                  </template>
                  <span>Unduh</span>
                </v-tooltip>
                <v-tooltip top>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      class="button-action"
                      outlined
                      depressed
                      small
                      width="40px"
                      height="40px"
                      v-bind="attrs"
                      v-on="on"
                      @click="reload"
                      v-if="btnReload"
                    >
                      <v-icon color="primary">mdi-reload</v-icon>
                    </v-btn>
                  </template>
                  <span>Muat Ulang</span>
                </v-tooltip>
                <v-tooltip top>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      color="primary"
                      depressed
                      small
                      width="40px"
                      height="40px"
                      v-bind="attrs"
                      v-on="on"
                      @click="add"
                      v-if="btnAdd"
                    >
                      <v-icon color="white">mdi-plus</v-icon>
                    </v-btn>
                  </template>
                  <span>Tambah</span>
                </v-tooltip>
              </div>
              <slot name="toolbar" style="width: 89%" />
            </v-toolbar>
          </div>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: 'BaseTableHeader',
  props: {
    options: {
      type: Object,
      default: () => {},
    },
    searchInput: {
      type: Boolean,
      default: true,
    },
    searchLabel: {
      type: String,
      default: 'Cari Data',
    },
    btnFilter: {
      type: Boolean,
      default: false,
    },
    btnReload: {
      type: Boolean,
      default: true,
    },
    btnAdd: {
      type: Boolean,
      default: false,
    },
    btnUpload: {
      type: Boolean,
      default: false,
    },
    btnDownload: {
      type: Boolean,
      default: false,
    },
    classToolbar: {
      type: String,
      default: 'mb-6',
    },
  },
  data() {
    return {
      keyword: '',
    };
  },
  methods: {
    search() {
      this.$emit('search', this.keyword);
    },

    filter() {
      this.$emit('filter');
    },

    reload() {
      this.$emit('reload');
    },

    add() {
      this.$emit('add');
    },

    download() {
      this.$emit('download');
    },

    upload() {
      this.$emit('upload');
    },

    mutate(value) {
      this.$emit('keyword', value);
    },
  },

  watch: {
    keyword: 'mutate',
  },
};
</script>
<style scoped>
.border-bottom {
  border-bottom: 1px solid #a3a3a3;
}
.button-action {
  margin-right: 3px;
  border: solid 1px grey;
}
</style>
