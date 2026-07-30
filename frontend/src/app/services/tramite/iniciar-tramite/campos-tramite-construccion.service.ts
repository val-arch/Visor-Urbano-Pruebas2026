import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { TokenService } from '@core/authentication/token.service';

@Injectable({
  providedIn: 'root'
})
export class CamposTramiteConstruccionService {

  constructor(private http: HttpClient, private _token: TokenService,private activatedRoute: ActivatedRoute) { 
    /* this.activatedRoute.queryParams.subscribe(params => {
     //  let date = params['startdate'];
       console.log(params); // Print the parameter to the console. 
   });*/
   }
}
