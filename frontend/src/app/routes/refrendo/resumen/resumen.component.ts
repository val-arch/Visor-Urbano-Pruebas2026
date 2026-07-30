import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { environment } from '@env/environment';
import { ConsultaRequisitos } from 'app/models/administrador/consulta_requisito.models';
import { HistoricoLicenciias } from 'app/models/administrador/historial_licencias.models';
import { CamposTramiteServiceService } from 'app/services/tramite/iniciar-tramite/campos-tramite-service.service';
import { ResumenService } from 'app/services/tramite/resumen.service';
import { ActivatedRoute } from '@angular/router';

export interface Employee1 {
  id: number;
  nombre_solicitante?: string;
  lastName: string;
  salary: number;
}

@Component({
  selector: 'app-resumen',
  templateUrl: './resumen.component.html',
  styleUrls: ['./resumen.component.scss']
})

export class ResumenComponent implements OnInit {

  typesOfShoes: string[] = ['Boots', 'Clogs', 'Loafers', 'Moccasins', 'Sneakers'];
  campos_dinamicos: [];
  @Input() folio :string ;
  @Input() necesitaCadena :boolean =false ;
  @Input() role :number =0 ;
  @Output() cadenaAfirmar = new EventEmitter(); 
  @Output() loadingOff = new EventEmitter(); 
  id_tramite  ;
  cadena=''  ;
  curp  ;
  infoTramite:HistoricoLicenciias;
  archivos:any;
  loading=true;
  loadingCampos=true;
  consultaPDF = '';
  env=environment.SERVER_ORIGIN;
  constructor(
    private _campoDinamico: CamposTramiteServiceService,
    private _resumenService: ResumenService,
    private route: ActivatedRoute
    ) {
      this.getData();
   
    }
  ngOnInit(): void {
   
  }

  getData(){
    var folio = this.route.snapshot['_routerState']._root.children[0].children[0].value.params.folio;    
    this._resumenService.getInfoTramiteRefrendo(atob(folio)).subscribe(
      (r:any)=>{
        this.infoTramite = r.data;
        console.log(this.infoTramite);
        this.loading = false;
        this.loadingOff.emit({loading:false,id_tramite:this.id_tramite,cadenaFirmada:r.data.firma,cartaResponsiva:r.data.carta_responsiva || 0});
      },e=>{
        console.error(e);
      }
    );

    this._resumenService.getFiles(atob(folio)).subscribe(
      (r:any)=>{
        this.archivos = r.data;
        console.log(this.archivos);
      },e=>{
        console.error(e);
      }
    );

  }
findName(val): any{
  const res = this.campos_dinamicos.find((campo:any) => campo.name ==val)
  ////console.log(res);
  return res;
}
download(row){
  console.log(row.archivo);
  console.log(environment.SERVER_ORIGIN);
  window.open(
    `${environment.SERVER_ORIGIN}${row.archivo}`,
    "_blank"
);
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
