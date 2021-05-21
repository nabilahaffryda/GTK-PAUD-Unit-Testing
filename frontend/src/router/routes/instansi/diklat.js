import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/diklat',
    name: 'diklat',
    component: () => lazyLoadView(import('@views/instansi/diklat/list/base/Main')),
    meta: {
      title: 'Diklatku',
    },
  },
  {
    path: '/i/:id(\\d+)/diklat-kelas/:kelas_id',
    name: 'diklat-kelas',
    component: () => lazyLoadView(import('@views/instansi/diklat/list/kelas/Index')),
    meta: {
      title: 'Kelola Diklat',
    },
  },
];
