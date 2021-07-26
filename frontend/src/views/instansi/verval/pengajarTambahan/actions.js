export default [
  {
    icon: 'mdi-account-lock',
    title: 'Kunci Berkas',
    event: 'onKunci',
    akses: 'petugas-verval-kunci.update',
  },
  {
    icon: 'mdi-account-key',
    title: 'Batal Kunci Berkas',
    event: 'onBatalKunci',
    akses: 'petugas-verval-kunci.delete',
  },
  {
    icon: 'mdi-close',
    title: 'Batal Verval',
    event: 'onBatalVerval',
    akses: 'petugas-verval-batal.update',
  },
];
