import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { environment } from '@env/environment';
import { fuseAnimations } from '@fuse/animations';
import { ConsultaRequisitos } from 'app/models/administrador/consulta_requisito.models';
import { CamposTramiteServiceService } from 'app/services/tramite/iniciar-tramite/campos-tramite-service.service';
import { ResumenService } from 'app/services/tramite/resumen.service';

@Component({
  selector: 'app-resumen',
  templateUrl: './resumen.component.html',
  styleUrls: ['./resumen.component.scss']
})
export class ResumenComponent implements OnInit {

  campos_dinamicos: [];
  @Input() folio :string ;
  @Input() necesitaCadena :boolean =false ;
  @Input() role :number =0 ;
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
  constructor(
    private _campoDinamico: CamposTramiteServiceService,
    private _resumenService: ResumenService,
    ) {
    }

  ngOnInit(): void {
    this.getData();
    if(this.necesitaCadena){
      this.generateCadena();
    }
  }

  getData(){
          
    this._resumenService.getInfoTramite(this.folio).subscribe(
      (r:any)=>{
       
        this.infoTramite = r.data.info;
        this.id_tramite = r.data.info.id;
        this.consultaPDF = `${environment.SERVER_ORIGIN}consulta_requisitos/requisitos/${btoa(this.folio)}/${btoa(r.data.info.id.toString())}`;
        this.loading = false;
        this.loadingOff.emit({loading:false,id_tramite:this.id_tramite,cadenaFirmada:r.data.firma,cartaResponsiva:r.data.carta_responsiva || 0});
    //    this.generateCadena();
      },e=>{
        console.error(e);
      }
    );
    this._resumenService.getFilesResume(this.folio).subscribe(
      (r: any) => {
        this.archivos = r.data;
        console.log(this.archivos);
      }, e => {
        console.error(e);
      }
    );

    this._campoDinamico.getCamposDinamicos().subscribe(
      (res: any) => {
        //////console.log(res);
        this.campos_dinamicos = JSON.parse(JSON.stringify(res.data));
        ////console.log(this.campos_dinamicos)
        this.loadingCampos=false;
      },
      error => {
        this.loadingCampos=false;
      }
    );
   
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
        this.cadena=`Nombre|=|${nombre_persona.toUpperCase()} |+|Tramite|=|${this.folio}|+|Actividad|=|${this.infoTramite.codigo_scian}|+|RFC|=|SDASDADSA|+|CURP|=|${curp_persona.toUpperCase()}` ;
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
