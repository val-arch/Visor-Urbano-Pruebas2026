<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <link
        href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <title>Ficha Informativa <?=date('d/m/Y H-i')?> </title>
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

        .box-pdf {
            width: 100%;
            height: 7.5in;
            /* max-width: 800px; */
            margin: 5px;
            padding: 20px;
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
            border: solid 2px black;
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
            margin: auto !important;
            display: block !important;
            width: 250px;
            /* min-height: 70px; */
            border: solid 1px #003E76;
            text-align: center !important;
            border-radius: 5px;
            /* padding: 3px !important; */
            font-family: 'Red Hat Display', sans-serif;
            /* margin: 5px !important; */
        }
        .boxL2 {
            margin: auto !important;
            display: block !important;
            width: 400px;
            /* min-height: 70px; */
            border: solid 1px #003E76;
            text-align: center !important;
            border-radius: 5px;
            /* padding: 3px !important; */
            font-family: 'Red Hat Display', sans-serif;
            /* margin: 5px !important; */
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

        .row {
            position: relative;
            width: 100%;
        }

        .row [class^="col"] {
            float: left;
            margin: 0.5rem 2%;
            min-height: 0.125rem;
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

        .texto {
            width: 180px;
            /* height:40px; */
            margin-top: 10px;
            font-family: 'Red Hat Display', sans-serif;
            line-height: 10px;
            line-break: anywhere;
            word-wrap: break-word;
            text-align: center;

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
            border: 0.3px solid #003E76;
            padding: 0;
        }

        .saltopagina {
            page-break-after: always;
        }

        #watermark {
            position: fixed;
            top:15%;
            width: 100%;
            text-align: center;
            opacity: .3;
            transform: rotate(15deg);
            transform-origin: 50% 50%;
            z-index: -1000;
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
<style type="text/css">
    table {
        padding: 15px;
    }

    .tg {
        border-collapse: collapse;
        border-spacing: 0;
    }

    .tg td {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        overflow: hidden;
        /* padding: 10px 5px; */
        word-break: normal;
    }

    .tg th {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-weight: normal;
        overflow: hidden;
        /* padding: 10px 5px; */
        word-break: normal;
    }

    .tg .tg-0lax {
        text-align: center;
        vertical-align: middle;
    }

</style>

<body>
    <div id="watermark">
        <img src="<?=env('APP_URL');?>images/meta_img.jpeg">
    </div>
    <footer>
        <div style="margin:10px;">
            La presente ficha es generada por la Coordinación General de Innovación Gubernamental de conformidad con sus facultades previstas en el artículo 8, fracción III del Reglamento Interno de la Jefatura de Gabinete del Gobierno del Estado de Jalisco. Toda información aquí difunda es obtenida de los Planes Parciales de Desarrollo Urbano publicados en la Gaceta Municipal respectiva y es meramente informativa y de referencia, por lo que queda sujeta a la validación de las dependencias competentes del municipio que corresponda.

        </div>
    </footer>
    {{-- <div class="box-pdf"> --}}
    <?php $count_f=1;?>
    <?php foreach ($features as $value): ?>
    <?php $count_f ++; ?>
    {{-- <?php if(count($value['properties']['normas_control_edificacion'])):?> --}}

    <?php foreach ($value['properties']['normas_control_edificacion'] as $key => $valuep):?>
    <?php if($valuep!=null){  ?>
    <table class="tg" style="text-aling:center;padding-top:30px;padding-left:-5px;padding-right:-5px;">
        <thead>
            <tr>
                <th class="tg-0lax" style="text-align:center; height:100px;">
                    <img src="data:image/png;base64,<?=$qr?>" style="width:100%; max-width:100px; padding-top:25px!important;">
                    <img src="https://visorurbano.jalisco.gob.mx/assets/images/logo.png" style="width:100%; max-width:125px; padding-top:25px!important;">

                    <div class="box">
                        <div class="box-titulo" style="font-size: 17px !important">
                            Distrito Urbano
                        </div>
                        <div class="box-text" style="font-size: 15px">
                          <b> {{$municipio->nombre}}</b>
                          <b> ({{ $value['properties']['distrito'] }})</b>
                          <?= isset($value['properties']['sub_distrito']) ? '<br><div><b style="font-size: 9px;">Sub distrito: '.$value['properties']['sub_distrito'].'</b>':'';?>
                        </div>
                    </div>
                </th>
                <th class="tg-0lax" colspan="2" rowspan="2" style="text-align: center;">
                    <div class="row">
                        <div class="boxL2">
                            <div class="box-titulo" style="font-size: 13px !important">
                                Dirección aproximada
                            </div>
                            <div class="box-text" style="font-size: 11px">
                                {{ ($direccion) }}
                            </div>
                        </div>

                    </div>

                    <img style="margin-top:15px; height:350px;" src={{($img)}}
                        alt="">
                </th>
            </tr>
            <tr>
                <td class="tg-0lax" rowspan="2" style="width: 300px; padding-top:5px;">
                    <div style="">
                        {{-- <hr> --}}
                        <div class="box">
                            <div class="box-titulo" style="font-size: 12px !important">
                                Zonificación
                            </div>
                            <div class="box-text" style="font-size: 13px">
                                {{ $value['properties']['clave_de_zonificacion'] }}
                                <div style="font-size: 9px;">
                                    {{-- {{ $features['properties']['descripcion_de_clasificacion_de_area_primaria'] }} --}}
                                    {{-- <br> --}}
                                    {{-- {{ $features['properties']['descripcion_de_uso_de_suelo'] }}
                                    {{ $features['properties']['clave_de_area_urbana'] }}
                                    {{ $features['properties']['descripcion_de_clasificacion_de_area_secundaria'] ? ', ' . $features['properties']['descripcion_de_clasificacion_de_area_secundaria'] : '' }}, --}}

                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="box">
                            <div class="box-titulo" style="font-size: 13px !important">
                                Superficie seleccionada
                            </div>
                            <div class="box-text" style="font-size: 13px">
                                <?= bcdiv($metros->area,'1',2)?> m<sup>2</sup>
                                {{-- <div style="font-size: 10px;">
                                    <b>Superficie edificada:</b>
                                    <?= bcdiv($metros->construccion,'1',2)?> m <sup>2</sup>
                                </div> --}}
                                {{-- <div style="font-size: 10px;">
                                    <b>Superficie desplante:</b>
                                    3000 m <sup>2</sup>
                                </div> --}}
                            </div>
                        </div>
                        <hr>
                        <div class="box">
                            <div class="box-titulo" style="font-size: 12px !important">
                                Normas de control de la urbanización y edificación (<?=isset($value['properties']['normas_control_edificacion'][$key]->clave_supletoria) ? $value['properties']['normas_control_edificacion'][$key]->clave_supletoria : $value['properties']['clave_act'];?>)
                            </div>
                            <div class="box-text" style="text-aling:left;padding-right:15px;">
                                <?php if(isset($value['properties']['normas_control_edificacion'][$key]->superficie_minima)):?>
                                    <div style="font-size: 10px;">
                                        <b>Superficie mínima de predio: </b>
                                        {{ $value['properties']['normas_control_edificacion'][$key]->superficie_minima }}
                                    </div>
                                <?php endif;?>
                                <?php if(isset($value['properties']['normas_control_edificacion'][$key]->frente_minimo)):?>
                                    <div style="font-size: 10px;">
                                        <b>Frente mínimo de predio (m):</b>
                                        {{ $value['properties']['normas_control_edificacion'][$key]->frente_minimo }}
                                    </div>
                                <?php endif;?>
                                <?php if(isset($value['properties']['normas_control_edificacion'][$key]->indice_edificacion)):?>
                                    <div style="font-size: 10px;">
                                        <b>Índice de edificación:</b>
                                        {{ $value['properties']['normas_control_edificacion'][$key]->indice_edificacion }}
                                    </div>
                                <?php endif;?>
                                <?php if(isset($value['properties']['normas_control_edificacion'][$key]->coeficiente_ocupacion_suelo)):?>
                                    <div style="font-size: 10px;">
                                        <b>Coeficiente de ocupación del suelo (COS):</b>
                                        {{ $value['properties']['normas_control_edificacion'][$key]->coeficiente_ocupacion_suelo }}
                                    </div>
                                <?php endif;?>
                                <?php if(isset($value['properties']['normas_control_edificacion'][$key]->coeficiente_utilizacion_suelo)):?>
                                    <div style="font-size: 10px;">
                                        <b>Coeficiente de utilización del suelo (CUS):</b>
                                        {{ $value['properties']['normas_control_edificacion'][$key]->coeficiente_utilizacion_suelo }}
                                    </div>
                                <?php endif;?>
                                <?php if(isset($value['properties']['normas_control_edificacion'][$key]->altura_maxima_edificacion)):?>
                                    <div style="font-size: 10px;">
                                        <b>Altura máxima de la edificación:</b>
                                        {{ $value['properties']['normas_control_edificacion'][$key]->altura_maxima_edificacion }}
                                    </div>
                                <?php endif;?>
                                <?php if(isset($value['properties']['normas_control_edificacion'][$key]->cajones_estacionamiento)):?>
                                    <div style="font-size: 10px;">
                                        <b>Cajones de estacionamiento:</b>
                                        {{ $value['properties']['normas_control_edificacion'][$key]->cajones_estacionamiento }}
                                    </div>
                                <?php endif;?>
                                <?php if(isset($value['properties']['normas_control_edificacion'][$key]->porcentaje_jardinado_restriccion_frontal)):?>
                                    <div style="font-size: 10px;">
                                        <b>Frente ajardinado (%):</b>
                                        {{ $value['properties']['normas_control_edificacion'][$key]->porcentaje_jardinado_restriccion_frontal }}
                                    </div>
                                <?php endif;?>
                                <?php if(isset($value['properties']['normas_control_edificacion'][$key]->restriccion_frontal)):?>
                                    <div style="font-size: 10px;">
                                        <b>Restricción frontal:</b>
                                        {{ $value['properties']['normas_control_edificacion'][$key]->restriccion_frontal }}
                                    </div>
                                <?php endif;?>
                                <?php if(isset($value['properties']['normas_control_edificacion'][$key]->restricciones_laterales)):?>
                                    <div style="font-size: 10px;padding-right:15px;">
                                        <b>Restricciones laterales:</b>
                                        {{ $value['properties']['normas_control_edificacion'][$key]->restricciones_laterales }}
                                    </div>
                                <?php endif;?>
                                <?php if(isset($value['properties']['normas_control_edificacion'][$key]->restriccion_posterior)):?>
                                    <div style="font-size: 10px;padding-right:15px;">
                                        <b>Restricción posterior:</b>
                                        {{ $value['properties']['normas_control_edificacion'][$key]->restriccion_posterior }}
                                    </div>
                                <?php endif;?>
                                <?php if(isset($value['properties']['normas_control_edificacion'][$key]->modo_edificacion)):?>
                                    <div style="font-size: 10px;">
                                        <b>Modo edificación:</b>
                                        {{ $value['properties']['normas_control_edificacion'][$key]->modo_edificacion }}
                                    </div>
                                <?php endif;?>
                                </div>
                            </div>
                        </div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                {{-- <td >
                <div class="box">
                  <div class="box-titulo" style="font-size: 12px !important">
                      Normas de control de la urbanización y edificación
                  </div>
                  <div class="box-text" style="font-size: 18px">

                  </div>
                </div>
              </td> --}}
              <td  colspan="2" style="width: 700px; text-align: left;vertical-align: top !important; padding-left:20px;">
                <h5 style="padding-top: -20px !important; padding-bottom:-15px !important;">Otras disposiciones</h5>
                <?= isset($value['properties']['descripcion_de_clasificacion_de_area_primaria']) || isset($value['properties']['descripcion_de_clasificacion_de_zona_secundaria'])  ? '<b>Zonificación: </b>' . (isset($value['properties']['descripcion_de_clasificacion_de_area_primaria']) ? ($value['properties']['descripcion_de_clasificacion_de_area_primaria']) : '') .' / '. (isset($value['properties']['descripcion_de_clasificacion_de_zona_secundaria']) ? ($value['properties']['descripcion_de_clasificacion_de_zona_secundaria']):'') :'' ?>
                <br>

                <?php if(isset($value['properties']['normas_control_edificacion'][0]->giro)):?>
                    <div style="font-size: 10px;padding-right:15px;" >
                    <b>Giro:</b>
                    {{ $value['properties']['normas_control_edificacion'][0]->giro }}
                    </div>
                <?php endif;?>
                <?php if(isset($value['properties']['normas_control_edificacion'][0]->observaciones)):?>
                <span style="font-size: <?= strlen($value['properties']['normas_control_edificacion'][0]->observaciones) > 700 ?  '9':'12';?>px;">
                    <?=$value['properties']['normas_control_edificacion'][0]->observaciones;?>
                <?php endif;?>
                </span>
                    <?php if(count($features)>1):?>
                    <br>
                    <div style="font-size: 10px; padding-right:15px;">
                        <?php if(isset($value['properties']['importante'])):?>
                            <b>Importante: <?=$value['properties']['importante'];?></b>
                        <?php endif;?>
                       <b>Nota: </b>La superficie seleccionada se encuentra dentro de distintas zonificaciones, por lo que se ha generado una ficha por cada una de ellas.
                    </div>
                <?php endif;?>
                </td>
            </tr>
        </tbody>
    </table>
    <?php if(count($features)>1 && count($features) >= $count_f):?>
        <div style="page-break-after:always;"></div>
    <?php endif;?>
    <?php } ?>
    <?php endforeach;?>

    {{-- </div> --}}
    {{-- <?php else:?>
    <div style="padding-left: 10%; padding-top:10%;font-size:30px;">
        No se encontró información de la zona seleccionada
    </div>
<?php endif;?> --}}
<?php endforeach; ?>
</body>
</html>
