import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

import { FuseConfigService } from '@fuse/services/config.service';
import { fuseAnimations } from '@fuse/animations';
import { AuthService } from '../../../core/authentication/auth.service';
import { TokenService } from '../../../core/authentication/token.service';
import { FuseSplashScreenService } from '../../../../@fuse/services/splash-screen.service';
import { SettingsService } from '../../../core/settings.service';
import { Router, ActivatedRoute } from '@angular/router';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MapaService } from 'app/services/mapa/mapa.service';

@Component({
    selector: 'login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})
export class LoginComponent implements OnInit {
    loginForm: FormGroup;
    cargando: boolean = true;
    hover1: boolean = false;
    hover2: boolean = false;
    routeAnterior;
    hide = true;
    /**
     * Constructor
     *
     * @param {FuseConfigService} _fuseConfigService
     * @param {FormBuilder} _formBuilder
     */
    constructor(
        private _fuseConfigService: FuseConfigService,
        private _formBuilder: FormBuilder,
        private _auth: AuthService,
        private token: TokenService,
        private _splash: FuseSplashScreenService,
        private _settings: SettingsService,
        private router: Router,
        private routerA: ActivatedRoute,
        private _snackBar: MatSnackBar,
        private _mapaService: MapaService,
    ) {
        // Configure the layout
        this._fuseConfigService.config = {
            layout: {
                navbar: {
                    hidden: true
                },
                toolbar: {
                    hidden: true
                },
                footer: {
                    hidden: true
                },
                sidepanel: {
                    hidden: true
                }
            }
        };
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Lifecycle hooks
    // -----------------------------------------------------------------------------------------------------

    /**
     * On init
     */
    ngOnInit(): void {
        this.loginForm = this._formBuilder.group({
            email: ['', [Validators.required, Validators.email]],
            password: ['', Validators.required]
        });
        // console.log(this.router);
        // console.log(this.routerA);


        //  this.routerA.queryParams.filter(params=>{params.p}).subscribe(params=>{
        //      console.log();
        //  })
        this.routerA.queryParams.subscribe(e => {
            this.routeAnterior = e.p;
            //  console.log(this.routeAnterior);
            //  console.log(atob(this.routeAnterior));
        })
    }

    async login() {
        if (!this.loginForm.valid) return;
        const user = this.loginForm.value;
        this._splash.show();
        await this._auth.login(user.email, user.password).subscribe(async (respuesta: any) => {
            const e = respuesta.data;

            const token = e.access_token,
                uid = e.user.id,
                username = e.user.email,
                role = e.user.role_id,
                expiration = e.expiration;
            let data = {
                id: uid,
                user_type: e.user.user_type,
                permiso_dir: e.user.permiso_dir,
                name: e.user.name,
                email: username,
                avatar: '/assets/images/default.png',
                rol: e.user.role_id,
                rol_name: e.user.role_name,
                id_municipio: e.user.id_municipio,
                nombre_municipio: '',
                image_municipio: e.user.image_municipio ?? ''
            };
            this.token.set({ token, uid, username, role, expiration });
            if (e.user.id_municipio > 0) {
                await this._mapaService.getGeomMunicipio(e.user.id_municipio)
                    .subscribe((r: any) => {
                        data.nombre_municipio = r.properties.nombre.replace(/ /g, "-");
                        this._settings.setUser(data);
                        this.goto(e.user.role_id)
                    })
            } else {
                this._settings.setUser(data);
                this.goto(e.user.role_id)
            }
            this.cargando = false;
        }, e => {
            //console.log(e, 'error');
            this.cargando = false;
            this._splash.hide();
            // this._prelo.hide();
            this._snackBar.open('Usuario o contraseña incorrectos', 'Cerrar', { duration: 5000, horizontalPosition: 'center' });
        }
        );
    }
    verPss(e: Event) {
        console.log(e.target)
        console.log(e);
        console.log(e.AT_TARGET)
        e.preventDefault()
        this.hide = !this.hide
    }

    goto(role) {
        let url = (this.routeAnterior) ? atob(this.routeAnterior) : '/tramites';
        if (role == 5) {
            url = '/reportes';
        }
        if (role == 6) {
            url = '/administrador/capas-municipio';
        }
        window.location.href = url;
        this.router.navigateByUrl(url);
        this._splash.hide();
    }
}
