import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { environment } from '@env/environment';
import { LicenciaStatusDialogComponent } from 'app/routes/licencias-emitidas-giro/licencia-status-dialog/licencia-status-dialog.component';
import { LicenciaStatusService } from 'app/services/licencia/licencia-status.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-historico-status-dialog',
  templateUrl: './historico-status-dialog.component.html',
  styleUrls: ['./historico-status-dialog.component.scss']
})
export class HistoricoStatusDialogComponent implements OnInit {

  errors = [];
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
              public matDialogRef: MatDialogRef<LicenciaStatusDialogComponent>,
              private licenciaService:LicenciaStatusService,
              @Inject(MAT_DIALOG_DATA) private _data: any) { }
 
  checkoutForm: FormGroup;
  fileStatus:any = null;
  
  motivo:any;
  motivo2:any;
  status:any ;
  status2:any ;
  archivoNombre = null;
  file:any;
  fecha_cambio_status:any;
  fileDescarga:any;
  id_municipio;
  id_municipio2;
  ngOnInit(): void {
    console.log(this._data);
  
    this.checkoutForm = this.formBuilder.group({
      status:'',
      motivo:  [null,Validators.required],
      file: '',
      id_municipio: ''
    }); 

    this.motivo2 = this._data.id['motivo'];
    this.status2 = this._data.id['status_licencia'];
    this.id_municipio2 = this._data.id['id_municipio'];
    this.fecha_cambio_status = this._data.id['fecha_cambio_status'].slice(0, -8);
    this.fileDescarga = this._data.id['archivo_motivo'];
    
  }
  InputChange(e){
  
    this.fileStatus = e.target.files[0];
    console.log(this.archivoNombre = this.fileStatus.name)
  }
  onClick(e){
    console.log(e);
    window.open(`${environment.SERVER_ORIGIN}${this.fileDescarga }`,'_blank')
  }

  
    get f() { return this.checkoutForm.controls; }
    
    onSubmit() {
      console.log(this.fileStatus);

      this.checkoutForm.patchValue({
        file:  this.fileStatus ,
        id_municipio:  this.id_municipio 

      });
     
      this.submitted = true;  
      if (this.checkoutForm.invalid) {
         return;
      }
      this.licenciaService.updateStatusHistorico(this.checkoutForm.value,this._data.id['id']).subscribe(
          resp=>{  
             
        Swal.fire({
            title: '¡Éxito!',
            text: 'Guardado correctamente!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
        this.matDialogRef.close()
      })           
  }
  onReset() {
        this.submitted = false;
        this.checkoutForm.reset();
  }
  updateStatus(){ 
    this.matDialogRef.close()
  }
}
