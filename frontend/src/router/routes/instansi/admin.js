import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/admin',
    name: 'admin',
    component: () => lazyLoadView(import('@views/instansi/home/Index')),
    meta: {
      title: 'Kelola Admin',
    },
  },
];
