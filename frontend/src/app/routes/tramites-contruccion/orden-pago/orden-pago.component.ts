import { Component, Inject, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import Swal from 'sweetalert2';
import { OrdenPagoService } from './orden-pago.service';

@Component({
  selector: 'app-orden-pago',
  templateUrl: './orden-pago.component.html',
  styleUrls: ['./orden-pago.component.scss']
})
export class OrdenPagoComponent implements OnInit {

  public emitirForm: FormGroup;
  public action: string;
  public row;
  public orden;
  orden_pago='';
  public dialogTitle: string;
  error;
  btnCss = {
    'background-color': '#003E76',
    'color': 'white'
  };
  btnCssCancel = {
    'background-color': 'red',
    'color': 'white'
  };


  constructor(public matDialogRef: MatDialogRef<OrdenPagoComponent>,
    @Inject(MAT_DIALOG_DATA) private _data: any,
    private fb: FormBuilder,
    private _orden: OrdenPagoService,
  ) {
    this.action = _data.action;
    this.row = _data.data;
    console.log(this.row);
  }

  ngOnInit(): void {
    console.log(this.row);
    this.dialogTitle = this._data.dialogTitle || '';
    if (this.row.orden_pago) {
      this.orden_pago = this.row.orden_pago;
    }
    if (this._data.action == 'new') {
    }
  }
  subirOrden(event) {
    console.log(event);
    this.orden = event.target.files[0];
    console.log(this.orden);
  }

  subirOrdenPost() {
    if (this.orden == '') {
      this.error = 'Adjuntar licencia';
    }
    const formData = new FormData();
    formData.append("orden", this.orden);
    formData.append("id", this.row.id);
    Swal.showLoading();
    this._orden.subirRecibo2(this.row.id, formData).subscribe((r: any) => {
      this.error = '';

      Swal.close();
      Swal.fire({
        title: '¡Éxito!',
        text: 'Guardado con exito',
        icon: 'success',
        confirmButtonText: 'Ok'
      });
      console.log(r);
      this.matDialogRef.close({ status: true });

      console.log(r);
    }, e => {
      Swal.close();
      Swal.fire({
        title: 'Algo a ocurrido',
        text: 'Intentar más tarde',
        icon: 'error',
        confirmButtonText: 'Ok'
      });
      // this.error = e.error.error;
      // console.error(e.error.error);
    });

  }
}
