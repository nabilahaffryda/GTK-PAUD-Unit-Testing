export default [
  { icon: 'mdi-lock-reset', title: 'Reset Password', event: 'onReset', akses: 'akun-pengajar.reset' },
  { icon: 'mdi-pencil', title: 'Edit Akun', event: 'onEdit', akses: 'akun-pengajar.update' },
  { icon: 'mdi-close', title: 'Non-aktifkan Akun', event: 'onNonAktif', akses: 'akun-pengajar.non-aktif' },
  { icon: 'mdi-check', title: 'Aktifkan Akun', event: 'onAktif', akses: 'akun-pengajar.aktif' },
  {
    icon: 'mdi-close',
    title: 'Batalkan Pengajar Inti',
    event: 'onResetInti',
    akses: 'akun-pengajar.reset-status',
  },
  {
    icon: 'mdi-close',
    title: 'Batalkan Lulus Bimtek',
    event: 'onResetBimtek',
    akses: 'akun-pengajar.reset-status',
  },
  { icon: 'mdi-delete', title: 'Hapus', event: 'onDelete', akses: 'akun-pengajar.delete' },
];
