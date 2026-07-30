import { NgModule } from "@angular/core";
import { RouterModule } from "@angular/router";
import { MtxGridModule } from "@ng-matero/extensions/data-grid";
import { CommonModule } from "@angular/common";
import { GraficasConstruccionComponent } from './graficas-construccion/graficas-construccion.component';
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { NgxChartsModule } from '@swimlane/ngx-charts';
import { BarComponent } from './graficas-construccion/bar/bar.component';
import { PieComponent } from './graficas-construccion/pie/pie.component';
import { AdvancedPieComponent } from './graficas-construccion/advanced-pie/advanced-pie.component';
import { MaterialModule } from '../../material.module';
import { DetalleDialogComponent } from './graficas-construccion/detalle-dialog/detalle-dialog.component';
import { PieAdminComponent } from './graficas-construccion/pie-admin/pie-admin.component';
import { FlexLayoutModule } from '@angular/flex-layout';


const routes = [
    {
        path: "",
        component: GraficasConstruccionComponent,
    },
];


@NgModule({
  declarations: [GraficasConstruccionComponent, BarComponent, PieComponent, AdvancedPieComponent, DetalleDialogComponent, PieAdminComponent],
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
export class ReporteConstruccionModule { }
