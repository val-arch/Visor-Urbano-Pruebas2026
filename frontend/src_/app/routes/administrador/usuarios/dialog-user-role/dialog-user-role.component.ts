import { Component, Inject, OnInit } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { UserroleService } from 'app/services/administrador/usuarios/userrole.service';
import Swal from 'sweetalert2';
import { DOCUMENT } from '@angular/common';
import { LocalStorageService } from '@shared/services/storage.service';


@Component({
  selector: 'app-dialog-user-role',
  templateUrl: './dialog-user-role.component.html',
  styleUrls: ['./dialog-user-role.component.scss']
})
export class DialogUserRoleComponent implements OnInit {
 titulo = {
    'background-color': '#70CE68',
     'color': 'white' 
  };

  constructor( private _role:UserroleService,
    public matDialogRef: MatDialogRef<DialogUserRoleComponent>,
    private _store: LocalStorageService,
    @Inject(DOCUMENT) private _document: Document,
    @Inject(MAT_DIALOG_DATA) private _data: any) {    
  }
  public dialogTitle:string;
  favoriteSeason: string;
  updateUser=false;
  list = [];
  list_subrole = [];
  roles_id =[];
  total = 0;
  isLoading = true;
  page = 0;
  editId=0;
  selected=-1;
  subSelected=12;
  query = {
    order: 'desc',
    page: 0,
  };
  data = { };
  panelOpenState = false;
  public checkboxModel = [];
  dialogRef;
  
  ngOnInit(): void {
    this.getData(); 
    // this.getSubroles();   
  }

  selectedItem(evento,id,idr,i) {
 
    if(evento.checked){
      this._role.setRole2(id,this._data.user)
      .subscribe(resp=>{   
        Swal.fire({
            title: '¡Éxito!',
            text: 'Guardado correctamente!',
            icon: 'success',
            confirmButtonText: 'Ok'
          }).then((result) => {
            // Read more about isConfirmed, isDenied below 
            if (result.isConfirmed) {
            //  this._document.defaultView.location.reload();
            
            this.matDialogRef.close('!');
            }
        });
          
      })
     
    }else{
        this._role.delRole(idr)
        .subscribe(resp=>{   
          Swal.fire({
              title: '¡Éxito!',
              text: 'Eliminado Correctamente!',
              icon: 'error',
              confirmButtonText: 'Ok'
          });
        })

    }
    return false;
  }

  
  
  selectedSubrole(evento,id) {
    if(evento.checked){
      this._role.setSubRole(id)
      .subscribe(resp=>{   
        Swal.fire({
            title: '¡Éxito!',
            text: 'Guardado correctamente!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
      })

    }else{
        this._role.delSubRole()
        .subscribe(resp=>{   
          Swal.fire({
              title: '¡Éxito!',
              text: 'Eliminado Correctamente!',
              icon: 'error',
              confirmButtonText: 'Ok'
          });
        })

    }
    return false;
  }
  
  getData() {
    this.isLoading = true;
    this._role.getRoles(this.page).subscribe(
      (res: any) => {
        console.log(this._data);
        this.total =res.total;
        this.list = res.data;
        console.log(this.list);
        let user = this._store.get("usr");
        if (user.rol == 5) {
          this.isLoading = true;
          this.total = 5;
          this.isLoading = this.isLoading = false;
          this.list = [
              {
                  id: 1,
                  name: "ciudadano",
                  descripcion: null,
                  id_municipio: 0,
                  deleted_at: null,
              },
              {
                  id: 2,
                  name: "ventanilla",
                  descripcion: null,
                  id_municipio: 0,
                  deleted_at: null,
              },
              {
                  id: 3,
                  name: "revisor",
                  descripcion: null,
                  id_municipio: 0,
                  deleted_at: null,
              },
              {
                  id: 4,
                  name: "director",
                  descripcion: null,
                  id_municipio: 0,
                  deleted_at: null,
              },
              {
                  id: 5,
                  name: "admin",
                  descripcion: null,
                  id_municipio: 0,
                  deleted_at: null,
              },
              {
                  id: 6,
                  name: "tecnico",
                  descripcion: null,
                  id_municipio: 0,
                  deleted_at: null,
              },
          ];
      }
        
        this.roles_id= this._data.user.roles;
        this.selected = this._data.user.role_id;
        this.selected = this.selected-1;
        if(this.selected > 4){
          this.selected =  this.selected-1;
        }
        /*
        if (Array.isArray( this.roles_id) &&  this.roles_id.length) {
          this.roles_id.forEach(element => {
            this.selected = element.id-1;
            if(element.id > 5){
              this.selected = element.id-2;
            }
        });
        }else{
          this.roles_id.forEach(element => {
            this.selected = element.id;
        });*
        }*/
        console.log( this.selected)
        this.isLoading = false;
      },
      error => {
        this.isLoading = false;
        console.log(error);
      }
    );
  }
  
  getSubroles() {
    this._role.getSubroles(this.page).subscribe(
      (res: any) => {
        
        this.list_subrole = res.data['data'];
        this.list_subrole.forEach(element => {
          console.log(element.id);
          if(element.id){
            console.log(element.id);
              this.subSelected = element.id;
          }
    });
        /*
        this.total =res.total;
       
        this.isLoading = false;*/
      },
      error => {
        this.isLoading = false;
        console.log(error);
      }
    );
  }
}