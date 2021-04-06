import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/institusi',
    name: 'institusi',
    component: () => lazyLoadView(import('@views/instansi/home/Index')),
    meta: {
      title: 'Kelola Admin',
    },
  },
];
