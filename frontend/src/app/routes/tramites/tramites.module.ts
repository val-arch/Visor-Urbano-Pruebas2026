import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';
import { FuseSharedModule } from '@fuse/shared.module';
import { NgxCopilotModule } from 'ngx-copilot';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { MtxGridModule } from '@ng-matero/extensions/data-grid';
import { MatButtonModule } from '@angular/material/button';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { CommonModule } from '@angular/common';
import { ListComponent } from './list/list.component';
import { TramitesComponent } from './tramites.component';
import { RevisionComponent } from './revision/revision.component';
import { ResumenTramiteComponent } from '../ingresar-tramite/tramite/shared/resumen-tramite/resumen-tramite.component';
import { ResumenTramiteRevisionComponent } from './resumen/resumen-tramite-revision.component';
import { SharedModule } from '../../shared/shared.module';
import { DetalleComponent } from './detalle/detalle.component';
import { OrdenPagoComponent } from './orden-pago/orden-pago.component';
import { EmitirLicenciaComponent } from './emitir-licencia/emitir-licencia.component';


const routes = [
  { path: '', component: TramitesComponent },
  { path: 'revision/:folio', component: RevisionComponent },
  { path: 'detalle/:folio', component: DetalleComponent },
  { path: 'detalle/:folio/:tipo', component: DetalleComponent },
];

@NgModule({
  declarations: [TramitesComponent, ListComponent, RevisionComponent, DetalleComponent, OrdenPagoComponent, EmitirLicenciaComponent,],
  imports: [
    CommonModule,
    TranslateModule,
    FuseSharedModule,
    MtxGridModule,
    NgxDatatableModule,
    MatButtonModule,
    MatCheckboxModule,
    MatFormFieldModule,
    MatIconModule,
    MatInputModule,
    NgxCopilotModule,
    SharedModule,
    // ScrollToModule.forRoot(),
    RouterModule.forChild(routes)
  ]
})
export class TramitesModule { }
