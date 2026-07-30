import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '@env/environment';
import { tap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class BoletinService {

  constructor(private http: HttpClient, ) { }

  getData(page: number) {

    return this.http.get(environment.SERVER_ORIGIN + `boletin/?page=${page}`);
  }
}
