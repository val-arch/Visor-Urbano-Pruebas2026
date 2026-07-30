import { Component, OnInit, Inject } from '@angular/core';
import { MAT_DIALOG_DATA } from '@angular/material/dialog';
export interface DialogData {
  animal: 'panda' | 'unicorn' | 'lion';
}

@Component({
  selector: 'app-dialog-historial-construccion',
  templateUrl: './dialog-historial-construccion.component.html',
  styleUrls: ['./dialog-historial-construccion.component.scss']
})
export class DialogHistorialConstruccionComponent implements OnInit {
  detalle:string = '';
  btnCssCancel = {
        "background-color": "red",
        color: "white",
    };
  constructor(@Inject(MAT_DIALOG_DATA)public data: DialogData)  {
    console.log(this.detalle=data['detalle']);
  }

  ngOnInit(): void {
  }

}
