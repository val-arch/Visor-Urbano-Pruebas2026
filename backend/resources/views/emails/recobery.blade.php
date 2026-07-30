
@component('mail::message')
# Aviso

Recuperación de contraseña
<br>
<br> Da click en el siguiente botón para conseguir una contraseña nueva.

@component('mail::button', ['url' => env('APP_FRONT').'changePassword?token='.$token,'color' => 'success'])


Recuperar Contraseña
@endcomponent

Gracias,<br>
@endcomponent
