import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { FormGroup, Validators, FormBuilder, FormControl } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { environment } from '@env/environment';
import { fuseAnimations } from '@fuse/animations';
import { MapaService } from 'app/services/mapa/mapa.service';
import { Observable } from 'rxjs';
import { map, startWith } from 'rxjs/operators';
import * as ol from "openlayers";
import { DatosPredio } from '../../../../models/mapa/DatosPredio';
import { DialogSuccessComponent } from '../../dialog-success/dialog-success.component';
import * as turf from '@turf/turf';
import { Router } from '@angular/router';
import proj4 from 'proj4';
import { M } from '@angular/cdk/keycodes';
import { FuseSplashScreenService } from '@fuse/services/splash-screen.service';
import { ConsultaRequisitos } from 'app/models/administrador/consulta_requisito.models';
import { FormFichasComponent } from '../form-fichas/form-fichas.component';

@Component({
    selector: 'app-map-info',
    templateUrl: './info.component.html',
    styleUrls: ['./info.component.scss'],
    animations: fuseAnimations
})
export class InfoComponent implements OnInit {
    @Input('activeMedia') activeMedia: any;
    @Input('barraIOpen') barraIOpen: any;
    @Input('municipio') municipio: any;
    @Input('DatosPredio') datosPredio: DatosPredio;
    @Input('predioCoords') predioCoords: any;
    @Input('datosDinamicosActual') datosDinamicosActual: any = {};
    @Input('pasoT') pasoT: number = 1;
    @Input('camposDiamicosMunicipio') camposDiamicosMunicipio: any;
    @Input('actividadesEconomicas') actividadesEconomicas: any;
    @Output() barra = new EventEmitter<boolean>();
    @Output() nuevoPoligonoID = new EventEmitter<number>();

    //

    cargandoMunicipio = false;
    actividaddSeleccionada: any;
    filteredActividades: Observable<any[]>;

    actividadSelected;
    controlActividades = new FormControl();


    infoTramite = 'https://api-visorurbano.jalisco.gob.mx/geoserver/ows?service=WMS&version=1.3.0&request=GetMap&FORMAT=image/png8&layers=VUJ:mapa_ficha_informativa&exceptions=application/vnd.ogc.se_inimage&CRS=EPSG:4326&width=600&height=600&BBOX=20.77867088903078,-102.90900628593921,20.779570209394507,-102.9080443997053';
    errorFormulario = false;
    errorFormularioMessage = [];
    errorActividadSelected = false;

    //datosDinamicosActual = {};
    //camposDiamicosMunicipio: any;
    dataEnvioPredio: any = {};


    // Constructor
    constructor(private apivujService: MapaService,
        private _formBuilder: FormBuilder,
        private dialog: MatDialog,
        private route: Router,
        private _splash: FuseSplashScreenService,) {
    }

    ngOnInit(): void {
     
        this.pasoT = 1;
        this.filteredActividades = this.controlActividades.valueChanges
            .pipe(startWith(''), map(value => this._filterActividades(value)));

        var wms_layers = [];
        var wmsSource = new ol.source.TileWMS({
            url: this.datosPredio.url_minimapa,
            params: { 'LAYERS': '', 'TILED': true },
            serverType: 'geoserver',
            crossOrigin: 'anonymous'
        });

        var wmsLayer = new ol.layer.Tile({
            source: wmsSource
            
        });
        console.log();


    }

    cancelarTramite() {
        this.barraIOpen = false;
        //this.tramiteList.nativeElement.style.cssText = 'display:none';
        this.pasoT = 1;
    }


    predioForm: FormGroup;
    predioForm2: FormGroup;
    new_pol;

    iniciarTramite(): void {
        this.pasoT = 2;
        this.guardarPoligono().subscribe(response => {
            console.log("guardarPoligono response", response)
            console.log("guardarPoligono response id", response.id)
            let id = btoa(response.id)
            this.new_pol = response.id;
            console.log("id", id)
            this.nuevoPoligonoID.emit(response.id)
        })
        this.createFromPredio();

    }

    openSuccessDialog(folio, emite = 0, consulta = ''): void {
        let dialogSuccess = this.dialog.open(DialogSuccessComponent, {
            width: '505px',
            height: '479px',
            id: 'successDialog',
            disableClose: true,
            panelClass: 'myDialogStyle',
            data: { emite, consulta },
        });
        dialogSuccess.afterClosed().subscribe(r => {
            if (r == 1) {
                this.route.navigate(['tramite', 'iniciar-tramite', btoa(folio)])
            }
            // this.route.navigate(['tramite', 'iniciar-tramite', btoa(folio)])
        })
    }

    actividadSeleccionada(v) {
        this.actividadSelected = v.codigo;
        this.actividaddSeleccionada = v.SCIAN;
        this.predioForm.get('nombre').errors
    }

    guardarPoligono(folio = '', giro = null, id = null): Observable<any> {
        const data = {
            id,
            giro,
            folio: folio,
            geom: { "type": "Polygon", "coordinates": [this.predioCoords] },
            area: turf.area(turf.polygon([this.predioCoords])),
            municipio: this.municipio.id
        }
        // area: turf.area(turf.polygon([this.predioCoords]))
        return this.apivujService.guardarRegistroTramite(data)
    }
    

    createFromPredio() {
        this.predioForm = this._formBuilder.group({
            nombre: ['', Validators.required],
            calle: [this.datosPredio.calle, Validators.required],
            colonia: [this.datosPredio.colonia.nombre || '', Validators.required],
            localidad: [this.datosPredio.localidad.nombre || '', Validators.required],
            actividad: ['', Validators.required],
            superficie: ['', [Validators.required, Validators.min(1)]],
            alcohol: ['0', [Validators.required]],
        });
        //console.log(this.predioForm);
    }
    campoDinamicoFunction(e) {
        if (e.campo_afectado != '' && e.campo_afectado != null) {
            if (e.campo_afectado.includes(',')) {
                let arrayAfectado = e.campo_afectado.split(',');
                for (let index = 0; index < arrayAfectado.length; index++) {
                    const element = arrayAfectado[index];
                    if (e.value == this.datosDinamicosActual[element].condicion_visible) {
                        this.datosDinamicosActual[element].visible = true;
                    } else {
                        this.datosDinamicosActual[element].visible = false;
                        this.datosDinamicosActual[element].value = null;
                    }
                }
            } else {
                if (e.value == this.datosDinamicosActual[e.campo_afectado].condicion_visible) {

                    this.datosDinamicosActual[e.campo_afectado].visible = true;
                } else {
                    this.datosDinamicosActual[e.campo_afectado].visible = false;
                    this.datosDinamicosActual[e.campo_afectado].value = null;

                }
            }

        }
    }

    downloadShapes(){
       console.log(this.datosPredio.url_zip)
        window.open(this.datosPredio.url_zip, '_blank')

    }
    
    openDownloadForm(): void {
        const dialogRef = this.dialog.open(FormFichasComponent, {
            width: '800px'
        });
        dialogRef.afterClosed().subscribe(result => {
            if (result) {
                this.guardarFichaPoligono(result);
            } else {
              console.log('Download failed!');
            }
          });
    }
    guardarFichaPoligono(result){
       
        this.guardarPoligono().subscribe(response => {
            console.log("guardarPoligono response", response)
            console.log("guardarPoligono response id", response.id)
            let id = btoa(response.id)
            console.log("id", id)
            this.nuevoPoligonoID.emit(response.id)
            this.datosPredio.vectorial = response;
            this.datosPredio.url_uso = this.datosPredio.url_uso.replace('DIBUJOID', response.id)
            let c = [];
            proj4.defs('EPSG:32614', '+proj=utm +zone=13 +ellps=WGS84 +datum=WGS84 +units=m +no_defs');
            proj4('EPSG:32614');
            let co = turf.simplify(this.datosPredio.vectorial, { tolerance: 2, highQuality: false });
            co.geometry.coordinates[0].forEach(val => {
                c.push(proj4('EPSG:4326', 'EPSG:32614', val));
            });
            let domicilio = this.datosPredio.domicilio.formatted_address || ''
            let metros = (JSON.stringify({ area: this.datosPredio.area_predio, construccion: this.datosPredio.area_construccion }));
            let img = (this.datosPredio.url_uso);
            let coords = btoa(JSON.stringify(c))
            let data = {
                direccion: domicilio,
                metros,
                img,
                coordenadas: coords,
                id_ficha_tecnica_descarga:null,
                municipio_id: this.municipio.id
            }
            this._splash.show();
            this.apivujService.registrarFicha(data).subscribe((r: any) => {
                this._splash.hide();
                if (r.data.uuid) {
                    window.open(`${environment.SERVER_ORIGIN}ficha_tecnica/${r.data.uuid}`, '_blank')
                }
            }, err => {
                this._splash.hide();
            })
            // 
        })
    }
    ficha_tecnica() {
        this.guardarFichaPoligono(0);
        this.openDownloadForm();
    }
    consultarRequisitos() {
        if (this.predioForm.valid) {
            this.errorFormulario = false;
            this.dataEnvioPredio = this.predioForm.value;
        } else {
            this.errorFormulario = true;
        }
        if (typeof this.actividadSelected == 'undefined') {

            this.errorFormulario = true;
            this.errorActividadSelected = true;
            //  return;
        } else {
            //  this.errorFormulario = false;
            this.errorActividadSelected = false;
        }
        this.errorFormularioMessage = [];

        let camposDinamicos = {};
        this.dataEnvioPredio['id_municipio'] = this.municipio.id;
        this.dataEnvioPredio['municipio'] = this.municipio.nombre;
        this.dataEnvioPredio['codigo_scian'] = this.actividadSelected;
        this.dataEnvioPredio['nombre_scian'] = this.actividaddSeleccionada;
        this.dataEnvioPredio['superficie_propiedad'] = this.datosPredio.area_predio;
        this.dataEnvioPredio['url_minimapa'] = this.datosPredio.url_minimapa;
        this.dataEnvioPredio['url_minimapa2'] = this.datosPredio.url_minimapa2;
        this.dataEnvioPredio['restricciones'] = new Object();
        switch (this.datosDinamicosActual['quien_tramita'].value) {
            case 'carta_poder':
                this.dataEnvioPredio['caracter'] = 'Carta poder';
                if (this.datosDinamicosActual['carta_poder_rad'].value == 'persona_fisica') {
                    this.dataEnvioPredio['tipo_persona'] = 'Física';
                } else {
                    this.dataEnvioPredio['tipo_persona'] = 'Moral';
                }
                break;
            case 'propietario':
                this.dataEnvioPredio['caracter'] = 'Propietario';
                if (this.datosDinamicosActual['propietario_rad'].value == 'propietario_i') {
                    this.dataEnvioPredio['tipo_persona'] = 'Física';
                } else {
                    this.dataEnvioPredio['tipo_persona'] = 'Moral';
                }
                break;
            case 'arrendatario':
                this.dataEnvioPredio['caracter'] = 'Arrendatario';
                if (this.datosDinamicosActual['arrendatario_rad'].value == 'arrendatario_i') {
                    this.dataEnvioPredio['tipo_persona'] = 'Física';
                } else {
                    this.dataEnvioPredio['tipo_persona'] = 'Moral';
                }
                break;
        }
        if (this.datosPredio.area_predio < this.dataEnvioPredio.superficie) {
            if ((this.datosPredio.area_predio * .7) > this.datosPredio.area_construccion) {
                //  console.log('Error es mayor la superficie y menor del 70 %');
                this.dataEnvioPredio['restricciones']['bloque_construccion_actividad'] = true;

            } else {
                this.dataEnvioPredio['restricciones']['bloque_construccion_actividad'] = false;
            }
        } else {
            this.dataEnvioPredio['restricciones']['bloque_construccion_actividad'] = false;
        }
        this.dataEnvioPredio['restricciones']['escuelas'] = Object.entries(this.datosPredio.escuelas).length > 0 ? true : false;
        this.dataEnvioPredio['restricciones']['edificios_gobierno'] = Object.entries(this.datosPredio.edificios_gobierno).length > 0 ? true : false;
        this.dataEnvioPredio['restricciones']['centros_salud'] = Object.entries(this.datosPredio.centros_salud).length > 0 ? true : false;
        this.dataEnvioPredio['restricciones']['cuerpos_agua'] = Object.entries(this.datosPredio.cuerpos_agua).length > 0 ? true : false;
        //console.log(this.datosPredio.cuerpos_agua);
        this.camposDiamicosMunicipio.forEach(element => {
            if (element.requerido == 1) {
                if (element.value) {
                    camposDinamicos[element.name] = element.value;
                } else {
                    this.errorFormularioMessage.push(element.description);
                    this.errorFormulario = true;
                }
            }
            if (element.requerido == 3 && this.datosDinamicosActual[element.name].visible) {
                if (element.value) {
                    camposDinamicos[element.name] = element.value;
                } else {
                    this.errorFormularioMessage.push(element.description);
                    this.errorFormulario = true;
                }
            }

        });
        // console.log(this.errorFormularioMessage);
        if (this.errorFormulario == true) {
            return;
        }
        this.dataEnvioPredio['camposDinamicos'] = camposDinamicos;
        //console.log(this.dataEnvioPredio);
        this.apivujService.consultaRequisitos(this.dataEnvioPredio).subscribe((x: any) => {
            console.log(x);
            this.cancelarTramite();
            this.openSuccessDialog(x.data.folio, x.data.emitir_licencia, x.data.url);
            window.open(`${environment.SERVER_ORIGIN}${x.data.url}`, "_blank");
            this.guardarPoligono(x.data.folio, this.actividaddSeleccionada, this.new_pol).subscribe(r => {
                console.log(r)
            });


        });
        //  console.log(JSON.stringify(this.dataEnvioPredio));

    }

    private _filterActividades(value) {
        const filterValue = this.clearF(value);
        return this.actividadesEconomicas.filter(option => (this.clearF(option.SCIAN).includes(filterValue) || this.clearF(option.palabras_relacion).includes(filterValue)));

    }
    clearF(cadena) {
        var specialChars = "!@#$^&%*()+=-[]\/{}|:<>¿?,.´";
        for (var i = 0; i < specialChars.length; i++) {
            cadena = cadena.replace(new RegExp("\\" + specialChars[i], 'gi'), '');
        }
        cadena = cadena.toLowerCase();
        cadena = cadena.replace(/á/gi, "a");
        cadena = cadena.replace(/é/gi, "e");
        cadena = cadena.replace(/í/gi, "i");
        cadena = cadena.replace(/ó/gi, "o");
        cadena = cadena.replace(/ú/gi, "u");
        cadena = cadena.replace(/ñ/gi, "n");
        cadena = cadena.replace(/ /gi, "");
        return cadena;
    }


}
