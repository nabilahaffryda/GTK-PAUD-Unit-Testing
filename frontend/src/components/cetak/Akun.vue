<template>
  <div class="print-header" style="display: none">
    <table class="tbl-head">
      <tr>
        <td width="60" class="ac"><img :src="$imgUrl('logo-diknas.png')" /></td>
        <td class="head-titemle">
          <h3>KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN REPUBLIK INDONESIA</h3>
          <h2>DIRJEN GURU & TENAGA KEPENDIDIKAN</h2>
        </td>
      </tr>
    </table>

    <table width="100%" class="tbl">
      <tr>
        <td class="form-titemle">
          <h1>SURAT PEMBERITAHUAN AKSES LAYANAN</h1>
          <em class="sml fr" style="color: #aaa !important">ver.201702011336</em>
          <h2>PENGEMBANGAN KEPROFESIAN BERKELANJUTAN - DIRJEN GTK - KEMENDIKBUD RI</h2>
        </td>
      </tr>
    </table>

    <table class="condensed" width="100%">
      <tr class="vt">
        <td class="kepada token vm">
          <div class="head-titemle">
            Kepada yth,
            <h2>{{ akun.nama || '-' }}</h2>
            <h3 v-if="akun.instansi">di {{ akun.instansi || '-' }}<br /> </h3>
          </div>
        </td>
        <td width="70">
          <p>Tanggal</p>
          <p>Perihal</p>
          <p>Sifat</p>
        </td>
        <td width="210">
          <p>: {{ nowDate }}</p>
          <p>: Surat Akun Login PKB</p>
          <p>: SANGAT RAHASIA</p>
        </td>
      </tr>
    </table>

    <p>Dengan hormat,</p>
    <p>
      Pengembangan Keprofesian Berkelanjutan (PKB) merupakan Layanan Pembelajaran secara online bagi Guru dan Tenaga
      Kependidikan (GTK) di Indonesia. Layanan ini diselenggarakan oleh Direktorat Jendral GTK - Kementerian Pendidikan
      dan Kebudayaan Republik Indonesia, dalam rangka meningkatkan kualitas GTK di Indonesia.
    </p>

    <p>
      Melalui surat ini, kami memberitahukan bahwa Anda RESMI TERCATAT SEBAGAI {{ akun.peran || 'FASILITATOR' }} di
      dalam layanan PKB dengan akun sbb:
    </p>
    <table class="tbl" style="width: 50%; margin-left: 20%">
      <tr>
        <th width="80" class="h">EMAIL</th>
        <td class="h">
          <code>{{ akun.email || '-' }}</code>
        </td>
      </tr>
      <tr>
        <th class="h">PASSWORD</th>
        <td class="h">
          <code>{{ akun.password || '-' }}</code>
        </td>
      </tr>
    </table>

    <p>Gunakan informasi diatas untuk melakukan login pada alamat berikut: {{ programs['url'][program] }}</p>
    <p>Melalui Layanan PKB ini, Anda dapat melakukan:</p>
    <br />
    <p>1.Pemutakhiran data personal Anda</p>
    <p>2.Melihat Riwayat Pelatihan Anda</p>
    <p>3.Melihat hasil penilaian Uji Kompetensi Anda</p>
    <p>4.dan berbagai fasilitas yang diberikan Dirjen GTK untuk Anda</p>
    <br />
    <p>Untuk informasi dan panduan selengkapnya dapat diakses di {{ programs['url'][program] }}</p>
    <table width="100%">
      <tr>
        <td>&nbsp;</td>
        <td width="280" class="ket-tbl token">
          <p>
            &nbsp;
            <br />&nbsp;
            <br />
            Jakarta, {{ nowDate }}<br />
            Hormat kami,<br />
            &nbsp;<br />
            &nbsp;<br />
            &nbsp;<br />
            <strong>Admin Pusat PKB,</strong>,<br />
            <strong>DIRJEN GTK - KEMENDIKBUD RI</strong>
          </p>
        </td>
      </tr>
    </table>

    <br />
    <br />
    <br />
    <br />
    <p style="font-size: 9px; font-style: italic">
      * Dokumen ini dihasilkan secara otomatis dari sistem dan dinyatakan sebagai dokumen sah
    </p>
    <div class="cut cutmax"></div>
  </div>
</template>
<script>
import { dateToString } from '@utils/format';
import printCss from './print';
import { Printd } from 'printd';

export default {
  props: {
    akun: {
      type: Object,
      default: () => {},
    },
    program: {
      type: String,
      default: 'pkb',
    },
  },

  computed: {
    nowDate() {
      return this.$localDate(dateToString(new Date()));
    },

    programs() {
      return {
        url: {
          pkb: 'https//app.simpkb.id',
          ppg: 'https://pendaftaran.ppg.kemdikbud.go.id',
          gurupenggerak: 'https://app-gurupenggerak.simpkb.id',
        },
      };
    },
  },

  methods: {
    print() {
      const p = new Printd();
      p.print(this.$el, [printCss]);
    },
  },
};
</script>
