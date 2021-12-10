export default {
  daring: [
    { icon: 'mdi-account-clock', title: 'Kelola Diklat', event: 'onDetailDiklat', akses: true },
    { icon: 'mdi-pencil', title: 'Ubah', event: 'onEditDiklat', akses: 'lpd-diklat.update' },
    { icon: 'mdi-delete', title: 'Hapus', event: 'onDeleteDiklat', akses: 'lpd-diklat.delete' },
  ],
  luring: [
    { icon: 'mdi-account-clock', title: 'Kelola Diklat', event: 'onDetailDiklat', akses: true },
    { icon: 'mdi-pencil', title: 'Ubah', event: 'onEditDiklat', akses: 'lpd-luring-diklat.update' },
    { icon: 'mdi-delete', title: 'Hapus', event: 'onDeleteDiklat', akses: 'lpd-luring-diklat.delete' },
  ],
};
