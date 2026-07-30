import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '@env/environment';
import { tap } from 'rxjs/operators';
import { TokenService } from '@core/authentication/token.service';
import { Requisitos } from '../../../models/administrador/requisito.models';
@Injectable({
  providedIn: 'root'
})
export class RequisitosService {

  constructor(private http: HttpClient,
              private _token: TokenService) { }

  getRequisitos(page: number,filter:string) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `campos?page=${page}&filter=${filter}`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }

  getGirosAdmin(){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `municipios/getGirosAdmin`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );

  }

  getGirosAdmin2(municipio){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `giros_public/getAll?municipio_id=${municipio}`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );

  }


  storeRequisitos(model) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    console.log(model);
    if (model.id) {
        delete model.name;    
        return this.http.put<any[]>(environment.SERVER_ORIGIN +`campos/${model.id}`,model,httpOptions);
    }else{ 
    return this.http.post<any[]>(environment.SERVER_ORIGIN +`campos`,model,httpOptions);
  }

  
   
  }
  postRequisitoChange(id_campo,id_requisito=0){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    let data = {
      id_campo,
      id_requisito
    };
    return this.http.post<any[]>(environment.SERVER_ORIGIN +`campos/requisitoChange`,data,httpOptions);
  
  }

}
