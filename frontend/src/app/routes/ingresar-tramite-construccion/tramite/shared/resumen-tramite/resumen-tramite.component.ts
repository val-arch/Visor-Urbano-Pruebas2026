import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { environment } from '@env/environment';
import { fuseAnimations } from '@fuse/animations';
import { ConsultaRequisitos } from 'app/models/administrador/consulta_requisito.models';
import { RevisionService } from 'app/routes/tramites/revision/services/revision.service';
import { CamposTramiteServiceService } from 'app/services/tramite/iniciar-tramite/campos-tramite-service.service';
import { ResumenService } from 'app/services/tramite/resumen.service';
//import { DialogHistorialComponent } from '../dialog-historial/dialog-historial.component';

@Component({
  selector: 'visor-resumen-tramite-construccion',
  templateUrl: './resumen-tramite.component.html',
  styleUrls: ['./resumen-tramite.component.scss'],
  animations:fuseAnimations
})
export class ResumenTramiteComponent implements OnInit {
  campos_dinamicos:any [];
  campos_dinamicos_anexo1;
  campos_dinamicos_anexo2;
  campos_dinamicos_anexo3;
  campos_dinamicos_anexo4;
  @Input() folio :string ;
  @Input() tipo :string ;
  @Input() necesitaCadena :boolean =false ;
  @Output() cadenaAfirmar = new EventEmitter(); 
  @Output() loadingOff = new EventEmitter(); 
  id_tramite  ;
  cadena=''  ;
  archivos;
  curp  ;
  infoTramite:ConsultaRequisitos;
  loading=true;
  loadingCampos=true;
  consultaPDF = '';
  env=environment.SERVER_ORIGIN;
  dataRevision;
  dataResolucion;
  dataSolventacion;
  resolucionRevisores;
  modelRequsito;
  mostrar_default = true;
  campos_construccion = [];
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
    this.getRequisitosConstruccion();
  
  
  if(this.necesitaCadena){
    this.generateCadena();
  }
  }

  getData(){
    console.log('folio: ' + this.folio);
    this._resumenService.getInfoTramiteConstruccion(this.folio).subscribe(
      (r:any)=>{
        console.log(r);
        this.infoTramite = r.data.info;
        this.id_tramite = r.data.info.id;
      
        this.consultaPDF = `${environment.SERVER_ORIGIN}consulta_requisitosConstruccion/requisitos/${btoa(this.folio)}/${btoa(r.data.info.id.toString())}`;
        this.loading = false;
        this.loadingOff.emit({loading:false,id_tramite:this.id_tramite,cadenaFirmada:r.data.firma,cartaResponsiva:r.data.info.carta_responsiva || 0,lic_v:r.data.lic_v});
        this._resumenService.getTipoTramite(r.data.info.tramite_relacionado).subscribe((p: any) => {
        this.mostrar_default = p.default_preguntas;
          if(p.length>0){
            this.mostrar_default = p[0].default_preguntas;
          }
        }, e => {
          console.error(e);
        });
      },e=>{
        console.error(e);
      }
    );
    this._campoDinamico.getCamposDinamicosConstruccion2().subscribe(
      (res: any) => {
        console.log(res);
        var temp = res[3][0];
      
        this.campos_dinamicos = JSON.parse(JSON.stringify(res[0].data));
        
        this.campos_dinamicos = JSON.parse(JSON.stringify(res[0].data.concat(temp)));

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

        const indexInt = this.campos_dinamicos.findIndex(y => y.name == 'int_tipo_persona');
        const indexProp = this.campos_dinamicos.findIndex(y => y.name == 'prop_tipo_persona');
        
        
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

        const indexFedatario = this.campos_dinamicos.findIndex(y => y.name == 'fedatario_publico');

        switch(this.campos_dinamicos[indexFedatario].value){
          case 'Otro':{
            const indexNotario = this.campos_dinamicos.findIndex(y => y.name == 'notario_publico');
            this.campos_dinamicos.splice(indexNotario, 1);
            break;
          }
          case 'Notario público':{
            const indexOtro = this.campos_dinamicos.findIndex(y => y.name == 'fedatario_publico_otro');
            this.campos_dinamicos.splice(indexOtro, 1);
            break;
          }
          default:{
            const indexNotario = this.campos_dinamicos.findIndex(y => y.name == 'notario_publico');
            this.campos_dinamicos.splice(indexNotario, 1);
            const indexOtro = this.campos_dinamicos.findIndex(y => y.name == 'fedatario_publico_otro');
            this.campos_dinamicos.splice(indexOtro, 1);
            break;
          }
        }

        const indexOtro2 = this.campos_dinamicos.findIndex(y => y.name == 'tipo_dcto_propiedad');

        if(this.campos_dinamicos[indexOtro2].value != 'Otro'){
          const indexOtro22 = this.campos_dinamicos.findIndex(y => y.name == 'tipo_dcto_propiedad_otro');
          this.campos_dinamicos.splice(indexOtro22, 1);
        }
      /*    this.campos_dinamicos_anexo1 = (res[1][0].data[0].value);
        this.campos_dinamicos_anexo2 = (res[1][0].data[1].value);
        this.campos_dinamicos_anexo3 = (res[1][0].data[2].value);
        this.campos_dinamicos_anexo4 = (res[1][0].data[3].value);
      */
       // console.log(res[1][0].data[3].value)

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
 
       });
        this.loadingCampos=false;
      },
      error => {
        this.loadingCampos=false;
      }
    );
   
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

 /* openDialog(row){
    this.dialog.open(DialogHistorialComponent, {
      data: {
        detalle: row,
      },
    });
  }*/
  getDataTipo(){
          
    this._resumenService.getInfoTramiteConstruccionTipo(this.folio).subscribe(
      (r:any)=>{
        ////console.log(this.folio);
        //console.log(r);
        this.infoTramite = r.data.info;
        this.id_tramite = r.data.info.id;
        ////console.log(this.infoTramite)
        this.consultaPDF = `${environment.SERVER_ORIGIN}consulta_requisitos/requisitos/${btoa(this.folio)}/${btoa(r.data.info.id.toString())}`;
        this.loading = false;
        this.loadingOff.emit({loading:false,id_tramite:this.id_tramite,cadenaFirmada:r.data.firma,cartaResponsiva:r.data.info.carta_responsiva || 0,lic_v:r.data.lic_v});
    //    this.generateCadena();
   

    this._resumenService.getFilesConstruccionTipo(this.id_tramite).subscribe(
      (r:any)=>{
        this.archivos = r.data;
      
      },e=>{
      }
    );



    
      },e=>{
        console.error(e);
      }
    );
    this._campoDinamico.getCamposDinamicosConstruccion().subscribe(
      (res: any) => {
        //console.log(res);
        this.campos_dinamicos = JSON.parse(JSON.stringify(res[0].data));
    
        this.loadingCampos=false;
      },
      error => {
        this.loadingCampos=false;
      }
    );

    
    this._revision
      .getDataRevisionConstruccion(this.folio)
      .toPromise()
      .then((r: any) => {
        console.log(r);
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



  generateCadena(){
    const inter =setInterval(()=>{
    //  ////console.log('Prueba');
      if(this.loading == false && this.loadingCampos==false){
        let nombre_persona,
            curp_persona;
        this.infoTramite.tipo_persona;
        this.infoTramite.caracter_solicitante;
        nombre_persona = this.findName('prop_nombre').value + ' ' + this.findName('prop_apellidouno').value + ' ' + this.findName('prop_apellidodos').value;
        curp_persona = this.findName('prop_curp').value;
        const fechaC = new Date();
        this.cadena=`Nombre|=|${nombre_persona.toUpperCase()} |+|Tramite|=|${this.folio}|+|RFC|=|SDASDADSA|+|CURP|=|${curp_persona.toUpperCase()}|+|FECHA|=|${fechaC.getDate()}-${fechaC.getMonth()}-${fechaC.getFullYear()} ${fechaC.getHours()}:${fechaC.getMinutes()}`;
        this.cadenaAfirmar.emit({
          cadena:this.cadena,
          curp:this.curp
        });
        clearInterval(inter);
      }
    },500)
}


findName(val): any{
  const res = this.campos_dinamicos.find((campo:any) => campo.name ==val)
  ////console.log(res);
  return res;
}

radioOption(item){
  if(item.type=="radio" || item.type=="select"){
    //console.log(item);
    let opc, resp ;
    opc = item.opciones.split('|');
    resp = item.opciones_desc.split('|');
    let r = resp.indexOf(item.value);
    return opc[r];
  }
 //return  item.opciones.split('|').find(element=> element == item.value)
}

getRequisitosConstruccion() {
    this._campoDinamico.getRequisitosConstruccion(btoa(this.folio)).subscribe(
      (res: any) => {

        this.campos_construccion[0] = res[0].superficie_habitacional;
        this.campos_construccion[1] = res[0].superficie_comercial_servicios;
        this.campos_construccion[2] = res[0].superficie_industrial;
        this.campos_construccion[3] = res[0].superficie_turistico;
        this.campos_construccion[4] = res[0].superficie_equipamiento;
        this.campos_construccion[5] = res[0].superficie_espacios_verdes;
        this.campos_construccion[6] = res[0].superficie_otro;
        var total_mts = 0;
        for(var x = 0; x<this.campos_construccion.length; x++){
          total_mts = total_mts + parseFloat(this.campos_construccion[x]);
        }
        this.campos_construccion[7] = total_mts;
        this.campos_construccion[8] = res[0].mdemolicion;
        
        console.log(this.campos_construccion);
      },
      error => {
        console.log(error)
      }
    );
  }

}
