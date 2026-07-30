import {Component, Input, Output, EventEmitter, OnChanges, SimpleChanges, SimpleChange} from '@angular/core';

@Component({
    selector: 'app-impacto-herramientas',
    templateUrl: './impacto-herramientas.component.html',
    styleUrls: ['./impacto-herramientas.component.scss']
})
export class ImpactoHerramientasComponent implements OnChanges {
    @Input() capasMunicipio: any;
    @Input() herramientaActiva: string;
    @Input() sourceMediciones: any;
    @Output() capasMunicipioChange = new EventEmitter();
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



    ngOnChanges(changes: SimpleChanges) {
        this.updateCapasMunicipio();
    }

}
