import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable } from 'rxjs';
import { share } from 'rxjs/operators';
import { HttpClient, HttpHeaders } from '@angular/common/http';

import { tap } from 'rxjs/operators';

import { User } from '../../models/user';
import { environment } from '@env/environment';
import { TokenService } from '@core/authentication/token.service';



const TOKEN_KEY = 'jwt';

@Injectable({
  providedIn: 'root',
})
export class AuthService {


  constructor(private http: HttpClient, private _token: TokenService) {}

  login(email: string, password: string) {
    const httpOptions = {
        headers: new HttpHeaders({ 
        //  'Access-Control-Allow-Origin':'*',
         // 'Accept': 'application/json',
        })
      };
    return this.http.post<any>(environment.SERVER_ORIGIN + 'identity/signin', {email: email, password: password},httpOptions)
          .pipe(
            tap((token: any) => {
              return token.data
            }),
          );
  }

  sendResetPasswordLink(email: string) {
    const httpOptions = {
        headers: new HttpHeaders({ 
             //  'Access-Control-Allow-Origin':'*',
         // 'Accept': 'application/json',
        })
      };
      return this.http.post<any>(environment.SERVER_ORIGIN + 'password/reset-password-request', {email: email},httpOptions)
          .pipe(
            tap((token: any) => {
              return token.data
            }),
          );
  }

  cambiarContrasena(currentPassword: string, passwordConfirm: string ) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
      console.log(currentPassword,passwordConfirm,httpOptions);

      return this.http.post<any>(environment.SERVER_ORIGIN + 'cambiarContrasena', {currentPassword: currentPassword, passwordConfirm: passwordConfirm},httpOptions)
          .pipe(
            tap((token: any) => {
              return token.data
            }),
          );
  }


 
  resetPassword(data) {
    const httpOptions = {
        headers: new HttpHeaders({ 
            
        })
      };
      console.log(data);

      return this.http.post<any>(environment.SERVER_ORIGIN + 'password/change-password', data,httpOptions)
      .pipe(
        tap((token: any) => {
          return token.data
        }),
      );  
  }
  
  registro(datos: User) {
    const httpOptions = {
        headers: new HttpHeaders({ 
        //  'Access-Control-Allow-Origin':'*'
        })
      };
      let data = {
        name: datos.nombre,
        apellido_p: datos.apellidoP,
        apellido_m: datos.apellidoM,
        celular: datos.celular,
        curp: datos.curp,
        email: datos.email,
        password: datos.password
      }
    return this.http.post(environment.SERVER_ORIGIN + 'identity/agregarUsuario', data,httpOptions)
          .pipe(
            tap(token => {
              return token
            }),
          );
  }


 
}
