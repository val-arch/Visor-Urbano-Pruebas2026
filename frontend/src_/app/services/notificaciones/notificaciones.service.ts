import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';
import { SolventacionModel } from 'app/models/administrador/solventacion.models';
import { Observable } from 'rxjs';
import { ActivatedRoute } from '@angular/router';
import { tap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class NotificacionesService {

  constructor(private http: HttpClient, private _token: TokenService,private activatedRoute: ActivatedRoute) { }


  actualizarSolventacion(comentario:string,id){
    console.log(id);
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };

    const formData = new FormData();
    formData.append("comentario", comentario);
    return this.http.post<SolventacionModel[]>(environment.SERVER_ORIGIN +`solventacion/comentario/${id}`,formData,httpOptions);
  } 

  getNotificaciones(id): Observable<any[]> { 
      if(this.activatedRoute.snapshot['_routerState']._root.children[0].children[0] == undefined){
         var folio =this.activatedRoute.snapshot['_routerState']._root.children[0].value.params.folio;
      }else{
         var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
      } 
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
      return  this.http.get<any[]>(environment.SERVER_ORIGIN +`solventacion/getNotificacion/${id}`,httpOptions);
    }
  
    getFiles(id): Observable<any[]> { 
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
      return  this.http.get<any[]>(environment.SERVER_ORIGIN +`solventacion/getFilesSolventacion/${id}`,httpOptions);
    }
     updateFiles(file,id): Observable<any[]> { 
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
      const formData = new FormData();
      formData.append("ruta", file);
      return this.http.post<any[]>(environment.SERVER_ORIGIN +`solventacion/updateFilesSolventacion/${id}`,formData,httpOptions);

    }
  

  async actualizarImagen(archivo,id){

    try {
      const url  = environment.SERVER_ORIGIN +`solventacion/subir_archivo/${id}`;
      const formData = new FormData();
      for(var i=0; i<archivo.length; i++){
      
        formData.append('image[]',archivo[i]);
      }
      
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
       const resp = await fetch(url,{
           method:'POST',
           headers:{
            'Authorization': this._token.get().token,
          },
          body:formData
       });
       console.log(resp)
    } catch (error) {
      console.log(error);
      return false;
    }

  }
}
