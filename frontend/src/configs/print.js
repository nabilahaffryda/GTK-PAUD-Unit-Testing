export default `html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td{margin:0;padding:0;border:0;outline:0;font-weight:inherit;font-style:inherit;font-size:100%;font-family:inherit;}
    :focus{outline:0;}
    body{line-height:1;color:black;background:white;}
    ul{list-style:none;}
    table{border-collapse:separate;border-spacing:0;}
    caption, th, td{text-align:left;font-weight:normal;}
    blockquote:before, blockquote:after, q:before, q:after{content:"";}
    blockquote, q{quotes:"" "";}/*Erik Meyer meyerweb.com/eric/thoughts/2007/05/01/reset-reloaded/*/
    /* common */
    body {
        font-family:Arial, Helvetica, sans-serif;
        font-size:8pt;
        width:17.1cm;
        padding-left:1px;
        position:relative;
    }
    strong, b {
        font-weight:bold;
    }
    em, i, blockquote {
        font-style:italic;
        font-family:Georgia, "Times New Roman", Times, serif;
    }
    .kepada, p{
        font-size:9pt;
        padding:10px;
        line-height:normal;
    }
    .kepada.token h2{
        font-size:12pt;
    }
    .clr {
        clear:both;
    }
    .cf:before, .cf:after { content: ""; display: block; }
    .cf:after { clear: both; }
    .sml {
        font-size:7pt;
    }
    sup {
        font-size:6pt;
        line-height:1;
    }
    .full{width:100%}
    .vm{vertical-align:middle}
    .vt{vertical-align:top}
    .ac{text-align:center}
    .ar{text-align:right}
    .huge{font-size:18pt;font-weight:bold}
    .fl{float:left}
    .fr{float:right}
    /* header 1st */
    .logo-psb {
        text-align:right;
        font-size:8pt;
    }
    .logo-psb img {
        margin-bottom:3px;
    }
    .head-title h1 {
        font-size:9pt;
        font-weight:bold;
    }
    .head-title h2 {
        font-size:8pt;
    }
    .head-title h3 {
        font-size:6pt;
    }
    .form-title h1 {
        font-size:11pt;
        font-weight:bold;
    }
    .form-title h2 {
        font-size:10pt;
    }
    .form-title h3 {
        font-size:9pt;
    }
    /* header 2nd */
    .head-desc {
        color:#444;
    }
    /* table */
    .tbl-head {
        margin-bottom:10px;
        width:100%;
    }
    .tbl {
        border-collapse:collapse;
        width:100%;
    }
    .tbl th, .tbl td {
        border:#626262 solid 1px;
        padding:7px 7px;
    }
    .tbl th {
        background-color:#ddd;
        white-space:nowrap;
        color:#444;
    }
    .tbl th.v, .tbl th.vc {
        border-bottom:#ccc solid 1px;
    }
    .tbl td.v, .tbl td.vc {
        border-top:#ccc solid 1px;
    }
    .tbl th.vc, .tbl td.vc {
        text-align:center;
    }
    .tbl th.h {
        border-right:#ccc solid 1px;
        text-align:right;
    }
    .tbl td.h {
        border-left:#ccc solid 1px;
    }
    .tbl th.h, .tbl td.h {
        vertical-align:top;
    }
    .nowrap {
        white-space:nowrap;
    }
    .tbl .big {
        padding:5px 10px;
        font-size:10pt;
        font-weight:bold;
    }
    .tbl .no {
        width:12px;
        padding:6px 10px;
        font-size:10pt;
        font-weight:bold;
        text-align:center !important;
        vertical-align:middle !important;
    }
    .tbl .title {
        font-size:9pt;
        font-weight:bold;
    }
    .tbl .ket-nilai {
        text-align:right;
    }
    .tbl .ket-nilai span {
        white-space:nowrap;
    }
    .tbl .ket-nilai,
    .tbl .ket-head,
    .tbl .ket-tbl {
        font-size:7pt;
        color:#444;
    }
    .tbl .ket-head {
        float:right;
        font-weight:normal;
    }
    .tbl+.tbl tr:first-child >* {
        border-top:none;
    }
    /* keterangan */
    ol.normal, ul.normal{
        margin-left:25px;
        font-size:9pt;
        line-height:normal;
    }
    ul.normal{
        list-style:disc;
    }
    ol.normal{
        margin-left:35px;
    }
    .normal li{
        padding:5px 0;
    }
    td.ket-tbl ul{
        list-style:disc;
        padding-left:15px;
    }
    td.ket-tbl ul li{
        padding:1px 0;
        line-height:normal;
    }
    td.ket-tbl.token p{
        padding:0;
    }
    /* alamat surat */
    .kepada{
        font-size:9pt;
        padding:10px;
        line-height:normal;
    }
    .kepada.token h2{
        font-size:12pt;
    }
    .kepada h1 {
        font-size:11pt;
    }
    .kepada h2, .kepada h3 {
        font-size:10pt;
    }
    .kepada h1, .kepada h2 {
        font-weight:bold;
    }
    /* condensed table */
    .condensed p {
        padding:0;
    }
    .condensed {
        margin:10px 0;
    }
    /* legend */
    td.legend {
        padding:7px;
        color:#444;
    }
    .legend div {
        font-size:6pt;
        padding-left:10px;
        position:relative;
        font-family:Arial, Helvetica, sans-serif;
        padding-bottom:3px;
    }
    .legend div:last-child {
        padding-bottom:0;
    }
    .legend span {
        width:8px;
        position:absolute;
        left:0;
        text-align:right;
    }
    /* signature */
    .ttd {width:166px;}
    /* iklan */
    .iklan {
        color:#444;
        line-height:normal;
    }
    /* helper */
    .cut{
        border-bottom:#666 dotted 1px;
        position:absolute;
        width:0.5cm;
        height:0.1cm;
    }
    .cut.c1{
        top:8cm;
        left:0;
    }
    .cut.c1r{
        top:8cm;
        right:0;
    }
    .cut.c2{
        top:17.9cm;
        left:0;
    }
    .cut.c2r{
        top:17.9cm;
        right:0;
    }
    .cutarea1{
        height:8.3cm;
    }
    .cutarea2{
        height:10.5cm;
    }
    .cutmax, .cutmax2{
        display:none;
    }
    @media screen {
        .cutmax, .cutmax2{
            top:24.3cm;
            width:100%;
            border-bottom:#900 dotted 2px;
            display:block;
        }
        .cutmax2{
            top:48.6cm;
        }
    }
    /* token */
    .token-block,
    .token-mask{
        width:200px;
        height:40px;
        text-align:center;
        overflow:hidden;
        position:relative;
    }
    .tokenf {
        font-family:"Times New Roman", Times, serif;
        font-size:18pt;
        color:#222;
        padding:5px;
        position:absolute;
        top:0;
        left:0;
        width:200px;
        z-index:1;
    }
    .break{
        page-break-after:always;
    }
    /* box isian */
    .box-input{
        padding:3px 5px !important;
    }
    .box-input .cbox,
    .box-input b,
    .box-input >span{
        display:inline-block;
        height:20px;
        vertical-align:middle;
        line-height:20px;
    }
    .box-input .cbox,
    .box-input b{
        width:20px;
        border:#666 solid 1px;
    }
    .box-input b + b{
        border-left:none;
    }
    .box-input .vsep{
        padding-top:3px;
        display:block;
        clear:both;
    }
    .box-input .cbox{
        border-radius:4px;
        height:12px;
        width:12px;
        margin:2px 1px 2px 2px;
    }
    .print-header{
        display: block !important
    }
    .boxfoto{overflow:hidden; height:5cm; background:#ddd; text-align:center; border:#CCC solid 1px; color:#666}
    .boxfoto >em{display:inline-block;margin-top:1cm}
    /* overide to black */
    * { color:black !important }`;
