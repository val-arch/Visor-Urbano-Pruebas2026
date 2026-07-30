import { Component, EventEmitter, Input, OnInit, Output, AfterViewInit} from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { environment } from '@env/environment';
import { fuseAnimations } from '@fuse/animations';
import { ConsultaRequisitos } from 'app/models/administrador/consulta_requisito.models';
import { RevisionService } from 'app/routes/tramites/revision/services/revision.service';
import { CamposTramiteServiceService } from 'app/services/tramite/iniciar-tramite/campos-tramite-service.service';
import { ResumenService } from 'app/services/tramite/resumen.service';
import { DialogHistorialConstruccionComponent } from '../dialog-historial-construccion/dialog-historial-construccion.component';
@Component({
  selector: 'app-resumen-tramite-construccion',
  templateUrl: './resumen-tramite-construccion.component.html',
  styleUrls: ['./resumen-tramite-construccion.component.scss']
})
export class ResumenTramiteConstruccionComponent implements OnInit {

  campos_dinamicos:any [];
  campos_dinamicos_anexo1;
  campos_dinamicos_anexo2;
  campos_dinamicos_anexo3;
  campos_dinamicos_anexo4;
  @Input() folio: string;
  @Input() tipo: string;
  @Input() necesitaCadena: boolean = false;
  @Output() cadenaAfirmar = new EventEmitter();
  @Output() loadingOff = new EventEmitter();
  id_tramite;
  cadena = '';
  archivos;
  curp;
  infoTramite: ConsultaRequisitos;
  loading = true;
  loadingCampos = true;
  consultaPDF = '';
  env = environment.SERVER_ORIGIN;
  dataRevision;
  dataResolucion;
  dataSolventacion;
  resolucionRevisores;
  modelRequsito;
  showResoluciones = false;
  arrayNameFiles = [];
  arrayValuesFiles = [];
  mostrar_default = true;
  flujo:any = [];
  tramiteNombre = '';
  campos_default : any = [];
  constructor(
    public dialog: MatDialog,
    private _campoDinamico: CamposTramiteServiceService,
    private _resumenService: ResumenService,
    private _revision: RevisionService,
  ) {
  }

  ngOnInit(): void {
    //  this.getDataTipo();
    this.getData();
    if (this.necesitaCadena) {
      //   this.generateCadena();
    }
  }

  

  getData() {
    console.log('folio: ' + this.folio);
    this._resumenService.getInfoTramiteConstruccion(this.folio).subscribe(
      (r: any) => {
        console.log(r);
        this.tramiteNombre = r.data.info.tramite;

        this.infoTramite = r.data.info;
        this.id_tramite = r.data.info.id;

        this.consultaPDF = `${environment.SERVER_ORIGIN}consulta_requisitosConstruccion/requisitos/${btoa(this.folio)}/${btoa(r.data.info.id.toString())}`;
        this.loading = false;
        this.loadingOff.emit({ loading: false, id_tramite: this.id_tramite, cadenaFirmada: r.data.firma, cartaResponsiva: r.data.info.carta_responsiva || 0, lic_v: r.data.lic_v });
        this._resumenService.getTipoTramite(r.data.info.tramite_relacionado).subscribe((p: any) => {
          this.mostrar_default = p.default_preguntas;
          if(p.length>0){
            this.mostrar_default = p[0].default_preguntas
          }
        }, e => {
              console.error(e);
        });
        this._resumenService.getFlujoResolucion(this.folio).subscribe((t: any) => {
          this.flujo = t.data;
          console.log(this.flujo);
        }, e => {
              console.error(e);
        });
        this.getRequisitosConstruccion(this.folio);
      }, e => {
        console.error(e);
      }
    );
    
    this._campoDinamico.getCamposDinamicosConstruccion2().subscribe(
      (res: any) => {
        var temp = res[3][0];
        this.campos_dinamicos = JSON.parse(JSON.stringify(res[0].data));
        this.campos_dinamicos = JSON.parse(JSON.stringify(res[0].data.concat(temp)));
        console.log(this.campos_dinamicos);
        const indexInt = this.campos_dinamicos.findIndex(y => y.name == 'int_tipo_persona');
        const indexProp = this.campos_dinamicos.findIndex(y => y.name == 'prop_tipo_persona');

        const fedatarioIndex = this.campos_dinamicos.findIndex(y => y.name == 'fedatario_publico');

        if(fedatarioIndex >0){
          var opciones = this.campos_dinamicos[fedatarioIndex].opciones.split("|");
          this.campos_dinamicos[fedatarioIndex].value = opciones[this.campos_dinamicos[fedatarioIndex].value -1];
        }

        const docPropiedadIndex = this.campos_dinamicos.findIndex(y => y.name == 'tipo_dcto_propiedad');

        if(docPropiedadIndex >0){
          var opciones = this.campos_dinamicos[docPropiedadIndex].opciones.split("|");
          this.campos_dinamicos[docPropiedadIndex].value = opciones[this.campos_dinamicos[docPropiedadIndex].value -1];
        }
        
        
        if(this.campos_dinamicos[indexInt].value == 'Fisica'){
          var arrayMoral = ['int_moral_rsocial', 'int_moral_rfc', 'int_moral_calle', 'int_moral_no_ext', 'int_moral_no_int', 'int_moral_colonia', 'int_moral_localidad', 'int_moral_mpio', 'int_moral_cp'];
          for(var x=0; x<arrayMoral.length; x++){
            const index = this.campos_dinamicos.findIndex(y => y.name == arrayMoral[x]);
            if(index > -1){
              this.campos_dinamicos.splice(index, 1);
            }
          }
        }else if(this.campos_dinamicos[0].value == 'Moral'){
            //var arrayFisica = ['int_moral_rsocial', 'int_moral_rfc', 'int_moral_calle', 'int_moral_no_ext', 'int_moral_no_int', 'int_moral_colonia', 'int_moral_localidad', 'int_moral_mpio', 'int_moral_cp'];

        }

        if(this.campos_dinamicos[indexProp].value == 'Fisica'){
          var arrayMoral2 = ['propmoral_rsocial', 'propmoral_rfc', 'propmoral_calle', 'propmoral_no_ext', 'propmoral_no_int', 'propmoral_colonia', 'propmoral_localidad', 'propmoral_mpio', 'propmoral_cp'];
          for(var x=0; x<arrayMoral2.length; x++){
            const index = this.campos_dinamicos.findIndex(y => y.name == arrayMoral2[x]);
            if(index > -1){
              this.campos_dinamicos.splice(index, 1);
            }
          }
        }else if(this.campos_dinamicos[0].value == 'Moral'){
            //var arrayFisica = ['int_moral_rsocial', 'int_moral_rfc', 'int_moral_calle', 'int_moral_no_ext', 'int_moral_no_int', 'int_moral_colonia', 'int_moral_localidad', 'int_moral_mpio', 'int_moral_cp'];

        }
        
        
        /*    this.campos_dinamicos_anexo1 = (res[1][0].data[0].value);
          this.campos_dinamicos_anexo2 = (res[1][0].data[1].value);
          this.campos_dinamicos_anexo3 = (res[1][0].data[2].value);
          this.campos_dinamicos_anexo4 = (res[1][0].data[3].value);
        */
        // console.log(res[1][0].data[3].value)
        this.loadingCampos = false;
      },
      error => {
        this.loadingCampos = false;
      }
    );
    this._revision
      .getDataRevisionConstruccion(this.folio)
      .toPromise()
      .then((r: any) => {
        this.dataRevision = r.data.revision;
        this.dataResolucion = r.data.resolucion;
        this.dataSolventacion = r.data.solventacion;
        this.resolucionRevisores = r.data.resolucion_revisores;
        //this.modelRequsito.resolucion = r.data.resolucion.length > 0 ? r.data.resolucion[0].resolucion_text : '';
        //this.modelRequsito.status_resolucion = r.data.resolucion.length ? r.data.resolucion[0].resolucion_status.toString() : "";
        for(let i=0; i<this.dataResolucion.length; i++){
          if(this.dataResolucion[i].resolucion_text=="Emitio Prorroga"){
            this.getFilesProrroga();
          }
        }
        if(this.dataResolucion.length>0){
          this.showResoluciones = true;
        }
        

      }).catch(e => {
        console.log(e);
      });

  }
  downloadAnexo(row) {
    window.open(
      `${environment.SERVER_ORIGIN}${row}`,
      "_blank"
    );
  }
  download(row) {
    window.open(
      `${environment.SERVER_ORIGIN}${row.archivo}`,
      "_blank"
    );
  }

  downloadFilesProrroga(index) {
    
    window.open(
      `${environment.SERVER_ORIGIN}`+ this.arrayValuesFiles[index],
      "_blank"
    );
  }

  openDialog(row){
      console.log(row);
     this.dialog.open(DialogHistorialConstruccionComponent, {
       data: {
         detalle: row,
       },
     });
   }
  getDataTipo() {

    this._resumenService.getInfoTramiteConstruccionTipo(this.folio).subscribe(
      (r: any) => {
        ////console.log(this.folio);
        this.infoTramite = r.data.info;
        this.id_tramite = r.data.info.id;
        ////console.log(this.infoTramite)
        this.consultaPDF = `${environment.SERVER_ORIGIN}consulta_requisitos/requisitos/${btoa(this.folio)}/${btoa(r.data.info.id.toString())}`;
        this.loading = false;
        this.loadingOff.emit({ loading: false, id_tramite: this.id_tramite, cadenaFirmada: r.data.firma, cartaResponsiva: r.data.info.carta_responsiva || 0, lic_v: r.data.lic_v });
        //    this.generateCadena();


        this._resumenService.getFilesConstruccionTipo(this.id_tramite).subscribe(
          (r: any) => {
            this.archivos = r.data;

          }, e => {
          }
        );




      }, e => {
        console.error(e);
      }
    );
    this._campoDinamico.getCamposDinamicosConstruccion().subscribe(
      (res: any) => {
        //console.log(res);
        this.campos_dinamicos = JSON.parse(JSON.stringify(res[0].data));

        this.loadingCampos = false;
      },
      error => {
        this.loadingCampos = false;
      }
    );


    this._revision
      .getDataRevisionConstruccion(this.folio)
      .toPromise()
      .then((r: any) => {

        this.dataRevision = r.data.revision;
        this.dataResolucion = r.data.resolucion;
        this.dataSolventacion = r.data.solventacion;
        this.resolucionRevisores = r.data.resolucion_revisores;

        this.modelRequsito.resolucion =
          r.data.resolucion.length > 0 ? r.data.resolucion[0].resolucion_text : '';
        this.modelRequsito.status_resolucion =
          r.data.resolucion.length ? r.data.resolucion[0].resolucion_status.toString() : "";

      }).catch(e => {
        console.log(e);
      });



  }



  generateCadena() {
    const inter = setInterval(() => {
      //  ////console.log('Prueba');
      if (this.loading == false && this.loadingCampos == false) {
        let nombre_persona,
          curp_persona;
        this.infoTramite.tipo_persona;
        this.infoTramite.caracter_solicitante;
        switch (this.infoTramite.caracter_solicitante) {
          case 'Carta poder':
            nombre_persona = this.findName('nombre_apoderado').value + ' '
              + this.findName('apellido_1_apoderado').value + ' '
              + this.findName('apellido_2_apoderado').value;
            curp_persona = this.findName('curp_apoderado').value;
            break;
          case 'Propietario':
            nombre_persona = this.findName(this.infoTramite.tipo_persona == 'Moral' ? 'nombre_apoderado_moral' : 'nombre_propietario').value + ' '
              + this.findName(this.infoTramite.tipo_persona == 'Moral' ? 'apellido_1_apoderado_moral' : 'apellido_1_propietario').value + ' '
              + this.findName(this.infoTramite.tipo_persona == 'Moral' ? 'apellido_2_apoderado_moral' : 'apellido_2_propietario').value;
            curp_persona = this.findName(this.infoTramite.tipo_persona == 'Moral' ? 'curp_apoderado_moral' : 'curp_propietario').value;

            break;
          case 'Arrendatario':
            nombre_persona = this.findName(this.infoTramite.tipo_persona == 'Moral' ? 'nombre_apoderado_moral' : 'nombre_arrendatario').value + ' '
              + this.findName(this.infoTramite.tipo_persona == 'Moral' ? 'apellido_1_apoderado_moral' : 'apellido_1_arrendatario').value + ' '
              + this.findName(this.infoTramite.tipo_persona == 'Moral' ? 'apellido_2_apoderado_moral' : 'apellido_2_arrendatario').value;
            curp_persona = this.findName(this.infoTramite.tipo_persona == 'Moral' ? 'curp_apoderado_moral' : 'curp_arrendatario').value;
            break;

        }
        //this.curp = curp_persona.toUpperCase();
        const fechaC = new Date();
        this.cadena = `Nombre|=|${nombre_persona.toUpperCase()} |+|Tramite|=|${this.folio}|+|Actividad|=|${this.infoTramite.codigo_scian}|+|RFC|=|SDASDADSA|+|CURP|=|${curp_persona.toUpperCase()}|+|FECHA|=|${fechaC.getDate()}-${fechaC.getMonth()}-${fechaC.getFullYear()} ${fechaC.getHours()}:${fechaC.getMinutes()}`;
        this.cadenaAfirmar.emit({
          cadena: this.cadena,
          curp: this.curp
        });
        clearInterval(inter);
      }
    }, 500)
  }


  findName(val): any {
    const res = this.campos_dinamicos.find((campo: any) => campo.name == val)
    ////console.log(res);
    return res;
  }

  getRequisitosConstruccion(folio: string) {
    this._campoDinamico.getRequisitosConstruccion(btoa(folio)).subscribe(
      (res: any) => {
        this.campos_default = res[0];
        this.campos_default.superficie_construir = parseFloat(res[0].superficie_habitacional) + parseFloat(res[0].superficie_comercial_servicios)
        + parseFloat(res[0].superficie_industrial) + parseFloat(res[0].superficie_turistico) + parseFloat(res[0].superficie_equipamiento) + parseFloat(res[0].superficie_espacios_verdes)
        + parseFloat(res[0].superficie_otro);
      },
      error => {
        console.log(error)
      }
    );
  }

  getFilesProrroga(){

    this._resumenService.getFilesProrroga(this.folio).subscribe((r: any) => {
      this.arrayNameFiles = Object.values(JSON.parse(r.data[0].nombres_archivos_prorroga));
      this.arrayValuesFiles = Object.values(JSON.parse(r.data[0].rutas_archivos_prorroga));
    }, e => {});

  }

  radioOption(item) {
    if (item.type == "radio" || item.type == "select") {
      //console.log(item);
      let opc, resp;
      opc = item.opciones.split('|');
      resp = item.opciones_desc.split('|');
      let r = resp.indexOf(item.value);
      return opc[r];
    }
    //return  item.opciones.split('|').find(element=> element == item.value)
  }

  showLicense(id){
    
    window.open(this.env+"licenciaConstruccionById/"+btoa(id));
  }

}
