import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup ,Validators} from '@angular/forms';
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import {LicenciaStatusService} from '../../../services/licencia/licencia-status.service'
import Swal from 'sweetalert2';
import { HistoricoLicenciaService } from 'app/services/historico/historico-licencia.service';
import { environment } from '@env/environment';

@Component({
  selector: 'app-licencias-emitidas-dialog',
  templateUrl: './licencias-emitidas-dialog.component.html',
  styleUrls: ['./licencias-emitidas-dialog.component.scss']
})
export class LicenciasEmitidasDialogComponent implements OnInit {
errors = [];
    motivo;
    motivo2;
    fecha_cambio_status;
    fileDescarga;
    status_licencia;
    status_licencia2;
    archivoNombre = null;
    btnCss = {
        "background-color": "#003E76",
        color: "white",
        "margin-right": "39px"
    };
    btnCssCancel = {
        "background-color": "red",
        color: "white",
    };
  submitted = false;
  constructor(private formBuilder: FormBuilder,
              public dialog: MatDialog,
              private _historicoLicencia: HistoricoLicenciaService,
              public matDialogRef: MatDialogRef<LicenciasEmitidasDialogComponent>,
              private licenciaService:LicenciaStatusService,
              @Inject(MAT_DIALOG_DATA) private _data: any) { }
 
  checkoutForm: FormGroup;
  fileStatus:any = null;
  ngOnInit(): void {

    console.log(this._data);
    
    console.log('g');
    this.checkoutForm = this.formBuilder.group({
      status:'',
      motivo:  [null,Validators.required],
      file: ''
    }); 

    this.motivo2 =  this._data.id['motivo']
    this.fecha_cambio_status = this._data.id['fecha_cambio_status'].slice(0, -8);
    this.fileDescarga = this._data.id['archivo_motivo']
    this.status_licencia2 =  this._data.id['status_licencia']
  }

  onClick(e){
    console.log(e);
    window.open(`${environment.SERVER_ORIGIN}${this.fileDescarga }`,'_blank')
  }
  InputChange(e){
    this.fileStatus = e.target.files[0];
    console.log(this.archivoNombre = this.fileStatus.name)
    return;
  }
    get f() { return this.checkoutForm.controls; }
    onSubmit() {
      
      this.checkoutForm.patchValue({
        file:  this.fileStatus 
      });
   
      this.submitted = true;  
      if (this.checkoutForm.invalid) {
         return;
      }
      this.licenciaService.updateStatus2(this.checkoutForm.value,this._data.id).subscribe(
          resp=>{
            if(this.checkoutForm.value['status']=='Baja'){
              this.bajaLicencia(this._data.id['id']);
            }
            
             
        Swal.fire({
            title: '¡Éxito!',
            text: 'Guardado correctamente!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
        this.matDialogRef.close()
      })           
  }

  async bajaLicencia(id) {
    let text = '';
    this._historicoLicencia.bajaLicencia(id, { motivo: text }).subscribe(
      (r: any) => {
     
        
      }, e => {
      
       
      });

  return false;

  
}
  onReset() {
        this.submitted = false;
        this.checkoutForm.reset();
  }
  updateStatus(){ 
    this.matDialogRef.close()
  }
}