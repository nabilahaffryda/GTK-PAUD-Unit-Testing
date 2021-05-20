import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/verval/pengajar',
    name: 'verval-pengajar',
    component: () => lazyLoadView(import('@views/instansi/verval/base/Main')),
    meta: {
      title: 'Verval Pengajar',
      tipe: 'petugas',
      paudkey: 'pengajar',
      atribut: 'akun',
    },
  },
  {
    path: '/i/:id(\\d+)/verval/lembaga',
    name: 'verval-lembaga',
    component: () => lazyLoadView(import('@views/instansi/verval/base/Main')),
    meta: {
      title: 'Verval Lembaga LPD',
      tipe: 'lpd',
      atribut: 'instansi',
      paudkey: 'instansi',
    },
  },
];
