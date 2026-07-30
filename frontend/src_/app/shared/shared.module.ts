import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ResumenTramiteRevisionComponent } from "../routes/tramites/resumen/resumen-tramite-revision.component";
import { MatButtonModule } from "@angular/material/button";
import { MatCheckboxModule } from "@angular/material/checkbox";
import { MatFormFieldModule } from "@angular/material/form-field";
import { MatIconModule } from "@angular/material/icon";
import { MatInputModule } from "@angular/material/input";
import { FuseSharedModule } from "@fuse/shared.module";
import { MtxGridModule } from "@ng-matero/extensions";
import { TranslateModule } from "@ngx-translate/core";
import { NgxDatatableModule } from "@swimlane/ngx-datatable";
import { ResumenTramiteComponent } from "app/routes/ingresar-tramite/tramite/shared/resumen-tramite/resumen-tramite.component";
import { SeguimientoLicenciaComponent } from "./seguimiento-licencia/seguimiento-licencia.component";

@NgModule({
    declarations: [
        ResumenTramiteRevisionComponent,
        ResumenTramiteComponent,
        SeguimientoLicenciaComponent,  
    ],
    // providers:[ResumenTramiteRevisionComponent],
    exports: [
        ResumenTramiteRevisionComponent,
        ResumenTramiteComponent,
        SeguimientoLicenciaComponent,
    ],
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
    ],
})
export class SharedModule {}