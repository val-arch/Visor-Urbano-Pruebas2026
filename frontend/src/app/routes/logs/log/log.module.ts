import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HistorialComponent } from './historial/historial.component';
import { RouterModule } from '@angular/router';
import { MaterialModule } from 'app/material.module';
import { FlexLayoutModule } from '@angular/flex-layout';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MtxGridModule } from '@ng-matero/extensions';
;
const routes = [
  {
      path: "log",
      component: HistorialComponent,
  },
];

@NgModule({
  declarations: [HistorialComponent],
  imports: [
    CommonModule,
    MtxGridModule,
    FormsModule,
    ReactiveFormsModule,
    FlexLayoutModule,
    MaterialModule,
    RouterModule.forChild(routes)
  ],
  exports: [HistorialComponent],
})
export class LogModule { }
