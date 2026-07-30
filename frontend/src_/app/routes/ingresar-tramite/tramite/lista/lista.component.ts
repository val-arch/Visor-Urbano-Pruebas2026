import { Component, OnInit } from '@angular/core';
import { MtxGridColumn } from '@ng-matero/extensions';
import { PageEvent } from '@angular/material/paginator';
import {MatTableDataSource} from '@angular/material/table';



@Component({
  selector: 'app-lista',
  templateUrl: './lista.component.html',
  styleUrls: ['./lista.component.scss']
})
export class ListaComponent implements OnInit {
  columns: MtxGridColumn[] = [];


  displayedColumns: string[] = ['folio', 'solicitante', 'direccion', 'fecha','dias_transcurridos','estatus','acciones'];
  dataSource = new MatTableDataSource<listaElement>(ELEMENT_DATA);
  
  constructor(
  
  ) { }



  ngOnInit(): void {
 
  }

  getColor(estatus) {
    if (estatus == "Aprobado")
        return '#70CE68';
    else if (estatus == "Solventado")
        return '#FFB11F';
    else if (estatus == "Negado")
        return '#EF5547';
    
  }
  
}
export interface listaElement {
  folio: string;
  solicitante: string;
  direccion: string;
  fecha: string;
  dias_transcurridos: number;
  estatus: string;
  acciones: any;

}
const ELEMENT_DATA: listaElement[] = [
  {folio: 'FEG6523456632', solicitante: 'Jaime Garcia Robles', direccion: 'A. De Los Maestros 124', fecha: '12/01/2021', dias_transcurridos:12, estatus: 'Aprobado', acciones:'Ver detalles'},
  {folio: 'FEG6523456632', solicitante: 'Jaime Garcia Robles', direccion: 'A. De Los Maestros 124', fecha: '12/01/2021', dias_transcurridos:12, estatus: 'Solventado', acciones:'Ver detalles'},
  {folio: 'FEG6523456632', solicitante: 'Jaime Garcia Robles', direccion: 'A. De Los Maestros 124', fecha: '12/01/2021', dias_transcurridos:12, estatus: 'Negado', acciones:'Ver detalles'},

  

];

