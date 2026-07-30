import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { environment } from '@env/environment';
import { fuseAnimations } from '@fuse/animations';
import { ConsultaRequisitos } from 'app/models/administrador/consulta_requisito.models';
import { RevisionService } from 'app/routes/tramites/revision/services/revision.service';
import { CamposTramiteServiceService } from 'app/services/tramite/iniciar-tramite/campos-tramite-service.service';
import { ResumenService } from 'app/services/tramite/resumen.service';
import { DialogHistorialComponent } from '../dialog-historial/dialog-historial.component';

@Component({
  selector: 'visor-resumen-tramite',
  templateUrl: './resumen-tramite.component.html',
  styleUrls: ['./resumen-tramite.component.scss'],
  animations:fuseAnimations
})
export class ResumenTramiteComponent implements OnInit {
  campos_dinamicos: [];
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
  constructor(
    public dialog: MatDialog,
    private _campoDinamico: CamposTramiteServiceService,
    private _resumenService: ResumenService,
    private _revision: RevisionService,
    ) {
    }

  ngOnInit(): void {
   //
   
  this.tipo='1';
  if(this.tipo == '1'){
    this.getDataTipo();
  }else{
    this.getData();
  }
  
  if(this.necesitaCadena){
      this.generateCadena();
  }
  }

  getData(){
          
    this._resumenService.getInfoTramite(this.folio).subscribe(
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
      },e=>{
        console.error(e);
      }
    );
    this._campoDinamico.getCamposDinamicos().subscribe(
      (res: any) => {
      
        this.campos_dinamicos = JSON.parse(JSON.stringify(res[0].data));
        this.campos_dinamicos_anexo1 = (res[1][0].data[0].value);
        this.campos_dinamicos_anexo2 = (res[1][0].data[1].value);
        this.campos_dinamicos_anexo3 = (res[1][0].data[2].value);
        this.campos_dinamicos_anexo4 = (res[1][0].data[3].value);
      
        console.log(res[1][0].data[3].value)
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

  openDialog(row){
    this.dialog.open(DialogHistorialComponent, {
      data: {
        detalle: row,
      },
    });
  }
  getDataTipo(){
          
    this._resumenService.getInfoTramiteTipo(this.folio).subscribe(
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
   

    this._resumenService.getFilesTipo(this.id_tramite).subscribe(
      (r:any)=>{
        this.archivos = r.data;
      
      },e=>{
      }
    );



    
      },e=>{
        console.error(e);
      }
    );
    this._campoDinamico.getCamposDinamicos().subscribe(
      (res: any) => {
        //console.log(res);
        
        this.campos_dinamicos = JSON.parse(JSON.stringify(res[0].data));
     
        this.campos_dinamicos_anexo1 = (res[1][0].data[0].value);
        this.campos_dinamicos_anexo2 = (res[1][0].data[1].value);
        this.campos_dinamicos_anexo3 = (res[1][0].data[2].value);
        this.campos_dinamicos_anexo4 = (res[1][0].data[3].value);
        this.loadingCampos=false;
      },
      error => {
        this.loadingCampos=false;
      }
    );

    
    this._revision
      .getDataRevision(this.folio)
      .toPromise()
      .then((r: any) => {
        console.log(r);
        this.dataRevision = r.data.revision;
        this.dataResolucion = r.data.resolucion;
        this.dataSolventacion = r.data.solventacion;
        this.resolucionRevisores = r.data.resolucion_revisores;
        
        if(this.resolucionRevisores!=undefined){
          this.dataResolucion = this.dataResolucion.concat(this.resolucionRevisores);
        }

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
        switch (this.infoTramite.caracter_solicitante) {
          case 'Carta poder':
              nombre_persona = this.findName('nombre_apoderado').value+' '
                    +this.findName('apellido_1_apoderado').value+' '
                    +this.findName('apellido_2_apoderado').value;
              curp_persona = this.findName('curp_apoderado').value;
            break;
          case 'Propietario':
              nombre_persona = this.findName(this.infoTramite.tipo_persona=='Moral' ? 'nombre_apoderado_moral' :'nombre_propietario').value+' '
                  +this.findName(this.infoTramite.tipo_persona=='Moral' ? 'apellido_1_apoderado_moral' :'apellido_1_propietario').value+' '
                  +this.findName(this.infoTramite.tipo_persona=='Moral' ? 'apellido_2_apoderado_moral' :'apellido_2_propietario').value;
              curp_persona = this.findName(this.infoTramite.tipo_persona=='Moral' ? 'curp_apoderado_moral' :'curp_propietario').value;
            
            break;
          case 'Arrendatario':
              nombre_persona = this.findName(this.infoTramite.tipo_persona=='Moral' ? 'nombre_apoderado_moral' :'nombre_arrendatario').value+' '
                  +this.findName(this.infoTramite.tipo_persona=='Moral' ? 'apellido_1_apoderado_moral' :'apellido_1_arrendatario').value+' '
                  +this.findName(this.infoTramite.tipo_persona=='Moral' ? 'apellido_2_apoderado_moral' :'apellido_2_arrendatario').value;
              curp_persona = this.findName(this.infoTramite.tipo_persona=='Moral' ? 'curp_apoderado_moral' :'curp_arrendatario').value;
            break;
        
        }
        this.curp = curp_persona.toUpperCase();
        const fechaC = new Date();
        this.cadena=`Nombre|=|${nombre_persona.toUpperCase()} |+|Tramite|=|${this.folio}|+|Actividad|=|${this.infoTramite.codigo_scian}|+|RFC|=|SDASDADSA|+|CURP|=|${curp_persona.toUpperCase()}|+|FECHA|=|${fechaC.getDate()}-${fechaC.getMonth()}-${fechaC.getFullYear()} ${fechaC.getHours()}:${fechaC.getMinutes()}`;
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

}
