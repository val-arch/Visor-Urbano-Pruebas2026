import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';

import { FuseSharedModule } from '@fuse/shared.module';

import { MatButtonModule } from '@angular/material/button';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { MapaComponent } from './mapa.component';
import { MapaViewComponent } from './mapa-view/mapa-view.component';

import { AngularOpenlayersModule } from 'ngx-openlayers';
import { MaterialModule } from '../../material.module';
import { DialogSuccessComponent } from './dialog-success/dialog-success.component';
import { InfoComponent } from './shared/info/info.component';
import { HeaderComponent } from './shared/header/header.component';
import { SidebarComponent } from './shared/sidebar/sidebar.component';
import { SheetDibujarComponent } from './shared/sheet-dibujar/sheet-dibujar.component';
import { DialogComercialComponent } from './shared/dialog-comercial/dialog-comercial.component';
import { CapasComponent } from './shared/capas/capas.component';
import { AnalisisComercialComponent } from './shared/analisis-comercial/analisis-comercial.component';
import {MtxUtilsModule} from "@ng-matero/extensions/utils";
import { FormFichasComponent } from './shared/form-fichas/form-fichas.component';

const routes = [
    {
        path     : 'mapa',
        redirectTo: '/mapa/CUERNAVACA',
        pathMatch: 'full'
    },
    {
        path     : 'mapa/:id',
        //redirectTo: '/mapa/',
        component: MapaComponent,
    },
];

@NgModule({
    declarations: [
        MapaComponent,
        MapaViewComponent,
        DialogSuccessComponent,
        InfoComponent,
        HeaderComponent,
        SidebarComponent,
        SheetDibujarComponent,
        DialogComercialComponent,
        CapasComponent,
        AnalisisComercialComponent,
        FormFichasComponent,
    ],
    imports: [
        RouterModule.forChild(routes),
        TranslateModule,
        FuseSharedModule,
        MatButtonModule,
        MatCheckboxModule,
        AngularOpenlayersModule,
        MatFormFieldModule,
        MatIconModule,
        MatInputModule,
        MaterialModule,
        MtxUtilsModule
    ],
    exports     : [
      MapaComponent
    ]
})

export class MapaModule
{
}
