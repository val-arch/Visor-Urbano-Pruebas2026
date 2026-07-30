import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';


import { TablasComponent } from './tablas.component';
import { FuseSharedModule } from '@fuse/shared.module';
const routes = [
    {
        path     : 'users',
        component: TablasComponent,
        redirectTo: 'users/',
        pathMatch: 'full'
    },
    {path: 'users/:id', component: TablasComponent}
];

@NgModule({
    declarations: [
        TablasComponent
    ],
    imports     : [
        RouterModule.forChild(routes),

        TranslateModule,

        FuseSharedModule
    ],
    exports     : [
        TablasComponent
    ]
})

export class TablasModule
{
}
