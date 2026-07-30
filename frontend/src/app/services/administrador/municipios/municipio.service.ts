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
    console.log("qui");
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get<MunicipioModel[]>(environment.SERVER_ORIGIN +`municipios?page=${page}&oder=${JSON.stringify(query.order)}&filter=${query.filter}`,httpOptions);
  }

  getAll2(page: number,query:any): Observable<MunicipioModel[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get<MunicipioModel[]>(environment.SERVER_ORIGIN +`municipios/getByUserType/1?page=${page}&oder=${JSON.stringify(query.order)}&filter=${query.filter}`,httpOptions);
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
  
  //Municipio Construccion


  storeCampoTemplate(form){

    const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
    };
    return this.http.post(environment.SERVER_ORIGIN +`municipios_construccion/storeCampoTemplate`,form,httpOptions);

  }

  emitirResolutivo(id){
    const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
    };
    return this.http.put(environment.SERVER_ORIGIN +`municipios_construccion/emitirResolutivo/`+id,{},httpOptions);

  }

  storeRespuestaTemplate(form){

    const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
    };
    return this.http.post(environment.SERVER_ORIGIN +`municipios_construccion/storeRespuestaTemplate`,form,httpOptions);

  }

  updateCampoTemplate(form){

    const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
    };
    return this.http.post(environment.SERVER_ORIGIN +`municipios_construccion/updateCampoTemplate`,form,httpOptions);

  }

  storeTipoTramite(form){

    const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
    };
    return this.http.post(environment.SERVER_ORIGIN +`municipios_construccion/storeTipoTramite`,form,httpOptions);

  }

  storeFirma(form){
    const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
    };
    return this.http.post(environment.SERVER_ORIGIN +`municipios_construccion/storeFirma`,form,httpOptions);
  }

  editFirma(form){
    const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
    };
    return this.http.post(environment.SERVER_ORIGIN +`municipios_construccion/editFirma`,form,httpOptions);
  }

  getOrdenResolucion(id_municipio, tipo_tramite){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN +`resolucion_construccion/getOrdenResolucion/`+id_municipio+`/`+tipo_tramite, httpOptions);
  }

  updateOrdenResolucion(id, value, tipo_tramite){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN +`resolucion_construccion/updateOrdenResolucion/`+id+`/`+value+`/`+tipo_tramite, {}, httpOptions);
  }

  getMunicipioConstruccion(id): Observable<MunicipioModel[]> {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get<MunicipioModel[]>(environment.SERVER_ORIGIN +`municipios_construccion/auth${id!=0 ? `?id=`+id:''}`,httpOptions);
  }
  actualizarMunicipioConstruccion(data:{name:string,director:string,id:number,ficha_tramite:number,dias_solventar:number,direccion :string,telefono:string,licencias_enlinea:number,restricciones_licencia}){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    console.log(data);
    let d = data;
    d.licencias_enlinea = Number(d.licencias_enlinea);
    
    return this.http.put<MunicipioModel[]>(environment.SERVER_ORIGIN +`municipios_construccion/${data.id}`,d,httpOptions);
  }
  deleteFirmaConstruccion(id) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.delete(environment.SERVER_ORIGIN +`municipios_construccion/firmate/${id}`,httpOptions);
  }
  getFirmasConstruccion(id) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN +`municipios_construccion/firmate${id!=0 ? `?id=`+id:''}`,httpOptions);
  }

  updateFirmaConstruccion(order, data) {
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
    return this.http.post(environment.SERVER_ORIGIN +`municipios_construccion/firmate/${order}`,formData,httpOptions);
  }

  getTipoTramites(id_municipio){
    const httpOptions = {
      headers: new HttpHeaders({
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN +`municipios_construccion/getTipoTramiteConstruccion/`+id_municipio,httpOptions);
  }

  getCamposTemplates(id_tramite){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN +`municipios_construccion/getCamposTemplate/`+id_tramite,httpOptions);

  }

  getRespuestasTemplates(id_tramite_relacionado, id_tramite_construccion, id_municipio){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN +`municipios_construccion/getRespuestasTemplate/`+id_tramite_relacionado+'/'+id_tramite_construccion+'/'+id_municipio, httpOptions);

  }

  updateTipoTramite(id, value, check, folio_interno){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.put(environment.SERVER_ORIGIN +`municipios_construccion/updateTipoTramite/`+id, {tramite: value, default_preguntas: check, folio_interno: folio_interno},httpOptions);
  }

  removeCampoTemplate(id){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.delete(environment.SERVER_ORIGIN +`municipios_construccion/removeCampoTemplate/`+id,httpOptions);
  }

  removeTipoTramites(id){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.delete(environment.SERVER_ORIGIN +`municipios_construccion/removeTipoTramites/`+id,httpOptions);
  }

  removeFirma(id){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.delete(environment.SERVER_ORIGIN +`municipios_construccion/removeFirma/`+id,httpOptions);
  }
}
