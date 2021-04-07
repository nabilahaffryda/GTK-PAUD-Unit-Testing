import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/akun/program',
    name: 'admin-program',
    component: () => lazyLoadView(import('@views/instansi/admin/list/Program')),
    meta: {
      title: 'Kelola Akun Admin Program',
    },
  },
  {
    path: '/i/:id(\\d+)/akun/operator',
    name: 'admin-operator',
    component: () => lazyLoadView(import('@views/instansi/admin/list/Operator')),
    meta: {
      title: 'Kelola Operator LPD',
    },
  },
];
