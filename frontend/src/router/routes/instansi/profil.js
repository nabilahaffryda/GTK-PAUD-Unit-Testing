import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/profil/pengajar',
    name: 'profil-pengajar',
    component: () => lazyLoadView(import('@views/instansi/profil/pengajar/Index')),
    meta: {
      title: 'Profil Pengajar',
      k_group: '174',
      tipe: 'pengajar',
    },
  },
  {
    path: '/i/:id(\\d+)/profil/bimtek',
    name: 'profil-bimtek',
    component: () => lazyLoadView(import('@views/instansi/profil/bimtek/Index')),
    meta: {
      title: 'Profil Pengajar BIMTEK',
      tipe: 'pengajar-bimtek',
    },
  },
  {
    path: '/i/:id(\\d+)/profil/operator',
    name: 'profil-operator',
    component: () => lazyLoadView(import('@views/instansi/profil/operator/Index')),
    meta: {
      title: 'Profil Lembaga LPD',
      tipe: 'lpd',
    },
  },
];
