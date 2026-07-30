@component('mail::message')
# Aviso

Usted ha recibido esta notificación porqué un ciudadano ha ingresado un nuevo trámite de Licencia de negocio con folio de requisitos  {{$folio_requisitos}}, puede visualizar el expediente del trámite ingresando a su cuenta en la columna de ventanilla.

@component('mail::button', ['url' => env('APP_FRONT').'ingresar?p='.base64_encode('/tramites'),'color' => 'success'])
Ingresa a Visor Urbano
@endcomponent
@endcomponent
