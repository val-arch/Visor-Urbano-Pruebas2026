<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <link
        href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">


    <title>Licencia de construcción </title>
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
            background-color: #003E76;
            color: white;
            text-align: center;
            border-radius: 5px;
            padding: 3px !important;
            font-family: 'Red Hat Display', sans-serif;
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

        td {
            font-size: 14px !important;
            text-align: justify center;
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

        .green-tittle {
            color: #7fab55;
        }

        /* ==== GRID SYSTEM ==== */

        .container {
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        .container2 {
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

        .bold {
            font-style: bold;
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

        .Titulo {
            font-size: 26px;
        }

        .box-white {
            background-color: #eeeeee;
        }

        .box-white-table {
            background-color: #eeeeee;
            width: 250px;
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
            background-color: #003E76;
            color: white !important;
            text-align: center;
            line-height: 35px;
            font-size: 12px;
            text-align: justify;
        }
    </style>
</head>

<body>

    <!--<div id="watermark">
        {{ $data->anio_licencia }}
    </div>-->
    <footer>
        <div style="margin:20px;">
            *Esta Licencia de Construcción no comprueba el pago de derechos realizado por parte del contribuyente. Para
            comprobarlo, el titular deberá tener disponible en todo momento la presente licencia junto con el
            comprobante del pago de derechos realizado.
            <br>
        </div>
    </footer>
    <div class="acuse-box">
        <div class="row">
            <div class="col-2">
                <img src="{{ $data->img_logo }}" alt="logo" style="width:100%; max-width:150px; z-index:99;">
            </div>
            <div class="col-10" style="margin-top:-30px; text-align:right;">
                <h2 class="Titulo"><strong>{{ $result3->tramite }} </strong></h2>
                <h2 class="Titulo" style="margin-top:-18px;padding-right:3px;font-weight:normal;" class="">
                    {{ $data->status_pago == 0 ? '(Preliminar)' : '' }}
                    {{ $data->status_baja == 1 ? '(Dada de baja)' : '' }}
                </h2>
            </div>
        </div>

        <div class="row" style="margin-top: 0px;">
            <div class="col-8"></div>
            <div class="col-4">
                <table>
                    <tr>
                        <td class="green-tittle box-white">Folio ingreso:</td>
                        <td class="box-white">{{ $result->folio_interno }}</td>
                    </tr>
                    <tr>
                        <td class="green-tittle">Folio requisitos:</td>
                        <td>{{ $data->folio }}</td>

                    </tr>
                    <tr>
                        <td class="green-tittle box-white">Folio único resolutivo:</td>
                        <td class="box-white">{{ $data->consecutivo }}</td>
                    </tr>
                    <tr>
                        <td class="green-tittle">Tipo licencia:</td>
                        <td>{{ $data->tipo_licencia }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12 green-tittle">
                DATOS DEL PROPIETARIO:
                <table style="width:10vh;">
                    <tr>
                        <td class="box-white">Nombre:</td>
                        <td class="box-white bold" style="width: 300px;">{{ $data_campos->nombre_completo_prop }}</td>
                        <td class="box-white">CURP/RFC</td>
                        <td class="box-white bold" style="width: 300px;"><?php echo e(isset($data_campos->prop_curp) ? $data_campos->prop_curp : ''); ?></td>
                    </tr>
                    <tr>
                        <td>Domicilio:</td>
                        <td class="bold">{{ $data_campos->direccion_prop }}</td>
                        <td style="width: 120px;">Correo electrónico:</td>
                        <td class="bold"><?php echo e(isset($data_campos->prop_correo) ? $data_campos->prop_correo : ''); ?></td>
                    </tr>
                    <tr>
                        <td class="box-white">Celular:</td>
                        <td class="box-white bold"><?php echo e(isset($data_campos->prop_cel) ? $data_campos->prop_cel : ''); ?></td>
                        <td class="box-white"></td>
                        <td class="box-white"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-9 green-tittle">
                UBICACIÓN DEL PREDIO Y/O CONSTRUCCION:
                <table style="width:5vh;">
                    <tr>
                        <td class="box-white">Calle y número:</td>
                        <td class="box-white bold">{{ $respuesta['domicilio']['calle'] }} #{{$respuesta['domicilio']['exterior']}}{{$respuesta['domicilio']['interior']}}</td>
                        <td class="box-white">Colonia:</td>
                        <td class="box-white bold">{{ $result->colonia }}</td>
                    </tr>
                    <tr>
                        <td>Entre la calle:</td>
                        <td class="bold">{{ $calle_uno->value }}</td>
                        <td>Y la calle:</td>
                        <td class="bold">{{ $calle_dos->value }}</td>
                    </tr>
                    <tr>
                        <td class="box-white">Localidad:</td>
                        <td class="box-white bold">{{ $result->municipio }}</td>
                        <td class="box-white">CP:</td>
                        <td class="box-white bold">{{ $data_campos->obra_cp ?? '-' }}</td>

                    </tr>
                    <!--<tr>
                        <td class="box-white">Mazana:</td>
                        <td class="box-white bold"></td>
                        <td class="box-white">Lote:</td>
                        <td class="box-white bold"><?php echo e(isset($data_campos->cp) ? $data_campos->cp : ''); ?></td>

                    </tr>-->
                    <tr>
                        <td>Clave catastral:</td>
                        <td class="bold">{{ $data_campos->obra_cve_catastral ?? '-' }}</td>
                        <td>Cuenta predrial:</td>
                        <td class="bold">{{ $data_campos->obra_cta_predial ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="box-white">Dcto propiedad:</td>
                        <td class="box-white bold">{{ $data_campos->tipo_dcto_propiedad }}</td>
                        <td class="box-white">No. documento:</td>
                        <td class="box-white bold">{{ $data_campos->no_dcto_propiedad ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Expedido por:</td>
                        <td class="bold">{{ $data_campos->fedatario }}</td>
                        <td>Fecha expedición:</td>
                        <td class="bold">
                            {{ \Carbon\Carbon::parse($data_campos->fecha_dcto_propiedad)->format('d/m/Y') ?? '-' }}
                        </td>

                    </tr>
                </table>
            </div>
            <div class="col-3">
                <img align="center" src="{{ $result->url_minimapa }}"
                    style="width:160px;height:160px; margin-top: 20px;">
            </div>
        </div>

        <?php
        if ($result->superficie_habitacional > 0) {
            $m2_result = 'Habitacional: ' . $result->superficie_habitacional . 'm², ';
        }
        if ($result->superficie_comercial_servicios > 0) {
            $m2_result = $m2_result . 'Comercial: ' . $result->superficie_comercial_servicios . 'm², ';
        }
        if ($result->superficie_industrial > 0) {
            $m2_result = $m2_result . 'Industrial: ' . $result->superficie_industrial . 'm², ';
        }
        if ($result->superficie_turistico > 0) {
            $m2_result = $m2_result . 'Turísstico: ' . $result->superficie_turistico . 'm², ';
        }
        if ($result->superficie_equipamiento > 0) {
            $m2_result = $m2_result . 'Equipamiento: ' . $result->superficie_equipamiento . 'm², ';
        }
        if ($result->superficie_espacios_verdes > 0) {
            $m2_result = $m2_result . 'Espacios verdes: ' . $result->superficie_espacios_verdes . 'm², ';
        }
        if ($result->superficie_otro > 0) {
            $m2_result = $m2_result . $result->concepto_otro . ': ' . $result->superficie_otro . 'm².';
        }
        ?>

        <?php
        if(sizeof($result2)>0){?>
        <div class="row">
            <div class="col-12 green-tittle">
                DETALLES DEL DOCUMENTO:
                @foreach ($result2 as $item)
                    <table style="width:10vh;">
                        <tr>
                            <td class="box-white" style="width: 100px;">Nombre:</td>
                            <td class="box-white bold">{{ $item->description ?? '-' }}</td>
                            <td class="box-white">Descripción:</td>
                            <td class="box-white bold">{{ $item->description ?? '-' }}</td>

                        </tr>
                    </table>
                @endforeach
            </div>
        </div>
        <?php
        }
        if(sizeof($resolutivo)>0){?>

        <div class="row">

            <div class="col-12 green-tittle">
                DETALLES DEL RESOLUTIVO:
                @foreach ($resolutivo as $item2)
                    <table style="width:10vh;">
                        <tr>
                            <td class="box-white-table">{{ $item2->campo . ':' ?? '-' }}</td>
                            <td class="box-white bold">{{ $item2->respuesta ?? '-' }}</td>

                        </tr>
                    </table>
                @endforeach
            </div>
        </div>
        <?php
        }
        ?>



        <div class="row">
            <div class="col-12" style="text-align: center; font-weight: 700;">
                Atentamente
                <br>
                {{ $result->municipio }}, Jalisco el día {{ date('d', strtotime($data->created_at)) }} de
                {{ $mes[date('m', strtotime($data->created_at)) - 1] }} del
                20{{ date('y', strtotime($data->created_at)) }}
                <br>
            </div>

        </div>
        <br>
        <br>


        <div class="row justify-content-center">
            <?php
        $i = 1;
        foreach($data->firmantes as $firma) { ?>
            <div class="col-6 text-center">
                {{ $firma['nombre_firmante'] ?? '-' }}
                <br>
                <?php
                if($firma['firma']!=null){?>
                <div style="margin-top: 10px;">
                    <img src="{{ $firma['firma'] }}" style="max-width: 180px;">
                </div>
                <?php
                }else{
                    ?>
                <?php
                }
                ?>
            </div>
            <?php
            if ($i % 2 == 0) {
                ?>
        </div>
        <div class="row justify-content-center"><?php
            }
            $i++;
        ?>
        <?php } ?>
        </div>
        <div class="row">
            <div class="col-8"></div>
            <div class="col-4" style="text-align: right;">
                <div class="qr" style="position: absolute;">
                    <img src="data:image/png;base64,<?= $qrCode ?>" style="width:80%; max-width:175px;">
                </div>
            </div>
        </div>

        <div style="page-break-after:always;"></div>
        <br>
        <div class="row" style="margin-top:50px">
            <div class="col-4">
                <img src="https://cdn.blueberriesconsulting.com/2021/11/Logo-Jalisco.png"
                    style="width:100%; max-width:300px;">
            </div>
            <div class="col-8" style="text-align: justify">
                El municipio de {{ $result->municipio }} <b>se moderniza y pone a tu disposición herramientas
                    digitales para emitir la presente Licencia.</b>
                Por ello, te invitamos a seguir haciendo buen uso de ellas respetando y cumpliendo con los reglamentos
                municipales y demás normatividad aplicable para que este Municipio sea más expedito y facilite la
                interacción entre ciudadanía y gobierno.
            </div>
        </div>
        <br>
        <div class="row" style="text-align: justify">
            <div class="col-12">
                Recuerda que el desconocimiento de los reglamentos o leyes no te exime de la obligación de cumplirlos. A
                continuación, mencionamos algunas consideraciones que debes de tener presente para tu obra:
            </div>
        </div>
        <div class="row" style="padding-left: 40px; text-align: justify">
            <div class="col-12">
                <li>La licencia deberá estar en un sitio visible al exterior del predio donde se construya, así como los
                    planos autorizados en su caso.</li>
                <li>Previo a que expire la vigencia de esta licencia, se deberá iniciar el trámite para prorrogarla.
                </li>
                <li>Una vez concluida la obra, y en caso de que así se requiera, se deberá tramitar la habitabilidad
                    correspondiente.</li>
                <li>Indispensable contar con las medidas en materia de seguridad y protección civil que garantice la
                    integridad de los trabajadores y de las fincas colindantes.</li>
                <li>No mantener escombro o materiales de construcción en la vía pública.</li>
            </div>
        </div>

        <div class="row" style="text-align: justify">
            <div class="col-12">
                Son motivos de clausura de la obra y/o revocación de la licencia:
            </div>
        </div>
        <div class="row" style="padding-left: 40px; text-align: justify">
            <div class="col-12">
                <li>No respetar o construir proyecto arquitectónico distinto al autorizado según los planos y/o
                    expediente de esta licencia.</li>
                <li>Ejecutar trabajos fuera del horario señalado por los reglamentos.</li>
                <li>Carecer de licencia de construcción.</li>
                <li>Descargar residuos nocivos al drenaje o a la vía pública.</li>
                <li>Operar maquinaria de construcción sin observar medidas de seguridad.</li>
                <li>Haber proporcionado datos o documentos falsos para obtener la presente licencia.</li>
                <li>Utilizar la vía pública para depositar escombro o materiales de construcción.</li>
            </div>
        </div>
        <hr>
        <div class="row" style="font-size:12px;padding:25px;">
            <?php
            $d = str_replace(PHP_EOL, '<br>', $data_muni->restricciones_licencia);

            ?> <?php if ($data->observaciones): ?>
            <strong>Información adicional sobre la presente licencia: </strong>
            <?php echo str_replace(PHP_EOL, '<br>', $data->observaciones); ?>
            <?php endif; ?>

        </div>

        <div class="row" style="font-size:12px;padding:5px;">
        </div>



</body>

</html>
