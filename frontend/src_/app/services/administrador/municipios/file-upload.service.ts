import { Injectable } from '@angular/core';
import { environment } from '@env/environment';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { TokenService } from '@core/authentication/token.service';

@Injectable({
  providedIn: 'root'
})
export class FileUploadService {

  constructor(private http: HttpClient, private _token: TokenService) { } 

 

  async actualizarImagen(archivo:File,id:number){
    try {
      const url  = environment.SERVER_ORIGIN +`municipios/${id}/image`;
      const formData = new FormData();
      formData.append('image',archivo);
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
  async actualizarImagenFirma(archivo:File,id:number){
    try {
      const url  = environment.SERVER_ORIGIN +`municipios/${id}/firma`;
      const formData = new FormData();
      formData.append('image',archivo);
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
