import store from '@store';
import localize from '@configs/localize';
import {
  assetsUrl,
  duration,
  durationYear,
  localDate,
  localTime,
  fAlamat,
  fkata,
  isArray,
  isObject,
  getDeepObj,
  arrayToObject,
  currency,
  dateToString,
  compareDate,
  stringToHtml,
  showMore,
  isLongText,
} from '@utils/format';

export default {
  data() {
    return {
      M_COLOR: {
        1: 'success',
        2: 'blue',
        3: 'purple ',
        4: 'pink',
        5: 'indigo',
      },

      M_HARI: {
        0: 'Minggu',
        1: 'Senin',
        2: 'Selasa',
        3: 'Rabu',
        4: 'Kamis',
        5: 'Jumat',
        6: 'Sabtu',
      },
    };
  },
  methods: {
    $imgUrl: (url) => (url ? assetsUrl(url) : ''),

    $localize: (key, program) => {
      return (localize && localize[key] && localize[key][program || 'ppg']) || '';
    },

    $lblBiodata: (key) => {
      return (localize && localize['lblBiodata'] && localize['lblBiodata'][key]) || '';
    },

    $allow(akses, policy) {
      // cek jika tidak ada akses
      if (!akses || (akses && typeof akses === 'boolean')) return typeof akses === 'boolean' ? akses : true;

      // jika ada ambil preferensi akses
      const akseses = store.getters['preferensi/akseses'] || [];

      let allow = false;
      const pathAkses = akses && typeof akses === 'string' && akses.split('|');
      if (pathAkses.length) {
        pathAkses.forEach((path) => {
          if (allow) return;
          if (!allow && policy) {
            allow =
              !path || path === 'true' || ((akseses || []).filter((item) => item === path).length > 0 && policy[path]);
          } else if (!allow && !policy) {
            allow = !path || path === 'true' || (akseses || []).filter((item) => item === path).length > 0;
          }
        });
      }

      return allow;
    },

    $downloadFile(sUrl) {
      // Creating new link node.
      const link = document.createElement('a');
      link.href = sUrl;

      // Dispatching click event.
      // if (document.createEvent) {
      //   const e = document.createEvent('MouseEvents')
      //   e.initEvent('click', true, true)
      //   link.dispatchEvent(e)
      //   return true
      // }
      window.open(sUrl);
      return true;
    },

    $durasi(start, end, options) {
      return duration(start, end, options);
    },

    $durasiTahun(start, end) {
      return durationYear(start, end);
    },

    $localDate(date, short, withTime, usingNumber, useSeconds) {
      if (!date) return '-';
      return localDate(date, short, withTime, usingNumber, useSeconds);
    },

    $localTime(time, options) {
      if (!time) return '-';
      return localTime(time, options);
    },

    $fAlamat(data) {
      return fAlamat(data);
    },

    $fkata(data) {
      return data.length ? fkata(data) : '-';
    },

    $fGender(data) {
      return data === 'L' ? 'Laki - laki' : data === 'P' ? 'Perempuan' : '-';
    },

    $status(status) {
      return Number(status) === 1 ? 'Aktif' : 'Non-aktif';
    },

    $isObject(data) {
      return isObject(data);
    },

    $isArray(data) {
      return isArray(data);
    },

    $getDeepObj(obj, desc) {
      return getDeepObj(obj, desc);
    },

    $getAttr(obj, data) {
      return getDeepObj(obj, `attributes.${data}`);
    },

    $getRelasi(obj, data) {
      return getDeepObj(obj, `relationships.${data}.data`);
    },

    $getIncluded(type, value, included) {
      this.included = included || this.included;
      const data = this.included.filter((item) => item.type === type && Number(item.id) === Number(value));
      return (data && data[0]) || {};
    },

    $getItem(item, value) {
      return item.filter((obj) => Number(obj.id) === Number(value))[0] || {};
    },

    $isAktif(data) {
      const akunAktif = this.$getAttr(data, 'is_aktif');
      const keterangan = Number(this.$getAttr(data, 'is_aktif')) === 1 ? 'Aktif' : 'Diblokir';
      const icon = Number(this.$getAttr(data, 'is_aktif')) === 1 ? 'mdi-check-circle' : 'mdi-close-circle';
      return { value: akunAktif, text: keterangan, icon };
    },

    $isAktivasi(data) {
      const akunAktif = this.$getDeepObj(data, 'is_aktivasi');
      const keterangan = Number(this.$getDeepObj(data, 'is_aktivasi')) === 1 ? 'Sudah Aktivasi' : 'Belum Aktivasi';
      const icon = Number(this.$getDeepObj(data, 'is_aktivasi')) === 1 ? 'mdi-check-circle' : 'mdi-close-circle';
      return { value: akunAktif, text: keterangan, icon };
    },

    $isVerifikasi(data) {
      const akunAktif = this.$getAttr(data, 'is_verifikasi');
      const keterangan = Number(this.$getAttr(data, 'is_verifikasi')) === 1 ? 'Sudah Verifikasi' : 'Belum Verifikasi';
      const icon = Number(this.$getAttr(data, 'is_verifikasi')) === 1 ? 'mdi-check-circle' : 'mdi-close-circle';
      return { value: akunAktif, text: keterangan, icon };
    },

    $mapForMaster(data, text = false) {
      // cek type data
      let temp = [];
      if (data && isObject(data)) {
        for (let key in data) {
          temp.push({
            text: data[key],
            value: text ? data[key] : Number(key),
          });
        }
      } else if (data && isArray(data)) {
        temp = data.map((value, idx) => {
          return {
            text: isObject(value) ? value?.keterangan ?? value.text : value,
            value: isObject(value) ? value[text] ?? value?.value ?? idx : value,
          };
        });
      }
      return temp;
    },

    $wordCount(kalimat) {
      if (!kalimat) return 0;
      let katas = kalimat.replace(/[^a-zA-Z0-9'\s]/g, '');
      katas = katas.replace(/\s+/g, ' ');
      // filter kata jika ada character selain digit dan letter
      const temp = katas.trim();
      const count = temp.split(' ');
      return count.length;
    },

    $titleCase(kata) {
      return kata.charAt(0).toUpperCase() + kata.slice(1);
    },

    $currency(data) {
      return currency(data);
    },

    $isNull(data) {
      return typeof data === 'object' && data === null;
    },

    $arrToObj(array, key) {
      if (!array || !array.length) return {};
      return arrayToObject(array, key);
    },

    $akseses(item, key) {
      let temp = [];
      item[key].forEach((key) => {
        if (this.$allow(key.akses)) temp.push(key);
      });

      return temp || [];
    },

    $checkExpired(dateEnd) {
      const now = new Date(dateToString(new Date())).getTime();
      const end = new Date(dateEnd).getTime();
      const result = Math.abs(end - now) / 100000;

      return result;
    },

    $compareDate(cDate, bDate, nDate) {
      return compareDate(cDate, bDate, nDate);
    },

    $stringToHtml(data) {
      return stringToHtml(data);
    },

    $dateToString(date) {
      return dateToString(date);
    },

    $showMore(value, isActived = false, maxLength = 200) {
      return showMore(value, isActived, maxLength);
    },

    $isLongText(value, maxLength = 200) {
      return isLongText(value, maxLength);
    },

    $isTutupPendaftaran(kFungsiGpm) {
      const fungsiGpm = {
        1: false,
        2: false,
      };

      return fungsiGpm[kFungsiGpm];
    },
  },
};
