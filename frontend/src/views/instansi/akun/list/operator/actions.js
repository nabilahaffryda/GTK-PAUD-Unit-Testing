export default [
  { icon: 'mdi-lock-reset', title: 'Reset Password', event: 'onReset', akses: 'akun-operator-lpd.reset' },
  { icon: 'mdi-pencil', title: 'Edit Akun', event: 'onEdit', akses: 'akun-operator-lpd.update' },
  { icon: 'mdi-close', title: 'Non-aktifkan Akun', event: 'onNonAktif', akses: 'akun-operator-lpd.non-aktif' },
  { icon: 'mdi-check', title: 'Aktifkan Akun', event: 'onAktif', akses: 'akun-operator-lpd.aktif' },
  { icon: 'mdi-delete', title: 'Hapus', event: 'onDelete', akses: 'akun-operator-lpd.delete' },
];
