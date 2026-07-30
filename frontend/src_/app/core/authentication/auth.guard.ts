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
  RoutesRecognized,
  ActivatedRoute,
} from '@angular/router';
import { DOCUMENT } from '@angular/common';
import { TokenService } from './token.service';
import { filter, pairwise, map } from 'rxjs/operators';
import Swal from 'sweetalert2';

const LOGIN_URL = '/ingresar';

@Injectable({
  providedIn: 'root',
})
export class AuthGuard implements CanActivate, CanActivateChild, CanLoad {
  private gotoLogin(url?: string,route?:any) {
    setTimeout(() => {
      if (/^https?:\/\//g.test(url!)) {
   //     this.document.location.href = url as string;
      } else {
      //  this.router.navigateByUrl(url);
        if(route){
           this.router.navigate([url],{queryParams:{p:btoa(route._routerState.url)}});
        }else{
          this.router.navigate([url]);
        }
        
      }
    });
  }

  private checkJWT(model: any, offset?: number): boolean {
    return !!model?.token;
  }
  private checkExpiration(model: any, offset?: number): boolean {
    var fecha = new Date(model.expiration), now = new Date();
    if(now > fecha){
      Swal.fire({
        title:'Sesión expirada',
        text:'Ingresa usuario',
        icon:'info',
        confirmButtonText:'Muy bien'
      });
      this.token.clear();
      return false;
    }else{
      return true;
    }
  }

  private process(route:any=''): boolean {
    // console.log(route);
    // console.log(this.token.get());
    const res = this.checkJWT(this.token.get<any>(), 1000);
    const exp = this.checkExpiration(this.token.get<any>(), 1000);
  // if (!res || !exp) {
  if (!res || !exp) {
  this.gotoLogin(LOGIN_URL,route);
    }
    return res;
  }

  constructor(
    private router: Router,
    private token: TokenService,
    private route2:ActivatedRoute,
    @Optional() @Inject(DOCUMENT) private document: any
  ) {}

  // lazy loading
  canLoad(route: Route, segments: UrlSegment[]): boolean {
    return this.process();
  }
  // route
  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean {
    return this.process(route);
  }
  // all children route
  canActivateChild(childRoute: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean {
    return this.process();
  }
}
