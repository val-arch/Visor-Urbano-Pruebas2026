import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { MunicipioService } from 'app/services/administrador/municipios/municipio.service';
import { FileUploadService } from 'app/services/administrador/municipios/file-upload.service';
import { MunicipioModel } from '../../../../models/administrador/municipio.models';
import { LocalStorageService } from '@shared/services/storage.service';
import Swal from 'sweetalert2';
import { ActivatedRoute } from '@angular/router';
@Component({
  selector: 'app-municipio-form',
  templateUrl: './municipio-form.component.html',
  styleUrls: ['./municipio-form.component.scss']
})
export class MunicipioFormComponent implements OnInit {

  constructor(  
    private _formBuilder: FormBuilder,
    private municipioService:MunicipioService,
    private uploadFileService: FileUploadService,
    private route: ActivatedRoute,
    private _store:LocalStorageService) { 
      this.dialogTitle     = 'Editar Municipio';
      this.idMunicipio = this.route.snapshot.params.id;
    }
    idMunicipio = 0;
      user:any;
      municipio :MunicipioModel;
      action: string;
      imagenSubir: File;
      imagenSubirFirma: File;
      municipioForm: FormGroup;
      firma = [];
      dialogTitle: string;
      list = [];

  ngOnInit(): void {
    
    this.user = this._store.get('usr');
    this.getMunicipio(); 
    this.getFirmas();
    this.municipioForm = this._formBuilder.group({
      nombre         :['',Validators.required],
      area_encargada :['',Validators.required],
      director       :['',Validators.required],
      direccion      :['',Validators.required],
      telefono       :['',Validators.required],
      dias_solventar :[''],
      folio_init     :[''],
      ficha_tramite  :['',Validators.required],
      precio_lic     :['',Validators.required],
      licencias_enlinea  :['',Validators.required],
      generar_licencia_ventanilla  :['',Validators.required],
      restricciones_licencia  :['',[Validators.required,Validators.maxLength(700)]],
    })
    this.firma[1] = this._formBuilder.group({
      nombre_firma         :['',Validators.required],
      cargo_firma :['',Validators.required],
      firma_img :[],
      id: 0,
    })
    this.firma[2] = this._formBuilder.group({
      nombre_firma         :['',Validators.required],
      cargo_firma :['',Validators.required],
      firma_img :[],
      id: 0,
    })
    this.firma[3] = this._formBuilder.group({
      nombre_firma         :['',Validators.required],
      cargo_firma :['',Validators.required],
      firma_img :[],
      id: 0,
    })
    this.firma[4] = this._formBuilder.group({
      nombre_firma         :['',Validators.required],
      cargo_firma :['',Validators.required],
      firma_img :[],
      id: 0,
    })

  }  
  actualizarMunicipio(){
    // console.log(this.municipioForm);
    if(this.municipioForm.invalid){
      return;
    }
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
     this.uploadFileService.actualizarImagen(this.imagenSubir,this.list['id'])
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
    let request = this.municipioService.getMunicipio(this.idMunicipio);
    request.subscribe((res: any) => {
      this.list          = res.data;
      this.municipioForm = this._formBuilder.group({
        nombre          :[{value:this.list['nombre'],disabled:true},Validators.required],
        area_encargada  :[ this.list['area_encargada'],Validators.required],
        director        :[ this.list['director'],Validators.required],
        direccion       :[ this.list['direccion'],Validators.required],
        telefono        :[ this.list['telefono'],Validators.required],
        id              :[ this.list['id'],Validators.required],
        dias_solventar  :[ this.list['dias_solventar'],Validators.required],
        folio_init  :[ this.list['folio_init']],
        ficha_tramite   :[ this.list['ficha_tramite'],Validators.required],
        precio_lic   :[ this.list['precio_lic'],Validators.required],
        licencias_enlinea   :[ this.list['emitir_licencia'],Validators.required],
        generar_licencia_ventanilla   :[ this.list['generar_licencia_ventanilla'],Validators.required],
        restricciones_licencia   :[ this.list['restricciones_licencia'],[Validators.maxLength(700)]],
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
      let r = this.municipioService.updateFirma(order,data).subscribe((result:any)=>{
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
        this.municipioService.deleteFirma(this.firma[id].value.id).subscribe((r)=>{
          this.firma[id].reset();
        })
      }
    })
  }

  getFirmas(){
    let request = this.municipioService.getFirmas(this.idMunicipio);
    request.subscribe((res: any) => {
      // console.log(res);
      let d = res.data;
      for (let i = 0; i < d.length; i++) {
        if(i>3){
          return;
        }
        const element = d[i];
        this.firma[element.orden] = this._formBuilder.group({
          nombre_firma  :[element.nombre_firmante,Validators.required],
          cargo_firma   :[element.dependencia,Validators.required],
          firma_img   :[element.firma],
          id            :element.id,
        })
      }
    },
    error => {
      Swal.fire('Upss.','Algo a pasado, intentar más tarde','warning')
    });
  }

}
