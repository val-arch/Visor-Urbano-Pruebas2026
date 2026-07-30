import { Component, OnDestroy, OnInit, ViewEncapsulation } from '@angular/core';
import { AbstractControl, FormBuilder, FormGroup, ValidationErrors, ValidatorFn, Validators } from '@angular/forms';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';

import { FuseConfigService } from '@fuse/services/config.service';
import { fuseAnimations } from '@fuse/animations';
import { User } from 'app/models/user';
import { ActivatedRoute, Router } from '@angular/router';
import { AuthService } from '@core/authentication/auth.service';
import { TokenService } from '@core/authentication/token.service';
import { SettingsService } from '@core/settings.service';
import { FuseSplashScreenService } from '@fuse/services/splash-screen.service';

import Swal from 'sweetalert2';
import { MatSnackBar } from '@angular/material/snack-bar';

import { MatDialog } from '@angular/material/dialog';
import { DialogAvisosPrivacidadComponent } from 'app/pages/dialogs/dialog-avisos-privacidad/dialog-avisos-privacidad.component';


@Component({
    selector: 'register',
    templateUrl: './register.component.html',
    styleUrls: ['./register.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})
export class RegisterComponent implements OnInit, OnDestroy {
    user: User;
    registerForm: FormGroup;
    hover:boolean = false;
    hover1:boolean = false;
    hover2:boolean = false;
    hover3:boolean = false;
    hover4:boolean = false;
    hover5:boolean = false;
    hover6:boolean = false;
    routeAnterior='';
    disabled = false;


    // Private
    private _unsubscribeAll: Subject<any>;

    constructor(
        private _fuseConfigService: FuseConfigService,
        private _formBuilder: FormBuilder,
        private _auth: AuthService,
        private token: TokenService,
        private _splash: FuseSplashScreenService,
        private _settings: SettingsService,
        private router: Router,
        private _snackBar: MatSnackBar,
        public dialog: MatDialog,
        private routerA: ActivatedRoute,
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

        // Set the private defaults
        this._unsubscribeAll = new Subject();
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Lifecycle hooks
    // -----------------------------------------------------------------------------------------------------

    /**
     * On init
     */
    ngOnInit(): void {
        this.registerForm = this._formBuilder.group({
            nombre: ['', Validators.required],
            apellidoP: ['', Validators.required],
            apellidoM: [''],
            celular: ['', [Validators.required, Validators.minLength(10),Validators.maxLength(10)]],
            curp: ['', [Validators.required, Validators.minLength(18),Validators.maxLength(18)]],
            email: ['', [Validators.required, Validators.email]],
            password: ['', [Validators.required, Validators.minLength(6)]],
            passwordConfirm: ['', [Validators.required, confirmPasswordValidator]]
        });
    

        // Update the validity of the 'passwordConfirm' field
        // when the 'password' field changes
        this.registerForm.get('password').valueChanges
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(() => {
                this.registerForm.get('passwordConfirm').updateValueAndValidity();
            });

            this.routerA.queryParams.subscribe(e=>{
                this.routeAnterior= e.p ? e.p:'';
                 console.log(this.routeAnterior);
                })
    }

    /**
     * On destroy
     */
    ngOnDestroy(): void {
        // Unsubscribe from all subscriptions
        this._unsubscribeAll.next();
        this._unsubscribeAll.complete();
    }
    
    registro() {
        this.user = this.registerForm.value;
        console.log(this.user);
        console.log(this.registerForm);
        this._splash.show();
        this._auth.registro(this.user).subscribe((e: any) => {
            this._splash.hide();
            Swal.fire({
                title: '¡Éxito!',
                text: 'Cuenta creda con exito!',
                icon: 'success',
                confirmButtonText: 'Ok'
            });
            this.router.navigateByUrl(`/ingresar${this.routeAnterior !='' ? '?p='+this.routeAnterior :  ''}`);
        }, e => {
            this._splash.hide();
            this._snackBar.open('Correo existente', 'Cerrar', { duration: 5000 });
        }
        );
    }

    openDialogAvisos(){
        this.dialog.open(DialogAvisosPrivacidadComponent);
      }

    
}

/**
 * Confirm password validator
 *
 * @param {AbstractControl} control
 * @returns {ValidationErrors | null}
 */
export const confirmPasswordValidator: ValidatorFn = (control: AbstractControl): ValidationErrors | null => {

    if (!control.parent || !control) {
        return null;
    }

    const password = control.parent.get('password');
    const passwordConfirm = control.parent.get('passwordConfirm');

    if (!password || !passwordConfirm) {
        return null;
    }

    if (passwordConfirm.value === '') {
        return null;
    }

    if (password.value === passwordConfirm.value) {
        return null;
    }

    return { passwordsNotMatching: true };
};
