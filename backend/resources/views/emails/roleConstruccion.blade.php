@component('mail::message')
# Aviso

El Director de Visor Urbano del Municipio de {{$municipio}} te ha designado en este correo {{$email}} con el cargo de {{$role_name}}
<br>
<br>Da clic en el siguiente enlace para aceptar el cargo:.

@component('mail::button', ['url' => env('APP_URL').'validarRoleConstruccion/'.$token.'/'.$id,'color' => 'success'])

Aceptar
@endcomponent

Gracias,<br>
@endcomponent
