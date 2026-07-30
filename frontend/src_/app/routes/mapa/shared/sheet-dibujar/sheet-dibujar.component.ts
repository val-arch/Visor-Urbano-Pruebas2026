import { Component, OnInit } from '@angular/core';
import {MatBottomSheet, MatBottomSheetRef} from '@angular/material/bottom-sheet';
@Component({
    templateUrl: './sheet-dibujar.component.html',
    styleUrls: ['./sheet-dibujar.component.scss']
})
export class SheetDibujarComponent implements OnInit {

    constructor(private _bottomSheetRef: MatBottomSheetRef<SheetDibujarComponent>) {}

    cancelar(event: Event): void {
        this._bottomSheetRef.dismiss('seleccionar_predio');
        event.preventDefault();
    }

    dibujar(event: Event): void {
        this._bottomSheetRef.dismiss('dibujar_predio');
        event.preventDefault();
    }

    ngOnInit(): void {
    }

}
