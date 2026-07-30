import { HttpErrorResponse, HttpEventType } from '@angular/common/http';
import { Component, Inject, OnInit } from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { FuseSplashScreenService } from '@fuse/services/splash-screen.service';
import { Role } from 'app/models/administrador/role';
import { HistoricoLicenciaService } from 'app/services/historico/historico-licencia.service';
import { of } from 'rxjs';
import { catchError, map } from 'rxjs/operators';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-historico-dialog',
  templateUrl: './historico-dialog.component.html',
  styleUrls: ['./historico-dialog.component.scss']
})

export class HistoricoDialogComponent implements OnInit {
  public capaForm: FormGroup;
  public roleForm   :FormGroup;
  public modelRole  :Role = new Role(); 
  loading = false;
  btnCss = {
    'background-color': '#70CE68',
     'color': 'white' 
  };
  btnCssCancel = {
    'background-color': 'red',
     'color': 'white' 
  };
  constructor(public matDialogRef: MatDialogRef<HistoricoDialogComponent>,
    @Inject(MAT_DIALOG_DATA) private _data: any,
    private fb         : FormBuilder,
    private _historicoLicencia: HistoricoLicenciaService,
    private _splash: FuseSplashScreenService) { }
  ngOnInit(): void {
    this.roleForm = this.fb.group({
      fileExcel         :['',Validators.required],     
    })
  }
  GuardarFiles(){
    this.matDialogRef.close();
  }
  fileChangeEvent(event, keyName): void {
      var numero_files = event.target.files.length;
      this.uploadFile(event.target.files[0], keyName)
  }
  uploadFile(file, argument) {
   this._splash.show();
    const formData = new FormData();
    formData.append("file", file);
    var fileSize = file;;
    if(fileSize.size>=8913637){
     
       Swal.fire({
        title: "Error al subir archivo",
        text: 'El archivo no puede pesar mas de 8MB"',
        icon: "error",
      
  
      }).then((result) => {
       
      });
      this._splash.hide();
      return false;
    }

    this._historicoLicencia.upload(formData, argument).pipe(
      map(event => {
        switch (event.type) {
          case HttpEventType.Sent:
            break;
          case HttpEventType.ResponseHeader:
            break;       
          case HttpEventType.Response:
            this._splash.hide();
            console.log(HttpEventType.Response)
            this.matDialogRef.close();     
        }    
      }),
      catchError((error: HttpErrorResponse) => {
        var Error = error.error.error;
        Swal.fire({
          title: 'Error!',
          text: Error,
          icon: 'error',
          confirmButtonText: 'Ok'
        });
        file.inProgress = false;
        return of(`${argument} upload failed.`);
      })).subscribe((event: any) => {
        
        console.log(this.loading);
        if (typeof (event) === 'object') {
        }
      });
  }
}