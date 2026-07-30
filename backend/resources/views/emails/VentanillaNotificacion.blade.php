@component('mail::message')
# Aviso

Estimado usuario, por medio del presente le informamos que su trámite de {{$nombre_tramite}} con folio {{$folio_ingreso}} se encuentra terminado. <br>

Para recibir su autorización acuda a dependencia.

@component('mail::button', ['url' => $link])
Descarga tu resolutivo digital
@endcomponent
@endcomponent
