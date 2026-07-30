import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { MunicipioService } from 'app/services/administrador/municipios/municipio.service';
import { FileUploadService } from 'app/services/administrador/municipios/file-upload.service';
import { UseradminService } from 'app/services/administrador/usuarios/useradmin.service';
import { MunicipioModel } from '../../../../models/administrador/municipio.models';
import { LocalStorageService } from '@shared/services/storage.service';
import Swal from 'sweetalert2';
import { ActivatedRoute } from '@angular/router';
import { MatDialog } from '@angular/material/dialog';
import { DialogAddTipoTramiteComponent } from '../dialog-add-tipo-tramite/dialog-add-tipo-tramite.component';
import { DialogAddFirmaComponent } from '../dialog-add-firma/dialog-add-firma.component';
import { DialogTemplatesComponent } from '../dialog-templates/dialog-templates.component';

@Component({
  selector: 'app-municipio-form',
  templateUrl: './municipio-form.component.html',
  styleUrls: ['./municipio-form.component.scss']
})
export class MunicipioFormComponent implements OnInit {
  usuariosSelect: [];
  displayedColumns: string[] = ['role', 'orden'];
  dialogRef;
  constructor(  
    private _formBuilder: FormBuilder,
    private municipioService:MunicipioService,
    private uploadFileService: FileUploadService,
    private route: ActivatedRoute,
    private _user: UseradminService,
    private _matDialog: MatDialog,
    private _store:LocalStorageService) { 
      this.dialogTitle     = 'Datos de la dependencia';
      this.idMunicipio = this.route.snapshot.params.id;
  }

  tiposTramite:any=  [];
  idMunicipio = 0;
  user:any;
  municipio :MunicipioModel;
  action: string;
  imagenSubir: File;
  imagenSubirFirma: File;
  municipioForm: FormGroup;
  firma: any = [];
  dialogTitle: string;
  list = [];
  firmaLoading = false;

  ngOnInit(): void {
    this.user = this._store.get('usr');
    this.getFirmas();
    
    this.getMunicipio(); 
    this.municipioForm = this._formBuilder.group({
      nombre         :['',Validators.required],
      area_encargada :['',Validators.required],
      director       :['',Validators.required],
      direccion      :['',Validators.required],
      telefono       :['',Validators.required],
      correo_dependencia :['',Validators.required],
      dias_solventar :[''],
      //folio_init     :[''],
      ficha_tramite  :['',Validators.required],
      //precio_lic     :['',Validators.required],
      licencias_enlinea  :['',Validators.required],
      generar_licencia_ventanilla  :['',Validators.required],
      restricciones_licencia  :['',[Validators.required,Validators.maxLength(700)]],
      usuarios_emision  :[''],
    })
    this.firma[1] = this._formBuilder.group({
      nombre_firma         :['',Validators.required],
      cargo_firma :['',Validators.required],
      firma_img :[],
      id: 0,
    })
    
    this.getUsersByTypeAndMunicipality();
    this.getTipoTramites();

  }

  addTipoTramite(){
    this.dialogRef = this._matDialog.open(DialogAddTipoTramiteComponent, {
      panelClass: 'contact-form-dialog',
      data: {
        type : 'new',
        id_municipio: this.user.id_municipio,
      },
      width: 'auto'
    });
    this.dialogRef.afterClosed().subscribe(result => {
      this.getTipoTramites();
    });
  }

  addFirma(){
    this.dialogRef = this._matDialog.open(DialogAddFirmaComponent, {
      panelClass: 'contact-form-dialog',
      data: {
        type : 'new',
        id_municipio: this.user.id_municipio,
      },
      width: 'auto'
    });
    this.dialogRef.afterClosed().subscribe(result => {
      this.getFirmas();
    });
  }

  editTipoTramite(index){
    this.dialogRef = this._matDialog.open(DialogAddTipoTramiteComponent, {
      panelClass: 'contact-form-dialog',
      data: {
        type : 'edit',
        data : this.tiposTramite[index]
      },
      width: 'auto'
    });
    this.dialogRef.afterClosed().subscribe(result => {
      this.getTipoTramites();
    });
  }

  editFirma(index){
    this.dialogRef = this._matDialog.open(DialogAddFirmaComponent, {
      panelClass: 'contact-form-dialog',
      data: {
        type : 'edit',
        data : this.firma[index]
      },
      width: 'auto'
    });
    this.dialogRef.afterClosed().subscribe(result => {
      this.getFirmas();
    });
  }

  /*changeOrder(id, value){
    this.municipioService.updateOrdenResolucion(id, value).subscribe(resp=>{
      this.getOrdenResolucion();
      Swal.fire({
        title: '¡Éxito!',
        text: '¡Actualizado correctamente!',
        icon: 'success',
        confirmButtonText: 'Ok'
      });
    });
  }*/
  
  actualizarMunicipio(){
    // console.log(this.municipioForm);
    if(this.municipioForm.invalid){
      return;
    }
    this.municipioService.actualizarMunicipioConstruccion(this.municipioForm.value)
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
     this.uploadFileService.actualizarImagenConstruccion(this.imagenSubir,this.list['id'])
     .then();
  }
  uploadFileFirma(file:File,id){
    // console.log(file);
    setTimeout(f=>{
      this.firma[id].patchValue({
        firma_img:file,
      })
    },200);
    
    return;
     this.imagenSubirFirma = file;
     this.uploadFileService.actualizarImagenFirma(this.imagenSubirFirma,this.list['id'])
     .then();
  }
  getMunicipio(): void {
    let request = this.municipioService.getMunicipioConstruccion(this.user.id_municipio);
    request.subscribe((res: any) => {
      this.list          = res.data;
      this.municipioForm = this._formBuilder.group({
        nombre          :[{value:this.list['nombre'],disabled:true},Validators.required],
        area_encargada  :[ this.list['area_encargada'],Validators.required],
        director        :[ this.list['director'],Validators.required],
        direccion       :[ this.list['direccion'],Validators.required],
        telefono        :[ this.list['telefono'],Validators.required],
        correo_dependencia        :[ this.list['correo_dependencia'],Validators.required],
        id              :[ this.list['id'],Validators.required],
        dias_solventar  :[ this.list['dias_solventar']],
        //folio_init  :[ this.list['folio_init']],
        ficha_tramite   :[ this.list['ficha_tramite'],Validators.required],
     //   precio_lic   :[ this.list['precio_lic'],Validators.required],
        licencias_enlinea   :[ this.list['emitir_licencia'],Validators.required],
        generar_licencia_ventanilla   :[ this.list['generar_licencia_ventanilla'],Validators.required],
        restricciones_licencia   :[ this.list['restricciones_licencia'],[Validators.maxLength(700)]],
        usuarios_emision: [ this.list['usuarios_emision']],
      })
    },
    error => {
    });
  }  

  actualizarFirma(order: any){
    // console.log(order,this.firma[order])
    // return;
    if(this.firma[order].valid){
      let data = this.firma[order].value;
      let r = this.municipioService.updateFirmaConstruccion(order,data).subscribe((result:any)=>{
        // console.log(result);
        Swal.fire('¡Éxito!','¡Firma actualizada correctamente!','success')
        const {nombre_firmante, dependencia,id,firma }= result.data;
        if(result.data != 1){
          let prueba  = this.firma[order].setValue({
            nombre_firma  :nombre_firmante,
            cargo_firma   :dependencia,
            firma_img     :firma,
            id            :id,
          })
        }
       
      },
      error=>{
        Swal.fire('Upss.','Algo a pasado, intenta más tarde','warning')
      })
    }
  }
  deleteFirma(id){
    let d = Swal.fire({
      title:'¿Seguro que deseas borrar la firma?',
      // text:'Estas apunto de borrar la firma',
      icon:'question',
      showCancelButton: true,
      confirmButtonText:'Confirmar',
      cancelButtonText:'Cancelar',
    }).then((result)=>{
      if(result.isConfirmed){
        this.municipioService.deleteFirmaConstruccion(this.firma[id].value.id).subscribe((r)=>{
          this.firma[id].reset();
        })
      }
    })
  }

  getFirmas(){
    let request = this.municipioService.getFirmasConstruccion(this.user.id_municipio);
    request.subscribe((res: any) => {
      this.firma = res.data;
      this.firmaLoading = true;
    },
    error => {
      Swal.fire('Upss.','Algo a pasado, intentar más tarde','warning')
    });
  }

  getUsersByTypeAndMunicipality(){
    this._user.getUsers4(1, '').subscribe(
      (res: any) => {
        this.usuariosSelect = res.data;
      },
      error => {
        console.log(error);
      }
    );
  }

  getTipoTramites(){
    this.municipioService.getTipoTramites(this.user.id_municipio).subscribe(resp=>{
      this.tiposTramite = resp;
    });
  }

  removeTramite(i) {
    this.municipioService.removeTipoTramites(this.tiposTramite[i].id).subscribe(resp=>{
      this.tiposTramite.splice(i,1);
    });
  }

  removeFirma(i, id) {
    this.municipioService.removeFirma(id).subscribe(resp=>{
      this.firma.splice(i,1);
    });
  }

  openTemplate(i, tramite){
    this.dialogRef = this._matDialog.open(DialogTemplatesComponent, {
      panelClass: 'contact-form-dialog',
      data: {
        type : 'new',
        id_tramite: i,
        nombreTramite: tramite
      },
      width: '100%'
    });
    this.dialogRef.afterClosed().subscribe(result => {
      //this.getTipoTramites();
    });

  }


}
