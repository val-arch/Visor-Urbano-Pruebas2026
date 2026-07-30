@component('mail::message')
# <b>¡Qué tal! </b>
# <b>Nos da gusto saludarte nuevamente y compartirte excelentes noticias por parte de Visor Urbano. </b>


<div style="text-align: justify">
<b>¡Gestiona los refrendos de tu municipio a través de Visor Urbano! </b>
</div>
Como sabes, en la plataforma se pueden realizar los siguientes procesos:

<li>Consultar los usos de suelo y normas de edificación de algunos municipios </li>
<li>Publicar la lista de requisitos oficiales para abrir un negocio </li>
<li>Conocer los apoyos financieros que dispone el Gobierno de Jalisco para emprender </li>
<li>Tramitar nuevas licencias de negocio</li>
<li>Subir el histórico de licencias del municipio</li>
<li>Editar datos de las licencias, así como cambiar su estatus o darlas de baja</li>
<br>

<div style="text-align: justify">
    ¡Ahora ya puedes refrendar cualquier licencia! Ya sean las gestionadas por medio de la plataforma o las emitidas previamente por el municipio.
</div>
@component('mail::button', ['url' => 'https://youtu.be/iMF8HOTo1Ec','color' => 'primary'])
Conoce cómo en este video
@endcomponent

<div style="text-align: justify">
    <b>¡Difunde Visor Urbano en tu ciudad!  </b>
</div>

<ol>
    <li>
        Usa este video para publicarlo en el sitio web oficial y en las redes sociales del municipio y que más personas conozcan los beneficios de la plataforma.
        <a href="https://www.youtube.com/watch?v=3AnbIQu1txA" target="_blank">Ver video</a>
    </li>
    <li>
        Imprime este póster y colócalo en la ventanilla municipal o en la dependencia pertinente para que las y los ciudadanos conozcan cómo utilizar la plataforma y los requisitos para tramitar o refrendar su licencia.
        <a href="<?=env('APP_URL').'formatosvisor/poster.pdf';?>" target="_blank">Descargar documento</a>
    </li>
</ol>

<br>
<div style="text-align: justify">
    <b>¡Únete a la conversación! </b> <br>
    Atendiendo su recomendación, hemos creado un grupo de Whatsapp que sólo estará disponible para representantes de gobierno en el que podrán interactuar entre ustedes, compartir sus aprendizajes, mejores prácticas e ideas sobre el uso de Visor Urbano.
</div>

@component('mail::button', ['url' => 'https://chat.whatsapp.com/BuVUHVmGhco9FJA0rj6tXu','color' => 'success'])
Da clic aquí para unirte al grupo
@endcomponent

<div style="text-align: justify">
    Recuerda que este grupo no es para atención ciudadana, por lo que te pedimos sólo compartirlo con personas relacionadas con el trámite de emisión de licencias en tu municipio.
</div>
<br>
<br>
<div style="text-align: justify">
    <b>¡Gracias por un gran 2021!</b> <br>
    Este año ha estado lleno de diversos logros y hallazgos importantes. Visor Urbano no sería un éxito sin tu colaboración y apoyo constante, y hoy podemos celebrar que:
<li><b>25</b> municipios de Jalisco ya están emitiendo licencias por Visor Urbano  y pronto estará disponible en más ciudades y estados de México </li>
<li><b>+200</b> licencias de negocio se han emitido en tan sólo unos meses </li>
<li><b>7</b> municipios ya cuentan con los Planes de Desarrollo Urbano digitalizados para su libre consulta, y debido a ello se han descargado +8,500 fichas técnicas  </li>
<li><b>+94</b> notas o artículos han mencionado a Visor Urbano como caso de éxito, en medios de comunicación de México y Latinoamérica</li>
<li><b> +5</b> municipios arrancarán 2022 con la certificación SARE otorgada por la Comisión Nacional de Mejora Regulatoria </li>
</div>
<div style="text-align: justify">
    Así, tu municipio y muchos más, ya son referentes nacionales e internacionales en temas de mejora regulatoria, transformación digital e innovación gubernamental
</div>

<br>

<div style="text-align: center; font-size:30px">
  <b>  ¡Te deseamos felices fiestas y un próspero 2022! </b>
</div>
<br>

Síguenos en redes sociales:

<a class="button button-info" href="https://twitter.com/VisorUrbanoJal" target="_blank">Twitter</a>
<a class="button button-error" href="https://www.instagram.com/visorurbanojalisco/" target="_blank">Instagram</a>





@endcomponent
