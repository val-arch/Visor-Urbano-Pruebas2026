import { NgModule } from "@angular/core";
import { RouterModule } from "@angular/router";
import { MtxGridModule } from "@ng-matero/extensions/data-grid";
import { CommonModule } from "@angular/common";
import { GraficasComponent } from "./graficas/graficas.component";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { NgxChartsModule } from '@swimlane/ngx-charts';
import { BarComponent } from './graficas/bar/bar.component';
import { PieComponent } from './graficas/pie/pie.component';
import { AdvancedPieComponent } from './graficas/advanced-pie/advanced-pie.component';
import { MaterialModule } from '../../material.module';
import { DetalleDialogComponent } from './graficas/detalle-dialog/detalle-dialog.component';
import { PieAdminComponent } from './graficas/pie-admin/pie-admin.component';
import { FlexLayoutModule } from '@angular/flex-layout';

const routes = [
    {
        path: "",
        component: GraficasComponent,
    },
];

@NgModule({
    declarations: [GraficasComponent, BarComponent, PieComponent, AdvancedPieComponent, DetalleDialogComponent, PieAdminComponent],
    imports: [
        NgxChartsModule,
        CommonModule,
        MtxGridModule,
        FormsModule,
        ReactiveFormsModule,
        FlexLayoutModule,
        MaterialModule,
        RouterModule.forChild(routes),
    ],
    exports: [GraficasComponent],
    entryComponents: [GraficasComponent],
})
export class ReporteModule {}
