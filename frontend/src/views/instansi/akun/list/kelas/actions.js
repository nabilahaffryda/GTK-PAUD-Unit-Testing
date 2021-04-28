export default [
  { icon: 'mdi-lock-reset', title: 'Reset Password', event: 'onReset', akses: 'akun-admin-kelas.reset' },
  { icon: 'mdi-pencil', title: 'Edit Akun', event: 'onEdit', akses: 'akun-admin-kelas.update' },
  { icon: 'mdi-close', title: 'Non-aktifkan Akun', event: 'onNonAktif', akses: 'akun-admin-kelas.non-aktif' },
  { icon: 'mdi-check', title: 'Aktifkan Akun', event: 'onAktif', akses: 'akun-admin-kelas.aktif' },
  { icon: 'mdi-delete', title: 'Hapus', event: 'onDelete', akses: 'akun-admin-kelas.delete' },
];
