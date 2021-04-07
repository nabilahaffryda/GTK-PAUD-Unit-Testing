import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/admin',
    name: 'admin',
    component: () => lazyLoadView(import('@views/instansi/admin/list/Index')),
    meta: {
      title: 'Kelola Akun Admin Program',
    },
  },
];
