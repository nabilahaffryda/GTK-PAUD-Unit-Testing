export default [
  {
    key: 'verval',
    program: 'Verval Berkas',
    subheading: 'Verval Berkas',
    submenu: false,
    menu: [
      {
        icon: 'mdi-account-check-outline',
        title: 'Daftar Riwayat Hidup (CV)',
        desc: 'Evaluasi dan Verifikasi Daftar Riwayat Hidup (CV) Peserta Sekolah Penggerak',
        color: 'blue',
        deepColor: 'darken-4',
        akses: 'psp-peserta-berkas.index',
        link: 'verval-cv',
        to: { name: 'verval-cv' },
      },
      {
        icon: 'mdi-account-check-outline',
        title: 'Pengecekan Prasyarat',
        desc: 'Pengecekan Prasyarat Pendaftaran Calon Sekolah Penggerak',
        color: 'success',
        deepColor: 'darken-2',
        akses: 'psp-peserta-profil.prasyarat',
        link: 'cek-prasyarat',
        to: { name: 'cek-prasyarat' },
      },
    ],
  },
  {
    key: 'penilaian',
    program: 'Penilaian Kandidat',
    subheading: 'Penilaian Kandidat',
    submenu: false,
    menu: [
      {
        icon: 'mdi-comment-edit',
        title: `Esai`,
        subtitle: '16 Maret - 30 Maret 2021',
        desc: 'Penilaian Esai Kandidat',
        color: 'orange',
        deepColor: 'darken-2',
        akses: 'psp-peserta-esai.index',
        link: 'penilaian-esai',
        to: { name: 'penilaian-esai' },
      },
      {
        icon: 'mdi-teach',
        title: `Simulasi & Wawancara`,
        subtitle: '07 - 17 April 2021',
        desc: 'Penilaian dan jadwal Simulasi Mengajar dan Wawancara',
        color: 'teal',
        deepColor: 'lighten-1',
        akses: 'psp-peserta-simulasi-ajar.index',
        link: 'penilaian-microteaching-kasek',
        // action: 'simulasi-ajar-asesor-cgp',
        to: { name: 'penilaian-microteaching-kasek' },
      },
      // {
      //   icon: 'mdi-google-hangouts',
      //   title: `Wawancara`,
      //   subtitle: '11 - 04 Maret 2021',
      //   desc: 'Penilaian dan jadwal Wawancara',
      //   color: 'orange',
      //   deepColor: 'darken-1',
      //   akses: 'guru-penggerak-wawancara.index-peserta',
      //   link: 'penilaian-wawancara-guru',
      //   to: { name: 'penilaian-wawancara-guru' },
      //   disable: false,
      // },
    ],
  },
  {
    key: 'kelola',
    program: 'Kelola Akun',
    subheading: 'Kelola Akun',
    submenu: false,
    menu: [
      {
        icon: 'mdi-account',
        title: 'Kelola Admin',
        desc: 'Pengelolaan data Akun Admin',
        color: 'red',
        deepColor: 'darken-4',
        akses: 'psp-admin.index',
        link: 'admin',
        to: { name: 'admin' },
      },
    ],
  },
  {
    icon: 'mdi-web',
    title: 'Portal Sekolah Penggerak',
    desc: '',
    color: 'secondary',
    deepColor: 'darken-2',
    dividerTop: true,
    sidebar: true,
    akses: true,
    newtab: true,
    href: '#PORTAL_URL#',
  },
  {
    icon: 'mdi-webhook',
    title: 'ke Aplikasi SIMPKB',
    desc: '',
    color: 'secondary',
    deepColor: 'darken-2',
    sidebar: true,
    akses: true,
    link: 'app-sim-pkb',
    href: '#SIM_PKB_URL#',
  },
];
