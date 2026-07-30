import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Routes, RouterModule } from '@angular/router';
import { TramiteComponent } from './tramite/tramite.component';
import { TramiteEditComponent } from './tramite/tramite-edit/tramite-edit.component';
import { FuseSharedModule } from '@fuse/shared.module';
import { TranslateModule } from '@ngx-translate/core';
import { MtxGridModule, MtxLoaderModule } from '@ng-matero/extensions';
import { MatButtonModule } from '@angular/material/button';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { IniciarTramiteComponent } from './tramite/iniciar-tramite/iniciar-tramite.component';
import { ListaComponent } from './tramite/lista/lista.component';
import { ResumenComponent } from './tramite/resumen/resumen.component';
import { ResumenTramiteComponent } from './tramite/shared/resumen-tramite/resumen-tramite.component';
import { IniciarTramiteGuard } from './tramite/iniciar-tramite/iniciar-tramite.guard';
import { ResumenGuard } from './tramite/resumen/resumen.guard';
import { EmitirProrrogaComponent } from './tramite/emitir-prorroga/emitir-prorroga.component';
import { DialogHistorialConstruccionComponent } from './tramite/shared/dialog-historial-construccion/dialog-historial-construccion.component';
import { SharedModule } from '../../shared/shared.module';
import { FirmarComponent } from './tramite/shared/firmar/firmar.component';

const routes: Routes = [
  { path: 'nuevo-tramite/:folio', component: TramiteComponent, canActivate: [IniciarTramiteGuard] },
  { path: 'nuevo-tramites/:folio/:id', component: TramiteEditComponent, canActivate: [IniciarTramiteGuard] },
  { path: 'prorroga-tramites/:folio/:id/:tipo', component: TramiteEditComponent, canActivate: [IniciarTramiteGuard] },
  { path: 'editar-licencia/:folio', component: TramiteComponent, canActivate: [IniciarTramiteGuard] },
  { path: 'iniciar-tramite', component: IniciarTramiteComponent },
  { path: 'iniciar-tramite/:folio', component: IniciarTramiteComponent },
  { path: 'lista', component: ListaComponent },
  { path: 'resumen/:folio', component: ResumenComponent, canActivate: [IniciarTramiteGuard] },

];
@NgModule({
  declarations: [TramiteComponent, IniciarTramiteComponent, ListaComponent, ResumenComponent, TramiteEditComponent, ResumenTramiteComponent, EmitirProrrogaComponent, DialogHistorialConstruccionComponent, FirmarComponent],
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
export class IngresarTramiteConstruccionModule { }
