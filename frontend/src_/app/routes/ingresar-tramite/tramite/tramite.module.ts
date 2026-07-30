import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Routes, RouterModule } from '@angular/router';
import { TramiteComponent } from './tramite.component';
import { TramiteEditComponent } from './tramite-edit/tramite-edit.component';
import { FuseSharedModule } from '@fuse/shared.module';
import { TranslateModule } from '@ngx-translate/core';
import { MtxGridModule, MtxLoaderModule } from '@ng-matero/extensions';
import { MatButtonModule } from '@angular/material/button';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { IniciarTramiteComponent } from './iniciar-tramite/iniciar-tramite.component';

import { ListaComponent } from './lista/lista.component';

import { ResumenComponent } from './resumen/resumen.component';
import { FirmarComponent } from './shared/firmar/firmar.component';
import { ResumenTramiteComponent } from './shared/resumen-tramite/resumen-tramite.component';
import { ResumenGuard } from './resumen/resumen.guard';
import { IniciarTramiteGuard } from './iniciar-tramite/iniciar-tramite.guard';
import { SharedModule } from '../../../shared/shared.module';
import { DialogHistorialComponent } from './shared/dialog-historial/dialog-historial.component';

const routes: Routes = [
  { path: 'nuevo-tramite/:folio', component: TramiteComponent ,canActivate:[IniciarTramiteGuard]},
  { path: 'nuevo-tramites/:folio/:id', component: TramiteEditComponent ,canActivate:[IniciarTramiteGuard]},
  { path: 'editar-licencia/:folio', component: TramiteComponent ,canActivate:[IniciarTramiteGuard]},
  { path: 'iniciar-tramite', component: IniciarTramiteComponent },
  { path: 'iniciar-tramite/:folio', component: IniciarTramiteComponent },
  { path: 'lista', component: ListaComponent  },
  { path: 'resumen/:folio', component: ResumenComponent, canActivate:[ResumenGuard] },

];

@NgModule({

  declarations: [TramiteComponent, IniciarTramiteComponent,ListaComponent, ResumenComponent, FirmarComponent, TramiteEditComponent, DialogHistorialComponent, ],
  imports: [
    CommonModule,
    TranslateModule,
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
    RouterModule.forChild(routes)
  ]
})
export class TramiteModule { }
