<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <link
        href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <title>Licencia de funcionamiento </title>
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
            background-color: {{ $data->color_licencia ?? '#003E76' }};
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
            background-color: {{ $data->color_licencia ?? '#003E76' }};
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
            background-color: {{ $data->color_licencia ?? '#003E76' }};
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
        {{ $data->anio_licencia }}
    </div>
    <footer>
        <div style="margin:20px;">
            *Esta Licencia de Funcionamiento no comprueba el pago de derechos realizado por parte del contribuyente. Para
            comprobarlo,
            el titular deberá tener visible en su negocio la presente Licencia junto con el comprobante del pago de
            derechos realizado.
            <br>
        </div>
    </footer>
    <div class="acuse-box">


        <div class="row" style="margin-top:-15px;">
            @if ($result->municipio == 'Arandas')
                <div class="col-3">
                    <img src="{{ $data->img_logo }}" style="width:100%; max-width:150px; z-index:99;">
                </div>
                <div class="col-7" style="margin-top:30px; text-align:right;">
                    <p>
                    <h2 class="Titulo"><strong>LICENCIA DE FUNCIONAMIENTO </strong> </h2>
                    </p>
                </div>
                <div class="col-2">
                    <br>
                    <h2 style="margin-top:30px;margin-left:-110px;padding-right:3px;font-weight:normal;" class="">
                        {{ $data->anio_licencia }} </h2>

                    <h3 style="margin-top:-40px;margin-left:-150px;padding-right:3px;font-weight:normal;"
                        class="">
                        {{ $data->status_pago == 0 ? '(Preliminar)' : '' }}
                        {{ $data->status_baja == 1 ? '(Dada de baja)' : '' }}
                        {{ $data->status_licencia == 'Cancelada' ? '(Cancelada)' : '' }}
                        {{ $data->status_licencia == 'Suspendida' ? '(Suspendida)' : '' }}
                        {{ $data->status_licencia == 'Sanción' ? '(Sanción)' : '' }}
                        {{ $data->status_licencia == 'Adeudo' ? '(Adeudo)' : '' }}
                    </h3>
                </div>
            @else
                <div class="col-3">
                    <img src="{{ $data->img_logo }}" style="width:100%; max-width:150px; z-index:99;">
                </div>
                <div class="col-9" style="margin-top:-30px; text-align:right;">
                    <h2 class="Titulo"><strong>LICENCIA DE FUNCIONAMIENTO </strong></h2>
                    <h2 style="margin-top:-35px;padding-right:3px;font-weight:normal;" class="">
                        {{ $data->status_pago == 0 ? '(Preliminar)' : '' }}
                        {{ $data->status_baja == 1 ? '(Dada de baja)' : '' }}
                        {{ $data->status_licencia == 'Cancelada' ? '(Cancelada)' : '' }}
                        {{ $data->status_licencia == 'Suspendida' ? '(Suspendida)' : '' }}
                        {{ $data->status_licencia == 'Sanción' ? '(Sanción)' : '' }}
                        {{ $data->status_licencia == 'Adeudo' ? '(Adeudo)' : '' }}
                        {{ $data->anio_licencia }} </h2>
                </div>
            @endif
        </div>
        <div class="row" style="margin-top:-25px;">
            <div class="col-8" style="padding-top:0px">
                <div class=" boxL"> Titular</div>
                <div class="textoL"> <b>{{ $dueno }} </b> <br>
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
                        {{ str_pad($data->numero_lic, 5, '0', STR_PAD_LEFT) }}</b> - {{ $data->tipo_licencia }}</div>
                {{-- <div class= "texto">  {{$data['fecha_limite']->toDateString()}}</div> --}}
            </div>
        </div>

        <div class="row">
            <div class="col-8" style="margin-top: -10px">
                <div class=" boxL"> Actividad comercial solicitada</div>
                <div class="textoL"><b>{{ $result->codigo_scian }} - {{ $result->nombre_scian }}</b> </div>
            </div>

            <div class="col-4">
                <div class=" box"> Superficie autorizada</div>
                @if ($data->superficie_autorizada)
                    <div class="texto" style="text-align: center;"> {{ $data->superficie_autorizada }}
                        m<sup>2</sup> </div>
                @else
                    <div class="texto" style="text-align: center;"> Sin especificar </div>
                @endif


            </div>
        </div>

        <div class="row">

            <div class="col-8">
                <div style="margin-top: -10px">
                    <div class=" boxL"> Actividad(es) detallada(s) a realizar</div>
                    <div class="textoL" style="font-size:<?= strlen($desc->value) > 45 ? '14px' : '15px' ?>">
                        {{ $desc->value }}</div>
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
                        @if ($data->hora_a)
                            <div class=" box"> Horarios</div>
                            <div class="texto" style="text-align:center !important;">Apertura: {{ $data->hora_a }} hrs
                                <br> Cierre: {{ $data->hora_c }} hrs
                            </div>
                        @else
                            <div class=" box"> Horario</div>
                            <div class="texto" style="text-align:center !important;">Sin especificar </div>
                        @endif


                    </div>
                    <div class="col-6"
                        style="text-align:center !important; padding-left: 250px;margin-top:-1px;<?= strlen($ubicacion) > 100 ? 'font-size:8px;' : 'font-size:12px;' ?>">
                        <div class=" box"> Ubicación</div>
                        <div class="texto">{{ $ubicacion }} <br> Municipio {{ $result->municipio }}, Morelos.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-4" style="">
                <img align="center" src="{{ $result->url_minimapa }}" style="width:225px;height:225px;" alt="">

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
            {{ $result->municipio }}, Morelos el día {{ date('d', strtotime($data->created_at)) }} de
            {{ $mes[date('m', strtotime($data->created_at)) - 1] }} del
            20{{ date('y', strtotime($data->created_at)) }}
            <br>
            <?php if($data->status_pago==0):?>
            <div style="color:red;font-size:12px;">
                Este documento es un preliminar de la licencia para su debida validación. Una vez pagados los
                derechos se emitirá la licencia definitiva.
            </div>
            <?php endif;?>
            <?php if($data->status_baja==1):?>
            <div style="color:red;font-size:12px;">
                Esta licencia fue dada de baja con fecha del {{ date('d/m/Y', strtotime($data->fecha_baja)) }}, por
                los siguientes motivos:<br> {{ substr($data->motivo_baja, 0, 100) }}...
            </div>
            <?php endif;?>
        </div>

    </div>

    <div class="row justify-content-center">
        <div class="col-8">
            <?php
        // Decodificar el JSON en un arreglo asociativo
        $firmantes = json_decode($data->firmantes);
        // Verificar si $firmantes es un arreglo válido
        if (is_array($firmantes) || is_object($firmantes)) {?>
            <table class="table" style="border-collapse: separate;border-spacing: 20px 10px;">
                <?php
                // Iterar sobre cada elemento del arreglo
                foreach ($firmantes as $index => $firma) {
                    // Abrir una nueva fila cada dos firmantes
                    if ($index % 2 == 0) {
                        echo '<tr>';
                    }
                    ?>
                <td class="text-center" style="width:250px;height:140px; font-size:13px; margin-top:-500px" >
                    <?php if ($firma->firma != null) { ?>
                    <img src={{ $firma->firma }} style="width:100%; max-width:130px; max-height:50px;">
                    <?php } ?>
                    <div style="font-weight: 700; text-align: center; font-size: 12px;">
                        {{ $firma->nombre_firmante ?? '-' }}
                    </div>
                    <div style="text-align:center" font-size: 12px;">
                        {{ $firma->dependencia ?? '-' }}
                   </div>
                    <br>
                </td>
                <?php
                    // Cerrar la fila después de cada par de firmantes o al final del array
                    if ($index % 2 == 1 || $index == count($firmantes) - 1) {
                        echo '</tr>'; // Cerrar la fila
                    }
                }
                ?>
            </table>
            <?php
        } else {
            ?>
            @if ($result->municipio == 'Arandas')
            <table class="table" style="margin-top: 50px;">
            @else
            <table class="table">
            @endif
                    <tbody>
                        <tr>
                            <td style="width:250px;height:170px; font-size:13px;">

                                <?php  if(isset($firmas[1]->nombre_firmante)):?>
                                <?php if(($firmas[1]->firma)):?>
                                <img src={{ $firmas[1]->firma }} style="width:100%; max-width:130px; max-height:50px;">
                                <?php endif;?>
                                <div style="font-weight: 700;text-align:center">
                                    {{ $firmas[1]->nombre_firmante }}
                                </div>
                                <div style="text-align:center">

                                    {{ $firmas[1]->dependencia }}
                                </div>
                                <?php else:?>
                                <div style="text-align:center">
                                    {{-- {{$result_muni->direccion}} --}}
                                    {{-- Dirección de padron y licencias --}}
                                </div>
                                <?php endif;?>
                            </td>
                            <td style="width:250px;height:170px; font-size:13px;">
                                <?php if(isset($firmas[2]->nombre_firmante)):?>
                                <?php if(($firmas[2]->firma)):?>
                                <img src={{ $firmas[2]->firma }} style="width:100%; max-width:130px; max-height:50px;">
                                <?php endif;?>
                                <div style="font-weight: 700;text-align:center">
                                    {{ $firmas[2]->nombre_firmante }}
                                </div>
                                <div style="text-align:center">

                                    {{ $firmas[2]->dependencia }}
                                </div>
                                <?php endif;?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:250px;height:100px; font-size:13px;">
                                <?php if(isset($firmas[3]->nombre_firmante)):?>
                                <?php if(($firmas[3]->firma)):?>
                                <img src={{ $firmas[3]->firma }} style="width:100%; max-width:130px; max-height:50px;">
                                <?php endif;?>
                                <div style="font-weight: 700;text-align:center">
                                    {{ $firmas[3]->nombre_firmante }}
                                </div>
                                <div style="text-align:center">

                                    {{ $firmas[3]->dependencia }}
                                </div>
                                <?php endif;?>
                            </td>
                            <td style="width:250px;height:100px; font-size:13px;">
                                <?php if(isset($firmas[4]->nombre_firmante)):?>
                                <?php if(($firmas[4]->firma)):?>
                                <img src={{ $firmas[4]->firma }} style="width:100%; max-width:130px; max-height:50px;">
                                <?php endif;?>
                                <div style="font-weight: 700;text-align:center">
                                    {{ $firmas[4]->nombre_firmante }}
                                </div>
                                <div style="text-align:center">

                                    {{ $firmas[4]->dependencia }}
                                </div>
                                <?php endif;?>
                            </td>
                        </tr>
                    </tbody>
                </table>


            <?php
        }
        ?>
        </div>
        <div class="col-4" style="text-align: right;">
            <div class="qr" style="">
                <img src="data:image/png;base64,<?= $qrCode ?>" style="width:80%; max-width:175px;">
            </div>
            @if (!is_null($data->costo_licencia) && $data->costo_licencia > 0)
                <div style="font-size:10px;text-align: center; margin-top: -4px;">
                    <p>Total pago de derechos:</p>
                    <p style="margin-top:-20px">${{ $data->costo_licencia }}</p>
                </div>
            @endif
        </div>
    </div>




    <div class="row row-cols-2" style="margin-top:10px; ">




    </div>

    <div style="page-break-after:always;"></div>
    <br>
    <div class="row" style="margin-top:100px">
        <div class="col-4">
            <img src="{{ $data->img_logo }}" style="width:100%; max-width:300px;">
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
            <li>Carecer de licencia de funcionamiento.</li>
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
        <?php $d = str_replace(PHP_EOL, '<br>', $data_muni->restricciones_licencia);
        echo $d; ?> <?php if(( $data->observaciones)):?>
        /<strong>Nota particular: </strong>
        <?php echo str_replace(PHP_EOL, '<br>', $data->observaciones); ?>
        <?php endif;?>

    </div>

    <div class="row" style="font-size:12px;padding:5px;">



    </div>



</body>

</html>
