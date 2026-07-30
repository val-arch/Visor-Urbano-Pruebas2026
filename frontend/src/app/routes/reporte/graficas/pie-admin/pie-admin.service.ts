import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';
@Injectable({
  providedIn: 'root'
})
export class PieAdminService {

  constructor(private http: HttpClient, private _token: TokenService) {}

  getData(data?) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
   
    return this.http.get(environment.SERVER_ORIGIN + `graficas/pie2?f_inicio=${data.f_inicio}&f_final=${data.f_fin}&tipo_licencia=${data.tipo_licencia}&origen_licencia=${data.origen_licencia}`, httpOptions);
  }
  getDataMunicipio(id,data?) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
   
    return this.http.get(environment.SERVER_ORIGIN + `graficas/bar/${id}?f_inicio=${data.f_inicio}&f_final=${data.f_fin}&tipo_licencia=${data.tipo_licencia}&origen_licencia=${data.origen_licencia}`, httpOptions);
  }
  getAdvancedPie(id_municipio=null) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
   //?id_municipio=${id_municipio}
    return this.http.get(environment.SERVER_ORIGIN + `graficas/advancedpie-admin`, httpOptions);
  }
  formatDate(dateString: string | null): string {
    if (!dateString) {
      return '';  // Handle the null case
    }
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    });
 }
 getMostGiros(startDate: string, endDate: string) {
  const formattedStartDate = this.formatDate(startDate);
  const formattedEndDate = this.formatDate(endDate);

  const httpOptions = {
    headers: new HttpHeaders({
      'Authorization': this._token.get().token,
    }),
    params: {
      startDate: formattedStartDate,
      endDate: formattedEndDate
    }
  };
  return this.http.get(environment.SERVER_ORIGIN + `graficas/getMostUsedScian`, httpOptions);
}


  getRefrendos() {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
   //?id_municipio=${id_municipio}
    return this.http.get(environment.SERVER_ORIGIN + `graficas/getReporteHistorico`, httpOptions);
  }
  getLicencias() {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
   //?id_municipio=${id_municipio}
    return this.http.get(environment.SERVER_ORIGIN + `graficas/getReporteLicencias`, httpOptions);
  }

  downloadExcel() {
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
      responseType: 'blob' as 'json'  // Required for Angular 6+
    };

    return this.http.get(environment.SERVER_ORIGIN + `/graficas/download-data`, httpOptions).subscribe(
      (response: Blob) => {  // Directly type the response as Blob
        this.downloadFile(response, "application/vnd.ms-excel", 'data.xlsx');
      },
      error => console.error('Error downloading the file:', error)
    );
  }

  private downloadFile(data: Blob, type: string, filename: string) {
    const blob = new Blob([data], { type: type });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
  }
}
