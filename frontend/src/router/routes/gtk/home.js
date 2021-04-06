import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/home',
    name: 'home',
    component: () => lazyLoadView(import('@views/instansi/home/Index')),
    meta: {
      title: 'Beranda',
    },
  },
  { path: '/', redirect: { name: 'home' } },
];
