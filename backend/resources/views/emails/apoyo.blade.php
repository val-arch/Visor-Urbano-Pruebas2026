@component('mail::message')
# ¡Qué tal! Recibe un cálido saludo por parte del equipo de Visor Urbano Jalisco para compartirte lo siguiente:

<div style="text-align: justify">
    Desde la Coordinación General de Innovación Gubernamental del Estado de Jalisco tenemos excelentes noticias, ya que a partir de hoy podrás consultar en nuestra plataforma la ficha informativa de los Planes de Desarrollo Urbano en formato digital, de los siguientes municipios:
</div>

- Zapopan
- Puerto Vallarta
- Guadalajara
- Tlajomulco
- Tlaquepaque
- Tonalá
- El Salto

<br>

<div style="text-align: justify">
    Entre la información a la que podrás acceder se encuentran las normas de control de la edificación y urbanización, así como el uso de suelo de cualquier predio dentro de las ciudades mencionadas.
</div>
@component('mail::button', ['url' => 'https://youtu.be/A1ULeezy0wQ','color' => 'primary'])
CONOCE MÁS EN ESTE VIDEO
@endcomponent

<div style="text-align: justify">
Aprovecha ésta y otras herramientas que Visor Urbano Jalisco tiene para ti de forma libre, gratuita y transparente:
</div>

@component('mail::button', ['url' => env('APP_FRONT').'inicio','color' => 'success'])
EXPLORA VISOR URBANO
@endcomponent

<div style="text-align: justify">
Estamos seguros de que esta información será bastante útil por lo que pedimos su apoyo para difundirla y promover que las personas se suscriban a nuestro canal de YouTube en donde próximamente publicaremos tutoriales, avances y otras actualizaciones importantes. También, podrán compartir su experiencia en Instagram y Twitter, utilizando los hashtags #VisorUrbano y #VisorUrbanoJalisco.
</div>

<br>


<div style="text-align: justify">
Agradezco tu atención y estaré atento a cualquier retroalimentación, duda o comentario.
</div>

<br>

<div style="text-align: center">
<img src="<?=env('APP_URL').'images/firma_dir.png';?>" alt="">
</div>

@endcomponent
