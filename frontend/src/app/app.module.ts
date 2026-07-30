import { NgModule } from "@angular/core";
import { BrowserModule } from "@angular/platform-browser";
import { HttpClientModule, HTTP_INTERCEPTORS } from "@angular/common/http";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { RouterModule, Routes, ExtraOptions } from "@angular/router";
import { MatMomentDateModule } from "@angular/material-moment-adapter";
import { TranslateModule } from "@ngx-translate/core";
import { MaterialExtensionsModule } from "@ng-matero/extensions";
// import { ScrollToModule } from "@nicky-lenaers/ngx-scroll-to";

import { PagesModule } from "app/pages/pages.module";
import { FuseModule } from "@fuse/fuse.module";
import { FuseSharedModule } from "@fuse/shared.module";
import {
    FuseProgressBarModule,
    FuseSidebarModule,
    FuseThemeOptionsModule,
} from "@fuse/components";
import { fuseConfig } from "app/fuse-config";
import { AppComponent } from "app/app.component";
import { LayoutModule } from "app/layout/layout.module";
import { SampleModule } from "app/main/sample/sample.module";

import { MaterialModule } from "./material.module";
import { AuthGuard } from "./core/authentication/auth.guard";
import { RolesGuard } from "./core/authentication/roles.guard";
import { DefaultInterceptor } from "./core/interceptors/default.interceptor";
// import  {  PdfViewerModule  }  from  'ng2-pdf-viewer';
import { AngularOpenlayersModule } from "ngx-openlayers";
import { MapaModule } from "./routes/mapa/mapa.module";
import { AuthModule } from "./routes/auth/auth.module";
import { NotificacionesModule } from "./routes/notificaciones/notificaciones.module";
import { CommonModule } from "@angular/common";
import { LocalStorageService } from "@shared/services/storage.service";
import { ConditionPerson } from './models/user2.service'
let userInfo = new ConditionPerson();
if (userInfo.type_user == null) {
    userInfo.type_user = 1;
}
const routerOptions: ExtraOptions = {
    anchorScrolling: "enabled",
    scrollPositionRestoration: "enabled",
};
const appRoutes: Routes = [

    {
        path: "administrador",
        loadChildren: () =>
            import("./routes/administrador/administrador.module").then(
                (m) => m.AdministradorModule
            ),
        canActivate: [AuthGuard],
        canActivateChild: [AuthGuard],
    },
    {
        path: "tramite",
        loadChildren: () =>
            import("./routes/ingresar-tramite/tramite/tramite.module").then(
                (m) => m.TramiteModule
            ),
        canActivate: [AuthGuard],
        canActivateChild: [AuthGuard],
    },
    {
        path: "tramite-giros",
        loadChildren: () =>
            import("./routes/ingresar-tramite/tramite/tramite.module").then(
                (m) => m.TramiteModule
            ),
        canActivate: [AuthGuard],
        canActivateChild: [AuthGuard],
    },
    {
        path: "tramite-construccion",
        loadChildren: () =>
            import("./routes/ingresar-tramite-construccion/ingresar-tramite-construccion.module").then(
                (m) => m.IngresarTramiteConstruccionModule
            ),
        canActivate: [AuthGuard],
        canActivateChild: [AuthGuard],
    },
    {
        path: "tramites",
        loadChildren: () =>
            import("./routes/tramites/tramites.module").then(
                (m) => m.TramitesModule
            ),
        canActivate: [AuthGuard],
        canActivateChild: [AuthGuard],
    },
    {
        path: "tramites-construccion",
        loadChildren: () =>
            import("./routes/tramites-contruccion/tramites-contruccion.module").then(
                (m) => m.TramitesContruccionModule
            ),
        canActivate: [AuthGuard],
        canActivateChild: [AuthGuard],
    },
    {
        path: "historico",
        loadChildren: () =>
            import("./routes/historico/historico.module").then(
                (m) => m.HistoricoModule
            ),
        canActivate: [AuthGuard],
        canActivateChild: [AuthGuard],
    },
    {
        path: "licencias-emitidas",
        loadChildren: () =>
            import(
                "./routes/licencias-emitidas-giro/licencias-emitidas-giro.module"
            ).then((m) => m.LicenciasEmitidasGiroModule),
        canActivate: [AuthGuard, RolesGuard],
        canActivateChild: [AuthGuard, RolesGuard],
    },
    {
        path: "reportes",
        loadChildren: () =>
            import("./routes/reporte/reporte.module").then(
                (m) => m.ReporteModule
            ),
        canActivate: [AuthGuard, RolesGuard],
        canActivateChild: [AuthGuard, RolesGuard],
    },
    {
        path: "refrendo",
        loadChildren: () =>
            import("./routes/refrendo/refrendo.module").then(
                (m) => m.RefrendoModule
            ),
        canActivate: [AuthGuard, RolesGuard],
        canActivateChild: [AuthGuard, RolesGuard],
    },
    {
        path: 'logs',
        loadChildren: () => import('./routes/logs/log/log.module').then(m => m.LogModule),
        canActivate: [AuthGuard, RolesGuard],
        canActivateChild: [AuthGuard, RolesGuard],
    },
    {
        path: "**",
        redirectTo: "inicio",
    },
];
const appRoutes2: Routes = [
    {
        path: "administrador",
        loadChildren: () =>
            import("./routes/administrador-construccion/administrador-construccion.module").then(
                (m) => m.AdministradorConstruccionModule
            ),
        canActivate: [AuthGuard],
        canActivateChild: [AuthGuard],
    },
    {
        path: "tramite",
        loadChildren: () =>
            import("./routes/ingresar-tramite-construccion/ingresar-tramite-construccion.module").then(
                (m) => m.IngresarTramiteConstruccionModule
            ),
        canActivate: [AuthGuard],
        canActivateChild: [AuthGuard],
    },
    {
        path: "tramite-giros",
        loadChildren: () =>
            import("./routes/ingresar-tramite/tramite/tramite.module").then(
                (m) => m.TramiteModule
            ),
        canActivate: [AuthGuard],
        canActivateChild: [AuthGuard],
    },
    {
        path: "tramite-construccion",
        loadChildren: () =>
            import("./routes/ingresar-tramite-construccion/ingresar-tramite-construccion.module").then(
                (m) => m.IngresarTramiteConstruccionModule
            ),
        canActivate: [AuthGuard],
        canActivateChild: [AuthGuard],
    },
    {
        path: "tramites",
        loadChildren: () =>
            import("./routes/tramites-contruccion/tramites-contruccion.module").then(
                (m) => m.TramitesContruccionModule
            ),
        canActivate: [AuthGuard],
        canActivateChild: [AuthGuard],
    },
    {
        path: "tramites-giros",
        loadChildren: () =>
            import("./routes/tramites/tramites.module").then(
                (m) => m.TramitesModule
            ),
        canActivate: [AuthGuard],
        canActivateChild: [AuthGuard],
    },
    {
        path: "licencias-emitidas",
        loadChildren: () =>
            import(
                "./routes/licencias-emitidas-construccion/licencias-emitidas-construccion.module"
            ).then((m) => m.LicenciasEmitidasConstruccionModule),
        canActivate: [AuthGuard, RolesGuard],
        canActivateChild: [AuthGuard, RolesGuard],
    },
    {
        path: "reportes",
        loadChildren: () =>
            import("./routes/reporte-construccion/reporte-construccion.module").then(
                (m) => m.ReporteConstruccionModule
            ),
        canActivate: [],
        canActivateChild: [],
    },
    {
        path: "reporte-admin",
        loadChildren: () =>
            import("./routes/reporte-admin/reporte-admin.module").then(
                (m) => m.ReporteAdminModule
            ),
        canActivate: [],
        canActivateChild: [],
    },
    {
        path: "**",
        redirectTo: "inicio",
    },
];
@NgModule({

    declarations: [AppComponent],
    imports: [
        BrowserModule,
        BrowserAnimationsModule,
        HttpClientModule,
        RouterModule.forRoot(userInfo.type_user == 1 ? appRoutes2 : appRoutes, routerOptions),
        TranslateModule.forRoot(),
        MatMomentDateModule,
        MaterialModule,
        AngularOpenlayersModule,
        FuseModule.forRoot(fuseConfig),
        FuseProgressBarModule,
        FuseSharedModule,
        FuseSidebarModule,
        FuseThemeOptionsModule,

        // App modules
        PagesModule,
        LayoutModule,
        //SampleModule,
        MapaModule,
        AuthModule,
        MaterialExtensionsModule,
        NotificacionesModule,
        CommonModule
    ],
    bootstrap: [AppComponent],
    providers: [
        {
            provide: HTTP_INTERCEPTORS,
            useClass: DefaultInterceptor,
            multi: true,

        },
    ],
})
export class AppModule {

}
