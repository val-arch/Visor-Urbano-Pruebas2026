import { HttpClient, HttpHeaders, HttpResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';
import { Historico } from 'app/models/historico';
import { Observable } from 'rxjs';
import { catchError, tap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class HistoricoLicenciaService {

  constructor(private http: HttpClient,
     private _token: TokenService,
     private activatedRoute: ActivatedRoute) { }

     public upload(formData,nombre_archivo) {
      return this.http.post<any>(environment.SERVER_ORIGIN + `import-historico-licencia`, formData ,{
         headers: new HttpHeaders({
           'Authorization': this._token.get().token,
         }),
         observe: 'events',
         reportProgress: true
       });  
     }

     getHistorico(page: number,filter:string) {
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
      return this.http.get(environment.SERVER_ORIGIN + `get-historico-licencia?page=${page}&filter=${filter}`, httpOptions).pipe(
        tap((respuesta: any) => {
          return respuesta;
        })
      );
    }
    getFileName(response: HttpResponse<Blob>) {
      let filename: string;
      try {
        const contentDisposition: string = response.headers.get('content-disposition');
        const r = /(?:filename=")(.+)(?:;")/
        filename = r.exec(contentDisposition)[1];
      }
      catch (e) {
        filename = 'myfile.txt'
      }
      return filename
    }


    public downloadReport(file): Observable<any> {
      // Create url
      let url = environment.SERVER_ORIGIN + `export-historico-licencia`;
      var body = { filename: file };
      let headers: HttpHeaders = new HttpHeaders();
      headers = headers.append('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      headers = headers.append('Authorization', this._token.get().token);
      return this.http.post(url, body, {
        responseType: "blob",
        headers
      });
    }
    
    public downloadReportVisor(file): Observable<any> {
      // Create url
      let url = environment.SERVER_ORIGIN + `export-licencia`;
      var body = { filename: file };
      let headers: HttpHeaders = new HttpHeaders();
      headers = headers.append('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      headers = headers.append('Authorization', this._token.get().token);
      return this.http.post(url, body, {
        responseType: "blob",
        headers
      });
    }
  
  
    
    exportExcel(): any {
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        
        }),
      };

   
      const requestOptions: Object = {
        headers: httpOptions,
        responseType: 'blob'
      }
      return this.http.get(environment.SERVER_ORIGIN + `export-historico-licencia`,requestOptions);
    }
    

    exportExcel2(): any {
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        
        }),
      };
      return this.http.get(environment.SERVER_ORIGIN + `export-historico-licencia`, {  responseType: 'blob'});
    }

   
     getLicenciasEmitidas(page: number,filter:string) {
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
      return this.http.get(environment.SERVER_ORIGIN + `licencias_giro/listado-licencias?page=${page}&filter=${filter}`, httpOptions).pipe(
        tap((respuesta: any) => {
          return respuesta;
        })
      );
    }

    licenciaPagadaHis(id,data){
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };

        return this.http.post(environment.SERVER_ORIGIN +`generarLicencia/pagarHis/${id}`,data,httpOptions);
    }

    licenciaPagada(id,data){
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };

        return this.http.post(environment.SERVER_ORIGIN +`generarLicencia/pagar/${id}`,data,httpOptions);
    }
    bajaLicencia(id,data){
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };

        return this.http.post(environment.SERVER_ORIGIN +`generarLicencia/baja/${id}`,data,httpOptions);
    }
    licenciaEscaneada(id,data){
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };

        return this.http.post(environment.SERVER_ORIGIN +`generarLicencia/pdf_firmado/${id}`,data,httpOptions);
    }

    licenciaEscaneadaHist(id,data){
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
        return this.http.post(environment.SERVER_ORIGIN +`generarLicencia/pdf_firmado_hist/${id}`,data,httpOptions);
    }


    storeHistorial(model:Historico,id) {
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
      if (id) {
        return this.http.put<Historico[]>(environment.SERVER_ORIGIN +`editar/${id}`,model,httpOptions);
      }else{ 
        delete model.id;  
       
        return this.http.post<Historico[]>(environment.SERVER_ORIGIN +`editar`,model,httpOptions);
      }
    }
    
  deleteHistorico(id) {
    const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
    };
    return this.http.delete<Historico[]>(environment.SERVER_ORIGIN +`eliminar/${id}`,httpOptions);
  }


  deleteAllHistorico() {
    const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
    };
    return this.http.delete<Historico[]>(environment.SERVER_ORIGIN +`eliminar`,httpOptions);
  }
  
  

}
