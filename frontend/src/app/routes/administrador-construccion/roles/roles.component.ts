import { Component, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { RolesDialogComponent } from './roles-dialog/roles-dialog.component';
import { fuseAnimations } from '@fuse/animations';
import { RolesListComponent } from './roles-list/roles-list.component';

@Component({
  templateUrl: './roles.component.html',
  styleUrls: ['./roles.component.scss'],
  animations:fuseAnimations
})
export class RolesComponent implements OnInit {
  @ViewChild(RolesListComponent) list: RolesListComponent;

  constructor(private _matDialog: MatDialog) { }
  dialogRef;
  ngOnInit(): void {
  }

  newRole(): void
  {
 
    /*
    this.giroService.apagarGiro(id_giro,id_municipio).subscribe((res) => {
    });
     */

      this.dialogRef = this._matDialog.open(RolesDialogComponent, {
          panelClass: 'role-form-dialog',
          data      : {
            action: 'new'
          }
      });
      this.dialogRef.afterClosed()
          .subscribe((response: FormGroup) => {
            this.list.getRole();
          });
  }


}
