import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { Observable } from 'rxjs';
import { environment } from '@env/environment';
@Injectable({
  providedIn: 'root'
})
export class ResumenService {

  constructor(private http: HttpClient, private _token: TokenService) { }

  
  getInfoTramiteRe(folio): Observable<any[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`get-historico-licencia-info/${folio}`,httpOptions);
  }

  getInfoTramiteRefrendo(folio): Observable<any[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`get-historico-licencia/${folio}`,httpOptions);
  }
  getFiles(folio): Observable<any[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`get-historico-files/${folio}`,httpOptions);
  }
  getFilesTipo(folio): Observable<any[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`getFileTipo/${folio}`,httpOptions);
  }
  getFilesResume(folio): Observable<any[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`get-historico-files/${folio}`,httpOptions);
  }
  getInfoTramite(folio): Observable<any[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`info-tramite/${btoa(folio)}`,httpOptions);
  }

  getInfoTramiteTipo(folio): Observable<any[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`info-tramite/tipo/${btoa(folio)}`,httpOptions);
  }

  
  getInfoHistorico(folio): Observable<any[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`info-tramite-historico/${btoa(folio)}`,httpOptions);
  }
  public uploadCarta(formData,folio) {
    return this.http.post<any>(environment.SERVER_ORIGIN + `licencias_giro/upload_file/${btoa(folio)}`, formData ,{
       headers: new HttpHeaders({
         'Authorization': this._token.get().token,
       }),
     });  
   }
  public tramiteContinuar(folio) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN + `licencias_giro/ingresoTramiteContinuar/${btoa(folio)}`,{} ,httpOptions);  
   }

   public noFirmaElectronica(folio) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN + `licencias_giro/noFirmaElectronica/${btoa(folio)}`,{} ,httpOptions);  
   }

   public noFirmaElectronicaConstruccion(folio) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN + `licencias_construccion/noFirmaElectronica/${btoa(folio)}`,{} ,httpOptions);  
   }


   getFlujoResolucion(folio){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`tramites_construccion/getFlujoResolucion/${btoa(folio)}`,httpOptions);
   }


    /// Construccion  
  getInfoTramiteConstruccion(folio): Observable<any[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`info-tramite_construccion/${btoa(folio)}`,httpOptions);
  }

  getTipoTramite(tramite_relacionado){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };

    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`municipios_construccion/getTipoTramiteConstruccion2/${tramite_relacionado}`,httpOptions);
  }

  getFilesProrroga(folio): Observable<any[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`licencias_construccion/getFilesProrroga/${btoa(folio)}`,httpOptions);
  }

  public tramiteConstruccionContinuar(folio) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN + `licencias_construccion/ingresoTramiteContinuar/${btoa(folio)}`,{} ,httpOptions);  
   }
   
   getInfoTramiteConstruccionTipo(folio): Observable<any[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`info-tramite_construccion/tipo/${btoa(folio)}`,httpOptions);
  }

  getFilesConstruccionTipo(folio): Observable<any[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`getFileTipo/${folio}`,httpOptions);
  }

  public uploadCartaConstruccion(formData,folio) {
    return this.http.post<any>(environment.SERVER_ORIGIN + `licencias_construccion/upload_file/${btoa(folio)}`, formData ,{
       headers: new HttpHeaders({
         'Authorization': this._token.get().token,
       }),
     });  
   }
}
