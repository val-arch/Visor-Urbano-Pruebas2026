import { CUSTOM_ELEMENTS_SCHEMA, NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UsuariosComponent } from './usuarios/usuarios.component';
import { RolesComponent } from './roles/roles.component';
import { RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';
import { FuseSharedModule } from '@fuse/shared.module';
import { NgSelectModule } from "@ng-select/ng-select";
import { NgxCopilotModule } from 'ngx-copilot';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { MtxGridModule } from '@ng-matero/extensions/data-grid';
import { MatButtonModule } from '@angular/material/button';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { AngularOpenlayersModule } from "ngx-openlayers";
import { RequisitosDialogComponent } from './requisitos/requisitos-dialog/requisitos-dialog.component';
import { RequisitosListComponent } from './requisitos/requisitos-list/requisitos-list.component';
import { RequisitosComponent } from './requisitos/requisitos.component';
import { RolesDialogComponent } from './roles/roles-dialog/roles-dialog.component';
import { MatStepperModule } from '@angular/material/stepper';
import { DialogUserComponent } from './usuarios/dialog-user/dialog-user.component';
import { DialogUserRoleComponent } from './usuarios/dialog-user-role/dialog-user-role.component';
import { UsuariosListComponent } from './usuarios/usuarios-list/usuarios-list.component';
import { RolesListComponent } from './roles/roles-list/roles-list.component';

import { DialogMunicipioComponent } from './municipios/dialog-municipio/dialog-municipio.component';
import { MunicipioFormComponent } from './municipios/municipio-form/municipio-form.component';
import { MunicipioListComponent } from './municipios/municipio-list/municipio-list.component';
import { RequisitosListaComponent } from './requisitos/requisitos-lista/requisitos-lista.component';
import { DialogAddTipoTramiteComponent } from './municipios/dialog-add-tipo-tramite/dialog-add-tipo-tramite.component';
import { DialogAddFirmaComponent } from './municipios/dialog-add-firma/dialog-add-firma.component';
import { DialogTemplatesComponent } from './municipios/dialog-templates/dialog-templates.component';
import { DialogAddCampoTemplateComponent } from './municipios/dialog-add-campo-template/dialog-add-campo-template.component';
const routes = [
  {
    path: 'usuarios',
    component: UsuariosComponent
  },
  {
    path: 'roles',
    component: RolesComponent
  },
  {
    path: 'municipios',
    component: MunicipioListComponent
  },
  {
    path: 'mi-municipio',
    component: MunicipioFormComponent
  },
  {
    path: 'municipio/:id',
    component: MunicipioFormComponent
  },
  {
    path: 'requisitos',
    component: RequisitosComponent
  },
 
];
@NgModule({
  declarations: [UsuariosComponent,
    RolesComponent,
    DialogUserComponent,
    DialogUserRoleComponent,
    UsuariosListComponent,
    RolesListComponent,
    RolesDialogComponent,
    DialogMunicipioComponent,
    MunicipioFormComponent, 
    MunicipioListComponent, 
    RequisitosListComponent,
    RequisitosComponent,
    RequisitosDialogComponent,
    RequisitosListaComponent,
    DialogAddTipoTramiteComponent,
    DialogAddFirmaComponent,
    DialogTemplatesComponent,
    DialogAddCampoTemplateComponent],
  imports: [
    CommonModule,
    RouterModule.forChild(routes),
    TranslateModule,
    FuseSharedModule,
    MtxGridModule,
    NgxDatatableModule,
    MatButtonModule,
    MatCheckboxModule,
    MatFormFieldModule,
    MatIconModule,
    MatInputModule,
    NgxCopilotModule,
    NgSelectModule,
    AngularOpenlayersModule,
    MatStepperModule
  ],
  exports: [
    UsuariosComponent, RequisitosListComponent
  ], schemas: [
    CUSTOM_ELEMENTS_SCHEMA
  ],
})
export class AdministradorConstruccionModule { }
