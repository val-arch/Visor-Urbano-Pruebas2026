import { NgModule } from "@angular/core";
import { RouterModule } from "@angular/router";

import { CommonModule } from "@angular/common";
import { LandingComponent } from "./landing/landing.component";
import { HeaderComponent, BottomSheetIdiomaComponent } from "./navigation/header/header.component";
import { VuComponent } from "./navigation/vu/vu.component";

import { MatToolbarModule } from "@angular/material/toolbar";
import { MatButtonModule } from "@angular/material/button";
import { MatIconModule } from "@angular/material/icon";
import { FlexLayoutModule, GridModule } from "@angular/flex-layout";
import { MatGridListModule } from "@angular/material/grid-list";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { MaterialModule } from "app/material.module";
import { MatListModule } from "@angular/material/list";
import { ContentComponent } from "./navigation/content/content.component";
import { DialogContactosComponent } from "./dialogs/dialog-contactos/dialog-contactos.component";
import { DialogVUComponent } from "./dialogs/dialog-vu/dialog-vu.component";
import { DialogAvisosPrivacidadComponent } from "./dialogs/dialog-avisos-privacidad/dialog-avisos-privacidad.component";
import { DialogScianComponent } from "./dialogs/dialog-scian/dialog-scian.component";
import { BoletinComponent } from "./navigation/boletin/boletin.component";
import { MtxGridModule } from "@ng-matero/extensions";
import { UtilidadesMunicipiosComponent } from "./navigation/utilidades-municipios/utilidades-municipios.component";
import { EditorModule } from "@tinymce/tinymce-angular";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { SafeHtmlPipe } from "./shared/safe-html.pipe";
import { BlogComponent } from "./navigation/blog/blog.component";
import { ManagerComponent } from "./navigation/manager/manager.component";
import { FootComponent } from './shared/foot/foot.component';
import { NoticiaPopComponent } from './shared/noticia-pop/noticia-pop.component';
import { TutorialesComponent } from './navigation/tutoriales/tutoriales.component';
import { TranslateModule } from "@ngx-translate/core";

const routes = [
    {
        path: "inicio",
        component: LandingComponent,
    },
    {
        path: "vu",
        component: VuComponent,
    },
    {
        path: "boletin",
        component: BoletinComponent,
    },
    {
        path: "sare",
        component: UtilidadesMunicipiosComponent,
    },
    {
      path: "tutoriales",
      component: TutorialesComponent,
  },
    {
        path: "blog/:id",
        component: BlogComponent,
    },
    {
        path: "manager",
        component: ManagerComponent,
    },
    {
        path: "aviso",
        component: DialogAvisosPrivacidadComponent,
    },
];

@NgModule({
    declarations: [
        LandingComponent,
        HeaderComponent,
        ContentComponent,
        DialogContactosComponent,
        DialogVUComponent,
        DialogAvisosPrivacidadComponent,
        DialogScianComponent,
        VuComponent,
        BoletinComponent,
        UtilidadesMunicipiosComponent,
        SafeHtmlPipe,
        BlogComponent,
        ManagerComponent,
        FootComponent,
        NoticiaPopComponent,
        TutorialesComponent,
        BottomSheetIdiomaComponent
    ],
    imports: [
        RouterModule.forChild(routes),
        CommonModule,
        MatToolbarModule,
        MatButtonModule,
        MatIconModule,
        MatGridListModule,
        MtxGridModule,
        BrowserAnimationsModule,
        FlexLayoutModule,
        MaterialModule,
        MatListModule,
        EditorModule,
        FormsModule,
        ReactiveFormsModule,
        TranslateModule.forRoot(),
    ],
    exports: [
        LandingComponent,
        MatToolbarModule,
        MatButtonModule,
        MatIconModule,
        MatGridListModule,
        BrowserAnimationsModule,
        FlexLayoutModule,
        MatListModule,
    ],
})
export class PagesModule {}
