import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { tap } from 'rxjs/operators';
import { environment } from '@env/environment';
import { UserRole } from '../../../models/administrador/userrole';
@Injectable({
  providedIn: 'root'
})
export class UserroleService {

  constructor(private http: HttpClient,private _token: TokenService) {
  }

  data ={};
  
  getRoles(page: number) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `roles/role_user?page=${page}`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }

  getRoles2(page : number) {
  
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `roles_construccion/role_user`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }


  getSubroles(page: number) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `sub_roles/municipio`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }
    
 delRole(id){         
       const httpOptions = {
           headers: new HttpHeaders({
             'Authorization': this._token.get().token,
           }),
       };
       return this.http.delete<UserRole[]>(environment.SERVER_ORIGIN +`control_roles/quitar/${id}`,httpOptions);
 }

 delRoleConstruccion(id){         
       const httpOptions = {
           headers: new HttpHeaders({
             'Authorization': this._token.get().token,
           }),
       };
       return this.http.delete<UserRole[]>(environment.SERVER_ORIGIN +`control_roles/quitarConstruccion/${id}`,httpOptions);
 }

 setRole(role_id,data){  
   console.log(data);       
      this.data = {
         role_id:role_id,
         email:data.email,
         id_user: data.user_id,
         token:this._token.get().token,
      };
      const httpOptions = {
          headers: new HttpHeaders({
            'Authorization': this._token.get().token,
          }),
      };
      return this.http.post<UserRole[]>(environment.SERVER_ORIGIN +`control_roles/asignar/${data.user_id}`,this.data,httpOptions);
  }
  setRole2(role_id,data){  
    console.log(data);       
       this.data = {
          role_id:role_id,
          email:data.email,
          id_user: data.id,
          token:this._token.get().token,
       };
       console.log(this.data);       
       const httpOptions = {
           headers: new HttpHeaders({
             'Authorization': this._token.get().token,
           }),
       };
       return this.http.post<UserRole[]>(environment.SERVER_ORIGIN +`control_roles/asignar/${data.id}`,this.data,httpOptions);
   }

   setRoleConstruccion(role_id,data){  
    console.log(data);       
       this.data = {
          role_id:role_id,
          email:data.email,
          id_user: data.id,
          token:this._token.get().token,
       };
       console.log(this.data);       
       const httpOptions = {
           headers: new HttpHeaders({
             'Authorization': this._token.get().token,
           }),
       };
       return this.http.post<UserRole[]>(environment.SERVER_ORIGIN +`control_roles/asignarConstruccion/${data.id}`,this.data,httpOptions);
   }
    setSubRole(role_id){          
       
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
          return this.http.post(environment.SERVER_ORIGIN +`control_suboles/asignar/${role_id}`,this.data,httpOptions);
    }

    delSubRole(){         
          const httpOptions = {
              headers: new HttpHeaders({
                'Authorization': this._token.get().token,
              }),
          };
          return this.http.post<UserRole[]>(environment.SERVER_ORIGIN +`control_suboles/quitar`,this.data,httpOptions);
    }

  
}
