import { Injectable } from '@angular/core';
import { Router, ActivatedRouteSnapshot } from '@angular/router';
import {
  HttpEvent,
  HttpInterceptor,
  HttpHandler,
  HttpRequest,
  HttpErrorResponse,
  HttpResponse,
} from '@angular/common/http';
import { Observable, of, throwError } from 'rxjs';
import { mergeMap, catchError } from 'rxjs/operators';
import { environment } from '@env/environment';

import { TokenService } from '../authentication/token.service';
import { SettingsService } from '@core/settings.service';

import Swal from 'sweetalert2';
import { FuseSplashScreenService } from '@fuse/services/splash-screen.service';

@Injectable()
export class DefaultInterceptor implements HttpInterceptor {
  constructor(
    private router: Router,
    private token: TokenService,
    private settings: SettingsService,
    private _splash: FuseSplashScreenService,
 //   private routeActive: ActivatedRouteSnapshot,
  ) {
//console.log(123);

  }

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    // Add server host
    const url =  req.url;

    // Only intercept API url
    /*
    if (!url.includes('/api/')) {
      return next.handle(req);
    }  */

    // All APIs need JWT authorization
    const headers = {
      //'Accept': 'application/json',
      //'Accept-Language': this.settings.language,
    };

    //this._splash.show();

    const newReq = req.clone({ url});
    //console.log(newReq);
    return next.handle(newReq).pipe(
      mergeMap((event: HttpEvent<any>) => this.handleOkReq(event)),
      catchError((error: HttpErrorResponse) => this.handleErrorReq(error))
    );
  }

  private goto(url: string) {
    setTimeout(() => this.router.navigateByUrl(url));
  }


  private handleOkReq(event: HttpEvent<any>): Observable<any> {
    if (event instanceof HttpResponse) {
      const body: any = event.body;
      // success: { code: 0,  msg: 'success', data: {} }
      if (body && event.status !== 0) {

          return of(event);
        } else {
            return throwError([]);
      }
    }
    // Pass down event if everything is OK
    return of(event);
  }

  private handleErrorReq(error: HttpErrorResponse): Observable<never> {
    //this._splash.hide();
    //console.log(error);
    switch (error.status) {
      case 401:
       //this.goto(`/login`);
        if(this.token.get()){
          Swal.fire({
            title:'Sesión expirada',
            text:'Ingresa usuario',
            icon:'info',
            confirmButtonText:'Muy bien'
          });
        }
        this.token.clear();
        // console.log(this.router);
        //let rou: any = this.routeActive;
        //this.router.navigate([url],{queryParams:{p:btoa(route._routerState.url)}});
        setTimeout(() => window.location.href = `/ingresar?p=${btoa(this.router.routerState.snapshot.url)}`);
        break;
      case 403:
        Swal.fire({
          title:'No tienes permiso',
          text:'',
          icon:'error',
          confirmButtonText:'Muy bien'
        });
        this.goto(`/tramites`);
        break;
      case 404:
      case 405:

        break
      case 500:
       //this.goto(`/tramites`);
        break;
      default:
        if (error instanceof HttpErrorResponse) {
          // console.error('ERROR', error);
          // this.toastr.error(error.error.msg || `${error.status} ${error.statusText}`);
        }
        break;
    }
    return throwError(error);
  }
}
