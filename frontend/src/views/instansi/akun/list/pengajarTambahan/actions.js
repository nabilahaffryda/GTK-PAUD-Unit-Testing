export default [
  { icon: 'mdi-account', title: 'Data Profil', event: 'onDetail' },
  { icon: 'mdi-lock-reset', title: 'Reset Password', event: 'onReset', akses: 'akun-pengajar-tambahan.reset' },
  { icon: 'mdi-pencil', title: 'Edit Akun', event: 'onEdit', akses: 'akun-pengajar-tambahan.update' },
  { icon: 'mdi-close', title: 'Non-aktifkan Akun', event: 'onNonAktif', akses: 'akun-pengajar-tambahan.non-aktif' },
  { icon: 'mdi-check', title: 'Aktifkan Akun', event: 'onAktif', akses: 'akun-pengajar-tambahan.aktif' },
  { icon: 'mdi-delete', title: 'Hapus', event: 'onDelete', akses: 'akun-pengajar-tambahan.delete' },
];
