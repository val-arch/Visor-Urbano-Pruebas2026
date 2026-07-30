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
    {{-- <img src="<?=env('APP_URL');?>logos/meta_img.jpeg"> --}}
    <img src="<?=env('APP_URL');?>images/meta_img.jpeg">
    </div>
    <footer>
        <div style="margin:10px;">
            La presente ficha es generada por la Coordinación General de Innovación Gubernamental de conformidad con sus facultades previstas en el artículo 8, fracción III del Reglamento Interno de la Jefatura de Gabinete del Gobierno del Estado de Jalisco. Toda información aquí difunda es obtenida de los Planes Parciales de Desarrollo Urbano publicados en la Gaceta Municipal respectiva y es meramente informativa y de referencia, por lo que queda sujeta a la validación de las dependencias competentes del municipio que corresponda.

        </div>
    </footer>
    <div style="padding-left: 10%; padding-top:10%;font-size:30px;">
        <div style="margin:10px;">
            <div style="text-align: center;">
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
            </div>
        </div>
        No se encontró información de la zona seleccionada
    </div>
</body>
</html>




