import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from "@angular/router";
import { ReporteComponent } from './reporte/reporte.component';
import { MaterialModule } from '../../material.module';
import { MtxGridModule } from "@ng-matero/extensions/data-grid";
import { NgxChartsModule } from '@swimlane/ngx-charts';
import { FlexLayoutModule } from '@angular/flex-layout';
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { ReporteFichasComponent } from '../reporte/graficas/reporte-fichas/reporte-fichas.component';

const routes = [
    {
        path: "",
        component: ReporteComponent,
    },
    
];

@NgModule({
  declarations: [ReporteComponent],
  imports: [
    NgxChartsModule,
    CommonModule,
    MtxGridModule,
    FormsModule,
    ReactiveFormsModule,
    FlexLayoutModule,
    MaterialModule,
    RouterModule.forChild(routes),
  ]
})
export class ReporteAdminModule { }