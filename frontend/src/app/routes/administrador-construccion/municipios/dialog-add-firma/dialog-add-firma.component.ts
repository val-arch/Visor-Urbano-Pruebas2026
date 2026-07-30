import { Component, OnInit, Inject } from '@angular/core';
import { FormBuilder, FormGroup, Validators} from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { MunicipioService } from 'app/services/administrador/municipios/municipio.service';
import Swal from 'sweetalert2';
import { FileUploadService } from 'app/services/administrador/municipios/file-upload.service';

@Component({
  selector: 'app-dialog-add-firma',
  templateUrl: './dialog-add-firma.component.html',
  styleUrls: ['./dialog-add-firma.component.scss']
})
export class DialogAddFirmaComponent implements OnInit {

  btnCss = {
    "background-color": "#003E76",
    color: "white",
  };

  firmaForm: FormGroup;
  titulo = '';
  edit = false;

  constructor(private municipioService: MunicipioService, private _formBuilder: FormBuilder, @Inject(MAT_DIALOG_DATA) public _data: any, public matDialogRef: MatDialogRef<DialogAddFirmaComponent>, private uploadFileService: FileUploadService,) { }

  ngOnInit(): void {

    if(this._data.type == 'new'){
      this.titulo = 'Agregar firma';
      this.firmaForm = this._formBuilder.group({
        dependencia: ['', Validators.required],
        nombre_firmante: ['', Validators.required],
        id_municipio: [this._data.id_municipio],
      });
    }else if(this._data.type == 'edit'){
      this.edit = true;
      this.titulo = 'Editar firma';
      this.firmaForm = this._formBuilder.group({
        id: [this._data.data.id],
        dependencia: [this._data.data.dependencia],
        nombre_firmante: [this._data.data.nombre_firmante],
        id_municipio : [this._data.data.id_municipio],
      });
    }
  }

  editFirma(){
    this.municipioService.editFirma(this.firmaForm.value).subscribe((result) => {
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

  saveFirma(){
    this.municipioService.storeFirma(this.firmaForm.value).subscribe((result) => {
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

  uploadFile(file:File){
    this.uploadFileService.actualizarImagenFirmaConstruccion(file, this._data.data.id).then();
  }

}
