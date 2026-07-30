import { Component, OnInit } from '@angular/core';
import { TokenService } from '../../../core/authentication/token.service';

@Component({
  selector: 'app-graficas-construccion',
  templateUrl: './graficas-construccion.component.html',
  styleUrls: ['./graficas-construccion.component.scss']
})
export class GraficasConstruccionComponent implements OnInit {
  role;
  constructor(public _token:TokenService) { 
    this.role = this._token.get().role;
  }

  ngOnInit(): void {
  }

}
