import gql from 'graphql-tag';

const ATTR_PESERTA = `
        paud_kelas_peserta_id
        k_konfirmasi_paud
        m_konfirmasi_paud {
          singkat
          keterangan
        }
        paud_kelas {
          nama
          k_verval_paud
          url_jadwal
          m_kecamatan {
            keterangan
          }
          m_kelurahan {
            keterangan
          }
          paud_diklat {
            nama
            paud_periode {
              nama
              tgl_diklat_mulai
              tgl_diklat_selesai
            }
            m_kota {
              keterangan
            }
            m_propinsi {
              keterangan
            }
            instansi {
              nama
            }
          }
        }
        `;

const SETUJU = gql`
  mutation ptkKelasPesertaBersedia($kelasPesertaId: Int!) {
    ptkKelasPesertaBersedia(kelasPesertaId: $kelasPesertaId) {
      ${ATTR_PESERTA}
    }
  }
`;

const TOLAK = gql`
  mutation ptkKelasPesertaTolak($kelasPesertaId: Int!) {
    ptkKelasPesertaTolak(kelasPesertaId: $kelasPesertaId) {
      ${ATTR_PESERTA}
    }
  }
`;

const RESET = gql`
  mutation ptkKelasPesertaBatal($kelasPesertaId: Int!) {
    ptkKelasPesertaBatal(kelasPesertaId: $kelasPesertaId) {
      ${ATTR_PESERTA}
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
        paud_kelas_id
        angkatan
        lms_url
        is_selesai
        paud_diklat {
          paud_periode_id
          paud_periode {
            tgl_diklat_mulai
            tgl_diklat_selesai
          }
        }
      }
    }
  }
`;

const GET_HASIL = gql`
  mutation GetHasil($kelasId: Int!) {
    ptkValidateSurvey(kelasId: $kelasId) {
      urlSurvey
      kelasPeserta {
        nilai
        predikat
        medali
        angkatan
        alasan
        tahun
        ptk_id
        ptk {
          nama
          instansi
        }
        is_lulus
        is_survey
      }
    }
  }
`;

const GET_SERTIFIKAT = gql`
  mutation GetSertifikat($kelasId: Int!) {
    ptkValidateSertifikat(kelasId: $kelasId) {
      url_download
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
      data { ${ATTR_PESERTA} } 
      }
  }
`;

export default { SETUJU, TOLAK, RESET, KELAS, DETAIL_KONFIRMASI, LIST_KONFIRMASI, GET_HASIL, GET_SERTIFIKAT };
