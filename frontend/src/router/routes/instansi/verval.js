import { lazyLoadView } from '@router/helpers';
export default [
  {
    path: '/i/:id(\\d+)/verval/pengajar',
    name: 'verval-pengajar',
    component: () => lazyLoadView(import('@views/instansi/verval/pengajarTambahan/Index.vue')),
    meta: {
      title: 'Verval Pengajar Tambahan',
      tipe: 'petugas',
      paudkey: 'petugas',
      atribut: 'akun',
    },
  },
  {
    path: '/i/:id(\\d+)/verval/lembaga',
    name: 'verval-lembaga',
    component: () => lazyLoadView(import('@views/instansi/verval/lembaga/Index.vue')),
    meta: {
      title: 'Verval Lembaga LPD',
      tipe: 'lpd',
      atribut: 'instansi',
      paudkey: 'instansi',
    },
  },
  {
    path: '/i/:id(\\d+)/verval/kelas',
    name: 'verval-kelas',
    component: () => lazyLoadView(import('@views/instansi/diklat/list/verval/Index.vue')),
    meta: {
      title: 'Verval Kelas Diklat',
      tipe: 'kelas',
      akses: 'daring',
    },
  },
  {
    path: '/i/:id(\\d+)/verval/kelas-luring',
    name: 'verval-kelas-luring',
    component: () => lazyLoadView(import('@views/instansi/diklat/list/verval/Index.vue')),
    meta: {
      title: 'Verval Kelas Diklat Luring',
      tipe: 'kelas-luring',
      akses: 'luring',
    },
  },
];
