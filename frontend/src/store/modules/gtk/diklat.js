import gql from 'graphql-tag';
import graphqlClient from '@plugins/graphql';

export const actions = {
  async fetch() {
    const resp = await graphqlClient.query({
      query: gql`
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
      `,
    });

    const { data } = resp;
    return Promise.resolve(data ?? null);
  },

  // eslint-disable-next-line no-empty-pattern
  async getDetail({}, payload) {
    const resp = await graphqlClient.query({
      query: gql`
        query fetchKelas {
          ptkFetchKelasPeserta(id: ${payload.id}) {
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
      `,
    });

    const { data } = resp;
    return Promise.resolve(data ?? null);
  },
};
