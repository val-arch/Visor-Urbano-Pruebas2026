import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';
import { tap } from 'rxjs/operators';
import { UserMunicipio } from '../../../models/administrador/usermunicipio';
import { UserAdmin} from '../../../models/administrador/useradmin';

@Injectable({
  providedIn: 'root'
})
export class UseradminService {

  constructor(private http: HttpClient, private _token: TokenService) { }
  data ={};
  getUsers(page: number,filter:string) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `usuarios?page=${page}&filter=${filter}`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }


  getUsers2(page: number,filter:string) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `usuarios/get?page=${page}&filter=${filter}`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }
  storeUsers(model:UserAdmin) {
    if (model.id) {
      const httpOptions = {
          headers: new HttpHeaders({
            'Authorization': this._token.get().token,
          }),
      };
      return this.http.put<UserAdmin[]>(environment.SERVER_ORIGIN +`usuarios/${model.id}`,model,httpOptions);
    }else{ 
      delete model.id;  
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
      return this.http.post<UserAdmin[]>(environment.SERVER_ORIGIN +`usuarios`,model,httpOptions);
    }
  }


  getMunicipios() {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `municipios`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }
  getMunicipios2() {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(`https://api-visorurbano.jalisco.gob.mx/mapa/rest/v1/municipios/`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }


  setMunicipio(id_municipio,id){         
    this.data = {
       id_municipio:id_municipio
    };
    const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
    };
    return this.http.put<UserMunicipio[]>(environment.SERVER_ORIGIN +`usuarios/${id}/municipio`,this.data,httpOptions);
}
}