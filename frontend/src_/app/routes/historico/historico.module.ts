import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HistoricoLicenciaComponent } from './historico-licencia/historico-licencia.component';
import { RouterModule } from '@angular/router';
import { MatCardModule } from '@angular/material/card';
import { MtxGridModule } from '@ng-matero/extensions/data-grid';
import { MatIconModule } from '@angular/material/icon';
import {MatButtonModule} from '@angular/material/button';
import { HistoricoDialogComponent } from './historico-dialog/historico-dialog.component';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import {MatToolbarModule} from '@angular/material/toolbar'; 
import { HistoricoLicenciaDialogComponent } from './historico-licencia-dialog/historico-licencia-dialog.component';
import { HistoricoStatusDialogComponent } from './historico-status-dialog/historico-status-dialog.component';
import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';


const routes = [

  { path: '', component: HistoricoLicenciaComponent },
];
@NgModule({
  declarations: [HistoricoLicenciaComponent, HistoricoDialogComponent, HistoricoLicenciaDialogComponent, HistoricoStatusDialogComponent],
  imports: [
    CommonModule,
    MatCardModule,
    MtxGridModule,
    MatIconModule,
    MatButtonModule,
    MatFormFieldModule,
    MatInputModule,
    FormsModule,
    MatToolbarModule,
    ReactiveFormsModule,
    MatProgressSpinnerModule,
    RouterModule.forChild(routes)
  ] ,
  schemas: [ CUSTOM_ELEMENTS_SCHEMA ]
})
export class HistoricoModule { }
