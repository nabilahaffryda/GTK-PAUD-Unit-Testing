import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/diklat',
    name: 'kelola-diklat',
    component: () => lazyLoadView(import('@views/instansi/diklat/list/base/Main')),
    meta: {
      title: 'Diklatku',
    },
  },
  {
    path: '/i/:id(\\d+)/diklat-kelas/:diklat_id',
    name: 'diklat-kelas',
    component: () => lazyLoadView(import('@views/instansi/diklat/list/kelas/Index')),
    meta: {
      title: 'Kelola Diklat',
    },
  },
  {
    path: '/i/:id(\\d+)/jadwal',
    name: 'kelola-jadwal',
    component: () => lazyLoadView(import('@views/instansi/diklat/list/jadwal/Index')),
    meta: {
      title: 'Kelola Jadwal Diklat',
    },
  },
];
