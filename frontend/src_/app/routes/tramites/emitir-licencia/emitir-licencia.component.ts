import { Component, Inject, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import Swal from 'sweetalert2';
import { EmitirService } from './emitir.service';

@Component({
  templateUrl: './emitir-licencia.component.html',
  styleUrls: ['./emitir-licencia.component.scss']
})
export class EmitirLicenciaComponent implements OnInit {


  public generarForm: FormGroup;
  public emitirForm: FormGroup;
  public action: string;
  public row;
  public type: number = 1;
  public licencia;
  public dialogTitle: string;
  error;
  btnCss = {
    'background-color': '#70CE68',
    'color': 'white'
  };
  btnCssCancel = {
    'background-color': 'red',
    'color': 'white'
  };


  constructor(public matDialogRef: MatDialogRef<EmitirLicenciaComponent>,
    @Inject(MAT_DIALOG_DATA) private _data: any,
    private fb: FormBuilder,
    private _emitir: EmitirService,
  ) {
    this.action = _data.action;
    this.row = _data.data;
    this.type = _data.type_lic ?? 1;
    //Type == 1 Emitir en listado admin
    //Type == 2 Emitir en resumen
  }

  ngOnInit(): void {
    console.log(this.row);
    this.dialogTitle = this._data.dialogTitle || '';
    if (this._data.action == 'new') {
    }
    this.generarForm = this.fb.group({
      superficie: ['', ],
      hora_a: ['', ],
      hora_c: ['', ],
      observaciones: ['', ]
    });
  }
  subirLicencia(event) {
    console.log(event);
    this.licencia = event.target.files[0];
    console.log(this.licencia);
  }

  generarLicencia(){

    if(this.generarForm.valid){
      if(this.type==2){
        Swal.fire({
          icon:'question',
          title: '¿Estás seguro de emitir la licencia?',
          showCancelButton: true,
          confirmButtonText: `Emitir`,
          cancelButtonText: `Cancelar`
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.showLoading();
            this.generate();
          } 
        })
      }else{
        this.generate();
      }
      
    }

  }

  generate(){
    Swal.showLoading();
      this._emitir.generar(this.row.folio, this.generarForm.value,this.type).subscribe((r: any) => {
        this.error = '';
  
        Swal.close();
        Swal.fire({
          title: '¡Éxito!',
          html: `¡Licencia emitida! <br> <a href="${r.data}" target="_blank">Clic para descargar</a>`,
          icon: 'success',
          confirmButtonText: 'Ok'
        });
        window.open(`${r.data}`, '_blank');
        this.matDialogRef.close({ status: true });
  
        console.log(r);
      }, e => {
        Swal.close();
        Swal.fire({
          title: 'No es posible emitir la licencia.',
          text: `${e.error.error}`,
          icon: 'error',
          confirmButtonText: 'Ok'
        });
        this.matDialogRef.close({ status: false ,message:e});
        // this.error = e.error.error;
        // console.error(e.error.error);
      });
  }
  emitirLicencia() {
    if (this.licencia == '') {
      this.error = 'Adjuntar licencia';
    }
    const formData = new FormData();
    formData.append("licencia", this.licencia);
    formData.append("folio", this.row.folio);
    Swal.showLoading();
    this._emitir.emitir(this.row.folio, formData).subscribe((r: any) => {
      this.error = '';

      Swal.close();
      Swal.fire({
        title: '¡Éxito!',
        text: '¡Licencia emitida!',
        icon: 'success',
        confirmButtonText: 'Ok'
      });
      console.log(r);
      this.matDialogRef.close({ status: true });

      console.log(r);
    }, e => {
      Swal.close();
      Swal.fire({
        title: 'Algo a ocurrido!',
        text: 'Intentar más tarde!',
        icon: 'error',
        confirmButtonText: 'Ok'
      });
      // this.error = e.error.error;
      // console.error(e.error.error);
    });

  }
}
