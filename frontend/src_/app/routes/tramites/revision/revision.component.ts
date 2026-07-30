import { AfterViewInit, Component, OnInit } from "@angular/core";
import { ActivatedRoute, Router } from "@angular/router";
import { CamposTramiteServiceService } from "app/services/tramite/iniciar-tramite/campos-tramite-service.service";

import { fuseAnimations } from "@fuse/animations/index";
import { MatDialog } from "@angular/material/dialog";
import { TokenService } from "@core/authentication/token.service";
import { environment } from "@env/environment";
import Swal from "sweetalert2";
import { RevisionService } from "./services/revision.service";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { RevisionM } from "./revision.models";
import { finalize, filter } from 'rxjs/operators';
import { FuseSplashScreenService } from '../../../../@fuse/services/splash-screen.service';

@Component({
  templateUrl: "./revision.component.html",
  styleUrls: [
    "./revision.component.scss",
    "../../ingresar-tramite/formulario-vu.scss",
  ],
  animations: fuseAnimations,
})
export class RevisionComponent implements OnInit {
  formRevision: FormGroup;
  folio = "";
  id_tramite;
  curp;
  loading = true;
  loadingCampos = true;
  consultaPDF = "";
  cadena = "";
  cadena_firmada = "";
  dialogRef;
  role;
  folio64;
  cartaResponsiva = "";
  cartaResponsivaUp = "";
  dataRevision;
  dataResolucion;
  urlArchivo='';
  dataSolventacion = [];
  resolucionRevisores = [];
  loadingForm = true;
  aprobado_director = 0;
  queryP;
  public modelRequsito: RevisionM = new RevisionM();
  constructor(
    private route: ActivatedRoute,
    private _matDialog: MatDialog,
    private _token: TokenService,
    private _route: Router,
    private _revision: RevisionService,
    private fb: FormBuilder,
    private _splash: FuseSplashScreenService
  ) {
    this.folio64 = this.route.snapshot.params.folio;
    this.folio = atob(this.route.snapshot.params.folio);
    this.role = this._token.get().role;
  
    this.route.queryParamMap
      .subscribe((params:any) => {
        this.queryP = params.params.dir; 
      }
    );
    this.createForm();
  }
  ngOnInit(): void { }

  getData() {
    return new Promise((resolve) => {
      this._revision
        .getDataRevision(this.folio)
        .toPromise()
        .then((r: any) => {
          console.log(r);
          this.dataRevision = r.data.revision;
          this.dataResolucion = r.data.resolucion;
          this.dataSolventacion = r.data.solventacion;
          this.resolucionRevisores = r.data.resolucion_revisores;
          if(this.role==4){
            this.aprobado_director = r.data.tramite.aprobado_director ?? 0;
          }
          this.modelRequsito.resolucion =
            r.data.resolucion.length > 0 ? r.data.resolucion[0].resolucion_text : '';
          this.modelRequsito.status_resolucion =
            r.data.resolucion.length ? r.data.resolucion[0].resolucion_status.toString() : "";
          resolve("Resolved");
        }).catch(e => {
          resolve("false")
           console.log(e);
        });
    });
  }

  async createForm() {
    console.log("llamando");
    await this.getData();
    this.formRevision = this.fb.group({
      resolucion: [this.modelRequsito.resolucion, Validators.required],
      status_resolucion: [
        this.modelRequsito.status_resolucion,
        Validators.required,
      ],
    });
    this.loadingForm = false;
  }
  validarButton(): boolean{
    let data: {
      resolucion,
      status_resolucion,
      archivo_url
    } = this.formRevision.value;
    data.archivo_url = this.urlArchivo;
    // (dataRevision.status_actual==3 || dataRevision.status_actual==null && role !=4) || (role==4 && dataRevision.status_actual==1)
    if(this.role == 4){
      if(this.queryP!=''  && this.aprobado_director==0 && data.status_resolucion!='' && data.resolucion != ''){
        return true;
      }else{
        return false;
      }
    }else{
      if(data.status_resolucion != '' && data.resolucion != '' && (this.dataRevision.status_actual==3 || this.dataRevision.status_actual==null)){
        return true
      }else{
        return false;
      }
    }
    
  }
  enviar() {
    if (this.formRevision.valid) {
      // if(this.urlArchivo==''){
      //   Swal.fire({
      //     title: 'Upps!',
      //     text: 'Adjuntar archivo!',
      //     icon: 'info',
      //     confirmButtonText: 'Continuar'
      //   })
      //   return 
      // }
      this._splash.show();
      let data: {
        resolucion,
        status_resolucion,
        archivo_url
      } = this.formRevision.value;
      data.archivo_url = this.urlArchivo;
      if(this.queryP!='' && this.role == 4){
        this._revision.uploadDataRevisionDir(this.folio, this.formRevision.value).subscribe(r => {
          this._splash.hide();
          Swal.fire({
            title: 'Resolución guardada',
            icon: 'success',
            confirmButtonText: 'Continuar'
          }).then(e=>{
            console.log(e)
            this._route.navigate(['/tramites']);
          })
        },e=>{
          this._splash.hide();
          Swal.fire({
            title: '¡Algo a ocurrido!',
            text:'Intentar más tarde o contactar al soporte',
            icon: 'error',
            confirmButtonText: 'Continuar'
          })
        });

      }else{
        this._revision.uploadDataRevision(this.folio, this.formRevision.value).subscribe(r => {
          this._splash.hide();
          Swal.fire({
            title: 'Resolución guardada',
            icon: 'success',
            confirmButtonText: 'Continuar'
          }).then(e=>{
            console.log(e)
            this._route.navigate(['/tramites']);
          })
        },e=>{
          this._splash.hide();
          Swal.fire({
            title: '¡Algo a ocurrido!',
            text:'Intentar más tarde o contactar al soporte',
            icon: 'error',
            confirmButtonText: 'Continuar'
          })
        });
      }
      
    }
  }
  uploadFile(event) {
    const formData = new FormData();
    formData.append("file", event.target.files[0]);
    formData.append("carta", "true");
    this._revision.uploadFileRevision(this.folio, formData).subscribe((r: any) => {
      console.log(r);
      this.urlArchivo = `${environment.SERVER_ORIGIN}${r.data}`;
    }, e => {
      console.error(e);
    });

  }
}
