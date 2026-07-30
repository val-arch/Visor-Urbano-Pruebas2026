import { Component, OnInit,Inject} from '@angular/core';
import { MatDialog,MatDialogRef,MAT_DIALOG_DATA } from '@angular/material/dialog';
import Swal from 'sweetalert2';
import { Router } from '@angular/router';
import { MatSnackBar } from '@angular/material/snack-bar';
import { NotificacionesService} from "../notificaciones.service";

@Component({
  selector: 'app-notificaciones-dialog',
  templateUrl: './notificaciones-dialog.component.html',
  styleUrls: ['./notificaciones-dialog.component.scss']
})
export class NotificacionesDialogComponent implements OnInit {
  btnCss1 = {
    'background-color': '#70CE68',
     'color': 'white',
    
  };
  btnCss2 = {
    'background-color': '#D5D5D5',
     'color': '#878E90',
     'margin-right': '5px'
  };
  errors = null;
  action:string;
  local_data:any;
  public folio: string;
  public municipio: string;
  public id: number;
  public type;

 

  constructor(public dialogRef: MatDialogRef<any>,
    public notificacion: NotificacionesService,
    private router: Router,
    public matDialogRef: MatDialogRef<NotificacionesDialogComponent>,
    @Inject(MAT_DIALOG_DATA) private _data: any) {
     
   }

  ngOnInit(): void {
   

  }

  updateNotificacion(){
    console.log(this.id);
    var n = this.id.toString();
    this.notificacion.updateNotificacion(this.id).subscribe(
      result => {
        console.log(result)
        // this.router.navigate([`notificaciones/detalle/${btoa(this.folio)}/${btoa(n)}`]);
        this.router.navigate([`notificaciones/detalle/${btoa(this.folio)}/${btoa(this.type)}/${btoa(n)}`]);
        this.dialogRef.close();
      },
      error => {
       this.errors = error.error.message;
      }
    );
  }


}
