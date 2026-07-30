import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '@env/environment';

@Injectable({
  providedIn: 'root'
})
export class ManagerService {

  constructor(private httpClient: HttpClient) {}

  getAll(key) {
    return this.httpClient.get(`${environment.SERVER_ORIGIN}blog/user/${key}`);
}

  send(item,type=1){
    console.log(item);
    if(item.id){
      return this.httpClient.post(`${environment.SERVER_ORIGIN}blog/${item.id}`,item);
    }else{
      return this.httpClient.post(`${environment.SERVER_ORIGIN}blog`,item);
    }
    // if(type==1){
    //   delete item.id;
    // }
    // console.log(type);
  }

  delete(item){
    return this.httpClient.delete(`${environment.SERVER_ORIGIN}blog/${item.id}/${atob(item.password)}`);
  }


}
