import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { EmitirService } from 'app/routes/tramites/emitir-licencia/emitir.service';
import { LicenciaStatusService } from 'app/services/licencia/licencia-status.service';
import Swal from 'sweetalert2';
import { RefrendoHistoricoComponent } from '../refrendo-historico/refrendo-historico.component';
import { Router } from "@angular/router";
@Component({
  selector: 'app-emitir-refrendo-historico',
  templateUrl: './emitir-refrendo-historico.component.html',
  styleUrls: ['./emitir-refrendo-historico.component.scss']
})
export class EmitirRefrendoHistoricoComponent implements OnInit {

  public generarForm: FormGroup;
  public emitirForm: FormGroup;
  public action: string;
  hora_a;
  hora_c;
  superficie;
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
  constructor(public matDialogRef: MatDialogRef<RefrendoHistoricoComponent>,
    
    @Inject(MAT_DIALOG_DATA) private _data: any,
    private fb: FormBuilder,
    private _emitir: EmitirService,
    private _getLienciaInfo: LicenciaStatusService,
    private _matDialog: MatDialog,
    private router: Router
  ) {
    this.action = _data.action;
    this.hora_c = _data.hora_c;
    this.hora_a = _data.hora_a;
    this.superficie = _data.superficie;
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
      superficie: [this.superficie],
      hora_a: [this.hora_a],
      hora_c: [this.hora_c],
      folio: [this.lic_v ],
      anio: [''],
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
   
      this._emitir.generarRefrendoHistorico(this.row, this.generarForm.value).subscribe((r: any) => {
        console.log(r);
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
        this.router.navigate(['/licencias-emitidas']);
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

}
