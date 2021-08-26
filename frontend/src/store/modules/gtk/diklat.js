import gql from 'graphql-tag';
import graphqlClient from '@plugins/graphql';
import graphQuery from '../../../graphql/kelas';

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
      `,
    });

    const { data } = resp;
    return Promise.resolve(data ?? null);
  },

  // eslint-disable-next-line no-empty-pattern
  async actions({}, payload) {
    const resp = await graphqlClient.mutate({
      mutation: graphQuery[payload.name.toUpperCase()],
      variables: {
        kelasPesertaId: payload.id,
      },
    });

    const { data } = resp;
    return Promise.resolve(data ?? null);
  },
};
