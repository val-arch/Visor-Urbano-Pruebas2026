import {Component, OnInit, ViewChild} from '@angular/core';
import {MatDialog} from "@angular/material/dialog";
import {FormGroup} from "@angular/forms";
import {CapasMunicipioDialogComponent} from "./capas-municipio-dialog/capas-municipio-dialog.component";
import {CapasMunicipioListComponent} from "./capas-municipio-list/capas-municipio-list.component";

@Component({
    selector: 'app-capas-municipio',
    templateUrl: './capas-municipio.component.html',
    styleUrls: ['./capas-municipio.component.scss']
})
export class CapasMunicipioComponent implements OnInit {
    @ViewChild(CapasMunicipioListComponent) list: CapasMunicipioListComponent;
    dialogRef;

    constructor(
        private _matDialog: MatDialog,
    ) {
    }

    ngOnInit(): void {
    }

    agregarCapa() {
        this.dialogRef = this._matDialog.open(CapasMunicipioDialogComponent, {
            panelClass: 'requisito-form-dialog',
            data: {
                action: 'new'
            },
        });
        this.dialogRef.afterClosed()
            .subscribe((response: FormGroup) => {
                this.list.getData();
            });
    }

}
