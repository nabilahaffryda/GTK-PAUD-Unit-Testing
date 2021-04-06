import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/home',
    name: 'home',
    component: () => lazyLoadView(import('@views/instansi/home/Index')),
    meta: {
      title: 'Beranda',
    },
  },
  { path: '/i/:id(\\d+)', redirect: { name: 'home' } },
  { path: '/', redirect: { name: 'home' } },
];
