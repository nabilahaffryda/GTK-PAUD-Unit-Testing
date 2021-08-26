import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/home',
    name: 'home',
    component: () => lazyLoadView(import('@views/gtk/home/Index')),
    meta: {
      title: 'Beranda',
    },
  },
  { path: '/', redirect: { name: 'home' } },
];
