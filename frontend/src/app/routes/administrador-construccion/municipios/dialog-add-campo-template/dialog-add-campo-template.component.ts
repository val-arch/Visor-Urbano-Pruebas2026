import { Component, OnInit, Inject } from '@angular/core';
import { FormBuilder, FormGroup, Validators} from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import Swal from 'sweetalert2';
import { MunicipioService } from 'app/services/administrador/municipios/municipio.service';

@Component({
  selector: 'app-dialog-add-campo-template',
  templateUrl: './dialog-add-campo-template.component.html',
  styleUrls: ['./dialog-add-campo-template.component.scss']
})
export class DialogAddCampoTemplateComponent implements OnInit {

  campoForm: FormGroup;
  btnCss = {
    "background-color": "#003E76",
    color: "white",
  };

  constructor(private municipioService: MunicipioService, private _formBuilder: FormBuilder, @Inject(MAT_DIALOG_DATA) public _data: any, public matDialogRef: MatDialogRef<DialogAddCampoTemplateComponent>) { }

  ngOnInit(): void {
    if(this._data.type == 'new'){
      this.campoForm = this._formBuilder.group({
        id_tramite: [this._data.id_tramite],
        campo: ['', Validators.required],
      });
    }else if(this._data.type == 'edit'){
      this.campoForm = this._formBuilder.group({
        id: [this._data.idCampo],
        campo: [this._data.campo, Validators.required],
      });
    }
    
  }

  saveCampo(){
    this.municipioService.storeCampoTemplate(this.campoForm.value).subscribe((result) => {
      Swal.fire({
        title: "¡Éxito!",
        text: "Guardado correctamente!",
        icon: "success",
        confirmButtonText: "Ok",
      });
      this.matDialogRef.close();
    },(error) => {
      Swal.fire({
        title: "Ops",
        text: error.error.error,
        icon: "warning",
        confirmButtonText: "Ok",
      });
                    
    });
  }

  editCampo(){
    this.municipioService.updateCampoTemplate(this.campoForm.value).subscribe((result) => {
      Swal.fire({
        title: "¡Éxito!",
        text: "Actualizado correctamente!",
        icon: "success",
        confirmButtonText: "Ok",
      });
      this.matDialogRef.close();
    },(error) => {
      Swal.fire({
        title: "Ops",
        text: error.error.error,
        icon: "warning",
        confirmButtonText: "Ok",
      });
                    
    });

  }

}
