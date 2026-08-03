<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('notarios_publicos')->insert(
          [
            [
                'idFedatario'=> '11114',
                'nombre'=> 'JOSE ANTONIO MARTINEZ RAMOS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'COCULA',
                'direccion'=> 'AGUSTIN YAÑEZ NO.71 CENTRO',
                'cp'=> '48500',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '237152',
                'nombre'=> 'NAPOLEON GALVAN MONTAÑO ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Colotlán',
                'direccion'=> 'MORELOS 52 Colotlan Centro',
                'cp'=> '46200',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '5482',
                'nombre'=> 'MIGUEL IGNACIO SANCHEZ REYNOSO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'ESTEBAN LOERA 199 BELISARIO DOMINGUEZ',
                'cp'=> '44320',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '246456',
                'nombre'=> 'AURELIO BONIFACIO TOSCANO HERNANDEZ ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Mazamitla',
                'direccion'=> 'PORTAL REFORMA 10 Mazamitla',
                'cp'=> '49500',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '254190',
                'nombre'=> 'ENRIQUE MALDONADO PEREZ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Lagos de Moreno',
                'direccion'=> 'CALZADA PEDRO MORENO 136 Lagos de Moreno Centro',
                'cp'=> '47400',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '15642',
                'nombre'=> 'ROSA CRISTINA SEGOVIA CERVANTES',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ATOTONILCO EL ALTO',
                'direccion'=> 'DEGOLLADO 394 SAN FELIPE',
                'cp'=> '47750',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '5112',
                'nombre'=> 'RUBEN CARDENAS VARGAS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'LA BARCA',
                'direccion'=> 'HIDALGO 79 CENTRO',
                'cp'=> '47910',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '3901',
                'nombre'=> 'FILIBERTO ALVAREZ VAZQUEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ARANDAS',
                'direccion'=> 'FRANCISCO MORA NO. 83 CENTRO',
                'cp'=> '47180',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '2824',
                'nombre'=> 'JOSE PASTOR PADILLA PADILLA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'BUENOS AIRES 2770 1-A PROVIDENCIA 1A 2A Y 3A SECCION',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '19925',
                'nombre'=> 'CRESCENCIO URIBE GARCIA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'AUTLAN DE NAVARRO',
                'direccion'=> 'ANTONIO BORBON NO. 145 CENTRO',
                'cp'=> '48900',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '9959',
                'nombre'=> 'JUAN MANUEL MERCADO MORA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'CUQUIO',
                'direccion'=> 'FELIPE PLASCENCIA 80 OTRA NO ESPECIFICADA EN EL CATALOGO',
                'cp'=> '45480',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '21051',
                'nombre'=> 'JOSE SAUL PARADA JIMENEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ENCARNACION DE DIAZ',
                'direccion'=> 'HIDALGO 156 CENTRO',
                'cp'=> '47270',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '647',
                'nombre'=> 'FELIPE DE JESUS RIVERA PADILLA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ETZATLAN',
                'direccion'=> 'CALLE HIDALGO 134 CENTRO',
                'cp'=> '46500',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '7788',
                'nombre'=> 'ANTONIO BASULTO RUIZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'CHAPALA',
                'direccion'=> 'MADERO 266 CENTRO',
                'cp'=> '45900',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '3664',
                'nombre'=> 'CESAR ALEJANDRO URIBE VAZQUEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'EL GRULLO',
                'direccion'=> 'ESTANISTALO GARCIA ESPINOSA 60 SANTA CECILIA',
                'cp'=> '48740',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '268521',
                'nombre'=> 'SALVADOR MUÑOZ PEREZ ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Jalostotitlán',
                'direccion'=> 'GONZALEZ HERMOSILLO 58 Jalostotitlán Centro',
                'cp'=> '47120',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '5483',
                'nombre'=> 'JORGE EDUARDO GUTIERREZ MOYA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'JUANACATLAN',
                'direccion'=> 'RAMON CORONA 14 CENTRO',
                'cp'=> '45880',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '5570',
                'nombre'=> 'GUILLERMO RIVAS BARBA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ACATIC',
                'direccion'=> 'JUAREZ 49 CENTRO',
                'cp'=> '45470',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '22818',
                'nombre'=> 'GERARDO HINOJOSA ZEPEDA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ACATLAN DE JUAREZ',
                'direccion'=> 'CUITLAHUAC 15 OTRA NO ESPECIFICADA EN EL CATALOGO',
                'cp'=> '45700',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '12085',
                'nombre'=> 'JUAN CARLOS LOPEZ JARA ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'JOCOTEPEC',
                'direccion'=> 'HIDALGO SUR 26 A CENTRO',
                'cp'=> '45800',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '9876',
                'nombre'=> 'GUILLERMO RENTERIA GIL',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOTLAN EL GRANDE',
                'direccion'=> 'REFUGIO BARRAGAN DE TOSCANO 10 CENTRO',
                'cp'=> '49000',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '5052',
                'nombre'=> 'ARTURO ORDUÑA PADILLA ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Ayotlán',
                'direccion'=> 'HIDALGO 151 A Ayotlán',
                'cp'=> '47930',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '17490',
                'nombre'=> 'NADIR ERNESTO DE ALBA PLASCENCIA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'OCOTLAN',
                'direccion'=> 'ALVARO OBREGON 139 CENTRO',
                'cp'=> '47800',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '21774',
                'nombre'=> 'CARLOS ALBERTO GONZALEZ GONZALEZ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Poncitlán',
                'direccion'=> 'CUAUHTEMOC 286 - B Poncitlán Centro',
                'cp'=> '45950',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '4349',
                'nombre'=> 'J. ROSARIO GONZALEZ TOSTADO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZACOALCO DE TORRES',
                'direccion'=> 'GONZALEZ ORTEGA 145 CENTRO',
                'cp'=> '45750',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '14528',
                'nombre'=> 'JORGE HERNANDEZ ZEPEDA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'AV. AVILA CAMACHO 2958 2B RESIDENCIAL CONJUNTO PATRIA',
                'cp'=> '45160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '5346',
                'nombre'=> 'JOSE DE JESUS GONZALEZ CUEVAS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'EL SALTO',
                'direccion'=> 'CALLE HIDALGO 68 A OBRERA',
                'cp'=> '45680',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '24408',
                'nombre'=> 'RUBEN FLORES CASTRO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'SAN CRISTOBAL DE LA BARRANCA',
                'direccion'=> 'AVENIDA MORELOS 151 OTRA NO ESPECIFICADA EN EL CATALOGO',
                'cp'=> '45250',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '26996',
                'nombre'=> 'ANTONIO MARQUEZ ROSALES',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'SAN MARTIN HIDALGO',
                'direccion'=> '16 DE SEPTIEMBRE 99 OTRA NO ESPECIFICADA EN EL CATALOGO',
                'cp'=> '46770',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '6725',
                'nombre'=> 'JORGE CHAVEZ GALVAN ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TIZAPAN EL ALTO',
                'direccion'=> 'INDEPENDENCIA 150 CENTRO',
                'cp'=> '49400',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '8806',
                'nombre'=> 'RUBEN BARBA HERNANDEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TEPATITLAN DE MORELOS',
                'direccion'=> 'PROGRESO 53 PISO 1 CENTRO',
                'cp'=> '47600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '8169',
                'nombre'=> 'SANTIAGO GUILLERMO VARGAS NOLAN',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TECOLOTLAN',
                'direccion'=> 'CALLE AVENIDA JUAREZ 210 CENTRO',
                'cp'=> '48540',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '12939',
                'nombre'=> 'CESAR LUIS RAMIREZ CASILLAS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'SAN MIGUEL EL ALTO',
                'direccion'=> 'SANTUARIO 11 CENTRO',
                'cp'=> '47140',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '33611',
                'nombre'=> 'MARIA CARMELA CHAVEZ GALINDO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GOMEZ FARIAS',
                'direccion'=> 'RAYON 1-C CENTRO',
                'cp'=> '49120',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '4889',
                'nombre'=> 'SERGIO ANTONIO MACIAS ALDANA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TUXCUECA',
                'direccion'=> 'AV. RAMON CORONA 102-B PISO 2 OTRA NO ESPECIFICADA EN EL CATALOGO',
                'cp'=> '49440',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '28787',
                'nombre'=> 'MIGUEL ARTURO RAMIREZ GONZALEZ ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TAPALPA',
                'direccion'=> '16 DE SEPTIEMBRE 43 CENTRO',
                'cp'=> '49340',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '15776',
                'nombre'=> 'ODILON CAMPOS NAVARRO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TUXPAN',
                'direccion'=> 'AV. JOSE ANGEL CENICEROS 62 CENTRO',
                'cp'=> '49800',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '7028',
                'nombre'=> 'LETICIA MARGARITA DOMINGUEZ LOPEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TLAQUEPAQUE',
                'direccion'=> 'MORELOS 287 TLAQUEPAQUE CENTRO',
                'cp'=> '45500',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '7519',
                'nombre'=> 'CESAR OMAR GONZALEZ ACEVES ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'YAHUALICA DE GONZALEZ GALLO',
                'direccion'=> 'PORTAL JUAREZ 46 ALTOS CENTRO',
                'cp'=> '47300',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '6804',
                'nombre'=> 'JOSE HINOJOSA TORRES',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'VILLA CORONA',
                'direccion'=> 'HIDALGO NO. 168 VILLA CORONA CENTRO',
                'cp'=> '45730',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '2274',
                'nombre'=> 'JUAN PEÑA ACOSTA ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Tlajomulco de Zúñiga',
                'direccion'=> 'PROLONGACIÓN LÓPEZ MATEOS SUR 5560 Los Naranjos',
                'cp'=> '45645',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '3532',
                'nombre'=> 'JUAN PEÑA RAZO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'MAGDALENA',
                'direccion'=> 'ITURBIDE 30 MAGDALENA CENTRO',
                'cp'=> '46470',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '8469',
                'nombre'=> 'JORGE FRANCISCO PELAYO BAÑUELOS ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'El Arenal',
                'direccion'=> 'FELICIANO SANCHEZ 3 0 El Arenal',
                'cp'=> '45350',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '2088',
                'nombre'=> 'ELENO VEGA GUERRERO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TALA',
                'direccion'=> 'HERRERA Y CAIRO 65 TALA CENTRO',
                'cp'=> '45300',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '6693',
                'nombre'=> 'CARLOS CARDENAS NAVARRO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'AHUALULCO DE MERCADO',
                'direccion'=> 'AMADO NERVO 22 LOS CASCOS',
                'cp'=> '46732',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '243304',
                'nombre'=> 'ALBERTO MACIAS COMPARAN ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Jamay',
                'direccion'=> 'JUAREZ 390 Jamay Centro',
                'cp'=> '47900',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '239083',
                'nombre'=> 'NARCISO PLUTARCO LOMELI ENRIQUEZ ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'Allende 2 El Carmen',
                'cp'=> '44980',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '252407',
                'nombre'=> 'FRANCISCO MARQUEZ HERNANDEZ ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Ameca',
                'direccion'=> 'Portal Hidalgo 25 Ameca Centro',
                'cp'=> '46600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '360100',
                'nombre'=> 'FERNANDO CASTRO RUBIO ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Puerto Vallarta',
                'direccion'=> 'Río Amarillo 235 S/N Residencial Fluvial Vallarta',
                'cp'=> '48312',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '1'
            ],
            [
                'idFedatario'=> '10182',
                'nombre'=> 'ARMANDO SANCHEZ ARAMBULA',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'CALLE JUAN MANUEL 173 El Retiro',
                'cp'=> '44280',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '11054',
                'nombre'=> 'GABRIEL ABELARDO HIJAR ZULOAGA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'EFRAIN GONZALEZ LUNA NO. 1940 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '32613',
                'nombre'=> 'ANTONIO DIAZ ARIAS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'MAZAMITLA',
                'direccion'=> 'GALEANA 53-E CENTRO MAZAMITLA',
                'cp'=> '49500',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '4056',
                'nombre'=> 'LUIS RICARDO VILLASEÑOR FLORES ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'LA BARCA',
                'direccion'=> 'AV. HIDALGO 233 CENTRO',
                'cp'=> '47910',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '9398',
                'nombre'=> 'RODOLFO VALLE HERNANDEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ARANDAS',
                'direccion'=> 'VICENTE GUERRERO 66 CENTRO',
                'cp'=> '47180',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '1322',
                'nombre'=> 'LUIS ENRIQUE RAMOS BUSTILLOS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'CHAPALA',
                'direccion'=> 'DEL PARQUE OTE. 60 OTRA NO ESPECIFICADA EN EL CATALOGO',
                'cp'=> '45900',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '25264',
                'nombre'=> 'MIGUEL GUTIERREZ BARBA ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'JALOSTOTITLAN',
                'direccion'=> 'ALFREDO R. PLASCENCIA NO. 9-B CENTRO',
                'cp'=> '47120',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '47815',
                'nombre'=> 'GENARO ALVAREZ DEL TORO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOTLAN EL GRANDE',
                'direccion'=> 'FEDERICO DEL TORO 547 PLANTA ALTA CENTRO',
                'cp'=> '49000',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '16668',
                'nombre'=> 'ELIAS MORAN GONZALEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOTLANEJO',
                'direccion'=> 'JUAREZ 64 19 CENTRO',
                'cp'=> '45430',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '1245',
                'nombre'=> 'JOSE MIGUEL SANCHEZ LOPEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'OCOTLAN',
                'direccion'=> 'CALLE HIDALGO 397 CENTRO',
                'cp'=> '47800',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '11062',
                'nombre'=> 'JULIO ORTEGA SANDOVAL',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TAMAZULA DE GORDIANO',
                'direccion'=> 'DAVID Y JUAN ZAIZAR PONIENTE NO. 18 CENTRO',
                'cp'=> '49650',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '15854',
                'nombre'=> 'MARIA MARGARITA COVARRUBIAS Y RAMOS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TLAQUEPAQUE',
                'direccion'=> 'ZARAGOZA 462 OTRA NO ESPECIFICADA EN EL CATALOGO',
                'cp'=> '45501',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '224577',
                'nombre'=> 'RODOLFO GOMEZ DE LA PAZ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Puerto Vallarta',
                'direccion'=> 'HIDALGO 419 1 SEGUNDO NIVEL Puerto Vallarta Centro',
                'cp'=> '48300',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '1177',
                'nombre'=> 'JAVIER ALEJANDRO MACIAS PRECIADO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'EL SALTO',
                'direccion'=> 'CONSTITUCION 28 7 CENTRO',
                'cp'=> '45680',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '1868',
                'nombre'=> 'MARIO ANTONIO SOSA CARDENAS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'SAYULA',
                'direccion'=> 'CALLE DANIEL LARIOS CÁRDENAS 34 A CENTRO',
                'cp'=> '49300',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '11993',
                'nombre'=> 'ALFONSO TOSTADO HERMOSILLO',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'San Juan de los Lagos',
                'direccion'=> 'PORFIRIO DIAZ 10 A San Juan de los Lagos Centro',
                'cp'=> '47000',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '5864',
                'nombre'=> 'GABRIELA GARCIA MEDEL',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TEPATITLAN DE MORELOS',
                'direccion'=> 'MORELOS 78 CENTRO',
                'cp'=> '47600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '26566',
                'nombre'=> 'JOSE LUIS ORGANISTA MACIAS ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TLAJOMULCO DE ZUÑIGA',
                'direccion'=> 'FLAVIANO RAMOS NORTE 28 ZONA CENTRO',
                'cp'=> '45640',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '1826',
                'nombre'=> 'JOSE ANTONIO TORRES GONZALEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TONALA',
                'direccion'=> 'AV. TONALA 39 CENTRO',
                'cp'=> '45400',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '17709',
                'nombre'=> 'JUAN HERNANDEZ RIVAS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TAPALPA',
                'direccion'=> 'HIDALGO 273 CENTRO',
                'cp'=> '49340',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '8526',
                'nombre'=> 'HECTOR SALAZAR LIZARDI',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'YAHUALICA DE GONZALEZ GALLO',
                'direccion'=> 'CALLE ALLENDE 130 CENTRO',
                'cp'=> '47300',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '2993',
                'nombre'=> 'FABIOLA LIZETTE MURILLO VARGAS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TALA',
                'direccion'=> 'CALLE VICENTE GUERRERO 35 TALA CENTRO',
                'cp'=> '45300',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            [
                'idFedatario'=> '231308',
                'nombre'=> 'ANA ELENA TOSTADO MALACON ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'San Juan de los Lagos',
                'direccion'=> 'RITA PÉREZ DE MORENO 55 San Juan de los Lagos Centro',
                'cp'=> '47000',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '2'
            ],
            
            [
                'idFedatario'=> '2685',
                'nombre'=> 'LUIS RAMIREZ OROZCO ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'JUSTO SIERRA 2326 1ER PISO Ladrón de Guevara',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '3'
            ],
            [
                'idFedatario'=> '767',
                'nombre'=> 'FELIPE VAZQUEZ MARTIN ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AVENIDA EFRAÍN GONZÁLEZ LUNA 2273 BARRERA',
                'cp'=> '44150',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '3'
            ],
            [
                'idFedatario'=> '14809',
                'nombre'=> 'ALFREDO MORENO GONZALEZ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Lagos de Moreno',
                'direccion'=> 'LIC. VERDAD 174 Lagos de Moreno Centro',
                'cp'=> '47400',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '3'
            ],
            [
                'idFedatario'=> '425',
                'nombre'=> 'LUIS AURELIO CASILLAS FRANCO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ATOTONILCO EL ALTO',
                'direccion'=> '16 DE SEPTIEMBRE 227 CENTRO',
                'cp'=> '47750',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '3'
            ],
            [
                'idFedatario'=> '328',
                'nombre'=> 'JOSE FLORES GUTIERREZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ARANDAS',
                'direccion'=> 'NICOLAS BRAVO 46 CENTRO',
                'cp'=> '47180',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '3'
            ],
            [
                'idFedatario'=> '11567',
                'nombre'=> 'EUGENIO ALBERTO GONZALEZ VILLANUEVA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'AUTLAN DE NAVARRO',
                'direccion'=> 'ANTONIO BORBON 57 CENTRO',
                'cp'=> '48900',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '3'
            ],
            [
                'idFedatario'=> '3637',
                'nombre'=> 'ALVARO RAMOS ALATORRE',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'EULOGIO PARRA 2879 Prados de Providencia',
                'cp'=> '44670',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '3'
            ],
            [
                'idFedatario'=> '15206',
                'nombre'=> 'ADRIANA VILLASEÑOR PUJOL ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Chapala',
                'direccion'=> 'CJON. DEL ARROYO 3 Ajijic Centro',
                'cp'=> '45920',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '3'
            ],
            [
                'idFedatario'=> '82',
                'nombre'=> 'LUIS MANUEL RAMIREZ PERCHES',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOTLANEJO',
                'direccion'=> 'MORELOS 32 3 CENTRO',
                'cp'=> '45430',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '3'
            ],
            [
                'idFedatario'=> '763',
                'nombre'=> 'FRANCISCO JOSE RUIZ HIGUERA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'PUERTO VALLARTA',
                'direccion'=> 'PARAGUAY 1101 CINCO DE DICIEMBRE',
                'cp'=> '48350',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '3'
            ],
            [
                'idFedatario'=> '9223',
                'nombre'=> 'CARLOS GUEVARA GOMEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TONALA',
                'direccion'=> 'LOMA TOTOTLAN SUR 7694 403 LOMA DORADA SECCION A',
                'cp'=> '45418',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '3'
            ],
            [
                'idFedatario'=> '26764',
                'nombre'=> 'LETICIA ROCIO GONZALEZ ACEVES ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'EL SALTO',
                'direccion'=> 'AVENIDA REVOLUCIÓN 12 ALVAREZ DEL CASTILLO',
                'cp'=> '45680',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '3'
            ],
         
            [
                'idFedatario'=> '745',
                'nombre'=> 'EDMUNDO MARQUEZ HERNANDEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TLAJOMULCO DE ZUÑIGA',
                'direccion'=> 'AV. SAN JOSE DEL TAJO 3 SAN JOSE DEL TAJO',
                'cp'=> '45645',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '3'
            ],
            [
                'idFedatario'=> '4578',
                'nombre'=> 'RENE MURILLO ALVIZO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TALA',
                'direccion'=> 'VICENTE GUERRERO 35 TALA CENTRO',
                'cp'=> '45300',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '3'
            ],
            [
                'idFedatario'=> '12814',
                'nombre'=> 'DIONISIO FLORES AGUILA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'FRANCISCO ZARCO 2430 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '4'
            ],
            [
                'idFedatario'=> '3620',
                'nombre'=> 'ANTONIO JOSE ALVAREZ ALVAREZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ARANDAS',
                'direccion'=> 'FRANCISCO MORA 83 CENTRO',
                'cp'=> '47180',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '4'
            ],
            [
                'idFedatario'=> '39515',
                'nombre'=> 'GABRIEL VILLALEVER GARCIA DE QUEVEDO ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Tonalá',
                'direccion'=> 'PINO SUÁREZ 77 Tonalá Centro',
                'cp'=> '45400',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '4'
            ],
            [
                'idFedatario'=> '1179',
                'nombre'=> 'JAVIER CUELLAR VAZQUEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'MIGUEL LERDO DE TEJADA 2454 ARCOS VALLARTA',
                'cp'=> '44130',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '4'
            ],
            [
                'idFedatario'=> '9161',
                'nombre'=> 'JUAN JOSE RODRIGUEZ AVILES',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'CHAPALA',
                'direccion'=> '5 DE MAYO 219 A CENTRO',
                'cp'=> '45900',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '4'
            ],
            [
                'idFedatario'=> '1244',
                'nombre'=> 'EDUARDO PAEZ CASTELL',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOTLAN EL GRANDE',
                'direccion'=> 'MIGUEL HIDALGO Y COSTILLA NO. 351 CENTRO',
                'cp'=> '49000',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '4'
            ],
            [
                'idFedatario'=> '2262',
                'nombre'=> 'REYNALDO DIAZ RAMIREZ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'San Pedro Tlaquepaque',
                'direccion'=> 'Efraín González Luna 2517 Santibáñez',
                'cp'=> '45638',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '4'
            ],
            [
                'idFedatario'=> '7166',
                'nombre'=> 'JUAN MARIO GALVAN SOTELO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'PUERTO VALLARTA',
                'direccion'=> 'CJON. DEL CALIFA - CARR. AL AEROPUERTO LOCAL 29 Ctro Com Genovesa ZONA HOTELERA LAS GLORIAS',
                'cp'=> '48333',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '4'
            ],
            [
                'idFedatario'=> '4899',
                'nombre'=> 'JOSE HERIBERTO ROJAS RIOS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TONALA',
                'direccion'=> 'CONSTITUCION NO. 80-A CENTRO',
                'cp'=> '45400',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '4'
            ],
            [
                'idFedatario'=> '75361',
                'nombre'=> 'ROBERTO MENDOZA CARDENAS ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'SAYULA',
                'direccion'=> '5 DE MAYO 33-A CENTRO',
                'cp'=> '49300',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '4'
            ],
            [
                'idFedatario'=> '25057',
                'nombre'=> 'ALBERTO JOSE MORALES SILVA',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Tepatitlán de Morelos',
                'direccion'=> '5 DE MAYO ESQ. GALEANA 96 B Tepatitlán de Morelos Centro',
                'cp'=> '47600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '4'
            ],
            [
                'idFedatario'=> '8786',
                'nombre'=> 'JOSE RAUL VAZQUEZ BRAMBILA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TEPATITLAN DE MORELOS',
                'direccion'=> 'VICENTE GUERRERO NO. 106 CENTRO',
                'cp'=> '47600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '4'
            ],
            [
                'idFedatario'=> '5560',
                'nombre'=> 'CARLOS EDMUNDO CABRERA VILLA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TLAJOMULCO DE ZUÑIGA',
                'direccion'=> 'CAMINO A LA TIJERA 20 TULIPANES',
                'cp'=> '45647',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '4'
            ],
            [
                'idFedatario'=> '4605',
                'nombre'=> 'ELIAS ESTRADA LOPEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'LUIS PEREZ VERDÍA 77 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '5'
            ],
            [
                'idFedatario'=> '7649',
                'nombre'=> 'GABRIELA VALENTINA MORENO PEREZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'LAGOS DE MORENO',
                'direccion'=> 'LICENCIADO PRIMO DE VERDAD Y RAMOS 294 CENTRO',
                'cp'=> '47400',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '5'
            ],
            [
                'idFedatario'=> '2345',
                'nombre'=> 'JOSE HUMBERTO GASCON OROZCO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. LUIS PEREZ VERDIA 77 CIRCUNVALACION GUEVARA',
                'cp'=> '44680',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '5'
            ],
            [
                'idFedatario'=> '24252',
                'nombre'=> 'SERGIO ERNESTO MACIAS AVILA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'CHAPALA',
                'direccion'=> 'AV. HIDALGO 245 D CENTRO',
                'cp'=> '45900',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '5'
            ],
            [
                'idFedatario'=> '12433',
                'nombre'=> 'LEON ELIZONDO DIAZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOTLAN EL GRANDE',
                'direccion'=> 'LAZARO CARDENAS 11 CENTRO',
                'cp'=> '49000',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '5'
            ],
            [
                'idFedatario'=> '373',
                'nombre'=> 'J FELIX FONSECA RODRIGUEZ ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'OCOTLAN',
                'direccion'=> 'ZARAGOZA 249 CENTRO',
                'cp'=> '47800',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '5'
            ],
            [
                'idFedatario'=> '4823',
                'nombre'=> 'CARLOS CASTRO SEGUNDO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'PUERTO VALLARTA',
                'direccion'=> 'RIO AMARILLO 235 RESIDENCIAL FLUVIAL VALLARTA',
                'cp'=> '48312',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '5'
            ],
            [
                'idFedatario'=> '683',
                'nombre'=> 'ENRIQUE CASILLAS FRANCO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TEPATITLAN DE MORELOS',
                'direccion'=> 'CALLE PEDRO MEDINA NO67 CENTRO',
                'cp'=> '47600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '5'
            ],
            [
                'idFedatario'=> '5016',
                'nombre'=> 'CARLOS ISIDRO SANTIAGO LOPEZ ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TONALA',
                'direccion'=> '16 DE SEPTIEMBRE NO. 209 CENTRO',
                'cp'=> '45400',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '5'
            ],
            [
                'idFedatario'=> '4193',
                'nombre'=> 'MANUEL ERNESTO SEPULVEDA SILVA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV ENRIQUE DIAZ DE LEON NORTE 2108 JARDINES DEL COUNTRY',
                'cp'=> '44210',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '6'
            ],
            [
                'idFedatario'=> '5694',
                'nombre'=> 'JUAN LOMELI GARCIA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'PEDRO LOZA 330 GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '6'
            ],
            [
                'idFedatario'=> '11143',
                'nombre'=> 'ALEJANDRO ELIZONDO VERDUZCO',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Zapotlán el Grande',
                'direccion'=> 'JOSÉ CLEMENTE OROZCO FLORES 28 Ciudad Guzmán Centro',
                'cp'=> '49000',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '6'
            ],
            [
                'idFedatario'=> '10990',
                'nombre'=> 'ADRIANA DEL CARMEN SAHAGUN MATA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'OCOTLAN',
                'direccion'=> 'ZARAGOZA NO. 214 CENTRO',
                'cp'=> '47800',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '6'
            ],
            [
                'idFedatario'=> '4881',
                'nombre'=> 'SERGIO ODILON RAMIREZ BRAMBILA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'PUERTO VALLARTA',
                'direccion'=> 'BOULEVARD FRANCISCO MEDINA ASCENCIO 1951-304 ZONA HOTELERA NORTE',
                'cp'=> '48333',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '6'
            ],
            [
                'idFedatario'=> '7402',
                'nombre'=> 'CESAR EDUARDO AGRAZ AGRAZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'TERRITORIO NACIONAL ESQ. MEXICO INDEPENDIENTE 24 RESIDENCIAL CONJUNTO PATRIA',
                'cp'=> '45160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '6'
            ],
            [
                'idFedatario'=> '2896',
                'nombre'=> 'FRANCISCO JAVIER HIDALGO Y COSTILLA HERNANDEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TONALA',
                'direccion'=> 'GALEANA 47 OTE CENTRO',
                'cp'=> '45400',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '6'
            ],
            [
                'idFedatario'=> '1434',
                'nombre'=> 'JUAN EMILIO LOMELI ACOSTA',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Zapopan',
                'direccion'=> 'Francia 1593 San Francisco',
                'cp'=> '45130',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '7'
            ],
            [
                'idFedatario'=> '3427',
                'nombre'=> 'CECILIA ODETTE ORTEGA HIJAR',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. LA PAZ 2530 T GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '7'
            ],
            [
                'idFedatario'=> '11468',
                'nombre'=> 'ELIAS AMEZCUA GONZÁLEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'PUERTO VALLARTA',
                'direccion'=> 'RIO AMARILLO 229 RESIDENCIAL FLUVIAL VALLARTA',
                'cp'=> '48312',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '7'
            ],
            [
                'idFedatario'=> '75069',
                'nombre'=> 'EDUARDO SANCHEZ ACOSTA ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'PUERTO VALLARTA',
                'direccion'=> 'PLAZA COMERCIAL EL PARÍAN DEL PUENTE LOCAL 8, NUMERO 105 CENTRO',
                'cp'=> '48300',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '7'
            ],
            [
                'idFedatario'=> '7389',
                'nombre'=> 'HECTOR BASULTO BAROCIO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'BOULEVARD PUERTA DE HIERRO 5090 PUERTA DE HIERRO',
                'cp'=> '45116',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '7'
            ],
            [
                'idFedatario'=> '719',
                'nombre'=> 'SALVADOR GUILLERMO PLAZA ARANA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'CALLE DEL PARQUE Y 482 480-482 CHAPALITA ORIENTE',
                'cp'=> '45040',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '7'
            ],
            [
                'idFedatario'=> '6376',
                'nombre'=> 'MANUEL RAMIREZ MARTINEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. LUIS PÉREZ VERDÍA 122 A LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '8'
            ],
            [
                'idFedatario'=> '29092',
                'nombre'=> 'SALVADOR COSIO GAONA ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'PABLO NERUDA 2886 PISO 2 OFICINA 6 PROVIDENCIA 1A 2A Y 3A SECCION',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '8'
            ],
            [
                'idFedatario'=> '16677',
                'nombre'=> 'CARLOS ENRIGUE ZULOAGA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'CALLE SANTA MARÍA 2396 VALLARTA NORTE',
                'cp'=> '44690',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '8'
            ],
            [
                'idFedatario'=> '46328',
                'nombre'=> 'JOSE DE JESUS RUIZ HIGUERA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'PUERTO VALLARTA',
                'direccion'=> 'BOULEVAR FRANCISCO MEDINA ASCENCIO S/N LOCAL F10 MARINA VALLARTA',
                'cp'=> '48354',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '8'
            ],
            [
                'idFedatario'=> '1749',
                'nombre'=> 'FERNANDO LOPEZ VERGARA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'LERDO DE TEJADA 1964 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '9'
            ],
            [
                'idFedatario'=> '682',
                'nombre'=> 'ALBERTO GARCIA UVENCE ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'OTTAWA 1621 Providencia 1a Secc',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '9'
            ],
           
            [
                'idFedatario'=> '3364',
                'nombre'=> 'FELIPE IGNACIO VAZQUEZ ALDANA SAUZA ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'RUIZ DE ALARCÓN 320 GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '9'
            ],
            [
                'idFedatario'=> '7726',
                'nombre'=> 'ENRIQUE TORRES JACOBO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'PUERTO VALLARTA',
                'direccion'=> 'MALECON DE LA MARINA OF 1 B CENTRO',
                'cp'=> '48300',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '9'
            ],
            [
                'idFedatario'=> '5518',
                'nombre'=> 'GUILLERMO GOMEZ VILLASEÑOR ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TONALA',
                'direccion'=> 'EMILIANO ZAPATA 60 CENTRO',
                'cp'=> '45400',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '9'
            ],
            [
                'idFedatario'=> '2196',
                'nombre'=> 'JESUS MANZANARES LEJARAZU',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'MARSELLA 446 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '10'
            ],
            [
                'idFedatario'=> '3073',
                'nombre'=> 'CARLOS HIJAR ESCAREÑO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'JESUS GARCIA 1968 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '10'
            ],
            [
                'idFedatario'=> '3008',
                'nombre'=> 'EDUARDO ROBLES IGUINIZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'RICARDO PALMA 2892 PRADOS DE PROVIDENCIA',
                'cp'=> '44670',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '10'
            ],
            [
                'idFedatario'=> '6088',
                'nombre'=> 'JOSE CORDOVA LEMUS ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TLAQUEPAQUE',
                'direccion'=> 'JUAREZ NO. 273 CENTRO',
                'cp'=> '45500',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '10'
            ],
            [
                'idFedatario'=> '847',
                'nombre'=> 'JUAN JOSE SERRATOS SALCEDO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'JOSE GUADALUPE ZUNO 2272 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '11'
            ],
            [
                'idFedatario'=> '2439',
                'nombre'=> 'FELIPE TORRES PACHECO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV LIBERTAD NO 1828 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '11'
            ],
            [
                'idFedatario'=> '7886',
                'nombre'=> 'ERNESTO NEGRETE PAEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'CORAL NO. 2706 RESIDENCIAL VICTORIA',
                'cp'=> '44560',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '11'
            ],
            [
                'idFedatario'=> '3498',
                'nombre'=> 'SARA ELISA ORTEGA GARNICA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'DIAGONAL ISABEL PRIETO 785 AYUNTAMIENTO',
                'cp'=> '44620',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '11'
            ],
            [
                'idFedatario'=> '227199',
                'nombre'=> 'CARLOS MARQUEZ RICO ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Zapopan',
                'direccion'=> 'Av. Niño Obrero 1006 Ciudad de los Niños',
                'cp'=> '45040',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '11'
            ],
            [
                'idFedatario'=> '10722',
                'nombre'=> 'OSCAR MACIEL RABAGO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'LERDO DE TEJADA NO. 1926 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '12'
            ],
         
            [
                'idFedatario'=> '1714',
                'nombre'=> 'JORGE ROBLES FARIAS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. AMERICAS 65 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '12'
            ],
            [
                'idFedatario'=> '37575',
                'nombre'=> 'JOSE LUIS AGUIRRE ANGUIANO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. PABLO NERUDA 2886 PROVIDENCIA 1A 2A Y 3A SECCION',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '12'
            ],
            
            [
                'idFedatario'=> '1598',
                'nombre'=> 'RAFAEL COVARRUBIAS FLORES',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'CRUZVERDE 674 ARTESANOS',
                'cp'=> '44200',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '13'
            ],
            [
                'idFedatario'=> '1269',
                'nombre'=> 'RAMON MENDOZA SILVA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV PLAN DE SAN LUIS NO 1788 CHAPULTEPEC COUNTRY',
                'cp'=> '44620',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '13'
            ],
            [
                'idFedatario'=> '10241',
                'nombre'=> 'JOSE GUSTAVO CHAVEZ LOZANO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'ANDADOR 20 DE NOVIEMBRE NO. 160 ZAPOPAN CENTRO',
                'cp'=> '45100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '13'
            ],
            [
                'idFedatario'=> '3129',
                'nombre'=> 'MANUEL TORRES JACOBO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TONALA',
                'direccion'=> 'CALLE CONSTITUCION 170 CENTRO',
                'cp'=> '45400',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '13'
            ],
            [
                'idFedatario'=> '2804',
                'nombre'=> 'ALBERTO FARIAS GONZALEZ RUBIO',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'PEDRO MORENO 1456 Americana',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '14'
            ],
            [
                'idFedatario'=> '11989',
                'nombre'=> 'JORGE ARTURO VAZQUEZ ORTIZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV CIRCUNVALACION DIVISION DEL NORTE NO 40 INDEPENDENCIA',
                'cp'=> '44290',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '14'
            ],
            [
                'idFedatario'=> '1131',
                'nombre'=> 'CARLOS GUILLERMO HERNANDEZ GONZALEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. TERRANOVA 720 PRADOS DE PROVIDENCIA',
                'cp'=> '44670',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '14'
            ],
            [
                'idFedatario'=> '3187',
                'nombre'=> 'SAMUEL FERNANDEZ AVILA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'CALLE AURELIO L GALLARDO 427 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '15'
            ],
            [
                'idFedatario'=> '10399',
                'nombre'=> 'SERGIO ALEJANDRO NAVARRO FLORES',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'VENUSTIANO CARRANZA NO. 96 GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '15'
            ],
            [
                'idFedatario'=> '11423',
                'nombre'=> 'JUAN MANUEL GARCIA MORQUECHO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'AV. PATRIA 2544 LAGOS DEL COUNTRY',
                'cp'=> '45177',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '15'
            ],
            [
                'idFedatario'=> '2263',
                'nombre'=> 'TERESITA DE JESUS HERNANDEZ AMAYA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AVENIDA ENRIQUE DIAZ DE LEON NORTE 2108 JARDINES DEL COUNTRY',
                'cp'=> '44210',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '16'
            ],
          
            [
                'idFedatario'=> '75455',
                'nombre'=> 'ARTURO ZAMORA JIMENEZ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'AV. LA PAZ 2611 Arcos Sur',
                'cp'=> '44500',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '16'
            ],
            [
                'idFedatario'=> '1784',
                'nombre'=> 'AGUSTIN IBARRA GARCIA DE QUEVEDO',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'AURELIO L. GALLARDO NO 109 Piso 1 Santa Teresita',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '17'
            ],
            [
                'idFedatario'=> '75188',
                'nombre'=> 'EUGENIO RODRIGO RUIZ URIBE',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'SEVERO DIAZ 16 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '17'
            ],
            [
                'idFedatario'=> '25296',
                'nombre'=> 'JOSE EDUARDO PRECIADO GALLO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'OTTAWA NO. 1134 PROVIDENCIA 1A 2A Y 3A SECCION',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '17'
            ],
            [
                'idFedatario'=> '11103',
                'nombre'=> 'LIC. SOFIA CAMARENA CORONA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. LUIS PEREZ VERDIA 195 ROJAS LADRON DE GUEVARA',
                'cp'=> '44650',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '17'
            ],
        
            [
                'idFedatario'=> '1460',
                'nombre'=> 'LUIS ROBLES BRAMBILA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AVENIDA PROVIDENCIA 2387 PROVIDENCIA 1A 2A Y 3A SECCION',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '18'
            ],
            [
                'idFedatario'=> '799',
                'nombre'=> 'RAFAEL GONZALEZ NAVARRO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TLAQUEPAQUE',
                'direccion'=> 'CALLE 16 DE SEPTIEMBRE 2 CENTRO',
                'cp'=> '45500',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '18'
            ],
         
            [
                'idFedatario'=> '215052',
                'nombre'=> 'ANGEL ZAMORA ESTRADA',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'Av. La paz 2611 Arcos Sur',
                'cp'=> '44500',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '19'
            ],
            [
                'idFedatario'=> '12992',
                'nombre'=> 'JUANAMARIA SOTELO ALONSO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'ENRIQUE GONZALEZ MARTINEZ 274 2 GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '19'
            ],
            [
                'idFedatario'=> '4929',
                'nombre'=> 'ADALBERTO ORTEGA SOLIS',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'LA PAZ 2350 Italia Providencia',
                'cp'=> '44648',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '20'
            ],
            [
                'idFedatario'=> '51167',
                'nombre'=> 'ALEJANDRO ORGANISTA PAREDES',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'REFORMA 596 Fray Antonio Alcalde',
                'cp'=> '44268',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '20'
            ],
          
            [
                'idFedatario'=> '2920',
                'nombre'=> 'SANTIAGO CAMARENA PLANCARTE',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'AV. AVILA CAMACHO PLAZA PRESIDENTES 3340 LOCAL B-14 PATRIA CONJUNTO',
                'cp'=> '45160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '20'
            ],
         
            [
                'idFedatario'=> '2367',
                'nombre'=> 'FERNANDO MANUEL RAMOS ARIAS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'AVENIDA LÓPEZ MATEOS SUR 1994 CHAPALITA SUR',
                'cp'=> '45046',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '21'
            ],
            [
                'idFedatario'=> '757',
                'nombre'=> 'JOSE ANTONIO JIMENEZ GONZALEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TLAQUEPAQUE',
                'direccion'=> 'MATAMOROS 95 CENTRO',
                'cp'=> '45500',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '21'
            ],
            [
                'idFedatario'=> '1476',
                'nombre'=> 'DAVID PARRA GRAVE',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'COLOMOS 2518 Providencia 1a Secc',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '21'
            ],
            [
                'idFedatario'=> '5276',
                'nombre'=> 'ALEJANDRO MORENO PEREZ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'COLOMOS 2468 Providencia 4a Secc',
                'cp'=> '44639',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '22'
            ],
            [
                'idFedatario'=> '9072',
                'nombre'=> 'JOSE DE JESUS BAILON CABRERA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. 16 DE SEPTIEMBRE NO. 730-600 GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '22'
            ],
      
            [
                'idFedatario'=> '212255',
                'nombre'=> 'DIEGO ROBLES FARIAS ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Zapopan',
                'direccion'=> 'Privada del Niño 676 Camino Real',
                'cp'=> '45040',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '22'
            ],
            [
                'idFedatario'=> '2289',
                'nombre'=> 'FERNANDO AGUSTIN GALLO PEREZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'MEXICALTZINGO NO. 1911 BARRERA',
                'cp'=> '44150',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '23'
            ],
            
            [
                'idFedatario'=> '8368',
                'nombre'=> 'JORGE HUMBERTO CHAVIRA MARTINEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'AV. GUADALUPE 4986 JARDINES DE GUADALUPE',
                'cp'=> '45030',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '23'
            ],
            [
                'idFedatario'=> '16316',
                'nombre'=> 'PABLO ALEJANDRO PRADO MEDINA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. MANUEL ACUÑA 2071 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '24'
            ],
         
            [
                'idFedatario'=> '2751',
                'nombre'=> 'JUAN ANTONIO JOSE CARDENAS DAVALOS ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'AVENIDA GUADALUPE 5228 JARDINES DE GUADALUPE',
                'cp'=> '45030',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '24'
            ],
            [
                'idFedatario'=> '7863',
                'nombre'=> 'LORENZO BAILON CABRERA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. AMERICAS 209 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '25'
            ],
            [
                'idFedatario'=> '6647',
                'nombre'=> 'MARIA DE LOURDES CHANES REYNOSO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'AVENIDA CUAUHTEMOC 1192 CIUDAD DEL SOL',
                'cp'=> '45050',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '25'
            ],
            [
                'idFedatario'=> '2615',
                'nombre'=> 'ANTONIO ALEJANDRO ROMERO HERNANDEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'THOMAS FULLER 555 LA PATRIA UNIVERSIDAD',
                'cp'=> '45119',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '26'
            ],
            [
                'idFedatario'=> '5189',
                'nombre'=> 'DAVID ALFARO RAMIREZ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'avenida la paz 2379 Observatorio',
                'cp'=> '44266',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '26'
            ],
            [
                'idFedatario'=> '950',
                'nombre'=> 'SALVADOR PEREZ GOMEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'COLOMOS 2598 PROVIDENCIA 1A 2A Y 3A SECCION',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '27'
            ],
            [
                'idFedatario'=> '75079',
                'nombre'=> 'REYNALDO DIAZ ARIAS ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. EFRAÍN GONZÁLEZ LUNA 2509 DESPACHO 01 ARCOS VALLARTA',
                'cp'=> '44130',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '27'
            ],
            [
                'idFedatario'=> '3870',
                'nombre'=> 'LORENZO GARCIA GARCIA MENDEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'PLANCARTE NO. 49 A CHAPALITA DE OCCIDENTE',
                'cp'=> '45030',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '27'
            ],
            [
                'idFedatario'=> '2282',
                'nombre'=> 'MARIO ENRIQUE CAMARENA OBESO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AVENIDA JUSTO SIERRA N. 2487 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '28'
            ],
            [
                'idFedatario'=> '35369',
                'nombre'=> 'JOSÉ ANTONIO CAMACHO CORTÉS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'HERRERA Y CAIRO 2597 CIRCUNVALACION VALLARTA',
                'cp'=> '44680',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '28'
            ],
            [
                'idFedatario'=> '893',
                'nombre'=> 'JAVIER HERRERA ANAYA',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'FRANCISCO JAVIER GAMBOA 285 Santa Teresita',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '29'
            ],
            [
                'idFedatario'=> '798',
                'nombre'=> 'SALVADOR OROPEZA CASILLAS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'AVENIDA PATRIA 2666 RESIDENCIAL PATRIA',
                'cp'=> '45160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '29'
            ],
            [
                'idFedatario'=> '217001',
                'nombre'=> 'RAUL ARMANDO ROBLES BECERRA',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'Jesús García 2379 Ladrón de Guevara',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '30'
            ],
            [
                'idFedatario'=> '3942',
                'nombre'=> 'JOSE ISMAEL TOLEDO LOPEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'SAN JUAN BOSCO NO. 1340 CAMINO REAL',
                'cp'=> '45040',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '30'
            ],
           
            [
                'idFedatario'=> '76541',
                'nombre'=> 'LUIS GERARDO SANDOVAL FERNANDEZ ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'JOSÉ GUADALUPE ZUNO 2103 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '31'
            ],
            [
                'idFedatario'=> '5842',
                'nombre'=> 'VICTOR GONZALEZ LUNA ORENDAIN',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AVENIDA ARCOS 747 JARDINES DEL BOSQUE',
                'cp'=> '44520',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '31'
            ],
            [
                'idFedatario'=> '6296',
                'nombre'=> 'MIGUEL HEDED MALDONADO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'AV. PATRIA 146 ATEMAJAC',
                'cp'=> '45180',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '31'
            ],
            [
                'idFedatario'=> '1785',
                'nombre'=> 'FRANCISCO JAVIER MACIAS VAZQUEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'AV NIÑO OBRERO NO 882 CIUDAD DE LOS NIÑOS',
                'cp'=> '45040',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '32'
            ],
            [
                'idFedatario'=> '276108',
                'nombre'=> 'SALVADOR GARCIA RODRIGUEZ ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'Calle Ottawa 1621 Providencia 1a Secc',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '32'
            ],
            [
                'idFedatario'=> '2225',
                'nombre'=> 'LORENZA COVARRUBIAS RADILLO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV JUSTO SIERRA 2435 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '33'
            ],
            [
                'idFedatario'=> '5883',
                'nombre'=> 'CARLOS FERNANDEZ AGRAZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'GENERAL ARTEAGA 57 SANTA FE',
                'cp'=> '45168',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '33'
            ],
            [
                'idFedatario'=> '2585',
                'nombre'=> 'RICARDO SALVADOR RODRIGUEZ VERA',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Zapopan',
                'direccion'=> 'AVENIDA TEPEYAC 4253 Ciudad Del Sol',
                'cp'=> '45050',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '34'
            ],
          
            [
                'idFedatario'=> '964',
                'nombre'=> 'HERNAN GASCON HERNANDEZ ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. LA PAZ 2601 ARCOS VALLARTA',
                'cp'=> '44130',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '36'
            ],
           
            [
                'idFedatario'=> '14203',
                'nombre'=> 'HECTOR ANTONIO MARTINEZ GONZALEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'ENRIQUE GONZALEZ MARTINEZ 274 GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '37'
            ],
            [
                'idFedatario'=> '10717',
                'nombre'=> 'MIGUEL ERNESTO NEGRETE DE ALBA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'AV. 12 DE DICIEMBRE 871 CHAPALITA ORIENTE',
                'cp'=> '45040',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '37'
            ],
            [
                'idFedatario'=> '11347',
                'nombre'=> 'JOSE IGNACIO MACIEL RABAGO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'LERDO DE TEJADA 1926 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '38'
            ],
         
            [
                'idFedatario'=> '663',
                'nombre'=> 'JOSE RODOLFO CHAVEZ DE LOS RIOS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'QUETZALCOATL 1509 CIUDAD DEL SOL',
                'cp'=> '45050',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '38'
            ],
            [
                'idFedatario'=> '4095',
                'nombre'=> 'J JESUS SANCHEZ NAVARRO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'MARIANO AZUELA 70 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '39'
            ],
            [
                'idFedatario'=> '5476',
                'nombre'=> 'RICARDO CARRILLO ROMERO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'CALLE NAPOLEÓN 453 TERRANOVA',
                'cp'=> '44689',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '39'
            ],
            [
                'idFedatario'=> '7418',
                'nombre'=> 'JOSE CARLOS MORA LOPEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'AVENIDA ABEDULES 454 LOS PINOS',
                'cp'=> '45120',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '39'
            ],
            [
                'idFedatario'=> '21524',
                'nombre'=> 'RODOLFO EDUARDO RAMOS RUIZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'MORELOS 740 GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '40'
            ],
        
            [
                'idFedatario'=> '3339',
                'nombre'=> 'SALVADOR LOPEZ VERGARA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'JOSE GUADALUPE ZUNO 1918 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '41'
            ],
         
            [
                'idFedatario'=> '1649',
                'nombre'=> 'MIGUEL FERNANDO RABAGO PRECIADO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'LIBERTAD 1725 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '42'
            ],
            [
                'idFedatario'=> '1521',
                'nombre'=> 'ALFONSO CHACON ROBLES',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'PROGRESO SUR 446 Santa Mónica',
                'cp'=> '44220',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '42'
            ],
         
            [
                'idFedatario'=> '2245',
                'nombre'=> 'JOSE ANTONIO JAIME REYNOSO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. AMERICAS 824 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '43'
            ],
            [
                'idFedatario'=> '1741',
                'nombre'=> 'FELIPE DE JESUS PRECIADO CORONADO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AVENIDA AMERICAS 91 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '43'
            ],
           
            [
                'idFedatario'=> '2525',
                'nombre'=> 'RAMON WONCHEE MONTAÑO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'CARLOS F. LANDEROS 170 203 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '44'
            ],
            
            [
                'idFedatario'=> '3041',
                'nombre'=> 'FERNANDO LOPEZ VERGARA CORCUERA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'LERDO DE TEJADA 1964 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '45'
            ],
           
            [
                'idFedatario'=> '2544',
                'nombre'=> 'LUIS VALDEZ ANGUIANO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'GARIBALDI 2428 ROJAS LADRON DE GUEVARA',
                'cp'=> '44650',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '46'
            ],
            [
                'idFedatario'=> '17966',
                'nombre'=> 'SALVADOR VILLASEÑOR MORALES',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'INDEPENDENCIA 115 1ER PISO GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '47'
            ],
           
            
            [
                'idFedatario'=> '1438',
                'nombre'=> 'ANA LAURA MAYORAL URIBE',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'MEXICALTZINGO 2162 Fray Antonio Alcalde',
                'cp'=> '44268',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '48'
            ],
            [
                'idFedatario'=> '17062',
                'nombre'=> 'RICARDO LOPEZ CAMARENA',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'AV. CHAPALITA 1256 Juan Diego',
                'cp'=> '44510',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '49'
            ],
          
            
          
            
            [
                'idFedatario'=> '68248',
                'nombre'=> 'GUILLERMO CORONADO FIGUEROA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'MORELOS 2210 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '50'
            ],
           
            
            [
                'idFedatario'=> '11984',
                'nombre'=> 'ARTURO RAMOS ALATORRE',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'Eulogio Parra 2879 Prados de Providencia',
                'cp'=> '44670',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '51'
            ],
            [
                'idFedatario'=> '22723',
                'nombre'=> 'ARTURO RAMOS ARIAS',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'EULOGIO PARRA 2879 Prados de Providencia',
                'cp'=> '44670',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '52'
            ],
           
            
            [
                'idFedatario'=> '22783',
                'nombre'=> 'MARCOS ARIAS GAMA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'JOSE GPE ZUNO 1921 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '53'
            ],
         
            
            [
                'idFedatario'=> '8152',
                'nombre'=> 'HECTOR ARCE ULLOA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. MARIANO OTERO ESQ. GRAL. SAN MARTIN 466 MODERNA',
                'cp'=> '44190',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '54'
            ],
            [
                'idFedatario'=> '76396',
                'nombre'=> 'ABELARDO SANCHEZ CASTELLANOS ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'SORRENTO 822 Italia Providencia',
                'cp'=> '44648',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '56'
            ],
            [
                'idFedatario'=> '2165',
                'nombre'=> 'ENRIQUE ROMERO GONZALEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'LEY 2810 TERRANOVA',
                'cp'=> '44689',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '56'
            ],
          
            
            [
                'idFedatario'=> '12985',
                'nombre'=> 'VICTOR FLORES MARQUEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. JOSE GUADALUPE ZUNO 2103 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '57'
            ],
          
        
            
            [
                'idFedatario'=> '217293',
                'nombre'=> 'VIDAL GONZALEZ DURAN VALENCIA ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'MEXICALTZINGO 1987 Nuevo Sur',
                'cp'=> '44240',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '58'
            ],
           
            
          
            
            [
                'idFedatario'=> '15777',
                'nombre'=> 'PEDRO RUIZ HIGUERA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'MONTEVIDEO 2772 COLOMOS PROVIDENCIA',
                'cp'=> '44660',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '60'
            ],
            [
                'idFedatario'=> '917',
                'nombre'=> 'JORGE RUIZ RODRIGUEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'JOSE GUADALUPE MONTENEGRO 2171 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '61'
            ],
          
            
            [
                'idFedatario'=> '5021',
                'nombre'=> 'JAIME EDUARDO NATERA LOPEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AVENIDA LÓPEZ MATEOS NORTE 591 LOMAS DE GUEVARA',
                'cp'=> '44657',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '61'
            ],
            [
                'idFedatario'=> '2082',
                'nombre'=> 'ADAN GODINEZ MONTES',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'AVENIDA HIDALGO 1330 Italia Providencia',
                'cp'=> '44648',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '62'
            ],
        
            [
                'idFedatario'=> '5946',
                'nombre'=> 'JESUS GUILLERMO RAMOS OROZCO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. DE LAS ROSAS 2991 CHAPALITA',
                'cp'=> '44510',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '63'
            ],
            [
                'idFedatario'=> '1037',
                'nombre'=> 'SERGIO ALEJANDRO LÓPEZ RIVERA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. LA PAZ 2469 GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '64'
            ],
           
            [
                'idFedatario'=> '810',
                'nombre'=> 'ROMUALDO SANDOVAL FERNANDEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'TLAQUEPAQUE',
                'direccion'=> 'BATALLA DE ZACATECAS NO. 3116 RESIDENCIAL REVOLUCION',
                'cp'=> '45580',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '65'
            ],
           
            [
                'idFedatario'=> '363057',
                'nombre'=> 'SERGIO BEAS CASARRUBIAS ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'Avenida Hidalgo 2292 5 Vallarta Norte',
                'cp'=> '44690',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '66'
            ],
            [
                'idFedatario'=> '2507',
                'nombre'=> 'JOSE LUIS LEAL CAMPOS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'GARIBALDI 2565 PISO 5 CIRCUNVALACION GUEVARA',
                'cp'=> '44680',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '67'
            ],
           
            [
                'idFedatario'=> '22191',
                'nombre'=> 'JOSE ALFREDO MEDINA RIESTRA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'AV. GUADALUPE 4986 A JARDINES DE GUADALUPE',
                'cp'=> '45030',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '68'
            ],
            [
                'idFedatario'=> '2371',
                'nombre'=> 'VICTOR HUGO URIBE VAZQUEZ ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AVENIDA LA PAZ 2566 ARCOS VALLARTA SUR',
                'cp'=> '44500',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '69'
            ],
           
            [
                'idFedatario'=> '323662',
                'nombre'=> 'MANUEL BAILON ZUÑIGA ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'AV 16 DE SEPTIEMBRE 730 600 Mexicaltzingo',
                'cp'=> '44180',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '70'
            ],
         
            [
                'idFedatario'=> '1373',
                'nombre'=> 'HERIBERTO RAUL SANTANA MURILLO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'LERDO DE TEJADA 2632 ARCOS VALLARTA',
                'cp'=> '44130',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '71'
            ],
            [
                'idFedatario'=> '216491',
                'nombre'=> 'LUIS GUERRERO CAMPOS ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'AVENIDA DE LA PAZ 2310 Americana',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '72'
            ],
           
            [
                'idFedatario'=> '1388',
                'nombre'=> 'JORGE ROBLES MADRIGAL',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'AMERICAS 65 6 Ladrón de Guevara',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '73'
            ],
         
          
            [
                'idFedatario'=> '2288',
                'nombre'=> 'JOSE LUIS LEAL SANABRIA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'GARIBALDI 2565 PISO 5 CIRCUNVALACION GUEVARA',
                'cp'=> '44680',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '74'
            ],
            [
                'idFedatario'=> '357813',
                'nombre'=> 'AGUSTIN MAYORAL URIBE',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'José María Vigil 2498 Italia Providencia',
                'cp'=> '44648',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '74'
            ],
            [
                'idFedatario'=> '3550',
                'nombre'=> 'CARLOS ENRIQUE GUEVARA RAMOS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'BELEN 184 GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '75'
            ],
      
            [
                'idFedatario'=> '522',
                'nombre'=> 'PABLO PRADO BLAGG',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'MANUEL ACUÑA 2071 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '76'
            ],
            [
                'idFedatario'=> '3511',
                'nombre'=> 'ALFREDO JUVENAL RAMOS GOMEZ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'BUENOS AIRES 2946 Providencia 1a Secc',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '77'
            ],
            
         
            [
                'idFedatario'=> '75063',
                'nombre'=> 'FERNANDO ANTONIO GUZMAN PEREZ PELAEZ ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AVENIDA CHAPALITA 1256 CHAPALITA',
                'cp'=> '44510',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '78'
            ],
           
            [
                'idFedatario'=> '3462',
                'nombre'=> 'JOSE GUILLERMO VALLARTA PLATA ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'CALLE COLOMOS 2516 PROVIDENCIA 1A 2A Y 3A SECCION',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '79'
            ],
            [
                'idFedatario'=> '72195',
                'nombre'=> 'MARIA ENRIQUETA ORTIZ GUERRERO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV PLAN DE SAN LUIS NO.1788 CHAPULTEPEC COUNTRY',
                'cp'=> '44620',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '80'
            ],
         
            [
                'idFedatario'=> '23890',
                'nombre'=> 'EUGENIO RODRIGO RUIZ OROZCO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'SEVERO DÍAZ 16 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '81'
            ],
          
            [
                'idFedatario'=> '5167',
                'nombre'=> 'HÉCTOR FRANCISCO CASTAÑEDA JIMÉNEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. NIÑOS HÉROES 2535 JARDINES DEL BOSQUE',
                'cp'=> '44520',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '82'
            ],
            [
                'idFedatario'=> '362928',
                'nombre'=> 'ALEJANDRO MEDINA RICO ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'LERDO DE TEJADA 2484 Jardines de Atemajac',
                'cp'=> '44227',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '82'
            ],
           
          
        
            [
                'idFedatario'=> '2866',
                'nombre'=> 'CARLOS CAMBEROS SANCHEZ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'EFRAIN GONZALEZ LUNA 1885 P.A. Niños Héroes',
                'cp'=> '44260',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '84'
            ],
            [
                'idFedatario'=> '14939',
                'nombre'=> 'MIGUEL EDUARDO GUTIERREZ BERUBEN',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'EULOGIO PARRA 2330 ROJAS LADRON DE GUEVARA',
                'cp'=> '44650',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '85'
            ],
         
            [
                'idFedatario'=> '4606',
                'nombre'=> 'JAIME MAYTORENA MARTINEZ NEGRETE',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'JUSTO SIERRA NO 2768 VALLARTA NORTE',
                'cp'=> '44690',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '86'
            ],
            [
                'idFedatario'=> '8376',
                'nombre'=> 'JUAN CARLOS VAZQUEZ MARTIN',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV MANUEL ACUÑA 2670 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '87'
            ],
            [
                'idFedatario'=> '666',
                'nombre'=> 'EDUARDO RAMOS MENCHACA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'MORELOS NO. 740 GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '88'
            ],
            [
                'idFedatario'=> '5358',
                'nombre'=> 'ALFREDO RAMOS RUIZ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'BUENOS AIRES ESQ. AV. TERRANOVA 2946 Providencia 1a Secc',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '89'
            ],
            [
                'idFedatario'=> '7378',
                'nombre'=> 'JAIME ERNESTO DE JESUS ACOSTA ESPINOSA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'CALLE NICOLÁS LEAÑO 83 ARCOS VALLARTA',
                'cp'=> '44130',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '90'
            ],
            [
                'idFedatario'=> '1189',
                'nombre'=> 'OSCAR ALVAREZ DEL TORO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AURELIO L. GALLARDO 272 ROJAS LADRON DE GUEVARA',
                'cp'=> '44650',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '91'
            ],
            [
                'idFedatario'=> '22454',
                'nombre'=> 'JOSE RAFAEL GUTIERREZ CORNEJO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'LOPEZ COTILLA 2065 ARCOS VALLARTA',
                'cp'=> '44130',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '92'
            ],
            [
                'idFedatario'=> '7057',
                'nombre'=> 'RAYMUNDO DIONISIO CALDERON REYNOSO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'PEDRO LOZA NO. 330 GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '93'
            ],
            [
                'idFedatario'=> '2462',
                'nombre'=> 'JORGE GONZALEZ VILLANUEVA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. LÓPEZ MATEOS NTE. 591 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '94'
            ],
            [
                'idFedatario'=> '1739',
                'nombre'=> 'MARIO ADRIAN FLORES TOPETE',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. LOS ARCOS NO. 169 ARCOS VALLARTA',
                'cp'=> '44130',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '95'
            ],
            [
                'idFedatario'=> '5415',
                'nombre'=> 'ADRIAN TALAMANTES LOBATO',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'COLOMOS 2468 Providencia 4a Secc',
                'cp'=> '44639',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '96'
            ],
            [
                'idFedatario'=> '1160',
                'nombre'=> 'ALBERTO GARCIA RUVALCABA',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'HIDALGO 1769 Santa Teresita',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '97'
            ],
            [
                'idFedatario'=> '231574',
                'nombre'=> 'MARTHA GLORIA GOMEZ HERNANDEZ ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'LÓPEZ MATEOS 591 Ladrón de Guevara',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '98'
            ],
            [
                'idFedatario'=> '10994',
                'nombre'=> 'JOSE MORA LUNA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'JUSTO SIERRA 2487 ROJAS LADRON DE GUEVARA',
                'cp'=> '44650',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '99'
            ],
            [
                'idFedatario'=> '362797',
                'nombre'=> 'RAFAEL RAMOS MENCHACA ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'AVENIDA AMERICAS 1500 PISO 12 Country Club',
                'cp'=> '44610',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '99'
            ],
            [
                'idFedatario'=> '1572',
                'nombre'=> 'JOSE HORACIO DE LA SALUD RAMOS RAMOS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'PEDRO MORENO 1743 1 LAFAYETTE',
                'cp'=> '44140',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '100'
            ],
            [
                'idFedatario'=> '41551',
                'nombre'=> 'RAFAEL CASTELLANOS ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'RICARDO PALMA 2725 PROVIDENCIA 1A 2A Y 3A SECCION',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '101'
            ],
            [
                'idFedatario'=> '4042',
                'nombre'=> 'JAVIER OSCAR RODRIGUEZ LOMELI ZEPEDA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'OSTIA 2495 PROVIDENCIA 1A 2A Y 3A SECCION',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '102'
            ],
            [
                'idFedatario'=> '34946',
                'nombre'=> 'RUBEN ALBERTO SANTANA MURILLO',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'Avenida Lerdo de Tejada 2632 La Normal',
                'cp'=> '44260',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '103'
            ],
            [
                'idFedatario'=> '11741',
                'nombre'=> 'EDUARDO GARCIA PEREZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. MEXICO 3ER PISO 2222 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '104'
            ],
            [
                'idFedatario'=> '4763',
                'nombre'=> 'LUIS CORNEJO GOMEZ ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'VALLARTA 2031 OBRERA CENTRO',
                'cp'=> '44140',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '105'
            ],
            [
                'idFedatario'=> '2080',
                'nombre'=> 'JAVIER LOZANO CASILLAS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'JOSE GUADALUPE MONTENEGRO 2145 BARRERA',
                'cp'=> '44150',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '106'
            ],
            [
                'idFedatario'=> '743',
                'nombre'=> 'ADRIANA MERCADO RUIZ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'JOSE MARIA VIGIL 867 Fray Antonio Alcalde',
                'cp'=> '44268',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '107'
            ],
            [
                'idFedatario'=> '54309',
                'nombre'=> 'DIEGO TORRES SOULE',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AVENIDA LIBERTAD 1828 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '108'
            ],
            [
                'idFedatario'=> '4410',
                'nombre'=> 'JORGE GOMEZ CARREON',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. LUIS PEREZ VERDIA 136 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '109'
            ],
            [
                'idFedatario'=> '11660',
                'nombre'=> 'FERNANDO GUILLERMO PONCE GONZALEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. ALEMANIA 1351 MODERNA',
                'cp'=> '44190',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '110'
            ],
            [
                'idFedatario'=> '5775',
                'nombre'=> 'PEDRO VARGAS AVALOS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'MALAGA 2439 SANTA MONICA',
                'cp'=> '44220',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '111'
            ],
            [
                'idFedatario'=> '1498',
                'nombre'=> 'SERGIO MANUEL BEAS PEREZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'JUANA DE ARCO 14 VALLARTA NORTE',
                'cp'=> '44690',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '112'
            ],
            [
                'idFedatario'=> '530',
                'nombre'=> 'ROBERTO ESPINOSA BADIAL',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'JUSTO SIERRA NUM. 3022 VALLARTA SAN LUCAS',
                'cp'=> '44690',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '113'
            ],
            [
                'idFedatario'=> '8178',
                'nombre'=> 'RAFAEL VARGAS ACEVES',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'NIÑO OBRERO NUM. 1016 CIUDAD DE LOS NIÑOS',
                'cp'=> '45040',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '114'
            ],
            [
                'idFedatario'=> '983',
                'nombre'=> 'JUAN DIEGO RAMOS URIARTE',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AVENIDA HIDALGO NO 2005 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '115'
            ],
            [
                'idFedatario'=> '1172',
                'nombre'=> 'JUAN JOSE SERRATOS CERVANTES',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'JOSE GUADALUPE ZUNO 2272 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '116'
            ],
            [
                'idFedatario'=> '1058',
                'nombre'=> 'RODOLFO RAMOS MENCHACA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. DE LAS ROSAS 2991 CHAPALITA',
                'cp'=> '44510',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '117'
            ],
            [
                'idFedatario'=> '8977',
                'nombre'=> 'OSCAR EDUARDO RAMOS REMUS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'PALERMO NO. 1025 ITALIA PROVIDENCIA',
                'cp'=> '44648',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '118'
            ],
            [
                'idFedatario'=> '4627',
                'nombre'=> 'DIEGO OLIVARES QUIROZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'CALLE LEY NO. 2685 CIRCUNVALACION VALLARTA',
                'cp'=> '44680',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '119'
            ],
            [
                'idFedatario'=> '1898',
                'nombre'=> 'GUILLERMO ALEJANDRO GATT CORONA ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'LOPEZ COTILLA 1809 LOS ARCOS SUR',
                'cp'=> '44130',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '120'
            ],
            [
                'idFedatario'=> '800',
                'nombre'=> 'RUBEN ARAMBULA CURIEL',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'MORELOS 1972 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '121'
            ],
            [
                'idFedatario'=> '935',
                'nombre'=> 'CARLOS GUTIERREZ ACEVES',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AVENIDA UNION 164 GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '122'
            ],
            [
                'idFedatario'=> '4596',
                'nombre'=> 'LUIS FERNANDO GONZALEZ LANDEROS ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'CALLE PROGRESO 379 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '123'
            ],
            [
                'idFedatario'=> '3566',
                'nombre'=> 'FERNANDO CALDERON CANALES',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'REFORMA 2503 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '124'
            ],
            [
                'idFedatario'=> '7071',
                'nombre'=> 'JORGE VILLA FLORES',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'CALLE VIDRIO 2341 BARRERA',
                'cp'=> '44150',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '125'
            ],
            [
                'idFedatario'=> '1949',
                'nombre'=> 'ALVARO GUZMAN MERINO',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'AVENIDA HIDALGO 1886 Santa Teresita',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '126'
            ],
            [
                'idFedatario'=> '7695',
                'nombre'=> 'JUAN BOSCO COVARRUBIAS GOMEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'JUSTO SIERRA 2435 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '127'
            ],
            [
                'idFedatario'=> '25635',
                'nombre'=> 'MARIO HUMBERTO TORRES VERDIN ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. MARIANO OTERO TORRE PACÍFICO NO. 1249 OF. B - 144 RINCONADA DEL BOSQUE',
                'cp'=> '44530',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '128'
            ],
            [
                'idFedatario'=> '6715',
                'nombre'=> 'JOSE RAMIRO SILVA DE LA MADRID',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. JUSTO SIERRA 2326 1ER PISO LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '129'
            ],
            [
                'idFedatario'=> '4038',
                'nombre'=> 'ROBERTO ARMANDO OROZCO ALONZO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'CALLE LEY NO. 2654 CIRCUNVALACION VALLARTA',
                'cp'=> '44680',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '130'
            ],
            [
                'idFedatario'=> '4895',
                'nombre'=> 'JOSE MARTIN HERNANDEZ NUÑO ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AVENIDA LIBERTAD 1651 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '131'
            ],
            [
                'idFedatario'=> '4207',
                'nombre'=> 'RAMIRO RUIZ CASILLAS',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. CIRCUNVALACIÓN DIVISIÓN DEL NORTE 1535 JARDINES DEL COUNTRY',
                'cp'=> '44210',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '132'
            ],
            [
                'idFedatario'=> '11261',
                'nombre'=> 'CARLOS ALBERTO HIJAR FERNANDEZ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'EFRAIN GONZALEZ LUNA NO. 1940 Americana',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '133'
            ],
            [
                'idFedatario'=> '2726',
                'nombre'=> 'ESTEBAN ROMERO VELARDE',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'LA LEY 2810 TERRANOVA',
                'cp'=> '44689',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '134'
            ],
            [
                'idFedatario'=> '9273',
                'nombre'=> 'JOSE ANTONIO GONZALEZ ROMERO',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'ZAPOPAN',
                'direccion'=> 'PROLONGACION AVENIDA AMERICAS 1194-1 AGRARIA',
                'cp'=> '45160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '135'
            ],
            [
                'idFedatario'=> '30734',
                'nombre'=> 'JOSE GUILLERMO MEZA GARCIA ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'MORELOS 2099 AMERICANA',
                'cp'=> '44160',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '136'
            ],
            [
                'idFedatario'=> '8995',
                'nombre'=> 'VICTOR MANUEL DELGADO MARQUEZ',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AVENIDA AMERICAS 880 1 COUNTRY CLUB',
                'cp'=> '44610',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '137'
            ],
            [
                'idFedatario'=> '11693',
                'nombre'=> 'SALVADOR OROZCO BECERRA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. 16 DE SEPTIEMBRE NO. 730 5O. PISO DESP. 504 GUADALAJARA CENTRO',
                'cp'=> '44100',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '138'
            ],
            [
                'idFedatario'=> '16398',
                'nombre'=> 'JOSE RUBEN HILARIO CASTELLANOS FIGUEROA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'SEVERO DIAZ NO. 16 LADRON DE GUEVARA',
                'cp'=> '44600',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '139'
            ],
            [
                'idFedatario'=> '1151',
                'nombre'=> 'JAVIER MANUEL GUTIERREZ DAVILA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. ARCOS 306 201 LOS ARCOS SUR',
                'cp'=> '44130',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '140'
            ],
            [
                'idFedatario'=> '6551',
                'nombre'=> 'SALVADOR PEGUERO HERNANDEZ',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'CALLE CORDOBA 2381 Providencia 1a Secc',
                'cp'=> '44630',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '141'
            ],
            [
                'idFedatario'=> '684',
                'nombre'=> 'ELEUTERIO VALENCIA CARRANZA',
                'estado'=> 'JALISCO',
                'delMunicipio'=> 'GUADALAJARA',
                'direccion'=> 'AV. EULOGIO PARRA 3145 MONRAZ',
                'cp'=> '44670',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '142'
            ],
            [
                'idFedatario'=> '3054',
                'nombre'=> 'ALEJANDRO ORGANISTA ZAVALA',
                'estado'=> 'Jalisco',
                'delMunicipio'=> 'Guadalajara',
                'direccion'=> 'REFORMA 596 Santa Mónica',
                'cp'=> '44220',
                'telefono'=> ' ',
                'descripcionFedatario'=> 'NOTARÍA PÚBLICA',
                'numNotaria'=> '143'
            ]
        
          ]
        );
    }
}
