export default [
  { icon: 'mdi-lock-reset', title: 'Reset Password', event: 'onReset', akses: 'akun-admin-gtk.reset' },
  { icon: 'mdi-pencil', title: 'Edit Akun', event: 'onEdit', akses: 'akun-admin-gtk.update' },
  { icon: 'mdi-close', title: 'Non-aktifkan Akun', event: 'onNonAktif', akses: 'akun-admin-gtk.non-aktif' },
  { icon: 'mdi-check', title: 'Aktifkan Akun', event: 'onAktif', akses: 'akun-admin-gtk.aktif' },
  { icon: 'mdi-delete', title: 'Hapus', event: 'onDelete', akses: 'akun-admin-gtk.delete' },
];
