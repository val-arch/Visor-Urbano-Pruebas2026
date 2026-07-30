import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '@env/environment';

@Injectable({
  providedIn: 'root'
})
export class ContentService {

  constructor(private httpClient: HttpClient) {}

  get() {
    return this.httpClient.get(`${environment.SERVER_ORIGIN}blog`);
}


}
