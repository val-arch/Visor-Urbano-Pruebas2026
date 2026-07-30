import { Component, OnInit } from '@angular/core';
import { PieService } from './pie.service';

@Component({
  selector: 'grafica-pie',
  templateUrl: './pie.component.html',
  styleUrls: ['./pie.component.scss']
})
export class PieComponent implements OnInit {

  loading: boolean = true;
  data = [
    {
      "name": "Aprobados",
      "value": 0
    },
    {
      "name": "En revisíon",
      "value": 0
    },
    {
      "name": "Solventados",
      "value": 0
    },
      {
      "name": "Desechados",
      "value": 0
    }
  ];
  result;
  view: any[] = [700, 400];

  // options
  gradient: boolean = false;
  showLegend: boolean = true;
  showLabels: boolean = true;
  isDoughnut: boolean = false;
  legendPosition: string = 'right';

  colorScheme = {
    domain: ['#003E76', '#ABE2F5', '#FFBA38', '#FF4A3B']
  };
  constructor(public _pie:PieService) {
    this._pie.getData().subscribe((res:any)=>{
      this.result = res.data;
      if(this.result){
        const { aprobadas, revision, solventaciones, desechadas} = this.result;
        this.data[0].value = aprobadas;
        this.data[1].value = revision;
        this.data[2].value = solventaciones;
        this.data[3].value = desechadas; 
        
      }
      this.loading = false;
    });
   }

  ngOnInit(): void {
  }

}