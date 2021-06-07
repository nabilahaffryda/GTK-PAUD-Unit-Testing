export default [
  {
    icon: 'mdi-account-lock',
    title: 'Kunci Berkas',
    event: 'onKunci',
    akses: 'verval-kunci.update',
  },
  {
    icon: 'mdi-account-key',
    title: 'Batal Kunci Berkas',
    event: 'onBatalKunci',
    akses: 'verval-kunci.delete',
  },
  {
    icon: 'mdi-close',
    title: 'Batal Verval',
    event: 'onBatalVerval',
    akses: 'verval-batal.update',
  },
];
