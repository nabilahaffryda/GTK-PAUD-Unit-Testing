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
        link: 'akun-program',
        to: { name: 'akun-program' },
      },
      {
        icon: 'mdi-account-cog',
        title: 'Kelola Operator LPD',
        desc: 'Pengelolaan data Operator LPD',
        color: 'blue',
        deepColor: 'darken-1',
        akses: 'akun-operator-lpd.index',
        link: 'akun-operator',
        to: { name: 'akun-operator' },
      },
      {
        icon: 'mdi-account-cog',
        title: 'Kelola Pengajar',
        desc: 'Pengelolaan data Pengajar',
        color: 'orange',
        deepColor: 'darken-1',
        akses: 'akun-pengajar.index',
        link: 'akun-pengajar',
        to: { name: 'akun-pengajar' },
      },
      {
        icon: 'mdi-account-cog',
        title: 'Kelola Pengajar BIMTEK',
        desc: 'Pengelolaan data Pengajar BIMTEK',
        color: 'yellow',
        deepColor: 'darken-2',
        akses: 'akun-pengajar-bimtek.index',
        link: 'akun-bimtek',
        to: { name: 'akun-bimtek' },
      },
      {
        icon: 'mdi-account-cog',
        title: 'Kelola Pengajar Tambahan',
        desc: 'Pengelolaan data Akun Pengajar Tambahan',
        color: 'red',
        deepColor: 'darken-2',
        akses: 'akun-pengajar-tambahan.index',
        link: 'akun-pengajar-tambahan',
        to: { name: 'akun-pengajar-tambahan' },
      },
      {
        icon: 'mdi-account-cog',
        title: 'Kelola Pembimbing Praktik',
        desc: 'Pengelolaan data Pembimbing Praktik',
        color: 'orange',
        deepColor: 'darken-3',
        akses: 'akun-pembimbing-praktik.index',
        link: 'akun-pembimbing',
        to: { name: 'akun-pembimbing' },
      },
    ],
  },
  {
    key: 'profil',
    program: 'Profil Akun',
    subheading: 'Profil Akun',
    submenu: false,
    menu: [
      {
        icon: 'mdi-account-voice',
        title: 'Profil Pengajar',
        desc: 'Profil data Pengajar',
        color: 'success',
        deepColor: 'darken-1',
        akses: 'pengajar-profil.index',
        link: 'profil-pengajar',
        to: { name: 'profil-pengajar' },
      },
      {
        icon: 'mdi-account-hard-hat',
        title: 'Profil Pengajar BIMTEK',
        desc: 'Profil data Pengajar BIMTEK',
        color: 'pink',
        deepColor: 'darken-2',
        akses: false,
        link: 'profil-bimtek',
        to: { name: 'profil-bimtek' },
      },
      {
        icon: 'mdi-account-voice',
        title: 'Pengajuan Lembaga',
        desc: 'Pengajuan dan Profil LPD',
        color: 'yellow',
        deepColor: 'darken-1',
        akses: 'lpd-profil.index',
        link: 'profil-operator',
        to: { name: 'profil-operator' },
      },
    ],
  },
  {
    key: 'verval',
    program: 'Verval',
    subheading: 'Verval Ajuan',
    submenu: false,
    menu: [
      {
        icon: 'mdi-account-voice',
        title: 'Verval Pendaftaran LPD',
        desc: 'Evaluasi dan Verifikasi Dokumen Penting Lembaga LPD',
        color: 'success',
        deepColor: 'darken-1',
        akses: false,
        link: 'verval-lembaga',
        to: { name: 'verval-lembaga' },
      },
      {
        icon: 'mdi-account-voice',
        title: 'Verval Pendaftaran Pengajar',
        desc: 'Evaluasi dan Verifikasi Dokumen Penting Pengajar',
        color: 'red',
        deepColor: 'darken-1',
        akses: false,
        link: 'verval-lembaga',
        to: { name: 'verval-lembaga' },
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
