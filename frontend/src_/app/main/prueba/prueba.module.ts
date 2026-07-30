import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';


import { PruebaComponent } from './prueba.component';
import { FuseSharedModule } from '../../../@fuse/shared.module';
const routes = [
    {
        path     : 'prueba',
        component: PruebaComponent
    }
];

@NgModule({
    declarations: [
        PruebaComponent
    ],
    imports     : [
        RouterModule.forChild(routes),

        TranslateModule,

        FuseSharedModule
    ],
    exports     : [
        PruebaComponent
    ]
})

export class PruebaModule
{
}
