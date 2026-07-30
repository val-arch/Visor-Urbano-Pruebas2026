import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';

import { FuseSharedModule } from '@fuse/shared.module';

import { LoginComponent } from './login/login.component';
import { RegisterComponent } from './register/register.component';
import { RecuperarComponent } from './recuperar/recuperar.component';

import { MatButtonModule } from '@angular/material/button';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { ChangePasswordComponent } from './change-password/change-password.component';
import { DialogCambiarContrasenaComponent } from './dialog-cambiar-contrasena/dialog-cambiar-contrasena.component';
import { LogModule } from '../logs/log/log.module';

const routes = [
    {
        path     : 'ingresar',
        component: LoginComponent
    },
    {
        path     : 'registrar',
        component: RegisterComponent
    },
    {
        path     : 'recuperar',
        component: RecuperarComponent
    },
    {
        path     : 'changePassword',
        component: ChangePasswordComponent
    },
    {
        path     : 'CambiarContrasena',
        component: DialogCambiarContrasenaComponent
    }
];

@NgModule({
    declarations: [
        RegisterComponent,
        LoginComponent,
        RecuperarComponent,
        ChangePasswordComponent,
        DialogCambiarContrasenaComponent
    ],
    imports     : [
        RouterModule.forChild(routes),
        TranslateModule,
        FuseSharedModule,
        MatButtonModule,
        MatCheckboxModule,
        MatFormFieldModule,
        MatIconModule,
        MatInputModule,
        LogModule,
    ],
    exports     : [
        RegisterComponent,  
        LoginComponent,
        RecuperarComponent,
        ChangePasswordComponent,
        DialogCambiarContrasenaComponent
    ]
})

export class AuthModule
{
}
