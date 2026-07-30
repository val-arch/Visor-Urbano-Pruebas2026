<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <link
        href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <title>Licencia de negocio </title>
    <!-- Compiled and minified CSS -->
    <style>
        .footable {
            width: 100%;

            border: solid #ccc 1px;
            border-collapse: separate;
            border-spacing: 0;
        }


        .footable>tbody>tr>td,
        .footable>thead>tr>th {
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
            padding: 2px;
            text-align: left;
        }

        .footable>thead>tr>th,
        .footable>thead>tr>td {
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

        .box {
            width: 200px;
            height: 40px;
            background-color: {{ $data_muni->color_hex ?? '#003E76' }};
            color: white;
            text-align: center;
            border-radius: 5px;
            padding: 3px !important;
            font-family: 'Red Hat Display', sans-serif;
            text-align: center;
        }

        .boxL {
            width: 450px;
            height: 40px;
            background-color: {{ $data_muni->color_hex ?? '#003E76' }};
            color: white;
            text-align: center;
            border-radius: 5px;
            padding: 3px !important;
            font-family: 'Red Hat Display', sans-serif;

        }

        .texto {
            width: 200px;
            /* height:40px; */
            margin-top: 10px;
            font-family: 'Red Hat Display', sans-serif;
            line-height: 10px;
            line-break: anywhere;
            word-wrap: break-word;
            text-align: justify;

        }

        .textoL {
            margin-left: 30px;
            margin-top: 10px;
            font-family: 'Red Hat Display', sans-serif;
            line-height: 10px;
            word-wrap: break-word;
            text-align: center;

        }

        /* ROOT FONT STYLES */

        * {
            font-family: 'Century Gothic', 'Lato', Helvetica, sans-serif;
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

        .row-cols-2>* {
            flex: 0 0 auto;
            width: 50%;
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

        @media only screen and (min-width: 33.75em) {

            /* 540px */
            .container {
                width: 80%;
            }
        }

        @media only screen and (min-width: 45em) {

            /* 720px */
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

        @media only screen and (min-width: 60em) {

            /* 960px */
            .container {
                width: 75%;
                max-width: 60rem;
            }
        }

        .p-parrafo {
            text-align: center;
            font-size: 12px;
        }

        .p-parrafo-fotter {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
        }

        .p-parrafo-full {
            font-size: 14px;
            font-family: 'Red Hat Display', sans-serif;
        }

        .p-pie-pagina {
            font-size: 8px;
        }

        .border-table>tbody>tr>td {

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

        .saltopagina {
            page-break-after: always;
        }

        #watermark {
            position: fixed;

            /**
                    Establece una posición en la página para tu imagen
                    Esto debería centrarlo verticalmente
                **/
            bottom: 10cm;
            left: 4.5cm;

            /** Cambiar las dimensiones de la imagen **/
            width: 13cm;
            height: 13cm;

            /** Tu marca de agua debe estar detrás de cada contenido **/
            z-index: -99999;
            text-align: center;
            font-size: 300px;
            -webkit-transform: rotate(-65deg);
            -moz-transform: rotate(-90deg);
            color: #6663;
            -webkit-user-select: none;
            cursor: not-allowed;
        }

        #watermark::selection {
            background: transparent;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            /** Extra personal styles **/
            background-color: {{ $data_muni->color_hex ?? '#003E76' }};
            color: white !important;
            text-align: center;
            line-height: 35px;
            font-size: 12px;
            text-align: justify;
        }
    </style>
</head>

<body>

    <div id="watermark">
       2024
    </div>
    <footer>
        <div style="margin:20px;">
            *Esta Licencia de negocio no comprueba el pago de derechos realizado por parte del contribuyente. Para
            comprobarlo,
            el titular deberá tener visible en su negocio la presente Licencia junto con el comprobante del pago de
            derechos realizado.
            <br>
        </div>
    </footer>
    <div class="acuse-box">


        <div class="row" style="margin-top:-15px;">

                <div class="col-4">
                    <img src="{{ $logos["logo_cuernavaca"] }}" style="width:100%; max-width:150px; z-index:99;">
                </div>
                <div class="col-8" style="margin-top:-30px; text-align:right;">
                    <h2 class="Titulo"><strong>LICENCIA DE NEGOCIO </strong></h2>
                    <h2 style="margin-top:-35px;padding-right:3px;font-weight:normal;" class="">

                       2024 </h2>
                </div>
        </div>

        <div class="row" style="margin-top:-25px;">
            <div class="col-8" style="padding-top:0px">
                <div class=" boxL"> Titular</div>
                <div class="textoL"> <b> Titular de la licencia </b> <br>
                    {{ $curp }}
                </div>
            </div>
            {{-- <div class="col-2">
              <div  class=" box"> CURP o RFC</div>
               <div class= "texto">  {{date('d/m/Y',strtotime($data['fecha_inicio']))}}</div>
               <div class= "texto">  {{$data['fecha_inicio']->toDateString()}}</div>
             </div> --}}

            <div class="col-4">
                <div class=" box"> Folio</div>
                <div class="texto" style="text-align: center;"><b>No.
                        1-1-2024</b> - Nueva</div>
                {{-- <div class= "texto">  {{$data['fecha_limite']->toDateString()}}</div> --}}
            </div>
        </div>

        <div class="row">
            <div class="col-8" style="margin-top: -10px">
                <div class=" boxL"> Actividad comercial solicitada</div>
                <div class="textoL"><b>Abarrotes</b> </div>
            </div>

            <div class="col-4">
                <div class=" box"> Superficie autorizada</div>

                    <div class="texto" style="text-align: center;">20
                        m<sup>2</sup> </div>



            </div>
        </div>

        <div class="row">

            <div class="col-8">
                <div style="margin-top: -10px">
                    <div class=" boxL"> Actividad(es) detallada(s) a realizar</div>
                    <div class="textoL" style="font-size:'14px'">
                       Actividad detallada a realizar</div>
                </div>
                @if ($info_anuncio != null)
                    <br>
                    <div style="margin-top: -10px">
                        <div class=" boxL">Información del anuncio</div>
                        <div class="textoL" style="font-size:<?= strlen($desc->value) > 45 ? '14px' : '15px' ?>">
                            {{ $info_anuncio }}</div>
                    </div>
                @endif
                <br>
                <div class="row" style="margin-top: -10px;">
                    <div class="col-6">

                            <div class=" box"> Horario</div>
                            <div class="texto" style="text-align:center !important;">Apertura:7:00 hrs
                                <br> Cierre: 8:000 hrs
                            </div>


                    </div>
                    <div class="col-6"
                        style="text-align:center !important; padding-left: 250px;margin-top:-1px;<?= strlen($ubicacion) > 100 ? 'font-size:8px;' : 'font-size:12px;' ?>">
                        <div class=" box"> Ubicación</div>
                        <div class="texto">{{ $ubicacion }} <br> Municipio {{ $result->municipio }}, Jalisco.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4" style="">
                <img align="center" src="{{ $logos["logo_mapa"] }}" style="width:225px;height:225px;" alt="">

            </div>
        </div>
       @if ($info_anuncio != null)
            <div class="row" style="margin-top:50px;">
            @else
                <div class="row" style="margin-top:10px;">
        @endif
        <div class="col-12" style="text-align: center; font-weight: 700;">
            Atentamente
            <br>
            {{ $data_muni->nombre }}, Jalisco el día {{ date('d', strtotime($data_muni->created_at)) }} de
            {{ $mes[date('m', strtotime($data_muni->created_at)) - 1] }} del
            20{{ date('y', strtotime($data_muni->created_at)) }}
            <br>

        </div>

    </div>
    <div class="row row-cols-2" style="margin-top:10px; ">
        <div class="col-8">
            <table class="table">

                <tbody>
                    @foreach(array_chunk($firmas, 2, true) as $chunk)
                        <tr>
                            @foreach($chunk as $index => $firma)
                                <td style="width:250px;height:170px; font-size:13px;">
                                    @if(isset($firma['nombre_firmante']))
                                        @if(isset($firma['firma']))
                                            <img src="{{ $firma['firma'] }}" style="width:100%; max-width:130px; max-height:50px;">
                                        @endif
                                        <div style="font-weight: 700;text-align:center">
                                            {{ $firma['nombre_firmante'] }}
                                        </div>
                                        <div style="text-align:center">
                                            {{ $firma['dependencia'] }}
                                        </div>
                                    @else
                                        <div style="text-align:center">
                                            {{ $data_muni->direccion }}
                                            Dirección de padrón y licencias
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>



            </table>
        </div>
        <div class="col-4" style="text-align: right; margin-left:-20px;">
            <div class="qr" style="">
                <img src="data:image/png;base64,<?= $qrCode ?>" style="width:80%; max-width:175px;">
            </div>
                       <div style=" font-size:10px;text-align: center; margin-top: -4px; margin-right: -170px;">

<p>Total pago de derechos:</p>
<p style="margin-top:-20px" >$100</p>

                </div>



        </div>


    </div>

    <div style="page-break-after:always;"></div>
    <br>
    <div class="row" style="margin-top:100px">
        <div class="col-4">
            <img src="{{ $logos["logo_cuernavaca"] }}" style="width:100%; max-width:300px;">
        </div>
        <div class="col-8" style="text-align: justify">
            El municipio de {{ $result->municipio }} <b>se moderniza y pone a tu disposición herramientas digitales
                para emitir la presente licencia.</b>
            Por ello, te invitamos a seguir haciendo buen uso de ellas respetando y cumpliendo con los reglamentos
            municipales
            y demás normatividad aplicable para que este Municipio sea más expedito y facilite la interacción entre
            ciudadano y gobierno.
        </div>
    </div>
    <br>
    <div class="row" style="text-align: justify">
        <div class="col-12">
            Recuerda que el desconocimiento de los reglamentos o leyes no te exime de la obligación de cumplirlos.
            A continuación, mencionamos algunas consideraciones que debes de tener presente para el buen
            funcionamiento de tu negocio.
        </div>
    </div>
    <div class="row" style="padding-left: 40px; text-align: justify">
        <div class="col-12">
            <li>La licencia deberá estar en un sitio visible dentro del negocio.</li>
            <li>La licencia tiene una vigencia anual, por lo que deberá ser refrendada.</li>
            <li>En caso de cerrar definitivamente el negocio, dar el aviso de baja a la autoridad.</li>
            <li>Contar con medidas en materia de seguridad y protección civil.</li>
            <li>Mantener una imagen ordenada y limpia al interior y exterior del establecimiento.</li>
        </div>
    </div>

    <div class="row" style="text-align: justify">
        <div class="col-12">
            Son motivos de clausura y/o revocación de la licencia:
        </div>
    </div>
    <div class="row" style="padding-left: 40px; text-align: justify">
        <div class="col-12">
            <li>Operar una actividad distinta a la señalada en la licencia.</li>
            <li>Funcionar en un horario distinto al permitido.</li>
            <li>Carecer de licencia de negocio.</li>
            {{-- <li>Exceder los aforos autorizados.</li> --}}
            <li>Descargar residuos nocivos al drenaje o a la vía pública.</li>
            {{-- <li>Operar máquinas de juegos de azar.</li> --}}
            <li>Haber proporcionado datos o documentos falsos para obtener la presente licencia.</li>
            <li>El consumo de bebidas alcohólicas por menores de 18 años.</li>
            {{-- <li>Utilizar la vía pública para publicidad y/o cualquier aprovechamiento del negocio, sin la autorización respectiva.</li> --}}
            {{-- <li>Operar en una superficie mayor a la autorizada.</li> --}}
            {{-- <li>Exceder las emisiones de ruido máximo permitido (NOM-081-SEMARNAT-1994).</li> --}}
            <li>Las demás que señalen los reglamentos aplicables.</li>
        </div>
    </div>
    <hr>
    <div class="row" style="font-size:12px;padding:25px;">

    </div>

    <div class="row" style="font-size:12px;padding:5px;">



    </div>


</body>

</html>
