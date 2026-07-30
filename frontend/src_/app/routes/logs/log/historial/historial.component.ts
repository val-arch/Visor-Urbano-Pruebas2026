import { Component, OnInit, TemplateRef, ViewChild } from '@angular/core';
import { FormControl } from '@angular/forms';
import { PageEvent } from '@angular/material/paginator';
import { environment } from '@env/environment';
import { MtxGridColumn } from '@ng-matero/extensions';
import { LogService } from 'app/services/administrador/logs/log.service';
import { MunicipioService } from 'app/services/administrador/municipios/municipio.service';

@Component({
  selector: 'app-historial',
  templateUrl: './historial.component.html',
  styleUrls: ['./historial.component.scss']
})
export class HistorialComponent implements OnInit {

  columns: MtxGridColumn[] = [];
  columnsRole: MtxGridColumn[] = [];
  filter = new FormControl();
  filter2 = new FormControl();
  tipo = new FormControl();
  @ViewChild("statusTpl", { static: true }) statusTpl: TemplateRef<any>;
  @ViewChild("statusTplAnterior", { static: true }) statusTplAnterior: TemplateRef<any>;
  @ViewChild("statusBaja", { static: true }) statusBaja: TemplateRef<any>;
  updateUser = false;
    list           = [];
    total          = 0;
    listMunicipios = []
    isLoading      = true;
    page           = 0;
    editId         = 0;
    query = {
        order: "desc",
        page: 0,
    };
  server = environment.SERVER_ORIGIN;
  constructor(   private _historialLog: LogService,private _municipio: MunicipioService) { 
    
  }


  ngOnInit(): void {
    const STATUS = [
      "Inicio de Session",
      "Registro de datos",
      "Actualizacion de datos",
      "Error",
      "Emision de licenca",
      "Se Elimina Datos"
  ];
    this.columns = [
      {
          header: "Accion",
          field: "accion",
      },
      { header: "Usuarios", field: "name" },
      {
        header: "Tipo",
        field: "tipo_log",
        formatter: (data) => {
            let f = data.tipo_log;
            return `${STATUS[f]}`;
        },
      },
      { header: "Municipio", field: "nombre" },
      {
          header: "Fecha Actualizacion",
          field: "updated_at",
          
          formatter: (data) => {
              let f = new Date(data.updated_at);
              return `${f.getDate()}/${f.getMonth()}/${f.getFullYear()}`;
          },
      },
     
      {
        header: "Fecha Creada",
        field: "created_at",
        formatter: (data) => {
            let f = new Date(data.updated_at);
            return `${f.getDate()}/${f.getMonth()}/${f.getFullYear()}`;
        },
    },
      
     
      { header: "Datos Anteriores", field: "anterior", cellTemplate: this.statusTpl ,
      
     },
      { header: "Datos", field: "post_request", cellTemplate: this.statusTplAnterior },
  ];
  this.getData();
  this.getMunicipio();
 
  }

  isShown: boolean = false ; // hidden by default


 fieldsChange(values:any,r):void {
  console.log(values.currentTarget.checked);
  if(!values.currentTarget.checked){
    document.getElementById('tx'+r).style.display = "none";
  }else{
    document.getElementById('tx'+r).style.display = "";
  } 
}
filtrar(){
  this.getData();
}
toggleShow(values:any,r):void {
  console.log(values.currentTarget.checked);
  if(!values.currentTarget.checked){
    document.getElementById('tx2'+r).style.display = "none";
  }else{
    document.getElementById('tx2'+r).style.display = "";
  } 
}
  getMunicipio() {
    this.isLoading = true;
    this._municipio
        .getMunicipios()
        .subscribe(
            (res: any) => {
              console.log(res);
            this.listMunicipios = res.data;
            },
            (error) => {
                this.isLoading = false;
            }
        );
}
  getData() {
    this.isLoading = true;
    this._historialLog
        .getLogsHistorial(this.page, this.filter.value, this.filter2.value, this.tipo.value)
        .subscribe(
            (res: any) => {
                this.total = res.total;
                
                this.list = res;
                //console.log(this.list);
                this.isLoading = false;
            },
            (error) => {
                this.isLoading = false;
                //console.log(error);
            }
        );
}
getNextPage(e: PageEvent) {
    this.page = e.pageIndex + 1;
    this.query.page = e.pageIndex;
    this.getData();
}

}