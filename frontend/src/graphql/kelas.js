import gql from 'graphql-tag';

const SETUJU = gql`
  mutation ptkKelasPesertaBersedia($kelasPesertaId: Int!) {
    ptkKelasPesertaBersedia(kelasPesertaId: $kelasPesertaId) {
      paud_kelas_peserta_id
      paud_kelas_id
    }
  }
`;

const TOLAK = gql`
  mutation ptkKelasPesertaTolak($kelasPesertaId: Int!) {
    ptkKelasPesertaTolak(kelasPesertaId: $kelasPesertaId) {
      paud_kelas_peserta_id
      paud_kelas_id
    }
  }
`;

const RESET = gql`
  mutation ptkKelasPesertaBatal($kelasPesertaId: Int!) {
    ptkKelasPesertaBatal(kelasPesertaId: $kelasPesertaId) {
      paud_kelas_peserta_id
      paud_kelas_id
    }
  }
`;

const KELAS = gql`
  query getKelas($page: Int!, $keyword: String) {
    ptkListKelas(first: 10, page: $page, keyword: $keyword) {
      paginatorInfo {
        currentPage
        lastPage
        total
      }
      data {
        nama
        paud_diklat {
          paud_periode {
            tgl_diklat_mulai
            tgl_diklat_selesai
          }
        }
      }
    }
  }
`;

const DETAIL_KONFIRMASI = gql`
  query fetchKelas($id: Int!) {
    ptkFetchKelasPeserta(id: $id) {
      paud_kelas_peserta_id
      k_konfirmasi_paud
      m_konfirmasi_paud {
        singkat
        keterangan
      }
      paud_kelas {
        nama
        m_kecamatan {
          keterangan
        }
        m_kelurahan {
          keterangan
        }
        paud_diklat {
          nama
          m_kota {
            keterangan
          }
          m_propinsi {
            keterangan
          }
          paud_periode {
            nama
            tgl_diklat_mulai
            tgl_diklat_selesai
          }
        }
      }
    }
  }
`;

const LIST_KONFIRMASI = gql`
  query getKelas {
    ptkListKelasPeserta(first: 10) {
      paginatorInfo {
        currentPage
        total
      }
      data {
        paud_kelas_peserta_id
        k_konfirmasi_paud
        m_konfirmasi_paud {
          singkat
          keterangan
        }
        paud_kelas {
          nama
          k_verval_paud
          paud_diklat {
            nama
            paud_periode {
              nama
              tgl_diklat_mulai
              tgl_diklat_selesai
            }
          }
        }
      }
    }
  }
`;

export default { SETUJU, TOLAK, RESET, KELAS, DETAIL_KONFIRMASI, LIST_KONFIRMASI };
