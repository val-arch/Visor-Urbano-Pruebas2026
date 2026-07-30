import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, ParamMap } from '@angular/router';
import { FuseConfigService } from '@fuse/services/config.service';
import { map } from 'rxjs/operators';
import { MapaService } from '../../services/mapa/mapa.service';

@Component({
  selector: 'app-mapa',
  templateUrl: './mapa.component.html',
  styleUrls: ['./mapa.component.scss']
})
export class MapaComponent implements OnInit {
  id;
  constructor(
    private _fuseConfigService: FuseConfigService,
    private _mapa:MapaService,
    private route: ActivatedRoute) {
    this._fuseConfigService.config = {
      layout: {
          navbar: {
              hidden: true
          },
          toolbar: {
              hidden: true
          },
          footer: {
              hidden: true
          },
          sidepanel: {
              hidden: true
          }
      }
  };
   }

   ngOnInit(): void {
    this.route.paramMap.pipe(
      map(
        (params: ParamMap) => {
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
   this._fuseConfigService.config = {
      layout: {
          navbar: {
              hidden: true
          },
          toolbar: {
              hidden: true
          },
          footer: {
              hidden: true
          },
          sidepanel: {
              hidden: true
          }
      }
  }; 
    this.id=id;
    //this._mapa.municipioActual.nombre=this.id;
  }

}
