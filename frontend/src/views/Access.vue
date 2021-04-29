<template>
  <v-card flat>
    <v-app-bar absolute color="secondary darken-2" elevate-on-scroll scroll-target="#scrolling-techniques-7">
      <v-toolbar-title class="white--text">{{ title }}</v-toolbar-title>
      <v-spacer />
      <v-btn dark text @click="onDownload">
        <v-icon>mdi-download</v-icon>
        Unduh
      </v-btn>
      <v-btn dark text @click="onSave">
        <v-icon>mdi-content-save</v-icon>
        Simpan
      </v-btn>
    </v-app-bar>
    <v-sheet id="scrolling-techniques-7" class="overflow-y-auto py-12" max-height="88vh">
      <v-container class="py-5" style="height: 100vh">
        <v-row justify="center" dense class="pa-5">
          <v-col cols="12">
            <v-select
              label="Guard"
              v-model="form.guard"
              :items="guards"
              item-text="text"
              item-value="value"
              outlined
              hide-details
              dense
            ></v-select>
          </v-col>
          <v-col cols="12">
            <v-autocomplete
              v-model="form.k_group"
              :items="peransOpt"
              label="Peran"
              item-text="keterangan"
              item-value="k_group"
              outlined
              hide-details
              multiple
              chips
              small-chips
              dense
            >
              <template v-slot:item="{ item }">
                <v-list-item-content>
                  <v-list-item-title v-html="item.keterangan" />
                </v-list-item-content>
              </template>
            </v-autocomplete>
          </v-col>
          <v-col cols="12">
            <v-text-field
              v-model="form.akses_key"
              label="Filter Akses"
              placeholder="Masukan kata kunci pencarian"
              outlined
              dense
              hide-details
              @keyup.enter="search"
            />
          </v-col>
          <v-col>
            <div class="text-right">
              <v-btn right @click="search" depressed color="secondary">Cari</v-btn>
            </div>
          </v-col>
        </v-row>
        <v-divider></v-divider>
        <v-data-table
          v-if="akseses.length"
          :headers="headers"
          :items="akseses"
          :items-per-page="akseses.length"
          hide-default-footer
          show-select
        >
          <template v-slot:[`header.data-table-select`]>
            <v-row align="center">
              <v-chip label outlined>
                <v-checkbox v-model="selectAll" :value="1" hide-details class="shrink mr-2 mt-0"></v-checkbox>
                <v-menu bottom left>
                  <template v-slot:activator="{ on }">
                    <v-btn x-small light icon :disabled="!selected.length" v-on="on">
                      <v-icon>mdi-chevron-down</v-icon>
                    </v-btn>
                  </template>
                  <v-list>
                    <v-list-item @click="onAktif">
                      <v-list-item-title>Aktifkan/Nonaktifkan</v-list-item-title>
                    </v-list-item>
                    <!--                  <v-list-item @click="onTutup">-->
                    <!--                    <v-list-item-title>Tutup/Buka</v-list-item-title>-->
                    <!--                  </v-list-item>-->
                  </v-list>
                </v-menu>
              </v-chip>
            </v-row>
          </template>
          <template v-slot:body="{ items }">
            <tbody>
              <tr v-for="item in items" :key="item.paud_akses_id">
                <td>
                  <v-checkbox v-model="selected" :value="item.paud_akses_id" hide-details />
                </td>
                <td>{{ item.akses }}</td>
                <template v-for="id in form.k_group">
                  <td :key="id">
                    <base-switch
                      :isTutup="Number(item.is_tutup) === 1"
                      :isAktif="Number(item.is_aktif) === 1"
                      v-model="akses[id][item.paud_akses_id]"
                    ></base-switch>
                  </td>
                </template>
              </tr>
            </tbody>
          </template>
        </v-data-table>
      </v-container>
    </v-sheet>
  </v-card>
</template>

<script>
import { mapActions } from 'vuex';
import { removeEmptyObject } from '@utils/format';
import BaseSwitch from '@/components/base/BaseSwitch';
export default {
  name: 'access',
  components: { BaseSwitch },
  data() {
    return {
      title: 'Peran Akses',
      selectAll: 0,
      selected: [],
      peransOpt: [],
      akseses: [],
      routings: [],
      M_COLOR: {
        0: 'error',
        1: 'success',
      },
      guards: [
        { text: 'Akun', value: 'akun' },
        { text: 'PTK', value: 'ptk' },
      ],
      form: {
        guard: this.$route.query.guard || 'akun',
        k_group: null,
        akses_key: null,
      },
      akses: {},
      height: window.innerHeight,
    };
  },
  computed: {
    M_GROUP() {
      return this.$arrToObj(this.peransOpt, 'k_group');
    },
    headers() {
      const header = [
        {
          text: 'Akses',
          value: 'akses',
        },
      ];

      if (this.form.k_group && this.form.k_group.length) {
        for (const obj of this.form.k_group) {
          header.push({
            text: this.M_GROUP[obj] && this.M_GROUP[obj]['keterangan'],
            value: obj,
          });
        }
      }

      return header;
    },
  },
  created() {
    this.getGroup({ guard: this.form.guard })
      .then((data) => {
        this.peransOpt = (data && data.groups) || [];
      })
      .then(() => {
        const kGroup = this.$route.query && this.$route.query.k_group;
        this.form.guard = this.$route.query.guard || 'akun';
        this.form.k_group = this.$isArray(kGroup)
          ? (kGroup || []).map((item) => Number(item))
          : kGroup
          ? [Number(kGroup)]
          : [];
        this.form.akses_key = this.$route.query.akses_key;
        this.getRouting();
      });
  },
  methods: {
    ...mapActions('access', ['getGroup', 'fetch', 'save', 'saveAktif', 'download']),

    checkAll(value) {
      if (value === 1) {
        this.selected = this.akseses && (this.akseses || []).map((item) => item.paud_akses_id);
      } else {
        this.selected = [];
      }
    },

    search() {
      if (!this.form.guard || !this.form.k_group) return;
      this.$router.push({
        query: {
          akses_key: this.form.akses_key,
          guard: this.form.guard,
          k_group: this.form.k_group,
        },
      });
    },

    getRouting() {
      if (!this.form.guard || !this.form.k_group.length) return;

      this.fetch(this.form).then((resp) => {
        this.akseses = (resp && resp.akseses) || [];

        // set model akses
        if (this.form.k_group && this.form.k_group.length) {
          for (const obj of this.form.k_group) {
            this.$set(this.akses, obj, {});

            if (resp.group_akses && resp.group_akses[obj] && Object.keys(resp.group_akses[obj]).length) {
              for (const akses in resp.group_akses[obj]) {
                this.$set(this.akses[obj], akses, String(resp.group_akses[obj][akses]));
              }
            }
          }
        }
      });
    },

    onSave() {
      const params = Object.assign({}, this.form, {
        group_akses: removeEmptyObject(this.akses),
      });

      this.save(params).then(() => {
        this.$success(`Akses Peran berhasil disimpan!`);
        this.getRouting();
      });
    },

    onDownload() {
      if (!this.form.guard || !this.form.k_group) return;
      this.download(this.form).then((url) => {
        this.$downloadFile(url);
      });
    },

    onAktif() {
      const akseses = this.akseses.filter((item) => this.selected.indexOf(item['paud_akses_id']) > -1);
      const isAktif = akseses && akseses[0] && akseses[0]['is_aktif'] === 1 ? 0 : 1;

      const params = Object.assign(
        {},
        {
          akses_id: this.selected,
          is_aktif: isAktif,
        }
      );

      this.$confirm(
        `Apakah Anda yakin ingin ${isAktif === 1 ? 'mengaktifkan' : 'menon-aktifkan'} akses yang Anda pilih?`,
        `Aktif/Non aktif Akses`,
        {
          tipe: 'warning',
          data: '',
        }
      ).then(() => {
        this.saveAktif(params).then(() => {
          this.$success(`Akses Peran berhasil ${isAktif === 1 ? 'diaktifkan' : 'dinonaktifkan'}!`);
          this.getRouting();
        });
      });
    },

    changeGuard(value) {
      this.getGroup({ guard: value })
        .then((data) => {
          this.peransOpt = (data && data.groups) || [];
        })
        .then(() => {
          this.akseses = [];
          this.form.k_group = [];
        });
    },
  },
  watch: {
    'form.guard': 'changeGuard',
    selectAll: 'checkAll',
  },
};
</script>
