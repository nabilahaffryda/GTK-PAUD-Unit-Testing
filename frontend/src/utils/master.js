export const M_KELAMIN = [
  { value: 'L', text: 'Laki-laki' },
  { value: 'P', text: 'Perempuan' },
];

export const M_KAWIN = [
  { value: '1', text: 'Kawin' },
  { value: '0', text: 'Belum Kawin' },
];

export const M_KEWARGANEGARAAN = [
  { value: 1, text: 'WNI' },
  { value: 2, text: 'WNA' },
];

export const M_AKTIF = [
  { value: '1', text: 'Aktif' },
  { value: '0', text: 'Tidak Aktif' },
];

export const M_AKTIVASI = [
  { value: '0', text: 'Belum Aktivasi' },
  { value: '1', text: 'Sudah Aktivasi' },
];

export const M_PEGAWAI_PPG = [
  { value: 1, text: 'PNS' },
  { value: 2, text: 'Non PNS' },
];

export const M_DOKUMEN_WARGA = [
  {
    k_dokumen_warga: '1',
    singkat: 'KTP',
    keterangan: 'Kartu Tanda Penduduk',
  },
  {
    k_dokumen_warga: '2',
    singkat: 'KIA',
    keterangan: 'Kartu Identitas Anak',
  },
  {
    k_dokumen_warga: '3',
    singkat: 'KK',
    keterangan: 'Kartu Keluarga',
  },
];

export const M_GOLONGAN_DARAH = [
  { value: 'A', text: 'A' },
  { value: 'B', text: 'B' },
  { value: 'AB', text: 'AB' },
  { value: 'O', text: 'O' },
  { value: 'A+', text: 'A+' },
  { value: 'A-', text: 'A-' },
  { value: 'B+', text: 'B+' },
  { value: 'B-', text: 'B-' },
  { value: 'AB+', text: 'AB+' },
  { value: 'AB-', text: 'AB-' },
  { value: 'O+', text: 'O+' },
  { value: 'O-', text: 'O-' },
  { value: 'Tidak Tahu', text: 'Tidak Tahu' },
];

export const M_WARGA_STAT = {
  jml_warga: { label: 'Warga', icon: 'mdi-account-multiple' },
  jml_kk: { label: 'Kepala Keluarga', icon: 'mdi-human-male-boy' },
  jml_laki: { label: 'Laki - laki', icon: 'mdi-gender-male' },
  jml_perempuan: { label: 'Perempuan', icon: 'mdi-gender-female' },
  jml_kawin: { label: 'Kawin', icon: 'mdi-ring' },
  jml_tidak_kawin: { label: 'Belum Kawin', icon: 'mdi-power-off' },
};

export const M_BULAN = {
  1: { value: 1, text: 'Januari', singkat: 'JAN' },
  2: { value: 2, text: 'Februari', singkat: 'FEB' },
  3: { value: 3, text: 'Maret', singkat: 'MAR' },
  4: { value: 4, text: 'April', singkat: 'APR' },
  5: { value: 5, text: 'Mei', singkat: 'MEI' },
  6: { value: 6, text: 'Juni', singkat: 'JUN' },
  7: { value: 7, text: 'Juli', singkat: 'JUL' },
  8: { value: 8, text: 'Agustus', singkat: 'AGS' },
  9: { value: 9, text: 'September', singkat: 'SEP' },
  10: { value: 10, text: 'Oktober', singkat: 'OKT' },
  11: { value: 11, text: 'November', singkat: 'NOV' },
  12: { value: 12, text: 'Desember', singkat: 'DES' },
};

export const MASA_KERJA = {
  1: 'Kurang dari 3 tahun',
  2: '3-5 tahun',
  3: 'Lebih dari 5 tahun',
};

export const STUDI_USIA = {
  1: 'Kurang dari 50 tahun',
  2: 'Lebih dari 50 tahun',
};
