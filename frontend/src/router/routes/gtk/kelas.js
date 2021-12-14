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
  {
    path: '/kelas/:id(\\d+)',
    name: 'kelas-detail',
    component: () => lazyLoadView(import('@views/gtk/kelas/Detail')),
    meta: {
      title: 'Kelas Diklat',
    },
  },
];
