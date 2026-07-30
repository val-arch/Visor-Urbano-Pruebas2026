import { Component, OnInit, ViewChild, ViewEncapsulation } from '@angular/core';


import { MtxGridColumn } from '@ng-matero/extensions';
import { PageEvent } from '@angular/material/paginator';
import { UseradminService } from '../../../services/administrador/usuarios/useradmin.service';
import { MatDialog } from '@angular/material/dialog';
import { DialogUserComponent } from './dialog-user/dialog-user.component';
import { FormGroup } from '@angular/forms';
import { fuseAnimations } from '@fuse/animations';
import { UsuariosListComponent } from './usuarios-list/usuarios-list.component';

@Component({
  templateUrl: './usuarios.component.html',
  styleUrls: ['./usuarios.component.scss'],
  encapsulation: ViewEncapsulation.None,
  animations: fuseAnimations
})
export class UsuariosComponent implements OnInit {

  constructor(
    private _user: UseradminService,
    private _matDialog: MatDialog
  ) { }
  dialogRef;
  @ViewChild(UsuariosListComponent) list: UsuariosListComponent;
  ngOnInit(): void {
  }
  newContact(): void {
    this.dialogRef = this._matDialog.open(DialogUserComponent, {
      panelClass: 'contact-form-dialog',
      data: {
        action: 'new'
      }
    });
    this.dialogRef.afterClosed()
      .subscribe((response: FormGroup) => {
        this.list.getData();
      });
  }
  setPage(pageInfo) {
    console.log(pageInfo);
  }
}
