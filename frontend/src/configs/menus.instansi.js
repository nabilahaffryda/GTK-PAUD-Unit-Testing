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
        title: 'Kelola Admin Program LPD',
        desc: 'Pengelolaan data Akun Admin Program LPD',
        color: 'blue',
        deepColor: 'darken-4',
        akses: 'akun-admin-program-lpd.index',
        link: 'akun-program',
        to: { name: 'akun-program' },
      },
      {
        icon: 'mdi-account-tie',
        title: 'Kelola Admin GTK',
        desc: 'Pengelolaan data Akun Admin GTK',
        color: 'pink',
        deepColor: 'darken-4',
        akses: 'akun-admin-gtk.index',
        link: 'akun-gtk',
        to: { name: 'akun-gtk' },
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
        title: 'Kelola Admin Kelas',
        desc: 'Pengelolaan data Admin Kelas',
        color: 'green',
        deepColor: 'darken-2',
        akses: 'akun-admin-kelas.index',
        link: 'akun-kelas',
        to: { name: 'akun-kelas' },
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
        icon: 'mdi-card-account-details',
        title: 'Profil Pengajar',
        desc: 'Profil data Pengajar',
        color: 'success',
        deepColor: 'darken-1',
        akses: 'pengajar-profil.index',
        link: 'profil-pengajar',
        to: { name: 'profil-pengajar' },
      },
      {
        icon: 'mdi-card-account-details',
        title: 'Profil Pengajar BIMTEK',
        desc: 'Profil data Pengajar BIMTEK',
        color: 'pink',
        deepColor: 'darken-2',
        akses: false,
        link: 'profil-bimtek',
        to: { name: 'profil-bimtek' },
      },
      {
        icon: 'mdi-card-account-details',
        title: 'Profil Admin Kelas',
        desc: 'Profil data Admin Kelas',
        color: 'blue',
        deepColor: 'darken-2',
        akses: 'admin-kelas-profil.index',
        link: 'profil-kelas',
        to: { name: 'profil-kelas' },
      },
      {
        icon: 'mdi-card-account-details',
        title: 'Profil Pembimbing Praktik',
        desc: 'Profil data Pembimbing Praktik',
        color: 'blue',
        deepColor: 'darken-2',
        akses: 'pembimbing-profil.index',
        link: 'profil-pembimbing',
        to: { name: 'profil-pembimbing' },
      },
      {
        icon: 'mdi-account-voice',
        title: 'Profil Lembaga',
        desc: 'Pengajuan dan Detail Profil LPD',
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
        icon: 'mdi-card-bulleted',
        title: 'Verval LPD',
        desc: 'Evaluasi dan Verifikasi Dokumen Penting Lembaga LPD',
        color: 'success',
        deepColor: 'darken-1',
        akses: 'lpd-verval.index',
        link: 'verval-lembaga',
        to: { name: 'verval-lembaga' },
      },
      {
        icon: 'mdi-account-check',
        title: 'Verval Pengajar Tambahan',
        desc: 'Evaluasi dan Verifikasi Dokumen Penting Pengajar Tambahan',
        color: 'red',
        deepColor: 'darken-1',
        akses: 'petugas-verval.index',
        link: 'verval-pengajar',
        to: { name: 'verval-pengajar' },
      },
      {
        icon: 'mdi-google-classroom',
        title: 'Verval Kelas Diklat Daring',
        desc: 'Evaluasi dan Verifikasi Kelas Diklat',
        color: 'red',
        deepColor: 'darken-1',
        akses: 'kelas-verval.index',
        link: 'verval-kelas',
        to: { name: 'verval-kelas' },
      },
      {
        icon: 'mdi-google-classroom',
        title: 'Verval Kelas Diklat Luring',
        desc: 'Evaluasi dan Verifikasi Kelas Diklat Luring',
        color: 'red',
        deepColor: 'darken-1',
        akses: 'kelas-luring-verval.index',
        link: 'verval-kelas-luring',
        to: { name: 'verval-kelas-luring' },
      },
      {
        icon: 'mdi-account-check',
        title: 'Verval Laporan Pelaksanaan Diklat Luring',
        desc: 'Evaluasi dan Verifikasi Laporan Pelaksanaan Diklat Luring',
        color: 'orange',
        deepColor: 'darken-1',
        akses: 'kelas-luring-laporan.index',
        link: 'verval-laporan-pelaksanaan',
        to: { name: 'verval-laporan-pelaksanaan' },
      },
    ],
  },
  {
    key: 'diklat',
    program: 'Diklat',
    subheading: 'Diklat',
    submenu: false,
    menu: [
      {
        icon: 'mdi-account-cog',
        title: 'Kelola Jadwal',
        desc: 'Kelola diklat kelas dan peserta diklat',
        color: 'red',
        deepColor: 'darken-1',
        akses: 'diklat-periode.index',
        link: 'kelola-jadwal',
        to: { name: 'kelola-jadwal' },
      },
      {
        icon: 'mdi-account-cog',
        title: 'Kelola Diklat Daring',
        desc: 'Kelola diklat kelas dan peserta diklat daring',
        color: 'success',
        deepColor: 'darken-1',
        akses: 'lpd-diklat.index',
        link: 'kelola-diklat',
        to: { name: 'kelola-diklat' },
      },
      {
        icon: 'mdi-account-cog',
        title: 'Kelola Diklat Luring',
        desc: 'Kelola diklat kelas dan peserta diklat daring',
        color: 'success',
        deepColor: 'darken-1',
        akses: 'lpd-luring-diklat.index',
        link: 'kelola-diklat-luring',
        to: { name: 'kelola-diklat-luring' },
      },
      {
        icon: 'mdi-chair-rolling',
        title: 'Kelas Diklat Daring',
        desc: 'Daftar Kelas diklat kelas dan peserta diklat daring',
        color: 'success',
        deepColor: 'darken-1',
        akses: 'petugas-kelas.index',
        link: 'kelas',
        to: { name: 'kelas' },
      },
      {
        icon: 'mdi-chair-rolling',
        title: 'Kelas Diklat Luring',
        desc: 'Daftar Kelas diklat kelas dan peserta diklat luring',
        color: 'success',
        deepColor: 'darken-1',
        akses: 'petugas-luring-kelas.index',
        link: 'kelas-luring',
        to: { name: 'kelas-luring' },
      },
      {
        icon: 'mdi-account-cog',
        title: 'PPM dan PPTM',
        desc: 'Daftar PPM dan PPTM',
        color: 'red',
        deepColor: 'darken-4',
        akses: 'petugas.index',
        link: 'petugas',
        to: { name: 'petugas' },
      },
      {
        icon: 'mdi-account-cog',
        title: 'Peserta Non Dapodik',
        desc: 'Daftar Peserta non dapodik',
        color: 'purple',
        deepColor: 'darken-5',
        akses: 'lpd-peserta-non-ptk.index',
        link: 'peserta',
        to: { name: 'peserta' },
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
