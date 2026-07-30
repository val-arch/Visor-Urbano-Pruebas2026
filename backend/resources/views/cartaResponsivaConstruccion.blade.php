<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <title>Carta Responsiva</title>
    <!-- Compiled and minified CSS -->
    <style>
      .footable {
      width: 100%;

      border: solid #ccc 1px;
      border-collapse: separate;
      border-spacing: 0;
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
      .acuse-box {
      max-width: 800px;
      margin: auto;
      padding: 30px;
      font-size: 16px;
      line-height: 24px;
      font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
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
      text-align: justify;

      }

      /* ROOT FONT STYLES */

      * {
        font-family: 'Red Hat Display', sans-serif;
      color: #333447;
      line-height: 1;
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

      @media only screen and (min-width: 60em) { /* 960px */
      .container {
      width: 75%;
      max-width: 60rem;
      }
      }

      .p-parrafo{
      text-align: center;
      font-size: 12px;
      }
      .p-parrafo-fotter{
      text-align: center;
      font-size: 13px;
      font-weight: bold;
      }

      .p-parrafo-full{
        font-size: 14px;
        font-family: 'Red Hat Display';
        font: normal normal 500 14px/14px Red Hat Display;
      }
      .p-pie-pagina{
        font-size: 8px;
      }
      .border-table > tbody > tr > td{

        border-bottom: 1px solid black;
        padding: 2px;


      }
      hr {
    display: block;
    height: 1px;
    border: 0;
    border-top: 0.3px solid #ccc;

    padding: 0;
   }
    </style>
</head>
<body>
    <div class="acuse-box">
        <div class="row">
            <div class="col-3">
                <img src="https://visorurbano.jalisco.gob.mx/assets/images/logo.png" style="width:100%; max-width:178px;">
            </div>
            <div class="col-6"></div>
            <div class="col-3">
                <img src={{$logo}} style="width:100%; max-width:200px;">
            </div>
        </div>
        <?php
        error_log($data['result'][0]->id_municipio);
        ?>
        <div class="row">
            <div class="col-12" style="text-align:left">
                <?php
                if($data['result'][0]->id_municipio == 37){
                ?>
                    <div style="font-size:12px;font-weight: 700;">
                    Gobierno Municipal de {{$data['result'][0]->municipio}}, Morelos. <br>Presente.
                    </div>
                <?php
                }else{
                ?>
                    <div style="font-size:12px;font-weight: 700;">
                    H. Ayuntamiento de {{$data['result'][0]->municipio}}, Morelos. <br>Presente.
                    </div>
                <?php
                }
                ?>
            </div>

        </div><div class="row" style="margin-top: -10 !important">
           <div class="col-6"></div>
           <div class="col-6">
           <div style="font-size:12px;" >
           <div style="font-size:14px;font-weight: 700;">Asunto:  </div>
           <br>
           Carta Responsiva sobre documentos e información adjuntos en solicitud de  {{$data['result_tipo'][0]->tramite}} con número de folio
           {{$data['result'][0]->folio_interno}}  realizada en la plataforma de Visor Urbano Morelos.
           </div>
           </div>
        </div><div class="row" style="padding-top:20px">
            <div class="col-12" >
                    <div style="font-size:14px; line-height: 1.5;" >En el municipio de {{$data['result'][0]->municipio}}, Morelos; siendo las {{$hora_actual}} horas del día
                       <i style="font-weight: 700;">{{date('d/m/Y')}}</i>,
                   la(el) suscrita(to) C. {{$data['data2']['nombre_completo']}}, comparezco en mi calidad de solicitante del trámite identificado como {{$data['result_tipo'][0]->tramite}}
                   mismo que se llevará a cabo  <i style="font-weight: 700;"></i> en el predio ubicado en
                  <i style="font-weight: 700;"> {{$data['domicilio']['calle']}} #{{$data['domicilio']['exterior']}}{{$data['domicilio']['interior']}}, en la colonia {{$data['domicilio']['colonia']}}, en este Municipio de  {{$data['result'][0]->municipio}}, Morelos{{$data['domicilio']['cp']}}. </i>
                  </div>

                   <br>
                   <div style="font-size:14px; line-height: 1.5;" >
                    Dicho lo anterior, comparezco ante esta autoridad con la finalidad de acreditar mi identidad exhibiendo
                    al efecto una identificación oficial consistente en  __________________________ número ________________________,
                     expedido por _________________________________________.
                   </div>
                   <br>

                   <div style="font-size:14px;" >
                   Siendo mi voluntad realizar y obtener el trámite ya señalado en líneas anteriores, manifiesto <b style="font-size:14px;font-weight: 700;">BAJO PROTESTA DE DECIR VERDAD,
                     que los documentos e información proporcionada a la plataforma de Visor Urbano Cuernavaca es auténtica, real y veraz</b>, la cual
                    acredita plenamente mi identidad y la situación jurídica y técnica del predio donde se solicita el trámite.
                    En ese sentido, tengo conocimiento que en caso de proporcionar documentos o información falsa puedo ser acreedor de responsabilidad
                     penal y/o administrativa.
                   </div>
                   <br>

                   <div style="font-size:14px;">
                   Por último, solicito que todas las notificaciones, incluso las personales, se me realicen a través del medio electrónico denominado
                    Visor Urbano Jalisco al cual puedes acceder en la dirección de internet <a style="font-size:14px;font-weight: 700;" href="url">https://visorurbano.cuernavaca.gob.mx/</a>, y autorizo para que
                     mis datos de contacto proporcionados en el presente trámite puedan ser utilizados para fines estadísticos,
                   de indicadores y encuestas, ya sea telefónicas o a través de correo electrónico.
                   </div>

            </div>
        </div>



       <div class="row" style="font-size:14px;font-weight: 700;">
           <div class="col-12" style="text-align:center ">
           <div>
           Atentamente
           <br>
           <br>
           <br>

           _____________________________
           <br>
           Nombre y firma del solicitante
           </div>
           </div>
       </div>



</body>
</html>
