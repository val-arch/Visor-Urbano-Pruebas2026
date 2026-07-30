import { Component, ElementRef, Input, OnInit, Output, ViewChild } from '@angular/core';
import { environment } from '@env/environment';
import { EventEmitter } from 'events';
import { ConsultaRequisitos } from 'app/models/administrador/consulta_requisito.models';
import { CamposTramiteServiceService } from 'app/services/tramite/iniciar-tramite/campos-tramite-service.service';
import { ResumenService } from 'app/services/tramite/resumen.service';
import { ActivatedRoute, Router } from '@angular/router';
import { TokenService } from '@core/authentication/token.service';
import { MatDialog } from '@angular/material/dialog';
import { NotificacionesService } from '../../../services/notificaciones/notificaciones.service';
import { FormBuilder } from '@angular/forms';
import Swal from 'sweetalert2';
import { DomSanitizer } from '@angular/platform-browser';
import { ListComponent } from '.././list/list.component';
import { fuseAnimations } from '@fuse/animations';


@Component({
  selector: 'app-detalle',
  templateUrl: './detalle.component.html',
  styleUrls: ['./detalle.component.scss'],
  animations   : fuseAnimations
})
export class DetalleComponent implements OnInit {
  form;
  id_notificacion;
  url;
  tipo;
  id;
  dialogRef;
  role;
  folio64;

  questions2 : [];
  safeUrl    : any;
  folio      = null;
  id_tramite;
  comentarios: string;
  curp;
  loading       = true;
  loader_pdf    = true;
  loadingCampos = true;
  campos_dinamicos: [];
  campos_notificacione: any[];
  consultaPDF    = '';
  consultaPDFs   = '';
  cadena         = '';
  cadena_firmada = '';
  infoTramite: ConsultaRequisitos;
  cartaResponsiva   = '';
  cartaResponsivaUp = '';
  files = null;
  progressFiles = false;
  type;
  id_solventacion;
  

  @ViewChild('fondovalor') fondovalor: ElementRef;
  constructor(private _campoDinamico: CamposTramiteServiceService,
    private _resumenService: ResumenService,
    private route: ActivatedRoute,
    private _matDialog: MatDialog,
    private _token: TokenService,
    private uploadFileService: NotificacionesService,
    private _route: Router,
    private formBuilder: FormBuilder,
    private sanitizer: DomSanitizer) {

    this.folio64 = this.route.snapshot.params.folio;
    this.folio = atob(this.route.snapshot.params.folio);

    this.role = this._token.get().role;
    this.form = formBuilder.group({
      comentarios: ['']
    });
    this.id_notificacion = atob(this.route.snapshot.params.id);
    let typ =atob(this.route.snapshot.params.type);
    this.type = typ;
    console.log(this.type,this.id_notificacion,this.folio)
 
  }
  guardaComentarios() {

    const valueInput = this.fondovalor.nativeElement.value
    console.log(valueInput);
    this.uploadFileService.actualizarSolventacion(valueInput, this.id_solventacion)
      .subscribe(resp => {
        Swal.fire({
          title: '¡Éxito!',
          text: '¡Actualizado correctamente!',
          icon: 'success',
          confirmButtonText: 'Ok'
        }).then((result) => {
          // Read more about isConfirmed, isDenied below 
          if (result.isConfirmed) {
            this._route.navigate(['/tramites']);
          }
        });
      })
  }
  ngOnInit(): void {
    this.getData();
  }
  deleteFiles(position){
   
    var  archivos =this.files;
    const index = archivos.indexOf(position);
    if (index > -1) {
      archivos.splice(index, 1);
    }
    var rutaArchivos = "";
    var interation = -1;
    archivos.forEach(element => {
      interation++;     
      if(interation != position){
       
        if(rutaArchivos == ''){
          rutaArchivos = element;
        }else{
          rutaArchivos = rutaArchivos +'|'+element;
        }
      }
      
    });
  
   this.UpdateFiles(rutaArchivos,this.id_solventacion);
  //  console.log(archivos[position]);
  }
  getFiles(id_tramite){

    this.uploadFileService.getFiles(id_tramite).subscribe(
      (r: any) => {
       var  archivos =r.data.archivos;

        var res = archivos.split("|");
        this.files =res;
      }, e => {
        console.error(e);
      }
    );
  }
  UpdateFiles(file,id_tramite){
    this.progressFiles = true;
    this.uploadFileService.updateFiles(file,this.id_solventacion).subscribe(
      (r: any) => {
        this.getFiles(id_tramite);
        this.progressFiles = false;
      }, e => {
     
      }
    );
  }
  subirFiles(file) {
    this.progressFiles = true;
    this.uploadFileService.actualizarImagen(file, this.id_solventacion)
      .then();
      setTimeout(()=>{                           //<<<---using ()=> syntax
       this.getFiles(this.id_solventacion);
       this.progressFiles = false;
   }, 3000);
  }
  pageRendered(e: CustomEvent) {
    this.loader_pdf = false;
    console.log('(page-rendered)', e);
  }
  submit() {
    if (this.form.valid) {
      console.log(this.form.value)
    }
    else {
      alert("FILL ALL FIELDS")
    }
  }

  getData() {
    this._resumenService.getInfoTramite(this.folio).subscribe(
      (r: any) => {
        this.infoTramite = r.data.info;
        this.id_tramite = r.data.info.id_tramite;
        this.safeUrl = this.sanitizer.bypassSecurityTrustResourceUrl(this.consultaPDFs);
        this.loading = false;
        
      }, e => {
        console.error(e);
      }
    );
    this._campoDinamico.getCamposDinamicos().subscribe(
      (res: any) => {
        console.log(res);
        this.campos_dinamicos = JSON.parse(JSON.stringify(res[0].data));
        // this.loadingCampos = false;
      },
      error => {
        // this.loadingCampos = false;
      }
    );
    this.uploadFileService.getNotificaciones(this.id_notificacion).subscribe(
      (res: any) => {
        console.log(res,'getnoti');
        var datos        = res.data.data;
        datos[0]["type"] = "input";
        this.campos_notificacione = JSON.parse(JSON.stringify(datos));
        this.tipo = datos[0].tipo;
        this.consultaPDF = res.data.data[0].pdf;
        console.log(this.consultaPDF);
        this.loader_pdf = false;
        this.loadingCampos = false;
        if(this.tipo == 2){
          this.id_solventacion = datos[0].id_solventacion;
          this.getFiles(datos[0].id_solventacion);
        }
      },
      error => {
        this.loadingCampos = false;
      }
    );

   
  }

}
