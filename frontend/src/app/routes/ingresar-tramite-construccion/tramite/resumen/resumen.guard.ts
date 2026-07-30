import { Injectable, Inject, Optional } from '@angular/core';
import {
    CanActivate,
    CanActivateChild,
    CanLoad,
    Route,
    ActivatedRouteSnapshot,
    RouterStateSnapshot,
    UrlSegment,
    Router,
    UrlTree,
} from '@angular/router';
import { DOCUMENT } from '@angular/common';
import { TokenService } from '../../../../core/authentication/token.service';
import { CamposTramiteServiceService } from 'app/services/tramite/iniciar-tramite/campos-tramite-service.service';
import { Observable } from 'rxjs';
import { environment } from '@env/environment';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { map } from 'rxjs/operators';
import { SettingsService } from '@core/settings.service';

const LOGIN_URL = '/tramites_construccion';

@Injectable({
    providedIn: 'root',
})
export class ResumenGuard implements CanActivate, CanActivateChild, CanLoad {
    private gotoLogin(url?: string) {
        setTimeout(() => {
            if (/^https?:\/\//g.test(url!)) {
                this.document.location.href = url as string;
            } else {
                this.router.navigateByUrl(url);
            }
        });
    }

    private process(route): any {
        // const res = this.checkJWT(, 1000);

    }

    constructor(
        private router: Router,
        private token: TokenService,
        private settings: SettingsService,
        private tramite: CamposTramiteServiceService,
        private http: HttpClient,
        @Optional() @Inject(DOCUMENT) private document: any
    ) { }

    // lazy loading
    canLoad(route: Route, segments: UrlSegment[]): any {
        return this.process(route);
    }
    // route
    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
        const user = this.settings.user;
        const formData = new FormData();
        formData.append("folio", route.params.folio);
        const httpOptions = {
            headers: new HttpHeaders({
                'Authorization': this.token.get().token,
            }),
        };
        return this.http.post<any>(environment.SERVER_ORIGIN + `tramites_construccion/ingreso`, formData, httpOptions).pipe(
            map(res => {
                if (user.rol > 1 && res['folio'].split('-')[0] == user.id_municipio) {
                    if(res['enviado_revisores']){
                        this.gotoLogin(LOGIN_URL);
                        return false;
                    }else{
                        return true
                    }
                }
                if (res['step_uno'] == 1 && res['step_dos'] == 1 && res['step_tres'] == 1 && res['step_cuatro'] == 1) {
                   if(res['enviado_revisores']){
                     this.gotoLogin(LOGIN_URL);  
                   }else{
                       return true;
                   }
                } else {
                    this.gotoLogin(LOGIN_URL);
                    return false;
                }
            }))
    }
    // all children route
    canActivateChild(childRoute: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean {
        return this.process(childRoute);
    }
}
