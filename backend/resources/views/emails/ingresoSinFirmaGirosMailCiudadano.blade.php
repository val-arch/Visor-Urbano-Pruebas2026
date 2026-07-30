@component('mail::message')
# Aviso

Usted esta recibiendo este correo porqué ha enviado a revisión un expediente de Licencia de negocio.

Su trámite se ha enviado sin firma electrónica, la dependencia revisará su expediente y en caso de cumplir con todos los requisitos, se le notificará el proceso para su ingreso oficial.

Una vez ingresado el trámite por la dependencia recibirá una notificación confirmando su ingreso y se le asignará un folio de seguimiento.

Visor Urbano {{$municipio}}.

Para un mejor seguimiento a su trámite, ingrese a su cuenta

@component('mail::button', ['url' => env('APP_FRONT').'ingresar?p='.base64_encode('/tramites'),'color' => 'success'])
Ingresa a Visor Urbano
@endcomponent
@endcomponent
