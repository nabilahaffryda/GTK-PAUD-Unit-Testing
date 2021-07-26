export default [
  {
    icon: 'mdi-account-lock',
    title: 'Kunci Berkas',
    event: 'onKunci',
    akses: 'lpd-verval-kunci.update',
  },
  {
    icon: 'mdi-account-key',
    title: 'Batal Kunci Berkas',
    event: 'onBatalKunci',
    akses: 'lpd-verval-kunci.delete',
  },
  {
    icon: 'mdi-close',
    title: 'Batal Verval',
    event: 'onBatalVerval',
    akses: 'lpd-verval-batal.update',
  },
];
