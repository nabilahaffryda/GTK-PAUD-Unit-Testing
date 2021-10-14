import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/petugas',
    name: 'petugas',
    component: () => lazyLoadView(import('@views/instansi/petugas/Index')),
    meta: {
      title: 'Daftar PPT dan PPTM',
    },
  },
];
