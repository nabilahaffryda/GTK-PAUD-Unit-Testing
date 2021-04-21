import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/akun/program',
    name: 'akun-program',
    component: () => lazyLoadView(import('@views/instansi/akun/list/program/Index')),
    meta: {
      title: 'Kelola Akun Admin Program',
      k_group: '171',
      tipe: 'admin-program-lpd',
    },
  },
  {
    path: '/i/:id(\\d+)/akun/operator',
    name: 'akun-operator',
    component: () => lazyLoadView(import('@views/instansi/akun/list/operator/Index')),
    meta: {
      title: 'Kelola Operator LPD',
      k_group: '172',
      tipe: 'operator-lpd',
    },
  },
  {
    path: '/i/:id(\\d+)/akun/bimtek',
    name: 'akun-bimtek',
    component: () => lazyLoadView(import('@views/instansi/akun/list/bimtek/Index')),
    meta: {
      title: 'Kelola Pengajar BIMTEK',
      k_group: '173',
      tipe: 'pengajar-bimtek',
    },
  },
  {
    path: '/i/:id(\\d+)/akun/pengajar',
    name: 'akun-pengajar',
    component: () => lazyLoadView(import('@views/instansi/akun/list/pengajar/Index')),
    meta: {
      title: 'Kelola Pengajar',
      k_group: '174',
      tipe: 'pengajar',
    },
  },
  {
    path: '/i/:id(\\d+)/akun/pengajar-tambahan',
    name: 'akun-pengajar-tambahan',
    component: () => lazyLoadView(import('@views/instansi/akun/list/pengajarTambahan/Index')),
    meta: {
      title: 'Kelola Pengajar Tambahan',
      k_group: '175',
      tipe: 'pengajar-tambahan',
    },
  },
  {
    path: '/i/:id(\\d+)/akun/pembimbing',
    name: 'akun-pembimbing',
    component: () => lazyLoadView(import('@views/instansi/akun/list/pembimbing/Index')),
    meta: {
      title: 'Kelola Pembimbing Praktik',
      k_group: '176',
      tipe: 'pembimbing-praktik',
    },
  },
];