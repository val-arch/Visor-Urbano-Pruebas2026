import { Component, Inject, OnInit } from '@angular/core';
import { MAT_DIALOG_DATA } from '@angular/material/dialog';
export interface DialogData {
  animal: 'panda' | 'unicorn' | 'lion';
}
@Component({
  selector: 'app-dialog-historial',
  templateUrl: './dialog-historial.component.html',
  styleUrls: ['./dialog-historial.component.scss']
})
export class DialogHistorialComponent implements OnInit {
  detalle:string = ''
  constructor(@Inject(MAT_DIALOG_DATA) public data: DialogData) {
    console.log(this.detalle=data['detalle']);
  }

  ngOnInit(): void {
  }

}
