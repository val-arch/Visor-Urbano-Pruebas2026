import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '@env/environment';
import { TokenService } from '@core/authentication/token.service';

@Injectable({
  providedIn: 'root'
})
export class ReporteService {

  constructor(private http: HttpClient, private _token: TokenService) { }

  getData() {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `reportes-admin/getLicenciasByTypeMain`, httpOptions);
  }

  getData2() {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `reportes-admin/getLicenciasByTypeMainConstruccion`, httpOptions);
  }

  getData3() {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get('https://api-visorurbano.jalisco.gob.mx/fichas-tecnicas-municipios', httpOptions);
  }

  getData4() {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get('https://api-visorurbano.jalisco.gob.mx//fichas-tecnicas-adm', httpOptions);
  }
  getGiros() {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    
    return this.http.get(environment.SERVER_ORIGIN + `reportes-admin/getLicenciasByTypeMainConstruccion`, httpOptions);
  }
}
