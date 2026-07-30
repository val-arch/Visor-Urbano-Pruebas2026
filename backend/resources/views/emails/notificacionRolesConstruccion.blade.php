@component('mail::message')
# Aviso

Usted ha recibido esta notificación porqué tiene un nuevo expediente de {{$nombre_tramite}} folio {{$folio_ingreso}} en su bandeja, pendiente de revisión. <br>

Para visualizar el expediente, ingrese a su cuenta de visor urbano.


@component('mail::button', ['url' => env('APP_FRONT').'ingresar?p='.base64_encode('/tramites'),'color' => 'success'])
Ingresa a Visor Urbano
@endcomponent
@endcomponent
