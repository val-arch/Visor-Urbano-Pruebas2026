import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';
import { tap } from 'rxjs/operators';


@Injectable({
  providedIn: 'root'
})
export class NotificacionesService {

  constructor(private http: HttpClient, private _token: TokenService) {}

  getData(page: number) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
   
    return this.http.get(environment.SERVER_ORIGIN + `listadoNotificaciones?page=${page}`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }


  updateNotificacion(id: number) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };

      return this.http.get<any>(environment.SERVER_ORIGIN +  `updateNotificacion/${id}`,httpOptions)
          .pipe(
            tap((token: any) => {
              return token.data
            }),
          );
  }


  

}

