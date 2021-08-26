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

export default { SETUJU, TOLAK, RESET };
