import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';

import { FuseSharedModule } from '@fuse/shared.module';
import { NgxCopilotModule } from  'ngx-copilot';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { MtxGridModule } from '@ng-matero/extensions/data-grid';
import { MatButtonModule } from '@angular/material/button';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { UsuariosComponent } from './usuarios/usuarios.component';
import { DialogUserComponent } from './usuarios/dialog-user/dialog-user.component';
import { GiroListComponent } from './giro/giro-list/giro-list.component';
import { RequisitosComponent } from './requisitos/requisitos.component';
import { RequisitosListComponent } from './requisitos/requisitos-list/requisitos-list.component';
import { RequisitosDialogComponent } from './requisitos/requisitos-dialog/requisitos-dialog.component';
import { UsuariosListComponent } from './usuarios/usuarios-list/usuarios-list.component';
import { CommonModule } from '@angular/common';
import { RolesComponent } from './roles/roles.component';
import { RolesListComponent } from './roles/roles-list/roles-list.component';
import { RolesDialogComponent } from './roles/roles-dialog/roles-dialog.component';
import { MunicipioListComponent } from './municipios/municipio-list/municipio-list.component';

import { DialogMunicipioComponent } from './municipios/dialog-municipio/dialog-municipio.component';
import { MunicipioFormComponent } from './municipios/municipio-form/municipio-form.component';
import { DialogUserRoleComponent } from './usuarios/dialog-user-role/dialog-user-role.component';
// import { ScrollToModule } from '@nicky-lenaers/ngx-scroll-to';
import { MtxLoaderModule } from '@ng-matero/extensions';
import { CapasMunicipioComponent } from './capas-municipio/capas-municipio.component';
import { CapasMunicipioListComponent } from './capas-municipio/capas-municipio-list/capas-municipio-list.component';
import { CapasMunicipioDialogComponent } from './capas-municipio/capas-municipio-dialog/capas-municipio-dialog.component';
import {NgSelectModule} from "@ng-select/ng-select";
import { ImpactoComponent } from './impacto/impacto.component';
import { ImpactoHerramientasComponent } from "./impacto/impacto-herramientas/impacto-herramientas.component";
import {AngularOpenlayersModule} from "ngx-openlayers";

import {ImpactoSidebarComponent} from './impacto/impacto-sidebar/impacto-sidebar.component';

const routes = [
    {
        path     : 'usuarios',
        component: UsuariosComponent
    },
    {
        path     : 'roles',
        component: RolesComponent
    },
    {
        path     : 'giros',
        component: GiroListComponent
    },
    {
        path     : 'municipios',
        component: MunicipioListComponent
    },
    {
        path     : 'mi-municipio',
        component: MunicipioFormComponent
    },
    {
        path     : 'municipio/:id',
        component: MunicipioFormComponent
    },
    {
        path     : 'requisitos',
        component: RequisitosComponent
    },
    {
        path     : 'capas-municipio',
        component: CapasMunicipioComponent
    },
    {
        path     : 'impacto',
        component: ImpactoComponent
    },

];

@NgModule({
    declarations: [
        UsuariosComponent,
        GiroListComponent,
        DialogUserComponent,
        RequisitosComponent,
        RequisitosListComponent,
        RequisitosDialogComponent,
        UsuariosListComponent,
        RolesComponent,
        RolesListComponent,
        RolesDialogComponent,
        MunicipioListComponent,
        DialogMunicipioComponent,
        MunicipioFormComponent,
        DialogUserRoleComponent,
        CapasMunicipioComponent,
        CapasMunicipioListComponent,
        CapasMunicipioDialogComponent,
        ImpactoComponent,
        ImpactoSidebarComponent,
        ImpactoHerramientasComponent,
    ],
    imports: [
        RouterModule.forChild(routes),
        TranslateModule,
        FuseSharedModule,
        FuseSharedModule,
        MtxGridModule,
        MtxLoaderModule,
        NgxDatatableModule,
        MatButtonModule,
        MatCheckboxModule,
        MatFormFieldModule,
        MatIconModule,
        MatInputModule,
        CommonModule,
        NgxCopilotModule,
        // ScrollToModule.forRoot(),
        NgSelectModule,
        AngularOpenlayersModule,
    ],
    exports     : [
        UsuariosComponent
    ]
    ,
    entryComponents: [
        DialogUserComponent,
        RequisitosDialogComponent
    ]
})

export class AdministradorModule
{
}
