import { Component, Inject, OnInit, TemplateRef, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatDialog, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { PageEvent } from '@angular/material/paginator';
import { MtxGridColumn } from '@ng-matero/extensions';
import { RolesService } from '../../../../services/administrador/roles/roles.service';
import { RolesDialogComponent } from '../roles-dialog/roles-dialog.component';
import { UserroleService } from 'app/services/administrador/usuarios/userrole.service';
import Swal from 'sweetalert2';
@Component({
  selector: 'app-roles-list',
  templateUrl: './roles-list.component.html',
  styleUrls: ['./roles-list.component.scss']
})
export class RolesListComponent implements OnInit {
  columns: MtxGridColumn[] = [];
  columnsRole: MtxGridColumn[] = [];

  @ViewChild('statusTpl', { static: true }) statusTpl: TemplateRef<any>;
  updateUser = false;
  list       = [];
  total      = 0;
  isLoading  = true;
  page       = 0;
  editId     =0;
  query      = {
    order: 'desc',
    page : 0,
  };
  roleForm: FormGroup;
  dialogRef;

  listRole      = [];
  totalRole     = 0;
  isLoadingRole = true;
  pageRole      = 0;
  editIdRole    =0;
  queryRole     = {
    order: 'desc',
    page: 0,
  };

  constructor(private _matDialog  : MatDialog, 
              private _roles      : RolesService,
              private _formBuilder: FormBuilder,
              private _userRole   : UserroleService,
             ) { }

  ngOnInit(): void {
    this.columns = [
      {
        header :'Nombre rol',
        field  :'nombre',
      },
      { header :'Descripción',
        field  : 'descripcion'
      },
      {
        header :'Acciones',
        field  :'acciones',
        type   :'button',
        buttons: [
          { type: 'icon',tooltip:'Editar', color: 'primary', text: 'Editar', icon: 'create' , click:(data)=>this.editRole(data)},
        //  { type: 'icon',tooltip:'Dar de baja', color: 'warn', text: 'Baja', icon: 'delete' }
        ],
      } 
    ];

    this.columnsRole = [
      {
        header: 'Role',
        field: 'name',
      },
       { header :'Descripción',
            field  : 'descripcion'
        },
        { header: 'Acciones', field: 'status', cellTemplate: this.statusTpl },
   
    ];
   // this.getData();
    this.getRole();
  }

  
  getNextPage(e: PageEvent) {
    this.pageRole = e.pageIndex + 1;
    this.queryRole.page = e.pageIndex;
    this.getRole();
  }

  getRole() {
    this.isLoadingRole = true;
    
    this._userRole.getRoles(this.pageRole).subscribe(
      (res: any) => {
        console.log(res.data);

        this.totalRole     = res.total;
        this.listRole      = res.data;
        this.isLoadingRole = false;
      },
      error => {
        this.isLoadingRole = false;
        console.log(error);
      }
    );
  }

  getData() {
    this.isLoading = true;
    this._roles.getSubRoles(this.page).subscribe(
      (res: any) => {
        
        this.total =res.total;
        this.list =res.data['data'];
        this.isLoading = false;
      },
      error => {
        this.isLoading = false;
        console.log(error);
      }
    );
  }
  editRole(role): void{  
      this.dialogRef = this._matDialog.open(RolesDialogComponent, {
          panelClass: 'role-form-dialog',
          data      : {
            role,
            action: 'edit'
          }
      });
      this.dialogRef.afterClosed()
          .subscribe((response: FormGroup) => {
      });
  }


  deleteRole(role):void{

    console.log(role);
    this._roles.deleteRoles(role).subscribe(
      (res: any) => {
        Swal.fire({
          title: '¡Éxito!',
          text: 'Eliminado Correctamente!',
          icon: 'warning',
          confirmButtonText: 'Ok'
      });
      this.getRole();
      },
      error => {
        this.isLoading = false;
        console.log(error);
      }
    );
  }
}
