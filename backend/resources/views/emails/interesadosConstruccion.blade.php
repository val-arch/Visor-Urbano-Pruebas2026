@component('mail::message')
# Aviso

Usted esta recibiendo este correo porqué se ha ingresado su trámite de {{$nombre_tramite}} con folio {{$folio_ingreso}} en la plataforma de Visor Urbano Construcción {{$municipio}}.

@component('mail::button', ['url' => env('APP_FRONT').'ingresar?p='.base64_encode('/tramites'),'color' => 'success'])
Ingresa a Visor Urbano
@endcomponent
@endcomponent
