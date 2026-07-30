import { Component, OnInit, Inject } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { DialogAddCampoTemplateComponent } from '../dialog-add-campo-template/dialog-add-campo-template.component';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { MunicipioService } from 'app/services/administrador/municipios/municipio.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-dialog-templates',
  templateUrl: './dialog-templates.component.html',
  styleUrls: ['./dialog-templates.component.scss']
})
export class DialogTemplatesComponent implements OnInit {

  campos:any = [];
  tipoTramite = '';
  dialogRef;

  constructor(private municipioService: MunicipioService, private _matDialog: MatDialog, @Inject(MAT_DIALOG_DATA) public _data: any) { }

  ngOnInit(): void {
    this.tipoTramite = this._data.nombreTramite;
    this.getCampos();
  }

  getCampos(){
    this.municipioService.getCamposTemplates(this._data.id_tramite).subscribe(resp=>{
      this.campos = resp;
    });
  }

  addCampo(){
    this.dialogRef = this._matDialog.open(DialogAddCampoTemplateComponent, {
      panelClass: 'contact-form-dialog',
      data: {
        type : 'new',
        id_tramite: this._data.id_tramite,
      },
      width: 'auto'
    });
    this.dialogRef.afterClosed().subscribe(result => {
      this.getCampos();
    });
  }

  editCampo(id, campo){
    this.dialogRef = this._matDialog.open(DialogAddCampoTemplateComponent, {
      panelClass: 'contact-form-dialog',
      data: {
        type : 'edit',
        idCampo: id,
        campo: campo
      },
      width: 'auto'
    });
    this.dialogRef.afterClosed().subscribe(result => {
      this.getCampos();
    });
  }

  removeCampo(id){
    Swal.fire({
      title: '¿Estás seguro que deseas eliminar este campo?',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: `Cancelar`,
      confirmButtonColor: "red",
    }).then((result) => {
      if (result.isConfirmed) {
        this.municipioService.removeCampoTemplate(id).subscribe(resp=>{
          Swal.fire({
            title: "¡Éxito!",
            text: "Eliminado correctamente!",
            icon: "success",
            confirmButtonText: "Ok",
          });
          this.getCampos();
        },(error) => {
          Swal.fire({
            title: "Ops",
            text: error.error.error,
            icon: "warning",
            confirmButtonText: "Ok",
          });  
          this.getCampos();           
        });
      } 
    });
  }

}
