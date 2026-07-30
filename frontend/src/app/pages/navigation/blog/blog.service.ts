import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '@env/environment';

@Injectable({
  providedIn: 'root'
})
export class BlogService {

  constructor(private httpClient: HttpClient) {}

  get(id) {
    return this.httpClient.get(`${environment.SERVER_ORIGIN}blog/${id}`);
}


}
