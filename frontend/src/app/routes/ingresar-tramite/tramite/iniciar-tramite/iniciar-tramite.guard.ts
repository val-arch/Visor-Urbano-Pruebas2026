import { DOCUMENT } from '@angular/common';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Inject, Injectable, Optional } from '@angular/core';
import { CanActivate, CanActivateChild, CanLoad, Route, UrlSegment, ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree, Router, ActivatedRoute } from '@angular/router';
import { TokenService } from '@core/authentication/token.service';
import { SettingsService } from '@core/settings.service';
import { environment } from '@env/environment';
import { ConsultaRequisitos } from 'app/models/administrador/consulta_requisito.models';
import { CamposTramiteServiceService } from 'app/services/tramite/iniciar-tramite/campos-tramite-service.service';
import { ResumenService } from 'app/services/tramite/resumen.service';
import { truncate } from 'lodash';
import { Observable } from 'rxjs';
import { IniciarTramiteService } from '../../../../services/tramite/iniciar-tramite/iniciar-tramite.service';
@Injectable({
  providedIn: 'root'
})
export class IniciarTramiteGuard implements CanActivate, CanActivateChild, CanLoad {
  id_municipio_tramite = null;
  f_user = null;
  canActivate(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    const user     = this.settings.user;
    const formData = new FormData();
    var folio      = route.params.folio;
    formData.append("folio", route.params.folio);
    const httpOptions = {
      headers: new HttpHeaders({
        'Authorization': this.token.get().token,
      }),
    };
    this._resumenService.getInfoTramite(atob(folio)).toPromise().then
      (res => {
        this.id_municipio_tramite = res['data'].info['id_municipio'];
        this.f_user               = res['data'].info['f_user'];
        
        if(this.f_user == ""){
        
        }
    });
    return new Promise((resolve) => {
      setTimeout(() => {
      
        let a;
        if (this.settings.user.rol > 1 && this.settings.user['id_municipio'] == this.id_municipio_tramite) {
          a = true;
       
        } else {
          
          if (this.settings.user.rol == 1 && (this.f_user == null || this.f_user == "" )){
            a = true;
        
          }else{
            a = false;
           
          }
         
        }
        resolve(a);
      }, 2000);
    })
  }
  constructor(

    private token: TokenService,
    private settings: SettingsService,
    private tramite: CamposTramiteServiceService,
    private http: HttpClient,
    private _resumenService: ResumenService,
    private tramiteService: IniciarTramiteService,
    @Optional() @Inject(DOCUMENT) private document: any
  ) {



  }

  canActivateChild(
    next: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    return true;
  }
  canLoad(
    route: Route,
    segments: UrlSegment[]): Observable<boolean> | Promise<boolean> | boolean {

    return true;
  }
}
