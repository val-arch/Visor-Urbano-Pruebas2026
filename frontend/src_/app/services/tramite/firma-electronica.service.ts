import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { Observable } from 'rxjs';
import { environment } from '@env/environment';

@Injectable({
  providedIn: 'root'
})
export class FirmaElectronicaService {
  constructor(private http: HttpClient, private _token: TokenService) { }

  firmar(form, folio=''): Observable<any[]> { 
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return  this.http.post<any[]>(environment.SERVER_ORIGIN +`firmaElectronica`,form,httpOptions);
  }
}
