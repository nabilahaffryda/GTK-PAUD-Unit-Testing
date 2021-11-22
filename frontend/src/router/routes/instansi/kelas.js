import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/kelas',
    name: 'kelas',
    component: () => lazyLoadView(import('@views/instansi/kelas/list/Index')),
    meta: {
      title: 'Daftar Kelas Diklat Daring',
      jenis: 'daring',
    },
  },
  {
    path: '/i/:id(\\d+)/kelas-luring',
    name: 'kelas-luring',
    component: () => lazyLoadView(import('@views/instansi/kelas/list/Index')),
    meta: {
      title: 'Daftar Kelas Diklat Luring',
      jenis: 'luring',
    },
  },
];
