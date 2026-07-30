import { Component, OnInit, Inject } from '@angular/core';
import {MAT_DIALOG_DATA} from '@angular/material/dialog';
@Component({
  templateUrl: './dialog-success.component.html',
  styleUrls: ['./dialog-success.component.scss']
})
export class DialogSuccessComponent implements OnInit {

  constructor(@Inject(MAT_DIALOG_DATA) public data: {emite,consulta, role, giro}) { }

  ngOnInit(): void {
  }

}
