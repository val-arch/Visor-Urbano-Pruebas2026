import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { TokenService } from '@core/authentication/token.service';
import { Observable } from 'rxjs';
import { environment } from '@env/environment';
import { ActivatedRoute } from '@angular/router';
import { map } from 'rxjs/operators';
@Injectable({
  providedIn: 'root'
})
export class CamposTramiteServiceService {

  constructor(private http: HttpClient, private _token: TokenService,private activatedRoute: ActivatedRoute) { 
   /* this.activatedRoute.queryParams.subscribe(params => {
    //  let date = params['startdate'];
      console.log(params); // Print the parameter to the console. 
  });*/
  }
  public validarIngreso(formData){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post<any>(environment.SERVER_ORIGIN + `tramites/ingreso`, formData, httpOptions);  
  }
  public validarIngresoRefrendo(formData){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post<any>(environment.SERVER_ORIGIN + `tramites/ingresoRefrendo`, formData, httpOptions);  
  }
  public validarResumenI(formData){
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
     this.http.post<any>(environment.SERVER_ORIGIN + `tramites/ingreso`, formData, httpOptions).pipe(
       map(res=>{
            if(res['step_uno'] == 1 && res['step_dos'] == 1 && res['step_tres'] == 1 && res['step_cuatro'] == 1 ){
              return   true;
          }else{
              return  false;
          }
     }));  
  }
  public upload(formData,nombre_archivo) {
   return this.http.post<any>(environment.SERVER_ORIGIN + `licencias_giro/folio_tramite/${nombre_archivo}`, formData ,{
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
      observe: 'events',
      reportProgress: true
    });  
  }
  
   
     
  public uploadDataRefrendo(formData) {
    var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
    this.activatedRoute.snapshot.params.folio;
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post<any>(environment.SERVER_ORIGIN + `licencias_giro/folio_tramite_text_refrendo`, formData, httpOptions);  
  }
  public uploadDataRefrendoHistorico(formData) {
    var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
    this.activatedRoute.snapshot.params.folio;
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post<any>(environment.SERVER_ORIGIN + `licencias_giro/folio_tramite_historico_refrendo`, formData, httpOptions);  
  }
  public uploadData(formData) {
    var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
    this.activatedRoute.snapshot.params.folio;
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.post<any>(environment.SERVER_ORIGIN + `licencias_giro/folio_tramite_text`, formData, httpOptions);  
  }
  
  getCamposDinamicosId(): Observable<any[]> { 
    
    if(this.activatedRoute.snapshot['_routerState']._root.children[0].children[0] == undefined){
      var folio =this.activatedRoute.snapshot['_routerState']._root.children[0].value.params.folio;
      var id =this.activatedRoute.snapshot['_routerState']._root.children[0].value.params.id;
   
    }else{
      var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
      var id = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.id;
    } 
  
      const httpOptions = {
        headers: new HttpHeaders({
          'Authorization': this._token.get().token,
        }),
      };
      return  this.http.get<any[]>(environment.SERVER_ORIGIN +`campos/getCampos/${folio}/${id}`,httpOptions);
    }
  getCamposDinamicos(): Observable<any[]> { 
    
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
    return  this.http.get<any[]>(environment.SERVER_ORIGIN +`campos/getCampos/${folio}`,httpOptions);
  }

  getCamposDinamicosRefrendo(): Observable<any[]> { 
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
      return  this.http.get<any[]>(environment.SERVER_ORIGIN +`campos/getCamposRefrendo/${folio}`,httpOptions);
    }

  updateHorasSuperficie(hora_a, hora_c, superficie, folio){

    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this._token.get().token,
      }),
    };
    return this.http.put<any>(environment.SERVER_ORIGIN + `licencias_giro/updateHorasSuperficie/${folio}`, {hora_apertura: hora_a, hora_cierre: hora_c, superficie_mts:superficie}, httpOptions);

  }
}