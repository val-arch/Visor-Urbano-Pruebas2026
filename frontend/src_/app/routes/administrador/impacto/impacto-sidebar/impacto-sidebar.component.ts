import {Component, OnInit, Input, Output, EventEmitter} from '@angular/core';
import {fuseAnimations} from '@fuse/animations';
import {Observable} from 'rxjs';

@Component({
    selector: 'app-impacto-sidebar',
    templateUrl: './impacto-sidebar.component.html',
    styleUrls: ['./impacto-sidebar.component.scss'],
    animations: fuseAnimations
})
export class ImpactoSidebarComponent implements OnInit {
    @Input('barraIOpen') barraIOpen: any;
    @Input() herramientaActiva: string = 'capas';

    @Output() panelHerramientasStatus = new EventEmitter<boolean>();
    @Output() herramientaActivaSeleccionada = new EventEmitter<string>();
    @Output() interaction = new EventEmitter<string>();
    barInteraction = ''
    barraI = false;

    constructor() {
    }

    ngOnInit(): void {
        //  this.activeMedia.pipe(r=> r).subscribe(respuesta => console.log(respuesta));

    }

    removeActive() {
        this.barraI = false;
    }

    iniciarTramiteButton(): void {
        // this.newTramite.emit(true);
    }

    setInteraction(val): void {
        this.barInteraction = val
        this.interaction.emit(val)
    }

    refreshMap(): void {

    }

    panelHerramientasButton(value): void {
        console.log(value)
        this.panelHerramientasStatus.emit(true);
        this.herramientaActivaSeleccionada.emit(value);
    }
}
