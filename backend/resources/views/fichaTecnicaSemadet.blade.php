<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Ficha Informativa</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <style>
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1cm;
            margin-top: 5%;
            /** Extra personal styles **/
            background-color: #6CC04A";
 color: white !important;
            text-align: center;
            line-height: 35px;
            font-size: 8px;
            text-align: justify;

        }

        .div_1 {
            width: 326px;
            height: 91.49px;
            left: 0px;

        }

        table thead tr {
            background-color: #6CC04A;
            color: #fff;
        }


        table tbody tr:nth-child(odd) {
            background: #b4b4b442;
            color: #000;
            font-weight: 500;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border-radius: 16px 16px 0px 0px;
            overflow: hidden;
        }

        th,
        td {
            padding: 15px;

        }

        .footer {
            position: absolute;
            bottom: 0%;
            margin-top: 80%;
        }
        .page-break {
        page-break-after: always;
    }

    </style>
</head>

<body>


    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                if ($PAGE_COUNT > 1) {
                    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                    $size = 12;
                    $pageText = "Página " . $PAGE_NUM . " de " . $PAGE_COUNT;
                    $y = 15;
                    $x = 520;
                    $pdf->text($x, $y, $pageText, $font, $size);

                    // Dibujando una línea como separador
                    $pdf->line(30, 770, 570, 770, array(0,0,0), 1);

                    // Dibujando texto
                    $pdf->text(35, 760, "Este es el contenido de tu pie de página", $font, $size);
                }
            ');
        }
        </script>
    <?php $id = $data->id;
    $clave = $data->clave;
    $clave_1 = $data->clave_1;
    $nombre = $data->nombre;
    $politica = $data->politica;
    $usos = $data->usos;
    $lineamiento = $data->lineamiento;
    $criterios_data = $data->criterios;
    $estrategia_data = $data->estrategia;
    $region = $data->region;
    ?>
    <div style="width: 900px; height: 700px; position: relative; background: white">
        <div style="height: 651px; left: 349px; top: 14px; position: absolute">
            <div style="width: 650px; height: 71.22px; left: 0px; top: 0px; position: absolute">
                <div
                    style="width: 650px; height: 33.74px; left: 0px; top: 0px; position: absolute; background: #6CC04A; border-radius: 6px">
                </div>
                <div
                    style="width: 650px; height: 71.22px; left: 0px; top: 0px; position: absolute; background: rgba(217, 217, 217, 0); border-radius: 5px; border: 0.50px #848484 solid">
                </div>
                <div
                    style="width: 148.57px; height: 21.24px; left: 237.95px; top: 6.25px; position: absolute; color: white; font-size: 13px; font-family: Red Hat Display; font-weight: 500; word-wrap: break-word">
                    Semadet</div>
                <div
                    style="width: 610.54px; height: 19.99px; left: 16.25px; top: 41.23px; position: absolute; text-align: center; color: black; font-size: 12px; font-family: Red Hat Display; font-weight: 500; word-wrap: break-word">
                    Esquina con, Avenida Circunvalación Agustín Yáñez, Av Niños Héroes 2343, 44190 Guadalajara, Jal.
                </div>
            </div>
            <div style="width: 650px; height: 204.92px; left: 0px; top: 446.08px; position: absolute">
                <div
                    style="width: 650px; height: 204.92px; left: 0px; top: 0px; position: absolute; background: rgba(217, 217, 217, 0); border-radius: 5px; border: 0.50px #848484 solid">
                </div>
                <div
                    style="width: 105.62px; height: 23.74px; left: 8.12px; top: 8.75px; position: absolute; color: #6CC04A; font-size: 14px; font-family: Red Hat Display; font-weight: 700; word-wrap: break-word">
                    {{ $nombre }}</div>
                <div
                    style="width: 636.07px; height: 154.94px; left: 8.12px; top: 10px; position: absolute; color: black; font-size: 9px; font-family: Red Hat Display; font-weight: 400; word-wrap: break-word">
                    {{ $lineamiento }}<br /></div>
            </div>
            <img style="width: 650px; height: 358.61px; left: 0px; top: 79.97px; position: absolute; border-radius: 8px"
                src="{{ $urlMapa }}" />
        </div>
        <div style="height: 662px; left: 11px; top: 6px; position: absolute">
            <div style="width: 320.26px; height: 113.09px; left: 0px; top: 0px; position: absolute">
                <img src="https://visorurbano.jalisco.gob.mx/assets/images/logo.png"
                    style="width: 55%; height: 75%; left: 129px; top: 4%; position: absolute">
                <div style="">
                    <img src="data:image/png;base64,<?= $qr ?>"
                        style="width: 126.38px; height: 111.82px; left: 0px; top: 1.27px; position: absolute; background: #D9D9D9">
                </div>
            </div>
            <div style="width: 326px; height: 57.18px; left: 0px; top: 218.55px; position: absolute">
                <div
                    style="width: 326px; height: 26.68px; left: 0px; top: 0px; position: absolute; background: #6CC04A; border-radius: 6px">
                </div>
                <div
                    style="width: 326px; height: 57.18px; left: 0px; top: 0px; position: absolute; background: rgba(217, 217, 217, 0); border-radius: 5px; border: 0.50px #848484 solid">
                </div>
                <div
                    style="width: 57.44px; height: 20.33px; left: 133.56px; top: 5.08px; position: absolute; text-align: center; color: white; font-size: 12px; font-family: Red Hat Display; font-weight: 500; word-wrap: break-word">
                    Política </div>
                <div
                    style="width: 307.33px; height: 20.33px; left: 8.62px; top: 30.50px; position: absolute; text-align: center; color: black; font-size: 12px; font-family: Red Hat Display; font-weight: 600; word-wrap: break-word">
                    Aprovechamiento Urbano</div>
            </div>
            <div style="width: 326px; height: 57.18px; left: 0px; top: 282.08px; position: absolute">
                <div
                    style="width: 326px; height: 27.95px; left: 0px; top: 0px; position: absolute; background: #6CC04A; border-radius: 6px">
                </div>
                <div
                    style="width: 326px; height: 57.18px; left: 0px; top: 0px; position: absolute; background: rgba(217, 217, 217, 0); border-radius: 5px; border: 0.50px #848484 solid">
                </div>
                <div
                    style="width: 186.70px; height: 20.33px; left: 70.37px; top: 3.81px; position: absolute; text-align: center; color: white; font-size: 12px; font-family: Red Hat Display; font-weight: 500; word-wrap: break-word">
                    Region</div>
                <div
                    style="width: 307.33px; height: 20.33px; left: 8.62px; top: 33.04px; position: absolute; text-align: center; color: black; font-size: 12px; font-family: Red Hat Display; font-weight: 600; word-wrap: break-word">
                    {{ $region }}</div>
            </div>
            <div class="div_1" style=" top: 345.61px; position: absolute">
                <div
                    style="width: 326px; height: 29.22px; left: 0px; top: 0px; position: absolute; background: #6CC04A; border-radius: 6px">
                </div>
                <div class="div_1"
                    style=" top: 0px; position: absolute; background: rgba(217, 217, 217, 0); border-radius: 5px; border: 0.50px #848484 solid">
                </div>
                <div
                    style="width: 307.33px; height: 20.33px; left: 10.05px; top: 5.08px; position: absolute; text-align: center; color: white; font-size: 12px; font-family: Red Hat Display; font-weight: 500; word-wrap: break-word">
                    Uso compatible</div>
                <div
                    style="width: 311.64px; height: 54.64px; left: 10.05px; top: 31.77px; position: absolute; color: black; font-size: 10px; font-family: Red Hat Display; font-weight: 500; word-wrap: break-word">
                    {{ $usos }}</div>
            </div>
            <div style="width: 326px; height: 108px; left: 0px; top: 554px; position: absolute">
                <div
                    style="width: 326px; height: 26.68px; left: 0px; top: 0px; position: absolute; background: #6CC04A; border-radius: 6px">
                </div>
                <div
                    style="width: 326px; height: 108px; left: 0px; top: 0px; position: absolute; background: rgba(217, 217, 217, 0); border-radius: 5px; border: 0.50px #848484 solid">
                </div>
                <div
                    style="width: 307.33px; height: 20.33px; left: 10.05px; top: 3.81px; position: absolute; text-align: center; color: white; font-size: 12px; font-family: Red Hat Display; font-weight: 500; word-wrap: break-word">
                    Estrategia</div>
                <div
                    style="width: 311.64px; height: 72.43px; left: 10.05px; top: 33.04px; position: absolute; color: black; font-size: 10px; font-family: Red Hat Display; font-weight: 500; text-transform: capitalize; word-wrap: break-word">
                    {{ $estrategia_data }}
                </div>
            </div>
            <div style="width: 326px; height: 104.19px; left: 0px; top: 443.45px; position: absolute">
                <div
                    style="width: 326px; height: 26.68px; left: 0px; top: 0px; position: absolute; background: #6CC04A; border-radius: 6px">
                </div>
                <div
                    style="width: 326px; height: 104.19px; left: 0px; top: 0px; position: absolute; background: rgba(217, 217, 217, 0); border-radius: 5px; border: 0.50px #848484 solid">
                </div>
                <div
                    style="width: 307.33px; height: 20.33px; left: 10.05px; top: 3.81px; position: absolute; text-align: center; color: white; font-size: 12px; font-family: Red Hat Display; font-weight: 500; word-wrap: break-word">
                    Criterios</div>
                <div
                    style="width: 311.64px; height: 72.43px; left: 10.05px; top: 27.95px; position: absolute; color: black; font-size: 6px; font-family: Red Hat Display; font-weight: 500; text-transform: capitalize; word-wrap: break-word">
                    {{ $criterios_data }}
                </div>
            </div>
            <div class="div_1" style=" top: 120.71px; position: absolute">
                <div
                    style="width: 326px; height: 34.31px; left: 0px; top: 0px; position: absolute; background: #6CC04A; border-radius: 6px">
                </div>
                <div class="div_1"
                    style=" top: 0px; position: absolute; background: rgba(217, 217, 217, 0); border-radius: 5px; border: 0.50px #848484 solid">
                </div>
                <div
                    style="width: 249.89px; height: 21.60px; left: 38.78px; top: 7.62px; position: absolute; color: white; font-size: 13px; font-family: Red Hat Display; font-weight: 500; word-wrap: break-word">
                    Unidad de Gestión Ambiental</div>
                <div
                    style="width: 168.03px; height: 31.77px; left: 74.68px; top: 34.31px; position: absolute; text-align: center; color: black; font-size: 20px; font-family: Red Hat Display; font-weight: 500; word-wrap: break-word">
                    {{ $clave }}</div>
                <div
                    style="width: 168.03px; height: 20.33px; left: 74.68px; top: 66.07px; position: absolute; text-align: center; color: black; font-size: 14px; font-family: Red Hat Display; font-weight: 500; word-wrap: break-word">
                </div>
            </div>
        </div>
        <div style="width: 1000px; height: 56px; left: 11px; top:80%; position: absolute">
            <div style="width: 1000px; height: 56px; left: -40px; top: 80%; position: absolute; background: #6CC04A">
                <div
                    style="width: 962px; height: 12px; left: 19px; top: 13px; position: absolute; text-align: justify; color: white; font-size: 10px; font-family: Red Hat Display; font-weight: 400; word-wrap: break-word">
                    La presente ficha es generada por la Coordinación General de Innovación Gubernamental de conformidad
                    con
                    sus facultades previstas en el artículo 8, fracción III del Reglamento Interno de la Jefatura de
                    Gabinete del Gobierno del Estado de Jalisco. Toda información aquí difunda es obtenida de las
                    gacetas
                    municipales y/o de el Periodico Oficial del Estado de Jalisco.</div>
            </div>
        </div>


    </div>

    <div>

        <div style=" left: 349px; top: 8px;"  class="page-break">
            <div style="width: 100%;  ">
                <div style="width: 100%; height: 43.74px; ; background: #6CC04A; border-radius: 6px;margin-bottom:10%;">
                    <div
                        style="margin-left: 40%; width: 300px; margin-top:1px; height: 21.24px; left: 237.95px; top: 3.25px; color: white; font-size: 25px; font-family: Red Hat Display; font-weight: 500; word-wrap: break-word">
                        Tabla de Criterios</div>
                    <div>
                    </div>
                </div>
            </div>
            <table style="margin-top: 10%">
                <thead>
                    <tr>

                        <th>ID Criterio</th>
                        <th>Región</th>
                        <th>Uso</th>
                        <th>Clave</th>
                        <th>Texto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($criterios as $criterio)
                        <tr>

                            <td>{{ $criterio->id_criterio }}</td>
                            <td>{{ $criterio->region }}</td>
                            <td>{{ $criterio->uso }}</td>
                            <td>{{ $criterio->clave }}</td>
                            <td>{{ $criterio->texto }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>
        </div>

   <!--     <div  class="page-break">
            <div style="width: 100%;  ">
                <div style="width: 100%; height: 43.74px; ; background: #6CC04A; border-radius: 6px;margin-bottom:10%;">
                    <div
                        style="margin-left: 40%; width: 300px; margin-top:1px; height: 21.24px; left: 237.95px; top: 3.25px; color: white; font-size: 25px; font-family: Red Hat Display; font-weight: 500; word-wrap: break-word">
                        Tabla de Estrategias</div>
                    <div>
                    </div>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID Estrategia</th>
                        <th>Región</th>
                        <th>Tema</th>
                        <th>Estrategia</th>
                        <th>Inciso</th>
                        <th>General</th>
                        <th>Texto</th>
                    </tr>
                </thead>
                <tbody>
                   foreach ($estrategias as $estrategia)
                        <tr>
                            <td> $estrategia->id }}</td>
                            <td>{$estrategia->id_estrategia }}</td>
                            <td>{ $estrategia->region }}</td>
                            <td>{ $estrategia->tema }}</td>
                            <td>{ $estrategia->estrategia }}</td>
                            <td>{ $estrategia->inciso }}</td>
                            <td>{ $estrategia->general }}</td>
                            <td>{ $estrategia->texto }}</td>
                        </tr>
                    endforeach
                </tbody>
            </table>
        </div>
    -->

    </div>

</body>

</html>
