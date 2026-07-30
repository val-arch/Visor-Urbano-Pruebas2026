
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <title>Cédula de Apertura Provisional </title>
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
      font-family: 'Red Hat Display', sans-serif;


      }

      .box{
        width:180px;
        height:40px;
        background-color:#003E76;
        color:white; text-align:center;
        border-radius: 5px;
        padding:3px !important;
        font-family: 'Red Hat Display', sans-serif;
        text-align:center;
      }
      .boxL{
        width:450px;
        height:40px;
        background-color:#003E76;
        color:white; text-align:center;
        border-radius: 5px;
        padding:3px !important;
        font-family: 'Red Hat Display', sans-serif;

      }
       .texto{
        margin-left: 60px;
        margin-top: 10px;
        font-family: 'Red Hat Display', sans-serif;
        line-height: 10px;
        line-break: anywhere;

       }
       .textoL{
        margin-left: 30px;
        margin-top: 10px;
        font-family: 'Red Hat Display', sans-serif;
        line-height: 10px;
        line-break: anywhere;

       }
      /* ROOT FONT STYLES */

      * {
      font-family:'Century Gothic','Lato', Helvetica, sans-serif;
      color: #333447;
      line-height: 1.5;
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
        font-family: 'Red Hat Display', sans-serif;
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
                <img src="{{$logo}}" style="width:100%; max-width:200px;">
            </div>
        </div>
        <div class="row" class="col-12">
           <div class="col-12" style="text-align:center;margin-top:-30px;">
                <h3 class="Titulo" ><strong>Cédula de Apertura Provisional </strong></h3>
            </div>

        </div>


        <div class="row" class="col-12" style="margin-top:-30px;">
            <div class="col-9">
            </div>
            <div class="col-3">
            Folio: <b>{{$result->folio}} </b>
            </div>
        </div>
        <div class="row" class="col-12">
            <div class="col-4" >
                <div  class=" box" > Días Permitidos:</div>
                <div class= "texto"> 30 días</div>
            </div>

            <div class="col-4">
               <div  class=" box"> Desde el día:</div>
                <div class= "texto">  {{date('d/m/Y',strtotime($data['fecha_inicio']))}}</div>
                {{-- <div class= "texto">  {{$data['fecha_inicio']->toDateString()}}</div> --}}
              </div>

              <div class="col-4">
                <div  class=" box"> Hasta:</div>
                <div class= "texto">  {{date('d/m/Y',strtotime($data['fecha_limite']))}}</div>
                {{-- <div class= "texto">  {{$data['fecha_limite']->toDateString()}}</div> --}}
              </div>
        </div>

        <div class="row" class="col-12" >
            <div class="col-4">
               <div  class=" box"> Código Actividad:</div>
                <div class= "texto">{{$result->codigo_scian}}</div>
            </div>

              <div class="col-8">
                <div  class=" boxL" > Actividad comercial solicitada:</div>
                <!--div class= "textoL">  Comercio al por menor de paletas de hielo y helados</div-->
                <div class= "textoL">  {{$result->nombre_scian}}</div>

              </div>
        </div>

        <div class="row">
            <div class="col-5">
                <div  class=" box"> Dueña(o) del negocio:</div>
                <div>{{$dueno}}
                <br>
                <!-- <b>CURP:</b> {{$curp}} -->
                </div>

                <br>
                <div  class=" box"> Ubicación</div>
                {{$ubicacion}} <br> Municipio {{$result->municipio}}, Jalisco.


                <div class="qr" style="margin-top:5px;">
                  <img src="data:image/png;base64,<?=$qrCode?>" style="width:70%; max-width:150px;">
                </div>
            </div>

              <div class="col-7" style="margin-top: 25px;">
              <img align="center" src="{{$result->url_minimapa}}" style="width:300px;height:300px;margin-top:25px;" alt="">

              </div>
        </div>


        <div class="row" style="margin-top:-15px">
            <div class="col-12">
              <h4>OBSERVACIONES: </h4>
              <div style="font-size:12px;margin-top:-15px;">
              1. Una vez que la autoridad valide la información recibida, enviará al correo del solicitante la propuesta de
              cobro/orden de pago para que el ciudadano pueda pagar los derechos y así obtener su Licencia de Funcionamiento.
              <br>
              2. En caso de que la autoridad requiera información adicional, se le enviará un correo electrónico avisándole dicha
              situación, por ello se sugiere revisar periódicamente el correo electrónico señalado para recibir notificaciones.
              </div>
            </div>


        </div>

        <div class="row">
            <div class="col-12">

              <div class="" style="font-weight: 700;">

              Atentamente
              <br>
                <?php
                if($result->id_municipio == 37){
                ?>
                Dirección de Reglamentos
                <br>
                Gobierno Municipal de  {{$result->municipio}}, Jalisco. </div>

                <?php
                }else{
                ?>
                Dirección de Padrón y Licencias
                <br>
                Ayuntamiento de {{$result->municipio}}, Jalisco. </div>
                <?php
                }
                ?>
            </div>


        </div>


</body>
</html>


