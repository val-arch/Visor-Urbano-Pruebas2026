
<!doctype html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <title>Comercial </title>
    <!-- Compiled and minified CSS -->
    <style type="text/css" media="all">

      .w3-table{
        padding-right:5px!important;
      }
     .w3-table,
     .w3-table-all{
      border-collapse:collapse;
      border-spacing:-1px;
      width:100%;
      display:table;

      }
      .w3-table-all{
        border:1px solid #ccc;
        border-right:1px solid #ddd
        }
      .w3-bordered tr,
      .w3-table-all tr{
        border-bottom:1px solid #ddd;
        border-left:1px solid #ddd;
        border-right:1px solid #ddd
      }
      .w3-striped tbody tr:nth-child(even){
       background-color:#f1f1f1
      }
      .w3-table-all tr:nth-child(odd){
      background-color:#fff
      }.w3-table-all tr:nth-child(even){
      background-color:#f1f1f1
      }
     .w3-hoverable tbody tr:hover,.w3-ul.w3-hoverable li:hover{
      background-color:#ccc
      }
      .w3-centered tr th,.w3-centered tr td{
       text-align:center
      }
      .w3-table td,.w3-table th,.w3-table-all td,.w3-table-all th{
       padding:4px 4px;display:table-cell;text-align:left;vertical-align:top
      }
     .w3-table th:first-child,.w3-table td:first-child,
     .w3-table-all th:first-child,
     .w3-table-all td:first-child{
       padding-left:16px
      }
     .w3-btn,
     .w3-button{
      border:none;
      display:inline-block;
      padding:8px 16px;
      vertical-align:middle;
      overflow:hidden;
      text-decoration:none;
      color:inherit;
      background-color:inherit;
      text-align:center;
      cursor:pointer;
      white-space:nowrap}

      @page {
                margin: 0 0;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 0;
                margin-left: 0;
                margin-right: 0;
                margin-bottom: 0;
            }

            /** Define the header rules **/


      .badg{
          padding-bottom: 10px;
      }
      .w3-badge,
      .w3-tag{
        background-color:#003E76;
        color:#fff;
        display:inline-block;
        padding-left:5px;
        padding-right:5px;
        width:12px;
        text-align:center
      }
      .w3-badge{
        border-radius:60%
      }
      .footable {
      width: 100%;
      border: solid #ccc 1px;
      border-collapse: separate;
      border-spacing: 0;
      }
      td > p {
        margin-top:0px;
        margin-bottom: 0px;
      }
      p > u {
        color:#3EA5FD;
        font-weight: bold;
      }
      td > div > div {
        color:#003E76;
      }
      td> div > div > div{
        border-bottom: .5px;
        border-style: solid;
        border-bottom-color: #cccccc;
        border-left: 0;
        border-top: 0;
        border-right: 0;
        color:red;
      }
      td> div > div> p {
        word-break: break-all;
        color:#333447;
        font-weight: normal !important;
      }
      td > div > div > div {
        color:#3EA5FD;

        font-style:italic;
      }
      .p-title {
        color:#3EA5FD;
        font-weight: bold;
      }
      .color-v{
        color:#3EA5FD;
      }
      .color-v2{
        color:#3EA5FD;
      }
      hr {
    display: block;
    height: 1px;
    border: 0;
    border-top: 0.3px solid #ccc;

    padding: 0;
}
      .badge {
          display: inline-block;
          padding: .5em .7em;
          border-radius: 10rem;
          font-size: 75%;
          font-weight: 700;
          line-height: 1;
          text-align: center;
          white-space: nowrap;
          vertical-align: baseline;
          background-color:#003E76;
          color:#f1f1f1f1;
          margin-right:20px;
      }
      .badge-pill {

      }
      .footable > tbody > tr > td, .footable > thead > tr > th {
        border-left: 1px solid #ccc;
        border-top: 1px solid #ccc;
        padding: 2px;
        text-align: left;
      }

      .footable > thead > tr > th, .footable > thead > tr > td {
        background-color: #dce9f9;
      }
      .strong {
          font-weight: bold;
      }
      .invoice-box {
      margin: auto;
      padding: 0px;
      color: #555;
      }

      html,
      body {
      height: 100%;
      width: 100%;
      margin: 0;
      padding: 0;
      left: 0;
      top: 0;
      font-size: 100%;
      }

      /* ROOT FONT STYLES */

      * {
      font-family: 'Red Hat Display', Bold !important;
      color: #333447;
      line-height: 1.2;
      }

      /* TYPOGRAPHY */

      h1 {
      font-size: 2.5rem;
      }

      h2 {
      font-size: 2rem;
      }

      h3 {
      font-size: 1.375rem;
      }

      h4 {
      font-size: 1.125rem;
      }

      h5 {
      font-size: 1rem;
      }

      h6 {
      font-size: 0.875rem;
      }

      p {
      font-size: 1.125rem;
      font-weight: 200;
      line-height: 1.8;
      }

      .font-light {
      font-weight: 300;
      }

      .font-regular {
      font-weight: 400;
      }

      .font-heavy {
      font-weight: 700;
      }

      /* POSITIONING */

      .left {
      text-align: left;
      }

      .right {
      text-align: right;
      }

      .center {
      text-align: center;
      margin-left: auto;
      margin-right: auto;
      }

      .justify {
      text-align: justify;
      }

      /* ==== GRID SYSTEM ==== */

      .container {
      width: 90%;
      margin-left: auto;
      margin-right: auto;
      }

      .row {
      position: relative;
      width: 100%;
      }

      .row [class^="col"] {
      float: left;
      margin: 0.5rem 2%;
      min-height: 0.125rem;
      }

      .col-1,
      .col-2,
      .col-3,
      .col-4,
      .col-5,
      .col-6,
      .col-7,
      .col-8,
      .col-9,
      .col-10,
      .col-11,
      .col-12 {
      width: 96%;
      }

      .col-1-sm {
      width: 4.33%;
      }

      .col-2-sm {
      width: 12.66%;
      }

      .col-3-sm {
      width: 21%;
      }

      .col-4-sm {
      width: 29.33%;
      }

      .col-5-sm {
      width: 37.66%;
      }

      .col-6-sm {
      width: 46%;
      }

      .col-7-sm {
      width: 54.33%;
      }

      .col-8-sm {
      width: 62.66%;
      }

      .col-9-sm {
      width: 71%;
      }

      .col-10-sm {
      width: 79.33%;
      }

      .col-11-sm {
      width: 87.66%;
      }

      .col-12-sm {
      width: 96%;
      }

      .row::after {
      content: "";
      display: table;
      clear: both;
      }

      .hidden-sm {
      display: none;
      }

      @media only screen and (min-width: 33.75em) {  /* 540px */
      .container {
      width: 80%;
      }
      }

      @media only screen and (min-width: 45em) {  /* 720px */
      .col-1 {
      width: 4.33%;
      }

      .col-2 {
      width: 12.66%;
      }

      .col-3 {
      width: 21%;
      }

      .col-4 {
      width: 29.33%;
      }

      .col-5 {
      width: 37.66%;
      }

      .col-6 {
      width: 46%;
      }

      .col-7 {
      width: 54.33%;
      }

      .col-8 {
      width: 62.66%;
      }

      .col-9 {
      width: 71%;
      }

      .col-10 {
      width: 79.33%;
      }

      .col-11 {
      width: 87.66%;
      }

      .col-12 {
      width: 96%;
      }

      .hidden-sm {
      display: block;
      }
      }



      .p-parrafo{
      text-align: center;
      font-size: 12px;
      }
      .p-parrafo-fotter{
      text-align: center;
      font-size: 16px;
      font-weight: bold;
      }

      .p-parrafo-full{
        font-size: 15px;
      }
      .p-pie-pagina{
        font-size: 10px;
      }
      .border-table > tbody > tr > td{
      /*
        //border-bottom: 1px solid black;
        //border-bottom-color:#e0e0e0;
        //padding: 2px;
        */
      }


    </style>

<style>

@page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 2cm;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 1.5cm;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 0cm;

                /** Extra personal styles **/

                color: white;
                text-align: center;
                line-height: 1.5cm;
            }

            /** Define the footer rules **/
            footer {
                position: fixed;
                bottom: 0cm;
                left: 0cm;
                right: 0cm;
                height: 0cm;

                /** Extra personal styles **/

                color: white;
                text-align: center;
                line-height: 1.5cm;
            }
            #watermark {
                position: fixed;

                /**
                    Set a position in the page for your image
                    This should center it vertically
                **/
                bottom:   2cm;
                left:     5cm;

                /** Change image dimensions**/
                 width:    12cm;
                height:   9cm;

                /** Your watermark should be behind every content**/
                z-index:  -1000;
                opacity: 0.1;
    filter: alpha(opacity=10); /* For IE8 and earlier */
            }

            footer {
              position: fixed;
                bottom: 0cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;
                /** Extra personal styles **/
                background-color: #003E76;
                color: white !important;
                text-align: center;
                line-height: 35px;
                font-size:12px;text-align: justify;
            }

</style>



<link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display&display=swap" rel="stylesheet">
</head>

<body>

<div id="watermark">
<img src="https://visorurbano.jalisco.gob.mx/assets/images/logo.png" height="100%" width="100%" >
        </div>
        <footer>
          <div style="margin:20px;">
            La fuente de consulta de la información aquí mostrada ha sido obtenida de la base de datos publicada por el INEGI.
          <br>
          </div>
        </footer>
        <!-- Wrap the content of your PDF inside a main tag -->
        <main>

                  <div class="invoice-box" >
                    <div class="row" style="margin-bottom:0px!important;">
                      <div class="col-4">
                         <img src="https://visorurbano.jalisco.gob.mx/assets/images/logo.png" style="width:100%; max-width:178px; margin-bottom:0px!important;">
                      </div>
                      <div class="col-4"></div>
                      <div class="col-4">
                         <img src={{$logo}} style="width:250px; max-width:250px; margin-top:-10px!important;">
                      </div>
                    </div>

                    <div class="row" style="margin-top:-7px !important;margin-bottom:0px!important;">
                      <div class="col-12"  style="margin-top:-5px !important;margin-bottom:0px!important;">
                        <h3 align="center" style="margin-top:1px;margin-bottom:0px; font-weight: normal !important;" class="">Diagnóstico Comercial</h3>
                      </div>
                    </div>


                    <div class="row" >
                        <div class="col-12" >
                        <div align="justify" style="margin-top:0px;margin-bottom:0px; font-size:15px;" class="" >Agradeciendo su interés por establecer un negocio de <div style="font-weight: bold !important;">{{$comercios}} </div> en este
Estado de Jalisco, por medio del presente conozca los establecimientos cercanos
cuya actividad comercial es la misma o similar a la pretendida por usted.
</div>
                        </div>
                    </div>

                    <div class="row" style="margin-top: -10px;">
                    <div class="col-6" >
                      <table class="styled-table p-parrafo-full border-table" style="padding-top:50px"  >
                        <tr>
                        <td>
                        <p class="p-title">Información del análisis:</p>
                        <div style="margin-left:15px;">
                        <div>
                        <div style="font-weight: bold !important;">Actividad Comercial:</div>
                        <p style="margin-top:-10px !important;margin-bottom:-12px !important;font-size:14px !important;font-weight: normal !important;">{{$comercios}}</p>
                        </div>
                        <hr/>
                        <div style="margin-top:-10px" >
                        <div style="font-weight: bold !important;">Área del diagnóstico:</div>
                        <p style="margin-top:-10px !important;margin-bottom:-12px !important;font-size:14px !important;font-weight: normal !important;">{{$area}}</p>

                        </div>
                        <hr/>
                        <div style="margin-top:-10px">
                        <div style="font-weight: bold !important;">Municipio (s) dentro del perímetro:</div>
                        <p style="margin-top:-10px !important;margin-bottom:-12px !important;font-size:14px !important;font-weight: normal !important;">{{$municipio}}</p>
                        </div>
                        <hr/>
                        <div style="margin-top:-10px">
                        <div style="font-weight: bold !important;">Negocios encontrados</div>
                        <p style="margin-top:-10px !important;margin-bottom:-12px !important;font-size:14px !important;font-weight: normal !important;">{{count($data)}}</p>
                        </div>
                        </div>
                        </td>
                        </tr>
                        </table>
                    </div>

                    <div  class="col-6" style="border:1px; margin-top:10px;">
                      <img align="center" src="{{$image}}" style="width:340px;height:340px;margin-top:50px;" alt="">
                    </div>
                    </div>
                    <hr/>
                    <div class="row">
                      <div class="col-12">
                      <div style="font-weight: bold !important;" class="color-v2">Tomando en cuenta los datos antes señalados, encontramos los siguientes negocios</div>
                      </div>
                    </div>
                    <div class="row" >
            <div class="col-12">
            <table class="w3-table w3-striped w3-border" style="margin-top:-14px; " >
                    <tr style="">
                      <th  style="font-weight: normal !important;" ><p style="margin-bottom:5px;text-align: center;  color:#003E76;font-style:italic;">#</p></th>
                      <th style="font-weight: normal !important;" ><p style="margin-bottom:5px;text-align: center;  color:#003E76;font-style:italic;">Nombre del negocio</p></th>
                      <th style="font-weight: normal !important;" ><p style="margin-bottom:5px;text-align: center;  color:#003E76;font-style:italic;">Ubicado en</p></th>
                    </tr>
                    <?php

               $cont=0; foreach($data as $info):?>
               <?php

               $propiedades= $info["properties"];
                      $nombre_comercial = $propiedades["nombre_comercial"];
                      $calle = "{$propiedades['tipo_vialidad']} {$propiedades['nombre_vialidad']} #{$propiedades['numero_exterior']}"; ?>
                    <tr>
                        <td style="font-size:12px !important; font-weight: normal !important;"><?=++$cont?></td>
                        <td style="font-size:12px !important; font-weight: normal !important;"><?=$nombre_comercial?></td>
                        <td style="font-size:12px !important; font-weight: normal !important;"><?=$calle?></td>
                    </tr>
                    <?php
                    if($cont==200){
                      break;
                      }
                  endforeach;?>



            </div>
        </div>
        <hr/>


         <div>

        </main>


    <script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            if ($PAGE_COUNT > 1) {
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $size = 12;
                $pageText =  $PAGE_NUM . " / " . $PAGE_COUNT;
                $y = 15;
                $x = 520;
                $pdf->text($x, $y, $pageText, $font, $size);
            }
        ');
    }
</script>


</body>

</html>
