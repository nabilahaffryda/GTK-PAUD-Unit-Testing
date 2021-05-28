export default [
  { icon: 'mdi-pencil', title: 'Edit Institusi', event: 'onEdit', akses: 'lpd.update' },
  { icon: 'mdi-delete', title: 'Hapus', event: 'onDelete', akses: 'lpd.delete' },
  { icon: 'mdi-close', title: 'Non-aktifkan', event: 'onNonAktif', akses: 'lpd-profil.set-aktif' },
  { icon: 'mdi-check', title: 'Aktifkan', event: 'onAktif', akses: 'lpd-profil.set-aktif' },
];
