import { lazyLoadView } from '@router/helpers';

export default [
  {
    path: '/404',
    name: '404',
    component: () => lazyLoadView(import('@layouts/404')),
    meta: {
      public: true,
    },
  },
  {
    path: '/akses',
    name: 'access',
    component: () => lazyLoadView(import('@views/Access')),
    meta: {
      title: 'Pengaturan Hak Akses',
    },
  },
  // {
  //   path: '/auth',
  //   component: () => lazyLoadView(import('@views/auth/Main')),
  //   children: [
  //     {
  //       path: 'daftar',
  //       name: 'daftar',
  //       component: () => lazyLoadView(import('@views/auth/Daftar')),
  //       alias: '/pendaftaran-sekolah-penggerak',
  //       meta: {
  //         title: 'Daftar',
  //         public: true,
  //         layouts: 'Layout',
  //       },
  //     },
  //     {
  //       path: 'aktivasi/:token',
  //       name: 'aktivasi',
  //       component: () => lazyLoadView(import('@views/auth/Aktivasi')),
  //       meta: {
  //         title: 'Aktivasi',
  //         public: true,
  //         layouts: 'Layout',
  //       },
  //     },
  //     {
  //       path: 'reaktivasi',
  //       name: 'reaktivasi',
  //       component: () => lazyLoadView(import('@views/auth/ReAktivasi')),
  //       meta: {
  //         title: 'ReAktivasi',
  //         public: true,
  //         layouts: 'Layout',
  //       },
  //     },
  //   ],
  // },
];
