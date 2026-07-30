import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { Observable } from 'rxjs';
import { environment } from '@env/environment';
@Injectable({
  providedIn: 'root'
})
export class EmitirService {

  constructor(private http: HttpClient, private _token: TokenService) { }


  generar(folio,data,tipo=1){
    const url =  tipo == 1 ? 'generarLicencia' : 'generarLicenciaContinuar' ;
    return this.http.post<any>(environment.SERVER_ORIGIN + `${url}/${btoa(folio)}`, data ,{
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      })
    });  
  }

  getFirmas(id) {
    console.log(id);
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN +`municipios/firmate${id!=0 ? `?id=2`:''}`,httpOptions);
  }

  generarRefrendo(folio,data){ 
    return this.http.post<any>(environment.SERVER_ORIGIN +`generarRefrendo/${btoa(folio)}`, data ,{
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      })
    });  
  }
  generarRefrendoHistorico(folio,data){
   
    return this.http.post<any>(environment.SERVER_ORIGIN +`generarRefrendo/historico/${btoa(folio)}`, data ,{
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      })
    });  
  }
  emitir(folio,formData){
    return this.http.post<any>(environment.SERVER_ORIGIN + `revision/emitir/${btoa(folio)}`, formData ,{
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      })
    });  
  }


  emitirRefrendoHistorico(folio,formData){
    return this.http.post<any>(environment.SERVER_ORIGIN + `revision/emitir/${btoa(folio)}`, formData ,{
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      })
    });  
  }

  

}
