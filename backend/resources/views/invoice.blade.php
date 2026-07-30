<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>A simple, clean, and responsive HTML invoice template</title>
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
      .invoice-box {
      max-width: 800px;
      margin: auto;
      padding: 30px;
      border: 1px solid #eee;
      box-shadow: 0 0 10px rgba(0, 0, 0, .15);
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
        font-size: 12px;
      }
      .p-pie-pagina{
        font-size: 8px;
      }
      .border-table > tbody > tr > td{

        border-bottom: 1px solid black;
        padding: 2px;


      }

    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="row">
            <div class="col-3">
                <img src="https://visorurbano.jalisco.gob.mx/assets/images/logo.png" style="width:100%; max-width:178px;">
            </div>
            <div class="col-6"></div>
            <div class="col-3">
                <img src="https://www.jalisco.gob.mx/sites/all/themes/custom/jwbootstrap3/img/2018-2024/isologo_jalisco_h.svg" style="width:100%; max-width:200px;">
            </div>
        </div>
        <div class="row">
            <div class="col-3">
            </div>
            <div class="col-6">
                <p class="p-parrafo" ><strong>Lista de requisitos para Licencia de Negocios</strong></p>
                <p class="p-parrafo">Direccion de << Encargada de emitir licencia>><br>H.Ayuntamiento de << Tepatitlan de morelos >> ,Jalisco </p>
            </div>
            <div class="col-3">
            </div>
        </div>
        <div class="row">
            <div class="col-10">
            </div>
            <div class="col-2">
            Folio: 090001/2020
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p class="p-parrafo-full">En atención a la solicitud de requisitos para tramitar una Licencia de Negocio presentada por el(la) C
                <b><< Sergio Villafan Anguiano >></b> , y cuyos datos para emitir la presente lista de requisitos son los siguientes</p>
            </div>
        </div>

        <div class="row">
            <div class="col-5">
                 <img src="https://img.blogs.es/anexom/wp-content/uploads/2020/02/INEGI.png" style="width:100%; max-width:350px;">
            </div>
            <div class="col-7">
              <table class="styled-table p-parrafo-full border-table" >
                <tr>
                <td><strong>Ubicación del predio:</strong><br>
                << Calle o avenida, numero, municipio >>, Jalisco.
                </td>
                </tr>
                <tr>
                <td><strong>Giro solicitado:</strong><br>
                << Calle o avenida, numero, municipio >>, Jalisco.</td>
                </tr>
                <tr>
                <td><strong>Superficie de propiedad:</strong><br>
                << Calle o avenida, numero, municipio >>, Jalisco.</td>
                </tr>
                <tr>
                <td><strong>Superficie de giro señalada:</strong><br>
                << Calle o avenida, numero, municipio >>, Jalisco.</td>
                </tr>
                <tr>
                <td><strong>Tipo de persona que solicita:</strong><br>
                Persona << Física o Moral >></td>
                </tr>
                <tr>
                <td><strong>Carácter de la (del) solicitante:</strong><br>
                << Propietario >>, << Arrendatario >> o << Representante de alguno de estos >>
                </td>
                </tr>
              </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p class="p-parrafo-full">En atención a los datos proporcionados, esta H. Dirección del municipio de <<Tepatitlán de Morelos>> determina que los requisitos para obtener la Licencia de negocio
                   para el giro de << Actividad Económica SCIAN >> motivo de la presente solicitud, son los SIGUIENTES:</p>
            </div>
        </div>
        <div class="row">
            <div class="col-10">
              <table class="footable p-parrafo-full" >
              <thead>
                <tr>
                <th>#</th>
                <th>Requisito</th>
                </tr>
              </thead>
              <tbody>
                  <tr>
                  <td class="strong">1 </td>
                  <td >Identificación del propietario </td>
                  </tr>
                  <tr>
                  <td class="strong">2 </td>
                  <td>Documento que acredite la propiedad del inmueble (Escritura, título de propiedad,cesión de derechos, etc.)</td>
                  </tr>
                  <tr>
                  <td class="strong">3 </td>
                  <td>Recibo de pago del impuesto predial actualizado</td>
                  </tr>
                  <tr>
                  <td class="strong">4 </td>
                  <td>Comprobante de domicilio vigente del negocio (Agua o luz no mayor a 3 meses de antigüedad)</td>
                  </tr>
                  <tr>
                  <td class="strong">5 </td>
                  <td>3 Fotografías del inmueble por fuera: <br>
                  Foto 1: fachada; <br>
                  Foto 2: fachada donde se aprecie también la finca o terreno al lado izquierdo; y, <br>
                  Foto 3: fachada con finca o terreno a lado derecho<br>
                  </td>
                  </tr>
                  <tr>
                  <td class="strong">6 </td>
                  <td>CURP del solicitante </td>
                  </tr>
                  </tbody>
              </table>
            </div>
        </div>
        <div class="row">
              <div class="col-2">
                   <img src="https://www.vanguardia.com/binrepository/716x477/0c0/0d0/none/12204/WTSC/DATA_ART_325251_BIG_CE_VL216321_MG21568226.jpg" style="width:100%; max-width:178px;">

            </div>
            <div class="col-8">
              <p class="p-parrafo-fotter">H. Ayuntamiento de << Tepatitlán de Morelos >><br>A << 21 de octubre del 2020 >>.</p>
              <p class="p-pie-pagina">El presente documento no constituye una autorización para la apertura de un negocio. Para ello, el solicitante deberá cumplir con los requisitos
                 aquí señalados y recibir la resolución correspondiente por la autoridad competente.</p>
            </div>
        </div>
    </div>

</body>
</html>
