import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/akun/program',
    name: 'admin-program',
    component: () => lazyLoadView(import('@views/instansi/admin/list/program/Index')),
    meta: {
      title: 'Kelola Akun Admin Program',
      k_group: '171',
      tipe: 'admin-program-lpd',
    },
  },
  {
    path: '/i/:id(\\d+)/akun/operator',
    name: 'admin-operator',
    component: () => lazyLoadView(import('@views/instansi/admin/list/operator/Index')),
    meta: {
      title: 'Kelola Operator LPD',
      k_group: '172',
      tipe: 'operator-lpd',
    },
  },
  {
    path: '/i/:id(\\d+)/akun/bimtek',
    name: 'admin-bimtek',
    component: () => lazyLoadView(import('@views/instansi/admin/list/bimtek/Index')),
    meta: {
      title: 'Kelola Pengajar BIMTEK',
      k_group: '173',
      tipe: 'pengajar-bimtek',
    },
  },
  {
    path: '/i/:id(\\d+)/akun/pengajar',
    name: 'admin-pengajar',
    component: () => lazyLoadView(import('@views/instansi/admin/list/pengajar/Index')),
    meta: {
      title: 'Kelola Pengajar',
      k_group: '174',
      tipe: 'pengajar',
    },
  },
  {
    path: '/i/:id(\\d+)/akun/pengajar-tambahan',
    name: 'admin-pengajar-tambahan',
    component: () => lazyLoadView(import('@views/instansi/admin/list/pengajarTambahan/Index')),
    meta: {
      title: 'Kelola Pengajar Tambahan',
      k_group: '175',
      tipe: 'pengajar-tambahan',
    },
  },
  {
    path: '/i/:id(\\d+)/akun/pembimbing',
    name: 'admin-pembimbing',
    component: () => lazyLoadView(import('@views/instansi/admin/list/pembimbing/Index')),
    meta: {
      title: 'Kelola Pembimbing Praktik',
      k_group: '176',
      tipe: 'pembimbing-praktik',
    },
  },
  {
    path: '/i/:id(\\d+)/profil/pengajar',
    name: 'profil-pengajar',
    component: () => lazyLoadView(import('@views/instansi/admin/list/pengajar/Profil')),
    meta: {
      title: 'Profil Pengajar',
      k_group: '174',
      tipe: 'pengajar',
    },
  },
  {
    path: '/i/:id(\\d+)/profil/bimtek',
    name: 'profil-bimtek',
    component: () => lazyLoadView(import('@views/instansi/admin/list/bimtek/Profil')),
    meta: {
      title: 'Profil Pengajar BIMTEK',
      tipe: 'pengajar-bimtek',
    },
  },
  {
    path: '/i/:id(\\d+)/profil/operator',
    name: 'profil-operator',
    component: () => lazyLoadView(import('@views/instansi/admin/list/operator/Profil')),
    meta: {
      title: 'Profil Lembaga LPD',
      tipe: 'lembaga',
    },
  },
];
