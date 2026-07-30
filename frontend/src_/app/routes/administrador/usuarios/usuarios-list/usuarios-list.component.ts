import { Component, OnInit, TemplateRef, ViewChild } from '@angular/core';
import { FormControl, FormGroup } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { PageEvent } from '@angular/material/paginator';
import { MtxGridColumn } from '@ng-matero/extensions';
import { UseradminService } from 'app/services/administrador/usuarios/useradmin.service';
import { DialogUserComponent } from '../dialog-user/dialog-user.component';
import { DialogUserRoleComponent } from '../dialog-user-role/dialog-user-role.component';
import { TokenService } from '@core/authentication/token.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-usuarios-list',
  templateUrl: './usuarios-list.component.html',
  styleUrls: ['./usuarios-list.component.scss']
})
export class UsuariosListComponent implements OnInit {
  @ViewChild('statusTpl', { static: true }) statusTpl: TemplateRef<any>;
  filter = new FormControl('');  
  columns: MtxGridColumn[] = [];
  updateUser=false;
  list = [];
  municipio = [];
  total = 0;
  isLoading = true;
  page = 0;
  editId=0;
  tipoRole = null;
  query = {
    order: 'desc',
    page: 0,
  };
  role = null;
  dialogRef;
  constructor(
    private _user:UseradminService,
    private _matDialog: MatDialog,
    private token: TokenService
    ) { }

  ngOnInit(): void {
    this.role =this.token.get().role;
    this.columns = [
      {
        header: 'Nombre',
        field: 'Nombre',
        width:'500px',
        formatter: (data: any) => `${data.nombre_user} ${data.apellido_p ?? ''} ${data.apellido_m ?? ''}`
      },
      { header: 'Email', field: 'email',  width:'250px'},
      { header: 'Rol',  width:'200px', field: 'roles1', formatter:(data:any)=>` ${data.name.length>0 ? data.name : 'ciudadano'} ` },
      { header: 'Rol Pendiente', field: 'rolePendiente',  width:'250px', formatter: (data: any) => `${ data.name2 != null ? data.name2 : '' } `},
      { header: 'Rol Status', field: 'role_status',  width:'250px',formatter: (data) => this.getStatusRole(data.role_status)},
    
    //  { header: 'Rol',  width:'200px', field: 'roles1', formatter:(data:any)=>` ${data.roles.length>0 ? data.roles[0].name : 'ciudadano'} ` },
     // { header: 'Rol Pendiente', field: 'rolePendiente',  width:'250px', formatter: (data: any) => `${data.roles_pendiente.length>0 ? data.roles_pendiente[0].name : ''} `},
      //{ header: 'Rol Status', field: 'role_status',  width:'250px',formatter: (data) => this.getStatusRole(data.role_status)},
      { header: 'Municipios', field: 'id_municipio', cellTemplate: this.statusTpl ,formatter:(data:any)=>`${data.nombre}`},
      {
        header: 'Acciones',
        field: 'acciones',
        type: 'button',
        width:'200px',
        buttons: [
          { type: 'icon',tooltip:'Editar', color: 'primary', text: 'Editar usuario', icon: 'create' , click:(data)=>this.edtiUser(data) },
          { type: 'icon',tooltip:'Cambiar rol', color: 'warn', text: 'Cambiar rol', icon: 'supervised_user_circle' , click:(data)=>this.setRoleUser(data) },
        //{ type: 'icon',tooltip:'Dar de baja', color: 'warn', text: 'Baja', icon: 'delete' }
        ],
      }
    ];
    this.getData();
    
  
    if(this.token.get().role == 5){
      this.tipoRole=false;
      this.getMunicipio2();
    }else{
      this.getMunicipio();
      this.tipoRole=true;
    }
  
  }
  buscar(){  
    //if(this.filter.value != ''){
      this.page = 0;
      this.query.page = 0;
      this.getData();
      // this.columns = [
      //   {
      //     header: 'Nombre',
      //     field: 'Nombre',
      //     width:'500px',
      //     formatter: (data: any) => `${data.name} ${data.apellido_p ?? ''} ${data.apellido_m ?? ''}`
      //   },
      //   { header: 'Email', field: 'email',  width:'250px'},
      //   { header: 'Rol',  width:'200px', field: 'roles1', formatter:(data:any)=>` ` },
      //   { header: 'Rol Pendiente', field: 'rolePendiente',  width:'250px', formatter: (data: any) => ` `},
      //   { header: 'Rol Status', field: 'role_status',  width:'250px',formatter: (data) => this.getStatusRole(data.role_status)},
       
      //  // { header: 'Rol',  width:'200px', field: 'roles1', formatter:(data:any)=>` ${data.roles.length>0 ? data.roles[0].name : 'ciudadano'} ` },
      //  // { header: 'Rol Pendiente', field: 'rolePendiente',  width:'250px', formatter: (data: any) => `${data.roles_pendiente.length>0 ? data.roles_pendiente[0].name : ''} `},  { header: 'Rol Status', field: 'role_status',  width:'250px',formatter: (data) => this.getStatusRole(data.role_status)},
      //   { header: 'Municipios', field: 'name', cellTemplate: this.statusTpl ,formatter:(data:any)=>``},
      //   {
      //     header: 'Acciones',
      //     field: 'acciones',
      //     type: 'button',
      //     width:'200px',
      //     buttons: [
      //       { type: 'icon',tooltip:'Editar', color: 'primary', text: 'Editar usuario', icon: 'create' , click:(data)=>this.edtiUser(data) },
      //       { type: 'icon',tooltip:'Cambiar rol', color: 'warn', text: 'Cambiar rol', icon: 'supervised_user_circle' , click:(data)=>this.setRoleUser(data) },
      //     //{ type: 'icon',tooltip:'Dar de baja', color: 'warn', text: 'Baja', icon: 'delete' }
      //     ],
      //   }
      // ];
   // }
    
  }
   getStatusRole(data) {
    switch (data) {

        case 1:
            return 'Aceptado';
            break;
          case 2:
            return  'Pendiente';
            break;
          default:
            return  '';
            break;
    }
    
  }
  getNextPage(e: PageEvent) {
    this.page = e.pageIndex + 1;
    this.query.page = e.pageIndex;
    this.getData();
   
  }
  getMunicipio(){
    this._user.getMunicipios().subscribe(
      (res: any) => {
        this.municipio = res.data;
      },
      error => {
        this.isLoading = false;
        console.log(error);
      }
    );
  }
  getMunicipio2(){
    this._user.getMunicipios2().subscribe(
      (res: any) => {
        this.municipio = res;
      },
      error => {
        this.isLoading = false;
        console.log(error);
      }
    );
  }
  onOptionsSelected(id_municipio,id){
        this._user.setMunicipio(Number(id_municipio),id)
        .subscribe(resp=>{   
          Swal.fire({
              title: '¡Éxito!',
              text: 'Asiganado Correctamente!',
              icon: 'success',
              confirmButtonText: 'Ok'
          });
        })
    
      return true;
  }



  getData() {
    this.isLoading = true;
    this._user.getUsers2(this.page,this.filter.value).subscribe(
      (res: any) => {
 
      this.total =res.total;
      this.list = res.data;
      console.log(this.list);
      this.isLoading = false;
      },
      error => {
        this.isLoading = false;
        console.log(error);
      }
    );
  }


  setRoleUser(user){
  
        this.dialogRef = this._matDialog.open(DialogUserRoleComponent, {
          autoFocus: false,
          maxHeight: '190vh' ,
          panelClass: 'contact-form-dialog',
          data      : {
              user,
              action: 'edit'
          }
      });
      this.dialogRef.afterClosed()
          .subscribe(response => {
            this.getData();
              if ( !response )
              {
                  return;
              }
              const actionType: string = response[0];
              const formData: FormGroup = response[1];
              switch ( actionType )
              {
                  /**
                   * Save
                   */
                  case 'save':
                   //   this._contactsService.updateContact(formData.getRawValue());
                      break;
                  /**
                   * Delete
                   */
              }
          });
}

  edtiUser(user){
      this.dialogRef = this._matDialog.open(DialogUserComponent, {
        panelClass: 'contact-form-dialog',
        data      : {
          user,
            action: 'edit'
        }
    });

    this.dialogRef.afterClosed()
            .subscribe(response => {
                if ( !response )
                {
                    return;
                }
                const actionType: string = response[0];
                const formData: FormGroup = response[1];
                switch ( actionType )
                {
                    /**
                     * Save
                     */
                    case 'save':
                     //   this._contactsService.updateContact(formData.getRawValue());
                        break;
                    /**
                     * Delete
                     */
                }
            });
  }

}
