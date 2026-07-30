import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';
import { tap } from 'rxjs/operators';
@Injectable({
  providedIn: 'root'
})
export class BarService {

  constructor(private http: HttpClient, private _token: TokenService) {}

  getData() {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
   
    return this.http.get(environment.SERVER_ORIGIN + `graficas/bar`, httpOptions);
  }

  getListado(data){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
   
    return this.http.post(environment.SERVER_ORIGIN + `graficas/bar-list`, data, httpOptions);
  }
}
