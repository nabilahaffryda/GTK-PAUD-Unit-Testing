export default [
  {
    key: 'kelola',
    program: 'Kelola Akun',
    subheading: 'Kelola Akun',
    submenu: false,
    menu: [
      {
        icon: 'mdi-office-building-outline',
        title: 'Kelola Institusi LPD',
        desc: 'Pengelolaan data Institusi LPD',
        color: 'red',
        deepColor: 'darken-4',
        akses: 'lpd.index',
        link: 'institusi',
        to: { name: 'institusi' },
      },
      {
        icon: 'mdi-account-tie',
        title: 'Kelola Admin program',
        desc: 'Pengelolaan data Akun Admin Program',
        color: 'blue',
        deepColor: 'darken-4',
        akses: 'akun-admin-program-lpd.index',
        link: 'admin-program',
        to: { name: 'admin-program' },
      },
      {
        icon: 'mdi-account-cog',
        title: 'Kelola Operator LPD',
        desc: 'Pengelolaan data Operator LPD',
        color: 'blue',
        deepColor: 'darken-1',
        akses: 'akun-operator-lpd.index',
        link: 'admin-operator',
        to: { name: 'admin-operator' },
      },
      {
        icon: 'mdi-account-cog',
        title: 'Kelola Pengajar',
        desc: 'Pengelolaan data Pengajar',
        color: 'orange',
        deepColor: 'darken-1',
        akses: true,
        link: 'admin-pengajar',
        to: { name: 'admin-pengajar' },
      },
      {
        icon: 'mdi-account-cog',
        title: 'Kelola Pengajar BIMTEK',
        desc: 'Pengelolaan data Pengajar BIMTEK',
        color: 'yellow',
        deepColor: 'darken-2',
        akses: true,
        link: 'admin-bimtek',
        to: { name: 'admin-bimtek' },
      },
      {
        icon: 'mdi-account-cog',
        title: 'Kelola Pengajar Tambahan',
        desc: 'Pengelolaan data Akun Pengajar Tambahan',
        color: 'green',
        deepColor: 'darken-2',
        akses: 'akun-pengajar-tambahan',
        link: 'admin-pengajar-tambahan',
        to: { name: 'admin-pengajar-tambahan' },
      },
      {
        icon: 'mdi-account-cog',
        title: 'Kelola Pembimbing Praktik',
        desc: 'Pengelolaan data Pembimbing Praktik',
        color: 'green',
        deepColor: 'darken-3',
        akses: 'akun-pembimbing-praktik.index',
        link: 'admin-pembimbing',
        to: { name: 'admin-pembimbing' },
      },
      {
        icon: 'mdi-account-voice',
        title: 'Profil Pengajar',
        desc: 'Profil data Pengajar',
        color: 'success',
        deepColor: 'darken-1',
        akses: true,
        link: 'profil-pengajar',
        to: { name: 'profil-pengajar' },
      },
      {
        icon: 'mdi-account-hard-hat',
        title: 'Profil Pengajar BIMTEK',
        desc: 'Profil data Pengajar BIMTEK',
        color: 'pink',
        deepColor: 'darken-2',
        akses: true,
        link: 'profil-bimtek',
        to: { name: 'profil-bimtek' },
      },
    ],
  },
  {
    icon: 'mdi-webhook',
    title: 'ke Aplikasi SIMPKB',
    desc: '',
    color: 'secondary',
    deepColor: 'darken-2',
    dividerTop: true,
    sidebar: true,
    akses: true,
    link: 'app-sim-pkb',
    href: '#SIM_PKB_URL#',
  },
];
