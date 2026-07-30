import { Component, OnInit, ViewChild, ViewEncapsulation } from '@angular/core';

import { MtxGridColumn } from '@ng-matero/extensions';
import { PageEvent } from '@angular/material/paginator';
import { UseradminService } from '../../../services/administrador/usuarios/useradmin.service';
import { MatDialog } from '@angular/material/dialog';
import { RequisitosDialogComponent } from './requisitos-dialog/requisitos-dialog.component';
import { FormGroup } from '@angular/forms';
import { fuseAnimations } from '@fuse/animations';
import { RequisitosListComponent } from './requisitos-list/requisitos-list.component';
import  {NgxCopilotService}  from  'ngx-copilot';
@Component({
  templateUrl: './requisitos.component.html',
  styleUrls: ['./requisitos.component.scss'],
  encapsulation: ViewEncapsulation.None,
  animations   : fuseAnimations
})
export class RequisitosComponent implements OnInit {
  @ViewChild(RequisitosListComponent) list: RequisitosListComponent;
 
  constructor(
    private _matDialog: MatDialog,
    private  copilot:  NgxCopilotService
    ) { }
    dialogRef;
  ngOnInit(): void {
    this.copilot.checkInit();
  }

  newRequisito(): void
  {
 
    /*this.giroService.apagarGiro(id_giro,id_municipio).subscribe((res) => {
    });  */
      this.dialogRef = this._matDialog.open(RequisitosDialogComponent, {
       // width:'30%',
          panelClass: 'requisito-form-dialog',
          disableClose:true,
          data      : {
            action: 'new'
          },
      });
      this.dialogRef.afterClosed()
          .subscribe((response: FormGroup) => {
            this.list.getData();
          });
  }
}
