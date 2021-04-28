export default [
  { icon: 'mdi-lock-reset', title: 'Reset Password', event: 'onReset', akses: 'akun-pengajar-bimtek.reset' },
  { icon: 'mdi-pencil', title: 'Edit Akun', event: 'onEdit', akses: 'akun-pengajar-bimtek.update' },
  { icon: 'mdi-close', title: 'Non-aktifkan Akun', event: 'onNonAktif', akses: 'akun-pengajar-bimtek.non-aktif' },
  { icon: 'mdi-check', title: 'Aktifkan Akun', event: 'onAktif', akses: 'akun-pengajar-bimtek.aktif' },
  { icon: 'mdi-delete', title: 'Hapus', event: 'onDelete', akses: 'akun-pengajar-bimtek.delete' },
];
