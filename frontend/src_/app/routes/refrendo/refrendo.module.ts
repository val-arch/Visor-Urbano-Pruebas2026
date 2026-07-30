import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RefrendoComponent } from './refrendo.component';
import { RouterModule } from "@angular/router";
import { FuseSharedModule } from '@fuse/shared.module';
import { MtxGridModule, MtxLoaderModule } from '@ng-matero/extensions';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { MatButtonModule } from '@angular/material/button';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { SharedModule } from '@shared/shared.module';
import { MapaViewComponent } from '../mapa/mapa-view/mapa-view.component';
import { EmitirRefrendoComponent } from './emitir-refrendo/emitir-refrendo.component';
import { MatTableModule } from '@angular/material/table';
import { MatGridListModule } from '@angular/material/grid-list';
import { DialogMapaComponent } from './dialog-mapa/dialog-mapa.component';
import { RefrendoHistoricoComponent } from './refrendo-historico/refrendo-historico.component';
import { EmitirRefrendoHistoricoComponent } from './emitir-refrendo-historico/emitir-refrendo-historico.component';
import { DetalleComponent } from './detalle/detalle.component';
import { ResumenComponent } from './resumen/resumen.component';
import {AngularOpenlayersModule} from "ngx-openlayers";
const routes = [
  {
      path: ":folio",
      component: RefrendoComponent,
  },
  {
    path     : 'historico-refrendo/:folio/:tipo',
    component: RefrendoHistoricoComponent
  },  
  { path: 'detalle/:folio', component: DetalleComponent },
];

@NgModule({
  declarations: [RefrendoComponent, EmitirRefrendoComponent, DialogMapaComponent, RefrendoHistoricoComponent, EmitirRefrendoHistoricoComponent, DetalleComponent, ResumenComponent],
    imports: [
        CommonModule,
        FuseSharedModule,
        FuseSharedModule,
        MtxGridModule,
        NgxDatatableModule,
        MtxLoaderModule,
        MatButtonModule,
        MatCheckboxModule,
        MatFormFieldModule,
        MatIconModule,
        MatInputModule,
        SharedModule,
        MatGridListModule,

        RouterModule.forChild(routes),
        AngularOpenlayersModule,
    ],
  exports: [RefrendoComponent],
})
export class RefrendoModule { }
