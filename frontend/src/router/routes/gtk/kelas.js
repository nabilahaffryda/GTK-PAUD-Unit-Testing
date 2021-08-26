import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/kelas',
    name: 'kelas',
    component: () => lazyLoadView(import('@views/gtk/kelas/Index')),
    meta: {
      title: 'Kelas Diklat',
    },
  },
];
