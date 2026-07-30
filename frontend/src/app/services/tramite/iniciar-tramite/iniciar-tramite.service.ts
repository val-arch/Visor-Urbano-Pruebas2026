import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ConsultaRequisitos } from '../../../models/administrador/consulta_requisito.models';
import { ConsultaRequisitosConstruccion } from '../../../models/administrador/consulta_requisito_construccion.models';

import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';

@Injectable({
  providedIn: 'root'
})

export class IniciarTramiteService {
  constructor(private http: HttpClient, private _token: TokenService) { }
  role = null;
  id_usuario = null;
  data = {};
  actualizarUsuario(id, type){
   
    this.role = this._token.get().role
    this.id_usuario = this._token.get().uid
    this.data ={id_usuario:this.id_usuario, tram_type: type }
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
   
    return this.http.put<ConsultaRequisitos[]>(environment.SERVER_ORIGIN +`requisitos_validar/folio/${id}`,this.data,httpOptions);
  } 

  actualizarUsuarioConstruccion(id){
    this.role = this._token.get().role
    this.id_usuario = this._token.get().uid
    this.data ={id_usuario:this.id_usuario }
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.put<ConsultaRequisitosConstruccion[]>(environment.SERVER_ORIGIN +`requisitos_validar_construccion/folio/${id}`,this.data,httpOptions);
  } 
  existeFolio(folio): Observable<ConsultaRequisitos[]> {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get<ConsultaRequisitos[]>(environment.SERVER_ORIGIN +`requisitos_validar/folio/${btoa(folio)}`,httpOptions);
  }
  existeFolioConstruccion(folio): Observable<ConsultaRequisitosConstruccion[]> {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get<ConsultaRequisitosConstruccion[]>(environment.SERVER_ORIGIN +`requisitos_validar_construccion/folio/${btoa(folio)}`,httpOptions);
  }
  
}
