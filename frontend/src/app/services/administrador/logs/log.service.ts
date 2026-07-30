import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { TokenService } from '@core/authentication/token.service';
import { tap } from 'rxjs/operators';
import { environment } from '@env/environment';

@Injectable({
  providedIn: 'root'
})
export class LogService {

  constructor(private http: HttpClient,
    private _token: TokenService,
    private activatedRoute: ActivatedRoute) { }

    getLogsHistorial(page: number,filter:string,filter2:string,tipo:string) {
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
      return this.http.get(environment.SERVER_ORIGIN + `logs/consultar?tipo=${tipo}&page=${page}&filter=${filter}&filter2=${filter2}`, httpOptions).pipe(
        tap((respuesta: any) => {
          return respuesta;
        })
      );
    }

}


