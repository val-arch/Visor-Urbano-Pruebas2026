import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { RolesService } from 'app/services/administrador/roles/roles.service';
import { Role } from '../../../../models/administrador/role';
import Swal from 'sweetalert2';

@Component({
  templateUrl: './roles-dialog.component.html',
  styleUrls: ['./roles-dialog.component.scss']
})
export class RolesDialogComponent implements OnInit {

  public modelRole  :Role = new Role(); 
  public roleForm   :FormGroup;
  public action     :string;
  public dialogTitle:string;
   btnCss = {
    'background-color': '#003E76',
     'color': 'white' 
  };
  btnCssCancel = {
    'background-color': 'red',
     'color': 'white' 
  };

  
  constructor(public matDialogRef: MatDialogRef<RolesDialogComponent>,
    @Inject(MAT_DIALOG_DATA) private _data: any,
    private fb         : FormBuilder,
    private roleService:RolesService){
      this.action = _data.action;
  }

  ngOnInit ():void{     
    this.dialogTitle = this._data.dialogTitle ||  '';  
    if(this._data.action == 'new'){
    }else{
      this.modelRole = this._data.role;
    }
    this.roleForm =   this.fb.group({
      id          : [ this.modelRole.id],
      name        : [ this.modelRole.name ,Validators.required],
      descripcion : [ this.modelRole.descripcion ,Validators.required],
     
    });
  }

  saveRole(){
    if(this.action=='edit'){
        this.roleService.storeRoles(this.roleForm.value)
        .subscribe(resp=>{   
        Swal.fire({
            title: '¡Éxito!',
            text: 'Guardado correctamente!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
        this.matDialogRef.close()
      },err=>{
        Swal.fire({
          title: 'Upps!',
          text: err.error.error,
          icon: 'warning',
          confirmButtonText: 'Ok'
      });
      })
    }else{

      this.roleService.storeRoles(this.roleForm.value)
      .subscribe(resp=>{   
        Swal.fire({
            title: '¡Éxito!',
            text: 'Guardado correctamente!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
        this.matDialogRef.close()
      },err=>{
        Swal.fire({
          title: 'Upps!',
          text: err.error.error,
          icon: 'warning',
          confirmButtonText: 'Ok'
      });
      })
    } 

  }
}