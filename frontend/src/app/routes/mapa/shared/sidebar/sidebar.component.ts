import {Component, OnInit, Input, Output, EventEmitter} from '@angular/core';
import {fuseAnimations} from '@fuse/animations';
import {Observable} from 'rxjs';

@Component({
    selector: 'app-map-sidebar',
    templateUrl: './sidebar.component.html',
    styleUrls: ['./sidebar.component.scss'],
    animations: fuseAnimations
})
export class SidebarComponent implements OnInit {
    @Input('activeMedia') activeMedia: any;
    @Input('barraIOpen') barraIOpen: any;
    @Input('barraInfoOpen') barraInfoOpen: any = false;
    //@Input() panelHerramientasIsOpen: boolean;
    @Input() mapZoom: number;
    @Input() municipioId: number = 0;
    @Input() herramientaActiva: string = 'capas';

    @Output() barra = new EventEmitter<boolean>();
    @Output() newTramite = new EventEmitter<boolean>();
    @Output() panelHerramientasStatus = new EventEmitter<boolean>();
    @Output() herramientaActivaSeleccionada = new EventEmitter<string>();
    @Output() mapaInteraccionSeleccionada: EventEmitter<string> = new EventEmitter<string>()
    barraI = false;

    constructor() {
    }

    ngOnInit(): void {
        //  this.activeMedia.pipe(r=> r).subscribe(respuesta => console.log(respuesta));
        if (this.activeMedia == 'xs') {
            // this.removeActive();
        } else {
            //   this.addActive();
            this.barraI = true;
        }
    }

    removeActive() {
        this.barraI = false;
        this.barra.emit(false);
    }

    iniciarTramiteButton(): void {
        this.newTramite.emit(true);
    }

    panelHerramientasButton(value): void {
        this.panelHerramientasStatus.emit(true);
        this.herramientaActivaSeleccionada.emit(value);
    }

    panelHerramientasButtonSubirArchivo(): void {
        this.panelHerramientasStatus.emit(true);
        this.herramientaActivaSeleccionada.emit('herramientas-seleccion')
        this.mapaInteraccionSeleccionada.emit('subir_archivo')
    }

    panelHerramientasButtonIdentifica(): void {
        this.panelHerramientasStatus.emit(true);
        this.herramientaActivaSeleccionada.emit('herramientas-seleccion')
        this.mapaInteraccionSeleccionada.emit('obtener_info')
    }
    panelHerramientasButtonIdentifica2(): void {
        //   this.panelHerramientasStatus.emit(true);
        //this.herramientaActivaSeleccionada.emit('herramientas-seleccion')
           this.mapaInteraccionSeleccionada.emit('obtener_info2')
    }
   

}
