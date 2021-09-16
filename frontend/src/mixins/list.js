import { isArray, isObject } from '@utils/format';
import BaseListTable from '@/components/base/BaseListTable';
import BaseListFilter from '@/components/base/BaseListFilter';
import BaseTableHeader from '@/components/base/BaseTableHeader';
import BaseTableFooter from '@/components/base/BaseTableFooter';
import BaseListAction from '@/components/base/BaseListAction';
export default {
  components: { BaseListAction, BaseTableFooter, BaseTableHeader, BaseListFilter, BaseListTable },
  data() {
    return {
      data: [],
      included: [],
      attr: {},
      readMore: {},
      params: {
        page: Number(this.$route.query.page) || 1,
      },
      filters: {},
      listLaporan: [],
      loading: false,
      keyword: '',
      page: 1,
      total: 0,
      pageTotal: 0,
      statistik: {},
    };
  },
  computed: {
    filtered() {
      const filters = this.filters;
      const master = this.filtersMaster;
      let label = [];
      for (let key in filters) {
        let value = filters[key];
        if (value && typeof value === 'object' && (isObject(value) || isArray(value))) {
          for (let i = 0; i < value.length; i++) {
            let text = master[key] && master[key][value[i]];
            label.push({ text, key, value });
          }
        } else if (value && (value !== '' || value !== null)) {
          let text = (master[key] && master[key][value]) || '';
          label.push({ text, key, value });
        }
      }
      return label;
    },
  },
  methods: {
    fetchData: function () {
      return new Promise((resolve) => {
        const params = Object.assign({}, this.params, this.$isObject(this.filters) ? { filter: this.filters } : {});
        const attr = Object.assign({}, this.attr);
        this.fetch({ params, attr }).then(({ data, meta }) => {
          this.data = data || [];
          this.total = meta?.total || data.length || 0;
          this.pageTotal = meta?.last_page || 1;
          this.statistik = meta?.statistik || {};
          resolve(true);
        });
      });
    },

    onReload() {
      this.fetchData();
    },

    onDownload() {
      const params = Object.assign(this.params, this.$isObject(this.filters) ? this.filters : {});
      this.downloadList({ params }).then((url) => {
        this.$downloadFile(url);
      });
    },

    onDownloadList() {
      const M_LAPORAN = this.listLaporan || [];
      let url = {};
      this.$confirm('Pilih jenis Berkas yang ingin di Unduh?', 'Unduh Berkas', {
        tipe: 'info',
        icon: 'mdi-download',
        form: {
          desc: 'Laporan Berkas',
          render: (h) => {
            return h(
              'select',
              {
                class: 'custom-select',
                domProps: {
                  value: '',
                },
                on: {
                  input: function (event) {
                    url.dokumen = event.target.value;
                  },
                },
              },
              [
                h('option', { attrs: { value: '' } }, '-- Pilih Laporan Berkas --'),
                M_LAPORAN.filter((laporan) => this.$allow(laporan.acl)).map((item) =>
                  h('option', { attrs: { value: item.key } }, item.label)
                ),
              ]
            );
          },
        },
        lblConfirmButton: 'Unduh',
      }).then(() => {
        if (!url.dokumen) {
          this.$error('Silakan pilih laporan yang ingin diunduh!');
          return;
        }

        const params = Object.assign(this.params, this.$isObject(this.filters) ? this.filters : {});
        this.downloadList({ params, url: url.dokumen }).then((url) => {
          this.$downloadFile(url);
        });
      });
    },

    onFilter() {
      this.$refs.filter.open(this.filters);
    },

    filterSave(filters) {
      // set filter
      this.filters = filters;
      Object.assign(this.params, { page: 1 });
      this.fetchData().then(() => {
        this.$refs.filter.close();
      });
    },

    onSearch(keyword) {
      Object.assign(this.params, { keyword: keyword, page: 1 });
      this.fetchData();
    },

    onChangePage(page) {
      this.$set(this.params, 'page', page);
      this.fetchData();
    },

    onAction(arg) {
      this[arg.event && arg.event.event](arg.data);
    },

    allow(action, data) {
      let allow = false;
      switch (action.event) {
        case 'onAktif':
          allow = Number(this.$getDeepObj(data, 'is_aktif')) !== 0;
          break;
        case 'onNonAktif':
          allow = !Number(this.$getDeepObj(data, 'is_aktif') || 0) === 1;
          break;
        default:
          allow = this.$allow(action.akses, data.policies);
          break;
      }
      return allow;
    },

    getColor(value) {
      switch (value) {
        case 1:
          return 'black';
        case 2:
          return 'info';
        case 3:
          return 'purple';
        case 4:
          return 'red';
        case 5:
          return 'warning';
        case 6:
          return 'success';
        default:
          return 'grey';
      }
    },

    onCall(item) {
      const url = `https://wa.me/62${Number(item)}`;
      window.open(url, '_blank');
    },
  },
};
