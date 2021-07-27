export default [
  { icon: 'mdi-lock-reset', title: 'Reset Password', event: 'onReset', akses: 'akun-pembimbing-praktik.reset' },
  { icon: 'mdi-pencil', title: 'Edit Akun', event: 'onEdit', akses: 'akun-pembimbing-praktik.update' },
  {
    icon: 'mdi-account-tie',
    title: 'Set Pembimbing Inti',
    event: 'setAkunInti',
    akses: 'akun-pembimbing-praktik.set-inti',
  },
  { icon: 'mdi-close', title: 'Non-aktifkan Akun', event: 'onNonAktif', akses: 'akun-pembimbing-praktik.non-aktif' },
  { icon: 'mdi-check', title: 'Aktifkan Akun', event: 'onAktif', akses: 'akun-pembimbing-praktik.aktif' },
  {
    icon: 'mdi-close',
    title: 'Reset Pembimbing Inti',
    event: 'onResetInti',
    akses: 'akun-pembimbing-praktik.reset-inti',
  },
  { icon: 'mdi-delete', title: 'Hapus', event: 'onDelete', akses: 'akun-pembimbing-praktik.delete' },
];
