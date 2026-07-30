import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { GiroModel } from '../../../models/giro.models';
import { GiroApagarModel } from '../../../models/administrador/giro_apagar.models';
import { GiroConfiguracion } from '../../../models/administrador/giro_configuracion.models';
import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';

@Injectable({
  providedIn: 'root'
})
export class GiroService {
  constructor(private http: HttpClient, private _token: TokenService) { } 
  getAll(page: number,query:any): Observable<GiroModel[]> {
 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get<GiroModel[]>(environment.SERVER_ORIGIN +`giros_apagados/getAllGiros?page=${page}&oder=${JSON.stringify(query.order)}&filter=${query.filter}`,httpOptions);
  }
  encenderGiro(id: number) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.delete(environment.SERVER_ORIGIN  + 'giros_apagados/' + id, httpOptions);
  }
  apagarGiro(giros_id,municipios_id) {
    let formData: GiroApagarModel = {
      giros_id : giros_id,
      municipios_id : municipios_id,
    };
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN  + 'giros_apagados',  formData, httpOptions);
  }

  cedulaConfiguracion(giros_id,municipios_id,status) {
    let formData: GiroApagarModel = {
      giros_id : giros_id,
      municipios_id : municipios_id
    };
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN  + 'giros_apagados/storeStatus/'+status,  formData, httpOptions);
  }
  apagarCedula(id: number) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.delete(environment.SERVER_ORIGIN  + 'cedula_giro/' + id, httpOptions);
  }
  encenderCedulaConfiguracion(giros_id,municipios_id,encendido) {
    let formData = {
      giros_id : giros_id,
      municipios_id : municipios_id,
    };
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN  + 'giros_apagados/cedula/'+ encendido, formData, httpOptions);
  }
  encenderCedulaGiro(giros_id,municipios_id) {
    let formData = {
      giro_id : giros_id,
      municipio_id : municipios_id,
    };
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN  + 'cedula_giro',  formData, httpOptions);
  }
  guardarImpacto(giros_id,municipios_id,impacto,actual) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    let formData = {
      giro_id : giros_id,
      municipio_id : municipios_id,
      impacto
    };
   
    //if(actual){
      return this.http.post(environment.SERVER_ORIGIN  + 'giro_impacto/update',formData, httpOptions);
    //}else{
    //  return this.http.post(environment.SERVER_ORIGIN  + 'giro_impacto' ,formData, httpOptions);
  //  }
  }
}