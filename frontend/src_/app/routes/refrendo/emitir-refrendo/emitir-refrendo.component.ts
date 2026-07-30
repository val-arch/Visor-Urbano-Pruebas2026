import { Component, Inject, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { EmitirLicenciaComponent } from 'app/routes/tramites/emitir-licencia/emitir-licencia.component';
import Swal from 'sweetalert2';
import { EmitirService } from '../../tramites/emitir-licencia/emitir.service';
import { LicenciaStatusService } from '../../../services/licencia/licencia-status.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-emitir-refrendo',
  templateUrl: './emitir-refrendo.component.html',
  styleUrls: ['./emitir-refrendo.component.scss']
})
export class EmitirRefrendoComponent implements OnInit {

 

  public generarForm: FormGroup;
  public emitirForm: FormGroup;
  public action: string;
  public row;
  public lic_v;
  public type: number = 1;
  public licencia;
  public dialogTitle: string;
  dialogRef;
  error;
  btnCss = {
    'background-color': '#70CE68',
    'color': 'white'
  };
  btnCssCancel = {
    'background-color': 'red',
    'color': 'white'
  };
  constructor(public matDialogRef: MatDialogRef<EmitirRefrendoComponent>,
    
    @Inject(MAT_DIALOG_DATA) private _data: any,
    private fb: FormBuilder,
    private _emitir: EmitirService,
    private _getLienciaInfo: LicenciaStatusService,
    private _matDialog: MatDialog,
    private router: Router
  ) {
    this.action = _data.action;
    this.row = _data.data;
    this.lic_v = _data.num_licencia;
    console.log(this.lic_v);
    this.type = _data.type_lic ?? 1;
    //Type == 1 Emitir en listado admin
    //Type == 2 Emitir en resumen
  }
  ngOnInit(): void {

    this.dialogTitle = this._data.dialogTitle || '';
    if (this._data.action == 'new') {
    }
    this.generarForm = this.fb.group({
      superficie: [''],
      anio: ['', Validators.required],
      hora_a: [''],
      hora_c: [''],
      folio: [this.lic_v ],
    });

    this.getInfoRefrendo();
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
  getInfoRefrendo() {
    this._getLienciaInfo.getInfoLicencia(btoa(this.row)).subscribe(
      (r: any) => {
       
      }, e => {
        console.error(e);
      }
    );
  }
  generate(){
    Swal.showLoading();

      this._emitir.generarRefrendo(this.row, this.generarForm.value).subscribe((r: any) => {
        console.log(r);
        this.error = '';
        
        let url = r.data+'/'+btoa('2');
        Swal.close();
        Swal.fire({
          title: '¡Éxito!',
          html: `¡Licencia emitida! <br> <a href="${url}" target="_blank">Clic para descargar</a>`,
          icon: 'success',
          confirmButtonText: 'Ok'
        });
        window.open(`${url}`, '_blank');
       this.router.navigate(['/licencias-emitidas']);
        this.matDialogRef.close({ status: true });
      }, e => {
        Swal.close();
        Swal.fire({
          title: 'No es posible emitir la licencia.',
          text: `${e.error.error}`,
          icon: 'error',
          confirmButtonText: 'Ok'
        });
        this.matDialogRef.close({ status: false ,message:e});
      });
  }
  emitirLicencia() {
    if (this.licencia == '') {
      this.error = 'Adjuntar licencia';
    }
    const formData = new FormData();
    console.log(this.row);
  
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

      this.matDialogRef.close({ status: true });
      }, e => {
      Swal.close();
      Swal.fire({
        title: 'Algo a ocurrido!',
        text: 'Intentar más tarde!',
        icon: 'error',
        confirmButtonText: 'Ok'
      });
 
    });

  }

}
