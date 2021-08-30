import { required, email, length, confirmed } from 'vee-validate/dist/rules';
import { extend, localize } from 'vee-validate';
import id from 'vee-validate/dist/locale/id.json';

extend('required', {
  ...required,
  message: '{_field_} wajib diisi',
});

extend('email', {
  ...email,
  message: 'Isian harus berupa valid email',
});

extend('nik', {
  validate(value) {
    return Number(value) && value.length === 16;
  },
  message: 'Isian NIK harus berupa 16 digit angka',
});

extend('nip', {
  validate(value) {
    return Number(value) && value.length >= 10;
  },
  message: 'Isian NIP terhitung kurang dari 10 karakter',
});

extend('minTahun', {
  validate(value, { length }) {
    return value >= length;
  },
  params: ['length'],
  message: '{_field_} harus minimal {length} tahun',
});

extend('minChar', {
  validate(value, { length }) {
    return value.length > length;
  },
  params: ['length'],
  message: 'Data isian Anda terhitung kurang dari {length} karakter',
});

extend('maxChar', {
  validate(value, { length }) {
    return value.length < length;
  },
  params: ['length'],
  message: 'Data isian Anda ditolak! Karena lebih dari {length} karakter, mohon untuk disesuaikan',
});

extend('max', {
  validate(value, { length }) {
    return value <= length;
  },
  params: ['length'],
  message: 'Maksimal nilai yang diperbolehkan adalah {length}, mohon untuk disesuaikan',
});

extend('min', {
  validate(value, { length }) {
    return value >= length;
  },
  params: ['length'],
  message: 'Minimal nilai yang diperbolehkan adalah {length}, mohon untuk disesuaikan',
});

extend('length', length);
extend('confirmed', confirmed);
// Install English and Arabic locales.
localize('id', id);
