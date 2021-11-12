import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/diklat',
    name: 'kelola-diklat',
    component: () => lazyLoadView(import('@views/instansi/diklat/list/base/Main')),
    meta: {
      title: 'Diklatku',
      tipe: 'daring',
    },
  },
  {
    path: '/i/:id(\\d+)/diklat-kelas/:diklat_id',
    name: 'diklat-kelas',
    component: () => lazyLoadView(import('@views/instansi/diklat/list/kelas/Index')),
    meta: {
      title: 'Kelola Diklat',
      tipe: 'daring',
    },
  },
  {
    path: '/i/:id(\\d+)/jadwal',
    name: 'kelola-jadwal',
    component: () => lazyLoadView(import('@views/instansi/diklat/list/jadwal/Index')),
    meta: {
      title: 'Kelola Jadwal Diklat',
      tipe: 'daring',
    },
  },
  {
    path: '/i/:id(\\d+)/diklat-luring',
    name: 'kelola-diklat-luring',
    component: () => lazyLoadView(import('@views/instansi/diklat/list/base/Main')),
    meta: {
      title: 'Diklatku',
      tipe: 'luring',
    },
  },
  {
    path: '/i/:id(\\d+)/diklat-kelas-luring/:diklat_id',
    name: 'diklat-kelas-luring',
    component: () => lazyLoadView(import('@views/instansi/diklat/list/kelas/Index')),
    meta: {
      title: 'Kelola Diklat',
      tipe: 'luring',
    },
  },
  {
    path: '/i/:id(\\d+)/jadwal-luring',
    name: 'kelola-jadwal-luring',
    component: () => lazyLoadView(import('@views/instansi/diklat/list/jadwal/Index')),
    meta: {
      title: 'Kelola Jadwal Diklat',
      tipe: 'luring',
    },
  },
];
