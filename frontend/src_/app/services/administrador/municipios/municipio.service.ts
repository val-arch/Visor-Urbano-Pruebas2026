import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { MunicipioModel } from '../../../models/municipio.models';
import { GiroApagarModel } from '../../../models/administrador/giro_apagar.models';
import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';

@Injectable({
  providedIn: 'root'
})
export class MunicipioService {
  constructor(private http: HttpClient, private _token: TokenService) { } 

  getAll(page: number,query:any): Observable<MunicipioModel[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get<MunicipioModel[]>(environment.SERVER_ORIGIN +`municipios?page=${page}&oder=${JSON.stringify(query.order)}&filter=${query.filter}`,httpOptions);
  }

  getMunicipio(id): Observable<MunicipioModel[]> {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get<MunicipioModel[]>(environment.SERVER_ORIGIN +`municipios/auth${id!=0 ? `?id=`+id:''}`,httpOptions);
  }
  getMunicipios(): Observable<MunicipioModel[]> {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get<MunicipioModel[]>(environment.SERVER_ORIGIN +`municipios/all`,httpOptions);
  }
  getFirmas(id) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN +`municipios/firmate${id!=0 ? `?id=`+id:''}`,httpOptions);
  }
  updateFirma(order, data) {
    const formData = new FormData();
    if(data.firma_img){
      formData.append('image',data.firma_img);
    }
    formData.append('id',data.id ?? 0);
    formData.append('nombre_firma',data.nombre_firma);
    formData.append('cargo_firma',data.cargo_firma);

    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN +`municipios/firmate/${order}`,formData,httpOptions);
  }
  deleteFirma(id) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.delete(environment.SERVER_ORIGIN +`municipios/firmate/${id}`,httpOptions);
  }

  actualizarMunicipio(data:{name:string,director:string,id:number,ficha_tramite:number,dias_solventar:number,direccion :string,telefono:string,licencias_enlinea:number,restricciones_licencia}){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    console.log(data);
    let d = data;
    d.licencias_enlinea = Number(d.licencias_enlinea);
    
    return this.http.put<MunicipioModel[]>(environment.SERVER_ORIGIN +`municipios/${data.id}`,d,httpOptions);
  } 
}



