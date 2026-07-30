import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { DialogAvisosPrivacidadComponent } from 'app/pages/dialogs/dialog-avisos-privacidad/dialog-avisos-privacidad.component';
import { IniciarTramiteService } from '../../../../services/tramite/iniciar-tramite/iniciar-tramite.service';
import Swal from 'sweetalert2';
import { ActivatedRoute, Router } from '@angular/router';
import { FuseSplashScreenService } from '@fuse/services/splash-screen.service';
import { fuseAnimations } from '@fuse/animations/index';
@Component({
  selector: 'app-iniciar-tramite',
  templateUrl: './iniciar-tramite.component.html',
  styleUrls: ['./iniciar-tramite.component.scss'],
  animations:fuseAnimations
})
export class IniciarTramiteComponent implements OnInit {

  constructor( public dialog: MatDialog,private tramiteService: IniciarTramiteService,
     private route2: Router, private route:ActivatedRoute,private splash: FuseSplashScreenService) {
      
      let folio2 = this.route.snapshot.params.folio;
      
      if(folio2){
       this.folio = atob(folio2);
      }else{
        this.folio='';
      }
      }

  folio:string='';
  id = null;
  existeFolio = false;
  disabled = false;
  ngOnInit(): void {
   // this.validarFolio(); 
   
  }

  openDialogAvisos(){
    this.dialog.open(DialogAvisosPrivacidadComponent);
  }
  validar():boolean{
    if(this.folio !='' && this.disabled == true){
      return false;
    }else{
      return true
    }
  }
  

  folioValidado(id){
    this.tramiteService.actualizarUsuario(id)
    .subscribe(resp=>{   
      this.route2.navigate([`tramite/nuevo-tramite/${btoa(this.folio)}`]).then(r=>{
        this.splash.hide();
      })
    
    },e=>{
      console.log(e);
      Swal.fire({
        title: 'No Existe!',
        text: 'No Existe el Folio',
        icon: 'error',
        confirmButtonText: 'Ok'
    });
      this.splash.hide();
    })
  }
  validarFolio(){

  
    let request = this.tramiteService.existeFolio(this.folio);
    this.splash.show();
    request.subscribe((res: any) => {

     
      
      if (res.data.length>0) {
          // Swal.fire({
          //     title: '¡Éxito!',
          //     text: 'Continuar con el tramite!',
          //     icon: 'success',
          //     confirmButtonText: 'Ok'
          //   }).then(e=>{
          //     console.log(this.folio);
          //     console.log(btoa(this.folio));
          //   });
          this.id = res.data[0].id;
          this.folioValidado(this.id);

          
          
        console.log(res.data[0].id);
        }else{
         
          Swal.fire({
            title: 'No Existe!',
            text: 'No Existe el Folio',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
        }
    },
    error => {
      console.log();
      Swal.fire({
        title: 'Upps!',
        text: error.error.error,
        icon: 'error',
        confirmButtonText: 'Ok'
    });
      this.splash.hide();
  });
  }
}
