import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router, ParamMap } from '@angular/router';  
import { of } from 'rxjs';
import { switchMap, map } from 'rxjs/operators';
@Component({
  selector: 'app-tablas',
  templateUrl: './tablas.component.html',
  styleUrls: ['./tablas.component.scss']
})
export class TablasComponent implements OnInit {

  constructor(private route: ActivatedRoute) {
    
   }
  id;
  ngOnInit(): void {
    this.route.paramMap.pipe(
      map(
        (params: ParamMap) => {
          console.log(params);
          let id: string = params.get('id');
          if (id == "") {
            return '';
          }
          return id;
        }
      )
    ).subscribe(r => this.change(r));
    
  }

  change(id){
    console.log('cxhange');
    this.id=id;
  }

}
