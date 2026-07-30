import { Component, OnInit,ViewEncapsulation } from '@angular/core';
import { fuseAnimations } from '@fuse/animations';
import { FuseConfigService } from '@fuse/services/config.service';
import { AbstractControl, FormBuilder, FormGroup, ValidationErrors, ValidatorFn, Validators } from '@angular/forms';
import Swal from 'sweetalert2';
import { AuthService } from '../../../core/authentication/auth.service';
import { TokenService } from '../../../core/authentication/token.service';
import { MatSnackBar } from '@angular/material/snack-bar';




@Component({
  selector: 'app-recuperar',
  templateUrl: './recuperar.component.html',
  styleUrls: ['./recuperar.component.scss'],
  encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})
export class RecuperarComponent implements OnInit {
  recuperarForm: FormGroup;
  cargando: boolean = true;
  hover1:boolean = false;
  errors = null;
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
    private _snackBar: MatSnackBar



   
  ) { 
    this.recuperarForm = this._formBuilder.group({
      email: ['', [Validators.required, Validators.email]]
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

  recuperar() {
    const user = this.recuperarForm.value;
    console.log(user);

   
    this.authService.sendResetPasswordLink(user.email).subscribe(
      (result) => {

        this.successMsg = result;
        Swal.fire({
          title: '¡Éxito!',
          text: 'Ingresa a tu bandeja de entrada para más información',
          icon: 'success',
          confirmButtonText: 'Ok'
      });
      this.recuperarForm.reset();

      },(error) => {
        this.errors = error.error.message;
        this._snackBar.open('Correo electrónico no encontrado', 'Cerrar', { duration: 5000, horizontalPosition: 'center' });

      })

   
}

}

