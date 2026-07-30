import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';
import { Role } from '../../../models/administrador/role';
import { tap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class RolesService {

  constructor(private http: HttpClient, private _token: TokenService) {}
  
  getRole(id: number) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `sub_roles/${id}`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }

  getSubRoles(page: number) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `sub_roles?page=${page}`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }

  getRoles(page: number) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `sub_roles?page=${page}`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }

  storeRoles(model:Role) {
    if (model.id) {
      const httpOptions = {
          headers: new HttpHeaders({
            'Authorization': this._token.get().token,
          }),
      };
      return this.http.put<Role[]>(environment.SERVER_ORIGIN +`roles/${model.id}`,model,httpOptions);
    }else{ 
      delete model.id;
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
      return this.http.post<Role[]>(environment.SERVER_ORIGIN +`roles`,model,httpOptions);
    }
  }


  deleteRoles(model:Role) {
 
      const httpOptions = {
          headers: new HttpHeaders({
            'Authorization': this._token.get().token,
          }),
      };
      return this.http.delete<Role[]>(environment.SERVER_ORIGIN +`roles/${model.id}`,httpOptions);
    }

    getRoleMunicipaly(){
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
    };
    return this.http.get(environment.SERVER_ORIGIN +`roles/roleMunicipio`,httpOptions);
    }

  
}
