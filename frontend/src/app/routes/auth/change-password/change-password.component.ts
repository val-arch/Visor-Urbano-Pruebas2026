import { Component, OnInit,ViewEncapsulation } from '@angular/core';
import { fuseAnimations } from '@fuse/animations';
import { FuseConfigService } from '@fuse/services/config.service';
import { AbstractControl, FormBuilder, FormGroup, ValidationErrors, ValidatorFn, Validators } from '@angular/forms';
import { AuthService } from '../../../core/authentication/auth.service';
import { throwError } from 'rxjs';
import {ActivatedRoute} from '@angular/router';
import { Router } from '@angular/router';
import Swal from 'sweetalert2';
import { MatSnackBar } from '@angular/material/snack-bar';





@Component({
  selector: 'app-change-password',
  templateUrl: './change-password.component.html',
  styleUrls: ['./change-password.component.scss'],
  encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})
export class ChangePasswordComponent implements OnInit {
  changePasswordForm: FormGroup;
  errors = null;
  hover1:boolean = false;
  hover2:boolean = false;
  hover3:boolean = false;
  successMsg = null;


  constructor(
    
      /**
     * Constructor
     *
     * @param {FuseConfigService} _fuseConfigService
     * @param {FormBuilder} _formBuilder
     */
    private _fuseConfigService: FuseConfigService,
    private _formBuilder: FormBuilder,
    public authService: AuthService,
    private router: Router,
    route: ActivatedRoute,
    private _snackBar: MatSnackBar



  ) { 
    this.changePasswordForm = this._formBuilder.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(6)]],
      passwordConfirm: ['', [Validators.required, confirmPasswordValidator]],
      passwordToken: ['']
  });
  route.queryParams.subscribe((params) => {
    this.changePasswordForm.controls['passwordToken'].setValue(params['token']);
  })
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

  ngOnInit(): void {
  }

  onSubmit(){
    this.authService.resetPassword(this.changePasswordForm.value).subscribe(
      result => {
        Swal.fire({
          title: '¡Éxito!',
          text: 'Contraseña recuperada con ¡Éxito!',
          icon: 'success',
          confirmButtonText: 'Ok'
        });
        this.router.navigateByUrl('/ingresar');
      },
 
      error => {
       this.errors = error.error.message;
       if(error.status == 406){
        this._snackBar.open('El tiempo de la validación expiro', 'Cerrar', { duration: 5000, horizontalPosition: 'center' });
        this.router.navigateByUrl('/recuperar');
       }
      if( error.status == 404){
        this._snackBar.open('Correo electrónico no encontrado', 'Cerrar', { duration: 5000, horizontalPosition: 'center' });

       }


      }
    );
  }

 



}
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
