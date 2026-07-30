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

  async uploadFileProrroga(archivo:File,folio:string, inputName:string){
    try {
      const url  = environment.SERVER_ORIGIN +`licencias_construccion/fileUpload/${folio}/${inputName}`;
      const formData = new FormData();
      formData.append('file',archivo);
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
      return await fetch(url,{
        method:'POST',
        headers:{
        'Authorization': this._token.get().token,
        },
        body:formData
      });
    } catch (error) {
      console.log(error);
      return false;
    }

  }


////Servicios Construccion  

async actualizarImagenConstruccion(archivo:File,id:number){
  try {
    const url  = environment.SERVER_ORIGIN +`municipios_construccion/${id}/image`;
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
    console.log(resp);
  } catch (error) {
    console.log(error);
    return false;
  }

}
async actualizarImagenFirmaConstruccion(archivo:File,id:number){
  try {
    const url  = environment.SERVER_ORIGIN +`municipios_construccion/${id}/firma`;
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
  } catch (error) {
    console.log(error);
    return false;
  }
}

}
