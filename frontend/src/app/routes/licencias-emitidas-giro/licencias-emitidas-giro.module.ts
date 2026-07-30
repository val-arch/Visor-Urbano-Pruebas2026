import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LicenciasEmitidasListComponent } from './licencias-emitidas-list/licencias-emitidas-list.component';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatCardModule } from '@angular/material/card';
import { MtxGridModule } from '@ng-matero/extensions';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatToolbarModule } from '@angular/material/toolbar';
import { RouterModule } from '@angular/router';
import { MatSlideToggleModule } from '@angular/material/slide-toggle';
import { MaterialModule } from '../../material.module';
import {MatTooltipModule} from '@angular/material/tooltip';
import {MatTabsModule} from '@angular/material/tabs';
import { MatMenuModule } from '@angular/material/menu';
import { LicenciaStatusDialogComponent } from './licencia-status-dialog/licencia-status-dialog.component';
import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { MatTableModule } from '@angular/material/table';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { MatSelectModule } from '@angular/material/select';

const routes = [

  { path: '', component: LicenciasEmitidasListComponent },
  
];
@NgModule({
  declarations: [LicenciasEmitidasListComponent, LicenciaStatusDialogComponent],
  imports: [
    CommonModule,
    MatCardModule,
    MtxGridModule,
    MatIconModule,
    MatTableModule,
    MatButtonModule,
    MatFormFieldModule,
    MatInputModule,
    FormsModule,
    MatToolbarModule,
    MatSlideToggleModule,
    ReactiveFormsModule,
    MatTooltipModule,
    MatSelectModule,
    MatTabsModule,
    MatMenuModule,
    MatProgressSpinnerModule,
    RouterModule.forChild(routes)
  ],
   schemas: [CUSTOM_ELEMENTS_SCHEMA]
})
export class LicenciasEmitidasGiroModule { }
