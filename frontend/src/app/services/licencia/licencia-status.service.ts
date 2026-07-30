import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';
@Injectable({
  providedIn: 'root'
})
export class LicenciaStatusService {

  constructor(private http: HttpClient, private _token: TokenService) { }
  
  updateStatus( data,id) {
    console.log(data);
    const formData = new FormData();
    formData.append('id',data.id ?? 0);
    formData.append('id_municipio',id['id_municipio'] ?? 0);
    formData.append('status_licencia',data.status);
    formData.append('motivo',data.motivo);
    formData.append('file',data.file);
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN +`licencias_giro/update-licencias-status/${id['id']}`,formData,httpOptions);
  }

  updateStatus2( data,id) {
    console.log(data);
    const formData = new FormData();
    formData.append('id',data.id ?? 0);
    formData.append('id_municipio',id['id_municipio'] ?? 0);
    formData.append('status_licencia',data.status);
    formData.append('motivo',data.motivo);
    formData.append('file',data.file);
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN +`licencias_construccion/update-licencias-status/${id['id']}`,formData,httpOptions);
  }

  updateStatusHistorico( data,id) {
    const formData = new FormData();

    formData.append('id',id ?? 0);
    formData.append('status_licencia',data.status)
    formData.append('id_municipio',data.id_municipio);
    formData.append('motivo',data.motivo);
    formData.append('file',data.file);

    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN +`licencias_giro/update-licencias-status-his/${id}`,formData,httpOptions);
  }
  copyTramiteHis(id){
  
    const formData = new FormData(); 
    formData.append('id',id ?? 0);

    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN +`licencias_giro/copy-tramite-historico`,formData,httpOptions);
  }
  copyTramite(folio,municipio_id,num_lic){
    console.log(num_lic)
    const formData = new FormData(); 
    formData.append('folio',folio ?? 0);
    formData.append('numero_lic',num_lic ?? 0);
    formData.append('numero_lic',num_lic ?? 0);
    formData.append('municipio_id',municipio_id ?? 0);
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post(environment.SERVER_ORIGIN +`licencias_giro/copy-tramite`,formData,httpOptions);
  }

  getInfoLicencia(id): Observable<any[]> { 
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
      return  this.http.get<any[]>(environment.SERVER_ORIGIN +`campos/getCampos/${id}`,httpOptions);
    }

    getInfoLicenciaDatos(id): Observable<any[]> {
        const httpOptions = {
            headers: new HttpHeaders({
                'Authorization': this._token.get().token,
            }),
        };
        return  this.http.get<any[]>(environment.SERVER_ORIGIN +`get-historico-licencia/${id}`,httpOptions);
    }

    setInfoLicenciaCoordenadas(id, x, y): Observable<any[]> {
        const httpOptions = {
            headers: new HttpHeaders({
                'Authorization': this._token.get().token,
            }),
        };
        return  this.http.get<any[]>(environment.SERVER_ORIGIN +`get-historico-licencia/${id}`,httpOptions);
    }



}