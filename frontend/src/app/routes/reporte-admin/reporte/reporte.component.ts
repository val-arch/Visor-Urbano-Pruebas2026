import { Component, OnInit } from '@angular/core';
import { MtxGridColumn } from "@ng-matero/extensions/data-grid";
import { ReporteService } from "./reporte.service";

@Component({
  selector: 'app-reporte',
  templateUrl: './reporte.component.html',
  styleUrls: ['./reporte.component.scss']
})
export class ReporteComponent implements OnInit {
  columns: MtxGridColumn[] = [];
  columns2: MtxGridColumn[] = [];
  columns3: MtxGridColumn[] = [];
  columns4: MtxGridColumn[] = [];
  totalFichas = 0;
  totalFichasAdm = 0;
  list    = [];
  list2    = [];
  list3    = [];
  list4    = [];
  total      = 0;
  total2      = 0;
  total3      = 0;
  total4      = 0;
  isLoading  = true;
  isLoading2  = true;
  isLoading3  = true;
  isLoading4  = true;
  query      = {
    order: 'desc',
    page : 0,
  };
  query2      = {
    order: 'desc',
    page : 0,
  };
  query3      = {
    order: 'desc',
    page : 0,
  };
  query4      = {
    order: 'desc',
    page : 0,
  };
  constructor(public _reporte: ReporteService) { }

  ngOnInit(): void {
    this.columns = [{
        header: "Licencias Emitidas por Visor (Nuevas)",
        field: 'valueGiroNuevas'
    },
    {
        header: "Licencias Emitidas por Visor (Refrendo)",
        field: 'valueGiroRefrendo'
    },
    {
        header: "Licencias Emitidas por Historico (Refrendo)",
        field: 'valueHistorico'
    },
    {
        header: "Total Refrendos",
        field: 'valueTotal'
    },
    {
        header: "Licencias Totales Emitidas",
        field: 'valueTotalPlus'
    }];

    this.columns2 = [{
        header: "Licencias Emitidas (Nuevas)",
        field: 'valueNuevas'
    },
    {
        header: "Licencias Emitidas (Prorroga)",
        field: 'valueProrroga'
    },
    {
        header: "Total Licencias Emitidas",
        field: 'valueTotal'
    }];

    this.columns3 = [{
        header: "Municipio",
        field: 'municipio'
    },
    {
        header: "Fichas",
        field: 'fichas'
    },];

    this.columns4 = [{
        header: "Fichas",
        field: 'fichas'
    },
    {
        header: "Fecha",
        field: 'mes',
        formatter: (data) => {
          const f = data.dia + '/' + data.mes + '/' + data.anio;
          return f;
        }
    }];
      
    this.getData();
    
    
  }

  getData(){
    this._reporte.getData().subscribe((r: any) => {
      var auxList = [];
      auxList.push({
        "valueGiroNuevas" : r.data.giro.original.data.toString(),
        "valueGiroRefrendo" : r.data.giro_refrendo.original.data.toString(),
        "valueHistorico" : r.data.historico.original.data.toString(),
        "valueTotal" : r.data.historico.original.data.toString(),
        "valueTotalPlus" : r.data.historico.original.data.toString(), 
      });

      this.list = auxList;
      this.total = this.list.length;
      this.isLoading=false;

    },(e) => console.log(e));

    this._reporte.getData2().subscribe((r: any) => {
      var auxList2 = [];
      auxList2.push({
        "valueNuevas" : r.data.nueva.original.data.toString(),
        "valueProrroga" : r.data.prorroga.original.data.toString(),
        "valueTotal" : r.data.total.original.data.toString(),
      });

      this.list2 = auxList2;
      this.total2 = this.list2.length;
      this.isLoading2=false;

    },(e) => console.log(e));

    this._reporte.getData3().subscribe((r: any) => {
      
      this.list3 = r.fichas;
      this.totalFichas = r.total;
      this.total3 = this.list3.length;
      this.isLoading3 = false;
    },(e) => console.log(e));

    this._reporte.getData4().subscribe((r: any) => {
    
      this.list4 = r.dias;
      this.totalFichasAdm = r.total;
      this.total4 = this.list4.length;
      this.isLoading4 = false;
    },(e) => console.log(e));
  }
}
