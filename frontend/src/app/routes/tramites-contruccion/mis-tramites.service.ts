import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';
import { tap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class MisTramitesService {

  constructor(private http: HttpClient, private _token: TokenService) { }

  getData(page: number, filtro: string = '') {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `licencias_construccion/listado?page=${page}${filtro != '' ? '&folio=' + filtro : ''}`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }

  getDataGiros(page: number, filtro: string = '') {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `licencias_giro/listado?page=${page}${filtro != '' ? '&folio=' + filtro : ''}`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }


  getDataVentanilla(page: number, filtro: string = '') {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `licencias_construccion/getListadoVentanilla?page=${page}${filtro != '' ? '&folio=' + filtro : ''}`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }
  getDataDir(page: number, filtro: string = '') {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `licencias_construccion/listadoDirRev?page=${page}${filtro != '' ? '&folio=' + filtro : ''}`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }
  getDataSolv(page: number, filtro: string = '') {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.get(environment.SERVER_ORIGIN + `licencias_construccion/getListadoSolv?page=${page}${filtro != '' ? '&folio=' + filtro : ''}`, httpOptions).pipe(
      tap((respuesta: any) => {
        return respuesta;
      })
    );
  }
}
