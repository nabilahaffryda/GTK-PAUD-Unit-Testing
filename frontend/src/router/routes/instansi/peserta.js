import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/peserta',
    name: 'peserta',
    component: () => lazyLoadView(import('@views/instansi/peserta/List')),
    meta: {
      title: 'Peserta Non Dapodik',
    },
  },
];
