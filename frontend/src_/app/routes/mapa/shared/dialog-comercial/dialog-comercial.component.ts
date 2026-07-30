import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA, MatDialog } from '@angular/material/dialog';

@Component({
  templateUrl: './dialog-comercial.component.html',
  styleUrls: ['./dialog-comercial.component.scss']
})
export class DialogComercialComponent implements OnInit {

  constructor(public matDialogRef: MatDialogRef<DialogComercialComponent>,
    @Inject(MAT_DIALOG_DATA) private _data: any,
    private fb               : FormBuilder,
    private _matDialog:MatDialog,) { }

  ngOnInit(): void {
  }

}
