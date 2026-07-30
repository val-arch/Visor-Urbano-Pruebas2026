import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { MunicipioService } from 'app/services/administrador/municipios/municipio.service';
import { FileUploadService } from 'app/services/administrador/municipios/file-upload.service';
import { MunicipioModel } from '../../../../models/administrador/municipio.models';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-dialog-municipio',
  templateUrl: './dialog-municipio.component.html',
  styleUrls: ['./dialog-municipio.component.scss']
})
export class DialogMunicipioComponent implements OnInit {

  constructor(
    public matDialogRef: MatDialogRef<DialogMunicipioComponent>,
    @Inject(MAT_DIALOG_DATA) private _data: any,
    private _formBuilder: FormBuilder,
    private municipioService:MunicipioService,
    private uploadFileService: FileUploadService
  ){
      this.action       = _data.action;
      this.dialogTitle  = 'Editar Municipio';
      this.municipio    = _data.user;  
  }
  municipio :MunicipioModel;
  action: string;
  imagenSubir: File;
  municipioForm: FormGroup;
  dialogTitle: string;
  ngOnInit(): void {
     this.municipioForm = this._formBuilder.group({  
      nombre         :[this.municipio.nombre,Validators.required],
      director       :[this.municipio.director,Validators.required],
      id             :[this.municipio.id,Validators.required],
      dias_solventar :[this.municipio.dias_solventar,Validators.required],
      ficha_tramite  :[this.municipio.ficha_tramite,Validators.required],
      telefono       :[this.municipio.telefono,Validators.required],
      direccion      :[this.municipio.direccion,Validators.required],
   })
  }
  actualizarMunicipio(){
    this.municipioService.actualizarMunicipio(this.municipioForm.value)
    .subscribe(resp=>{   
      Swal.fire({
          title: '¡Éxito!',
          text: '¡Actualizado correctamente!',
          icon: 'success',
          confirmButtonText: 'Ok'
      });
    })
  }
  uploadFile(file:File){
     this.imagenSubir = file;
     this.uploadFileService.actualizarImagen(this.imagenSubir,this.municipio.id)
     .then();
  }

}
