@component('mail::message')
# Aviso

Tienes una notificación sobre tu trámite con el folio:  <b>{{$folio}}. </b> <br> Entra a tu perfil para consultarla.

@component('mail::button', ['url' => env('APP_FRONT').'ingresar?p='.base64_encode('/notificaciones/list'),'color' => 'success'])
Iniciar sesión
@endcomponent
@endcomponent
