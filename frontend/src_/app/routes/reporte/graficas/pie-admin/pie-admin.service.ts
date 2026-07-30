import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';
@Injectable({
  providedIn: 'root'
})
export class PieAdminService {

  constructor(private http: HttpClient, private _token: TokenService) {}

  getData(data?) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
   
    return this.http.get(environment.SERVER_ORIGIN + `graficas/pie2?f_inicio=${data.f_inicio}&f_final=${data.f_fin}&tipo_licencia=${data.tipo_licencia}`, httpOptions);
  }
  getDataMunicipio(id,data?) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
   
    return this.http.get(environment.SERVER_ORIGIN + `graficas/bar/${id}?f_inicio=${data.f_inicio}&f_final=${data.f_fin}`, httpOptions);
  }
  getAdvancedPie(id_municipio=null) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
   //?id_municipio=${id_municipio}
    return this.http.get(environment.SERVER_ORIGIN + `graficas/advancedpie-admin`, httpOptions);
  }
}
