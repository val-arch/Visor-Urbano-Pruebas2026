import {Component, Input, Output, EventEmitter, OnChanges, SimpleChanges} from '@angular/core';

@Component({
    selector: 'app-capas',
    templateUrl: './capas.component.html',
    styleUrls: ['./capas.component.scss']
})
export class CapasComponent implements OnChanges {
    @Input() capasMunicipio: any;
    @Input() herramientaActiva: string;
    @Input() mapaInteraccion: string;
    @Input() predioCoords: any;
    @Input() sourceMediciones: any;
    @Input() sourcePredio: any;
    @Input() sourceDibujoPredio: any;
    @Input() WMSInfo: any;
    @Output() capasMunicipioChange = new EventEmitter();
    //@Output() limpiarMediciones = new EventEmitter();
    @Output() dibujoAnalisisComercial: EventEmitter<string> = new EventEmitter<string>()
    @Output() mapaInteraccionSeleccionada: EventEmitter<string> = new EventEmitter<string>()
    @Output() limpiarMapa: EventEmitter<string> = new EventEmitter<string>()
    @Output() semadetSeleccionada: EventEmitter<void> = new EventEmitter<void>();

    capaOpacidad: number = undefined;

    constructor() {
    }

    capasMunicipioWMS(): any {
        return this.capasMunicipio ? this.capasMunicipio.filter(val => val.type === 'wms') : []
    }

    updateCapasMunicipio(): void {
        if (this.capasMunicipio) {
            this.capasMunicipioChange.emit(this.capasMunicipio)
        }
    }

    updateMapaInteraccion(value): void {
        console.log("update")
        this.mapaInteraccionSeleccionada.emit(value)
    }

    setLimpiarMapa() {
        console.log("WMSInfo",  this.WMSInfo)
        console.log("sourcePredio",  this.sourcePredio)
        this.mapaInteraccionSeleccionada.emit('limpiarMapa')
        this.limpiarMapa.emit(this.mapaInteraccion)
    }

    dibujarAnalisis(value) {
        this.dibujoAnalisisComercial.emit(value);
    }

    ngOnChanges(changes: SimpleChanges) {
        this.updateCapasMunicipio();
    }
}
