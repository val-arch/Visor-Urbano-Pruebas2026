import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { Historico } from 'app/models/historico';
import { UserAdmin } from 'app/models/administrador/useradmin';
import { DialogUserComponent } from 'app/routes/administrador/usuarios/dialog-user/dialog-user.component';
import { UseradminService } from 'app/services/administrador/usuarios/useradmin.service';
import { UserroleService } from 'app/services/administrador/usuarios/userrole.service';
import { HistoricoLicenciaService } from 'app/services/historico/historico-licencia.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-historico-licencia-dialog',
  templateUrl: './historico-licencia-dialog.component.html',
  styleUrls: ['./historico-licencia-dialog.component.scss']
})
export class HistoricoLicenciaDialogComponent implements OnInit {


  btnCss = {
    'background-color': '#70CE68',
     'color': 'white' 
  };
  btnCssCancel = {
      'background-color': 'red',
       'color': 'white' 
  };
  user :UserAdmin;
  historico : Historico;
  action: string;
  userForm: FormGroup;
  dialogTitle: string;
  errors = null;
  list = [];
  total = 0;
  isLoading = true;
  page = 0;


  constructor(
    private _role:UserroleService,
    public matDialogRef: MatDialogRef<DialogUserComponent>,
    @Inject(MAT_DIALOG_DATA) private _data: any,
    private _formBuilder: FormBuilder,
    private userService:UseradminService,
    private _historicoLicencia: HistoricoLicenciaService
    
  ) {
            this.action = _data.action;
            this.dialogTitle = 'Editar Historico';
            this.historico = this._data.user;
            console.log(this.userForm);
            this.userForm = this.editeuserForm();    
   }
  
  ngOnInit(): void {
    this.getData(); 
  }
  createuserForm(): FormGroup
  {
      return this._formBuilder.group({               
           folio_licencia: [this.historico.folio_licencia], 
           fecha_emision:  [this.historico.fecha_emision], 
           giro:  [this.historico.giro], 
           descripcion_detallada: [this.historico.descripcion_detallada], 
           codigo_giro: [this.historico.codigo_giro], 
           superficie_giro: [this.historico.superficie_giro], 
           calle: [this.historico.calle], 
           numero_ext: [this.historico.numero_ext], 
           numero_int:[this.historico.numero_int], 
           colonia: [this.historico.colonia], 
           clave_catastral: [this.historico.clave_catastral], 
           referencia: [this.historico.referencia], 
           coordonadas_x: [this.historico.coordonadas_x], 
           coordonadas_y: [this.historico.coordonadas_y], 
           nombre_titular:[this.historico.nombre_titular], 
           apellido_p: [this.historico.apellido_p], 
           rfc:[this.historico.rfc], 
           apellido_m: [this.historico.apellido_m], 
           curp: [this.historico.curp], 
           telefono: [this.historico.telefono], 
           razon_social: [this.historico.razon_social], 
           email: [this.historico.email], 
           calle_titular: [this.historico.calle_titular], 
           numero_ext_titular: [this.historico.numero_ext_titular], 
           numero_int_titular: [this.historico.numero_int_titular], 
           colonia_titular: [this.historico.colonia_titular], 
           venta_alcohol: [this.historico.venta_alcohol], 
           horario: [this.historico.horario], 
      
      });
  }
  editeuserForm(): FormGroup
  {
      return this._formBuilder.group({
     
        folio_licencia: [this.historico.folio_licencia], 
        fecha_emision:  [this.historico.fecha_emision], 
        giro:  [this.historico.giro], 
        descripcion_detallada: [this.historico.descripcion_detallada], 
        codigo_giro: [this.historico.codigo_giro], 
        superficie_giro: [this.historico.superficie_giro], 
        calle: [this.historico.calle], 
        numero_ext: [this.historico.numero_ext], 
        numero_int:[this.historico.numero_int], 
        colonia: [this.historico.colonia], 
        clave_catastral: [this.historico.clave_catastral], 
        referencia: [this.historico.referencia], 
        coordonadas_x: [this.historico.coordonadas_x], 
        coordonadas_y: [this.historico.coordonadas_y], 
        nombre_titular:[this.historico.nombre_titular], 
        apellido_p: [this.historico.apellido_p], 
        rfc:[this.historico.rfc], 
        apellido_m: [this.historico.apellido_m], 
        curp: [this.historico.curp], 
        telefono: [this.historico.telefono], 
        razon_social: [this.historico.razon_social], 
        email: [this.historico.email], 
        calle_titular: [this.historico.calle_titular], 
        numero_ext_titular: [this.historico.numero_ext_titular], 
        numero_int_titular: [this.historico.numero_int_titular], 
        colonia_titular: [this.historico.colonia_titular], 
        venta_alcohol: [this.historico.venta_alcohol], 
        horario: [this.historico.horario], 
      });
  }

  saveUser(){
    if(this.action=='edit'){

      console.log(this.userForm.value);

       this.userForm.removeControl('role');
       this._historicoLicencia.storeHistorial(this.userForm.value,this.historico.id).subscribe(
          resp=>{   
        Swal.fire({
            title: '¡Éxito!',
            text: 'Guardado correctamente!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
        this.matDialogRef.close()
     
      })
    }else{

      this.userService.storeUsers(this.userForm.value)
      .subscribe(resp=>{   
        Swal.fire({
            title: '¡Éxito!',
            text: 'Guardado correctamente!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
        this.matDialogRef.close()
      })
    } 

  }

  getData() {
    this.isLoading = true;
    this._role.getRoles(this.page).subscribe(
      (res: any) => {
        this.total =res.total;
        this.list = res.data;
        this.list =  this.list.filter(role => role.name != 'admin');
        this.isLoading = false;
      },
      error => {
        this.isLoading = false;
        console.log(error);
      }
    );
  }

}
