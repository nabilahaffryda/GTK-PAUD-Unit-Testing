import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/institusi',
    name: 'institusi',
    component: () => lazyLoadView(import('@views/instansi/institusi/list/Index')),
    meta: {
      title: 'Kelola Institusi LPD',
    },
  },
];
