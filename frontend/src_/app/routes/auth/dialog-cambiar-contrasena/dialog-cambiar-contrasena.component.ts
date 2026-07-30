import { Component, OnInit } from '@angular/core';
import { MatDialog,MatDialogRef } from '@angular/material/dialog';

import { fuseAnimations } from '@fuse/animations';
import { FuseConfigService } from '@fuse/services/config.service';
import { AbstractControl, FormBuilder, FormGroup, ValidationErrors, ValidatorFn, Validators } from '@angular/forms';
import { AuthService } from '../../../core/authentication/auth.service';
import { throwError } from 'rxjs';
import {ActivatedRoute} from '@angular/router';
import Swal from 'sweetalert2';
import { Router } from '@angular/router';
import { MatSnackBar } from '@angular/material/snack-bar';



@Component({
  selector: 'app-dialog-cambiar-contrasena',
  templateUrl: './dialog-cambiar-contrasena.component.html',
  styleUrls: ['./dialog-cambiar-contrasena.component.scss']
})
export class DialogCambiarContrasenaComponent implements OnInit {
  cambiarContrasenaForm: FormGroup;
  errors = null;
  hover1:boolean = false;
  hover2:boolean = false;
  hover3:boolean = false;
  successMsg = null;
  constructor(public dialogRef: MatDialogRef<any>,
       /**
     * Constructor
     *
     * @param {FuseConfigService} _fuseConfigService
     * @param {FormBuilder} _formBuilder
     */
    private _fuseConfigService: FuseConfigService,
    private _snackBar: MatSnackBar,
    private _formBuilder: FormBuilder,
    public authService: AuthService,
    private router: Router,
    route: ActivatedRoute) { 
      this.cambiarContrasenaForm = this._formBuilder.group({
        currentPassword: ['', [Validators.required, Validators.minLength(6)]],
        password: ['', [Validators.required, Validators.minLength(6)]],
        passwordConfirm: ['', [Validators.required, confirmPasswordValidator]],
        passwordToken: ['']
    });
   route.queryParams.subscribe((params) => {
      this.cambiarContrasenaForm.controls['passwordToken'].setValue(params['token']);
    })
    }

  ngOnInit(): void {
    this.dialogRef.updateSize('30%', '80%');

  }

  OnSubmit(){

    const cambiar = this.cambiarContrasenaForm.value;
    this.authService.cambiarContrasena(cambiar.currentPassword, cambiar.passwordConfirm).subscribe(
      result => {
       // this.successMsg = result;

       Swal.fire({
          title: '¡Éxito!',
          text: 'Contraseña cambiada con ¡Éxito!',
          icon: 'success',
          confirmButtonText: 'Ok'
        });
        this.dialogRef.close();
        //this.router.navigateByUrl('/login');

      },
      error => {
       this.errors = error.error.message;
       this._snackBar.open('Contraseña no existente', 'Cerrar', { duration: 5000 });


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
