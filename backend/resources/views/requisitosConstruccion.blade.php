<!doctype html>
<html>

<head>
    <link
        href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Requisitos Folio: {{ $data->folio }} </title>

    <!-- Compiled and minified CSS -->
    <style type="text/css" media="all">
        .w3-table,
        .w3-table-all {
            border-collapse: collapse;
            border-spacing: -1px;
            width: 110%;
            display: table;

        }

        .w3-table-all {
            border: 1px solid #ccc;
            border-right: 1px solid #ddd
        }

        .w3-bordered tr,
        .w3-table-all tr {
            border-bottom: 1px solid #ddd;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd
        }

        .w3-striped tbody tr:nth-child(even) {
            background-color: #f1f1f1
        }

        .w3-table-all tr:nth-child(odd) {
            background-color: #fff
        }

        .w3-table-all tr:nth-child(even) {
            background-color: #f1f1f1
        }

        .w3-hoverable tbody tr:hover,
        .w3-ul.w3-hoverable li:hover {
            background-color: #ccc
        }

        .w3-centered tr th,
        .w3-centered tr td {
            text-align: center
        }

        .w3-table td,
        .w3-table th,
        .w3-table-all td,
        .w3-table-all th {
            padding: 4px 4px;
            display: table-cell;
            text-align: left;
            vertical-align: top
        }

        .w3-table th:first-child,
        .w3-table td:first-child,
        .w3-table-all th:first-child,
        .w3-table-all td:first-child {
            padding-left: 16px
        }

        .w3-btn,
        .w3-button {
            border: none;
            display: inline-block;
            padding: 8px 16px;
            vertical-align: middle;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            background-color: inherit;
            text-align: center;
            cursor: pointer;
            white-space: nowrap
        }

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
        .classTest {
            background-color: transparent;
            border: none;
            border-bottom: 1px solid #9e9e9e;
            border-radius: 0;
            outline: none;
            height: 3rem;
            width: 100%;
            font-size: 16px;
            margin: 0 0 8px 0;
            padding: 0;
            -webkit-box-shadow: none;
            box-shadow: none;
            -webkit-box-sizing: content-box;
            box-sizing: content-box;
            -webkit-transition: border .3s, -webkit-box-shadow .3s;
            transition: border .3s, -webkit-box-shadow .3s;
            transition: box-shadow .3s, border .3s;
            transition: box-shadow .3s, border .3s, -webkit-box-shadow .3s;
        }

        .green-tittle {
            background-color: transparent;
            color: #7fab55;
            border: none;
            border-radius: 0;
            outline: none;
            height: 3rem;
            width: 100%;
            font-size: 16px;
            margin: 0px;
        }

        .subtittle{
            background-color: transparent;
            color: black;
            border: none;
            border-radius: 0;
            outline: none;
            height: 3rem;
            width: 100%;
            font-size: 15px;
            margin: 0px;
        }

        .badg {
            padding-bottom: 10px;
        }

        .w3-badge,
        .w3-tag {
            background-color: #003E76;
            color: #fff;
            display: inline-block;
            padding-left: 5px;
            padding-right: 5px;
            width: 18px;
            text-align: center
        }

        .w3-badge {
            border-radius: 90%
        }

        .footable {
            width: 100%;
            border: solid #ccc 1px;
            border-collapse: separate;
            border-spacing: 0;
        }

        td>p {
            margin-top: 0px;
            margin-bottom: 0px;
        }

        p>u {
            color: #3EA5FD;
            font-weight: bold;
        }

        td>div>strong {
            color: #003E76;
        }

        td>div>div {
            border-bottom: .5px;
            border-style: solid;
            border-bottom-color: #cccccc;
            border-left: 0;
            border-top: 0;
            border-right: 0;
            color: red;
        }

        td>div>div>p {
            word-break: break-all;
            color: #333447;
            font-weight: normal !important;
        }

        td>div>div>strong {
            color: #3EA5FD;

            font-style: italic;
        }

        .p-title {
            color: #3EA5FD;
            font-weight: bold;
        }

        .color-v {
            color: #3EA5FD;
        }

        .color-v2 {
            color: #7fab55;
        }

        hr {
            display: block;
            height: 1px;
            border: 0;
            border-top: 0.3px solid #ccc;

            padding: 0;
        }

        .badge {
            width: 30px;
            padding: .5em .7em;
            border-radius: 15rem;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            background-color: #003E76;
            color: #f1f1f1f1;
        }

        .badge-pill {}

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



        .p-parrafo {
            text-align: center;
            font-size: 12px;
        }

        .p-parrafo-fotter {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        .p-parrafo-full {
            font-size: 15px;
        }

        .p-pie-pagina {
            font-size: 10px;
        }

        .border-table>tbody>tr>td {
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
        .box .box-text {
            /* min-height: 30px !important; */
            margin: 0px !important;
            /* padding: 3px !important; */
            color: black !important;
        }

        .boxL {
            width: 450px;
            height: 40px;
            background-color: #003E76;
            color: white;
            text-align: center;
            border-radius: 5px;
            padding: 3px !important;
            font-family: 'Red Hat Display', sans-serif;

        }

        .box .box-titulo {
            /* min-height: 29px; */
            margin: 0px !important;
            padding: 3px !important;
            background-color: #003E76;
            color: white !important;
            font-size: 24px;
        }
        .boxL2 .box-titulo {
            /* min-height: 29px; */
            margin: 0px !important;
            padding: 3px !important;
            background-color: #003E76;
            color: white !important;
            font-size: 24px;
        }
        .box {
            margin: auto;

            display: block !important;
            width: 80%;
            margin-left: 20%;
            /* min-height: 70px; */
            border: solid 1px #003E76;
            text-align: center !important;
            border-radius: 5px;
            /* padding: 3px !important; */
            font-family: 'Red Hat Display', sans-serif;
            /* margin: 5px !important; */
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
            bottom: 12cm;
            left: 3cm;

            /** Change image dimensions**/
            width: 15cm;
            height: 9cm;

            /** Your watermark should be behind every content**/
            z-index: -1000;
            opacity: 0.1;
            filter: alpha(opacity=10);
            /* For IE8 and earlier */
        }

        #imagen-zonificacion-normas img {
  height: 350px;
  width: auto; /* Esto asegura que la imagen se ajuste al ancho del contenedor */
  margin-bottom: 20px;
}

.zonificacion-titulo {
  font-size: 12px !important;
}

.zonificacion-texto {
  font-size: 13px;
  font-weight: bold;
}

.normas-titulo {
  font-size: 12px !important;
}

.normas-texto {
  font-size: 10px;
  margin-bottom: 10px;
}
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display&display=swap" rel="stylesheet">



    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display&display=swap" rel="stylesheet">
</head>

<body>

    <div id="watermark">
        <img src="https://visorurbano.jalisco.gob.mx/assets/images/logo.png" height="100%" width="100%">
    </div>
    <!-- Wrap the content of your PDF inside a main tag -->
    <main>

        <div class="invoice-box">
            <div class="row" style="margin-bottom:0px!important;">
                <div class="col-4">
                    <img src="https://visorurbano.jalisco.gob.mx/assets/images/logo.png"
                        style="width:100%; max-width:178px; margin-bottom:0px!important;">
                </div>
                <div class="col-4"></div>
                <div class="col-4">
                    <img src="https://cdn.blueberriesconsulting.com/2021/11/Logo-Jalisco.png" style="width:178px; max-width:178px; margin-top:-20px!important;">
                </div>
            </div>
            <div align="right" style=""> Folio: <strong> {{ $data->folio }} </strong> </div>
            <br>

            <br>
            <div class="row" style="margin-top:-7px !important;margin-bottom:0px!important;">
                <div class="col-12" style="margin-top:-5px !important;margin-bottom:0px!important;">
                    <h3 align="center" style="margin-top:1px;margin-bottom:0px; font-weight: normal !important;"
                        class=""> {{ $municipio->area_encargada }} </h3>
                    <h3 align="center" style="margin-top:-10px;margin-bottom:2px; font-weight: normal !important;"> H.
                        Ayuntamiento de {{ $data->municipio }}, Jalisco </h3>

                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <div align="center" style="margin-top:0px;margin-bottom:0px; " class="">
                        <h5 style="margin-top:0px; font-size:19px;">Solicitud de requisitos para tramitar  {{ $tramite->first()->tramite }}.</h5>

                    </div>

                </div>
            </div>

            <div class="row" style="">
                <div class="col-6">
                    <table class="">
                        <tr>
                            <td>
                                <p class="green-tittle" style="text-decoration: underline;">Datos de la obra y predio:</p>
                                <p class="green-tittle" style="margin-top:-24px;">Domicilio del predio: <br>
                                </p>
                                <p class="subtittle" style="margin-top: -26px;">
                                    {{ $data->calle }} {{ $data->colonia }}, {{ $data->municipio }},
                                    Jalisco.
                                </p>
                                <p class="green-tittle" style="margin-top:-24px;">Superficie de propiedad: <br>
                                </p>
                                <p class="subtittle" style="margin-top: -26px;">
                                    {{ $data->superficie_propiedad }} m<sup>2</sup>
                                </p>
                                <p class="green-tittle" style="margin-top:-24px;">Destino(s) de la construcción: <br>
                                </p>
                                <?php
                                $data_headers = array('Habitacional', 'Comercial y/o servicios', 'Industrial', 'Alojamiento temporal (Turístico)', 'Equipamiento', 'Espacios verdes, abiertos y recreativos');
                                $data_values = array('superficie_habitacional', 'superficie_comercial_servicios', 'superficie_industrial', 'superficie_turistico', 'superficie_equipamiento', 'superficie_espacios_verdes');
                                $properties = get_object_vars($data);
                                for ($i = 0; $i < count($data_values); $i++) {
                                    if($properties[$data_values[$i]]>0){
                                    ?>
                                    <p class="subtittle" style="margin-top: -27px;">{{$data_headers[$i]}}: {{ $properties[$data_values[$i]]}} m<sup>2</sup></p>
                                    <?php
                                    }
                                }
                                if($data->concepto_otro != null && $data->concepto_otro != '' and $data->superficie_otro > 0){
                                ?>
                                    <p class="subtittle" style="margin-top: -27px;">{{$data->concepto_otro}}: {{ $data->superficie_otro}} m<sup>2</sup></p>
                                <?php
                                }
                                ?>

                                @if(!empty($data->mdemolicion))
                                <p class="green-tittle" style="margin-top:-26px;">m² de demolición:</p>
                                <p class="subtittle" style="margin-top: -28px;">
                                    {{ $data->mdemolicion}} m<sup>2</sup>
                                </p>
                                @endif

                                @if(!empty($data->niveles_nuevos_construir))
                                <p class="green-tittle" style="margin-top:-26px;">Niveles nuevos a construir:</p>
                                <p class="subtittle" style="margin-top: -28px;">
                                    {{ $data->niveles_nuevos_construir}}
                                </p>
                                @endif

                                @if(!empty($data->numero_viviendas))
                                <p class="green-tittle" style="margin-top:-26px;">Número de Viviendas:</p>
                                <p class="subtittle" style="margin-top: -28px;">
                                    {{ $data->numero_viviendas}}
                                </p>
                                @endif

                                @if(!empty($data->sotano))
                                <p class="green-tittle" style="margin-top:-26px;">Sótanos al finalizar obra:</p>
                                <p class="subtittle" style="margin-top: -30px;">
                                    {{ $data->sotano}}
                                </p>
                                @endif

                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-6" style="border:1px; margin-top:-35px;">
                    <img align="center" src="{{ $data->url_minimapa }}"
                        style="width:340px;height:340px;margin-top:50px;" alt="">
                </div>
            </div>
            <hr />
            <div style="page-break-after:always;"></div>
            <div class="row">
                <div class="col-12">
                    <strong class="color-v2">Requisitos para obtener {{ $tramite[0]->tramite}} </strong>
                </div>
            </div>
            <div class="row">
                <table class="table w3-striped" style="width: 100%">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-aling:center;"></th>
                            <th>Requisito</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cont=0; foreach($respuestas as $respuesta):?>
                        <?php if(isset($respuesta)):?>

                        <tr>
                            <td style="width: 50px !important; text-aling:center !important;"><span
                                        class="w3-badge"><?= ++$cont ?></span></td>
                            <td scope="row"> {{ $respuesta->description}} </td>
                            <td scope="row"> {{ $respuesta->description_rec}} </td>

                        </tr>
                        <?php endif;?>

                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
            <hr />
            <?php $restricciones = json_decode($data->restricciones, true); ?>
            <div class="row">
                <div class="col-12" style="margin-top:-14px;    font-size: 12px;">
                    <div style="font-weight: normal !important;">
                        <p style="text-align: center;  color:#003E76;font-style:italic;">Otras características</p>
                        <b>En caso de que el trámite consultado se encuentre cercana o dentro de alguno de los siguientes sitios, la autoridad podrá
                            requerir los requisitos adicionales que considere pertinentes: </b>
                    </div>
                </div>
            </div>
            <div class="row justify-content-around">
                <div class="col-9">
                    <table class="w3-table w3-striped w3-border col-9">
                        <tbody>
                             <tr>
                                <td>Escuelas</td>
                                <td>{{ $restricciones['escuelas'] == 0 ? 'Sin restricciones' : 'Con restricción' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Hospitales</td>
                                <td>{{ $restricciones['centros_salud'] == 0 ? 'Sin restricciones' : 'Con restricción' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Espaciós Publicos</td>
                                <td>{{ $restricciones['edificios_gobierno'] == 0 ? 'Sin restricciones' : 'Con restricción' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Bloques de construcción</td>
                                <td>{{ $restricciones['bloque_construccion_actividad'] == 0 ? 'Sin restricciones' : 'Con restricción' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Cuerpos de agua</td>
                                <td>{{ $restricciones['cuerpos_agua'] == 0 ? 'Sin restricciones' : 'Con restricción' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-3" align="center" style="margin-left: -50px;">
                    <img src="data:image/png;base64,<?= $qr ?>" style="width:75%; max-width:200px;">
                </div>
            </div>
            <hr/>
            <div class="row">
                <table class="table w3-striped" style="width: 100%">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-aling:center;"></th>
                            <th>Información solicitada</th>
                            <th>Respuesta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cont=0; foreach($rc as $respuesta):?>
                        {{-- <?php print_r($respuesta); ?> --}}
                        {{-- <?php if($respuesta['name'] && ($respuesta['campo']['type']=="file" || $respuesta['campo']['type']=="multifile" )):?> --}}
                        <tr>
                            <th style="width: 50px; text-aling:center;"></th>
                            <td scope="row"> {{ $respuesta->description }} </td>
                            <td scope="row"> <?php switch ($respuesta->type) {
                                case 'radio':
                                case 'select':
                                    $opciones = explode('|', $respuesta->opciones);
                                    $opcionesd = explode('|', $respuesta->opciones_desc);
                                    $d = array_search($respuesta->value, $opcionesd);
                                    echo $opciones[$d];
                                    break;
                                case 'input':
                                 ?>
                                    {{ $respuesta->value }}
                                <?php
                                    break;
                                case 'boolean':
                                ?>
                                    {{ $respuesta->value == 'true' ? 'Si' : 'No' }}
                                <?php
                                    break;
                                } ?>
                            </td>
                        </tr>
                        {{-- <?php endif;?> --}}
                        {{-- <?php endif;?> --}}
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>

    {{-- <div class="box-pdf"> --}}
        <?php $count_f=1;?>

            <div class="row">
                <div class="col-12">
                    <div class="p-parrafo-fotter">H. Ayuntamiento de {{ $data->municipio }} A <?= date('d') ?> de
                        <?php $mes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        echo $mes[date('m') - 1]; ?> de <?= date('Y') ?> </div>
                    <div class="p-pie-pagina">El presente documento es meramente ilustrativo y no constituye una
                        autorización para {{ $tramite->first()->tramite }}. Para ello, la/el solicitante deberá cumplir con los
                        requerimientos señalados por la autoridad competente y recibir la resolución correspondiente.
                    </div>
                </div>
            </div>
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
