import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { Observable } from 'rxjs';
import { environment } from '@env/environment';

@Injectable({
  providedIn: 'root'
})
export class RevisionService {

  
  constructor(private http: HttpClient, private _token: TokenService) { }


  getDataRevision(folio){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`revision_construccion/${btoa(folio)}`,httpOptions);
  }
  getDataRevisionRefrendo(folio){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`revision_construccion/refrendo/${folio}`,httpOptions);
  }
  uploadFileRevision(folio,formData){
    return this.http.post<any>(environment.SERVER_ORIGIN + `licencias_giro/upload_file/${btoa(folio)}`, formData ,{
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      })
    });  
  }
  uploadDataRevision(folio,data){
    return this.http.post<any>(environment.SERVER_ORIGIN + `revision_construccion/update/${btoa(folio)}`, data ,{
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      })
    });  
  }
  deleteHistoricoFiles(id){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.delete<any[]>(environment.SERVER_ORIGIN +`eliminarFile/${id}`,httpOptions);
  }
  uploadDataRevisionDir(folio,data){
    return this.http.post<any>(environment.SERVER_ORIGIN + `revision_construccion/updateDir/${btoa(folio)}`, data ,{
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      })
    });  
  }

  uploadFile(data,id): Observable<any[]> { 
    return this.http.post<any>(environment.SERVER_ORIGIN + `revision_construccion/uploadFiles/${btoa(id)}`, data ,{
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      })
    });  
  }

  uploadFileHist(data,id): Observable<any[]> { 
    return this.http.post<any>(environment.SERVER_ORIGIN + `revision_construccion/uploadFilesHist/${btoa(id)}`, data ,{
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      })
    });  
  }

  //Construccion 
  
  getDataRevisionConstruccion(folio){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`revision_construccion/${btoa(folio)}`,httpOptions);
  }


  uploadFileRevisionConstruccion(folio,formData){
    return this.http.post<any>(environment.SERVER_ORIGIN + `licencias_construccion/upload_file/${btoa(folio)}`, formData ,{
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      })
    });  
  }
}
