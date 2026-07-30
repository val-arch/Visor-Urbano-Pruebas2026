import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { Observable } from 'rxjs';
import { environment } from '@env/environment';
@Injectable({
  providedIn: 'root'
})
export class OrdenPagoService {

  constructor(private http: HttpClient, private _token: TokenService) { }


  subirRecibo(id,formData){
    return this.http.post<any>(environment.SERVER_ORIGIN + `tramites/ordenPago/${btoa(id)}`, formData ,{
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      })
    });  
  }
}
