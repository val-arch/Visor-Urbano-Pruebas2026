import {Component,EventEmitter, ElementRef, Input, OnInit, ViewChild, ViewChildren, QueryList, Output} from '@angular/core';
import {FormControl} from '@angular/forms';
import {MapaService} from '../../../services/mapa/mapa.service';
import {Municipio} from '../../../models/mapa/Muinicipio';
import {MapaBase} from '../../../models/mapa/MapaBase';
import {CapaMapa} from '../../../models/mapa/CapaMapa';
import {Observable, Subscription} from 'rxjs';
// @ts-ignore
import proj4 from 'proj4';
import {map, startWith} from 'rxjs/operators';
import {Router} from '@angular/router';
import {FuseUtils} from '@fuse/utils/index';

import {MediaObserver, MediaChange} from '@angular/flex-layout';
import {fuseAnimations} from '@fuse/animations';
import * as turf from '@turf/turf';

import {environment} from '@env/environment';
import {InfoComponent} from '../shared/info/info.component';
import {MatBottomSheet} from '@angular/material/bottom-sheet';
import * as ol from "openlayers";
import {SheetDibujarComponent} from '../shared/sheet-dibujar/sheet-dibujar.component';
import {MatDialog} from '@angular/material/dialog';
import {COMMA, ENTER} from "@angular/cdk/keycodes";
import {MatChipInputEvent} from "@angular/material/chips";
import {MatAutocompleteSelectedEvent} from "@angular/material/autocomplete";
import {RequisitosService} from "../../../services/administrador/requisitos/requisitos.service";
import {values} from "lodash";
import Swal from "sweetalert2";

import * as JSZip from 'jszip'


@Component({
    selector: 'app-mapa-view',
    templateUrl: './mapa-view.component.html',
    styleUrls: ['./mapa-view.component.scss'],
    animations: fuseAnimations
})
export class MapaViewComponent implements OnInit {
    active: boolean;
    nombre: string;
    popUp: ol.Overlay;

    private mediaSub: Subscription;

    constructor(
        private apivujService: MapaService,
        private router: Router,
        private _mediaObserver: MediaObserver,
        private dialog: MatDialog,
        private _bottomSheet: MatBottomSheet,
        private _requisitos: RequisitosService,
        private mapaService: MapaService,
    ) {
        this.mediaSub = this._mediaObserver.media$.subscribe((res: MediaChange) => {
            this.activeMedia = res.mqAlias;
            if (this.activeMedia == 'xs') {
                this.removeActive();
            } else {
                this.addActive();
                this.barraOpen = true;
            }
        })
    }

    @ViewChild('barraLateral', {static: true}) barraLateral: ElementRef;
    @ViewChild('formularioDinamico') formularioDinamico: ElementRef;
    @ViewChild('popUps') popUps: ElementRef;
    @ViewChild(InfoComponent) infoComponent: InfoComponent;
    @ViewChildren('SourceWMS') SourceWMS: QueryList<any>;
    geoserver_url = environment.SERVER_ORIGIN_GEO_SERVER
    errorFormularioMessage = [];
    vistaInicial: boolean = true;
    mapZoom: number = 7;
    clickCoord4326 = [];
    clickCoord32614 = [];
    pasoT: number = 1;
    errorActividadSelected = false;
    activeMedia;
    barraOpen = false;
    barraIOpen = false;
    municipioControl = new FormControl();
    municipios: Municipio[] = [];
    municipioCoords = [];
    busquedaDomicilioCoords = [];
    estadoCoords = [];
    predioCoords = [];
    aolInteraction = 'seleccionar_predio';
    WMSInfo = {mostrar: false, contenido: []};
    girosSeleccionados: string[] = [];
    nombresGirosSeleccionados: string[] = [];
    prediocoord: any;
    direccionInput: boolean = true;
    capasMunicipio: Observable<CapaMapa[]>;
    construccionCoords = [];
    datosPredio: any = {
        localidad: {},
        colonia: {},
        domicilio: {},
        calle: "",
        area_predio: 0,
        area_construccion: 0,
        denue: [],
        escuelas: [],
        centros_salud: [],
        edificios_gobierno: [],
        espacio_publico: [],
        url_minimapa: "",
        url_minimapa2: "",
        url_zip:"",
        url_uso: "",
        cuerpos_agua: {},
        nivel_impacto: 0,
    };
    municipioId = 0;
    municipio = {
        id: 0,
        nombre: '',
        tiene_zonificacion: false
    };
    denueFilter = '';
    buscar_domicilio = "";
    filteredOptions: Observable<Municipio[]>;
    filteredActividades: Observable<any[]>;
    panelHerramientas = false;
    herramientaActiva: string = 'capas'
    nuevoPoligonoArchivoID: 0
    actividadesEconomicas: any;
    actividaddSeleccionada: any;
    camposDiamicosMunicipio: any;
    datosDinamicosActual = {};
    cargandoMunicipio = false;
    capaAnalisisComercial: Object;
    capaAnalisisComercialURL: string = '';
    filteredGiros: Observable<any[]>;
    separatorKeysCodes: number[] = [ENTER, COMMA];
    girosCtrl = new FormControl();
    girosDisponibles: Object[];
    nuevoPoligonoID: number;
    sketch: any;
    helpTooltipElement: any;
    helpTooltip: any;
    measureTooltipElement: any;
    measureTooltip: any;
    helpMsg = 'Click sobre un predio para seleccionarlo';
    startDrawMsg: string = 'Click para empezar a dibujar';
    continuePolygonMsg: string = 'Click para continuar dibujando el polígono<br>Doble click para finalizar';
    continueLineMsg: string = 'Click para continuar dibujando la línea<br>Doble click para finalizar';

    drawMedicion: ol.interaction.Draw;
    capaMediciones: ol.layer.Vector;
    sourceMediciones: ol.source.Vector;
    medicionesDistancia: any[] = [];
    medicionesArea: any[] = [];
    new_pol;
    @ViewChild('giroInput') giroInput: ElementRef<HTMLInputElement>;
    @ViewChild('aolview') aolview: any;
    @ViewChild('aolmap') aolmap: any;
    @ViewChild('dialogActividades') dialogActividades: any;
    @ViewChild('aolfeatureMunicipio') aolfeatureMunicipio: any;
    @ViewChild('limiteEstatal') limiteEstatal: any;
    @ViewChild('mediciones') mediciones: any;
    @ViewChild('aolfeaturePredio') aolfeaturePredio: any;
    @ViewChild('dibujoPredio') dibujoPredio: any;
    @ViewChild('predioMunicipio') predioMunicipio: any;
    @ViewChild('dibujoComercial') dibujoComercial: any;
    @ViewChild('municipios') searchElement: ElementRef;
    @ViewChild('tramiteList') tramiteList: ElementRef;
    @ViewChild('toolTipT') toolTipT;
    @Input('idMunicipio') idActualIngresado: string;
    ngOnInit(): void {

        setTimeout(() => {
            this.createMeasureTooltip();
            this.createHelpTooltip();
            this.aolmap.instance.on('moveend', (e) => {
                console.log("moveend", e)
                this.mapZoom = e.map.getView().getZoom()
                const center = e.map.getView().getCenter()
                const center32614 = proj4('EPSG:4326', 'EPSG:32614', center);

                if (this.mapZoom < 19) {
                    if (this.helpTooltipElement) {
                        this.helpTooltipElement.classList.add('hidden');
                    }
                }
                if (this.mapZoom < 17) {
                    this.barraIOpen = false
                    if (this.predioCoords.length > 0) {
                        this.limpiarMapa(null)
                    }
                }
                if (this.mapZoom < 14) {
                    this.herramientaActiva = 'capas'
                }
                if (this.mapZoom >= 10) {
                    this.apivujService.getWFSFeature(
                        `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:base_municipio
&count=1&outputFormat=application/json&cql_filter=CONTAINS(geom, POINT (${center32614[0]} ${center32614[1]}))`
                    ).subscribe(response => {
                        if (response.totalFeatures === 1) {
                            const mun = response.features[0]

                            if (mun.properties.id !== this.municipio.id) {
                                let idActualIngresado = mun.properties.id.toString().replace(/-/g, " ")
                                let i = FuseUtils.filterArrayByString(this.municipios, idActualIngresado);
                                this.municipio = i[0];
                                this.municipio = mun.properties
                                this.municipioId = mun.properties.id
                                this.municipioSelected(false)
                            }
                        }
                    });
                } else {
                    this.barraIOpen = false;
                    this.municipioUnselected();
                }
            });

            this.aolmap.instance.on('pointermove', (evt) => {
                this.pointerMoveHandler(evt);
            })

            this.sourceMediciones = new ol.source.Vector();
            this.capaMediciones = new ol.layer.Vector({
                source: this.sourceMediciones,
                zIndex: 20,
                style: new ol.style.Style({
                    fill: new ol.style.Fill({
                        color: 'rgba(255, 255, 255, 0.2)',
                    }),
                    stroke: new ol.style.Stroke({
                        color: '#7edd76',
                        width: 2,
                    }),
                    image: new ol.style.Circle({
                        radius: 7,
                        fill: new ol.style.Fill({
                            color: '#7edd76',
                        }),
                    }),
                }),
            });
            this.aolmap.instance.addLayer(this.capaMediciones)
        }, 300)

        proj4.defs('EPSG:32614', '+proj=utm +zone=13 +ellps=WGS84 +datum=WGS84 +units=m +no_defs');
        proj4('EPSG:32614');
        this.apivujService.getMunicipios().subscribe(
            response => {
                this.municipios = response;
                if (this.idActualIngresado != '') {
                    this.idActualIngresado = this.idActualIngresado.replace(/-/g, " ")
                    let i = FuseUtils.filterArrayByString(this.municipios, this.idActualIngresado);
                    this.municipio = i[0];
                    setTimeout(() => {
                        this.municipioSelected(true);
                    }, 300)
                }
            });

        this.apivujService.getWFSFeature(
            `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:estado&count=1&outputFormat=application/json&cql_filter=cve_ent=14`
        ).subscribe(estado => {
            this.estadoCoords = [];
            estado.features[0].geometry.coordinates[0].forEach(val => {
                this.estadoCoords.push(proj4('EPSG:32614', 'EPSG:4326', val));
            });

            setTimeout(() => {
                if (this.municipio.id > 0) {
                    const extent = this.limiteEstatal.instance.O.geometry.getExtent();
                    this.aolview.instance.fit(extent, {duration: 0})
                }
            }, 600)
        })

        this.filteredOptions = this.municipioControl.valueChanges.pipe(
            startWith(''),
            map(value => typeof value === 'string' ? value : value.nombre),
            map(nombre => nombre ? this._filter(nombre) : this.municipios.slice())
        );

    }

    pointerMoveHandler(evt) {
        if (this.mapZoom > 17) {
            if (evt.dragging) {
                return;
            }

            if (this.sketch) {
                this.helpMsg = this.startDrawMsg
                var geom = this.sketch.getGeometry();
                if (geom instanceof ol.geom.Polygon) {
                    this.helpMsg = this.continuePolygonMsg;
                } else if (geom instanceof ol.geom.LineString) {
                    this.helpMsg = this.continueLineMsg;
                }
            }

            this.helpTooltipElement.innerHTML = this.helpMsg;
            this.helpTooltip.setPosition(evt.coordinate);
            this.helpTooltipElement.classList.remove('hidden');


        } else {
            if (this.helpTooltipElement && this.helpTooltipElement.innerHTML) {
                this.helpTooltipElement.innerHTML = ''
            }
            this.sketch = null;
        }
    };


    quitarPopupInfo(): void {
        this.WMSInfo = {mostrar: false, contenido: []};
    }

    showNombreCapa(data) {
        return data.split(".")[0]
    }

    dataTableKeyValue(data) {
        return Object.entries(data).map(val => {
            return {clave: val[0], valor: String(val[1])}
        })
    }

    xml2json(xml) {
        try {
            let obj = {};
            if (xml.children.length > 0) {
                for (let i = 0; i < xml.children.length; i++) {
                    let item = xml.children.item(i);
                    let nodeName = item.nodeName;

                    if (typeof (obj[nodeName]) == "undefined") {
                        obj[nodeName] = this.xml2json(item);
                    } else {
                        if (typeof (obj[nodeName].push) == "undefined") {
                            let old = obj[nodeName];
                            obj[nodeName] = [];
                            obj[nodeName].push(old);
                        }
                        obj[nodeName].push(this.xml2json(item));
                    }
                }
            } else {
                obj = xml.textContent;
            }
            return obj;
        } catch (e) {
            console.log(e.message);
        }
    }

    displayFn(municipio: Municipio): string {
        if (municipio) {
            this.municipioId = municipio.id;
            this.municipio = municipio;
            return municipio && municipio.nombre ? municipio.nombre : '';
        }
    }

    herramientaSeleccionada(event): void {
        this.limpiarMapa(null)
        this.herramientaActiva = event
        if (this.herramientaActiva === 'analisis-comercial') {
            this.aolInteraction = ''
            this.helpMsg = ''
            this.helpTooltipElement.classList.add('hidden');
        }
        if (this.herramientaActiva === 'herramientas-seleccion') {
            this.aolInteraction = 'seleccionar_predio'
            this.helpMsg = 'Click sobre un predio para seleccionarlo'
        }
        if (this.herramientaActiva === 'obtener_info') {
            this.aolInteraction = 'obtener_info'
        }

        if (this.herramientaActiva === 'analisis-comercial' && this.mapZoom < 15) {
            this.aolview.instance.animate({zoom: 15, duration: 500})
        }
        if (this.herramientaActiva === 'herramientas-seleccion' && this.mapZoom < 18 &&
            (this.aolInteraction === 'seleccionar_predio' || this.aolInteraction === 'dibujar_predio'
                || this.aolInteraction === 'dibujar_predio' || this.aolInteraction === 'medir_distancia'
                || this.aolInteraction === 'medir_area' || this.aolInteraction === 'obtener_info')
        ) {
            this.aolview.instance.animate({zoom: 18, duration: 500})
        }
        if (this.herramientaActiva === 'obtener_info' && this.mapZoom < 18) {
            this.aolview.instance.animate({zoom: 18, duration: 500})
        }
    }

    iniciarTramite(): void {
        this.pasoT = 2;
        this.guardarPoligono().subscribe(response => {
           
            let id = btoa(response.id)
            this.new_pol = response.id;    
            this.datosPredio.url_uso = this.datosPredio.url_uso.replace('DIBUJOID', response.id)
            this.datosPredio.url_minimapa2 = this.datosPredio.url_uso;
       
        })
       

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
    setNuevoPoligonoID(id): void {
       
        this.datosPredio.url_minimapa = this.datosPredio.url_minimapa.replace('DIBUJOID', id);
        this.datosPredio.url_uso = this.datosPredio.url_uso.replace('DIBUJOID', id)
    }

    private _filterGiro(value: any) {
        if (!value) {
            return
        }
        let filterValue = (value.SCIAN ? value.SCIAN.toLowerCase() : value.toLowerCase());
        return this.girosDisponibles.filter(option => (FuseUtils.getCleanedString(option['SCIAN'].toLowerCase()).includes(filterValue) || (option['SCIAN'].toLowerCase()).includes(filterValue)));
    }

    openBottomSheet(): void {
        let sheet = this._bottomSheet.open(SheetDibujarComponent);
        sheet.afterDismissed().subscribe(res => {
            if (!res) {
                this.aolInteraction = 'seleccionar_predio'
                this.dibujoPredio.instance.clear()
                if (this.predioCoords.length > 0)
                    this.barraIOpen = true
            }
            this.aolInteraction = res;
            if (res === 'dibujar_predio') {
                this.limpiarMapa('dibujar_predio')
                this.aolInteraction = 'dibujar_predio'
            } else {
                this.aolInteraction = 'seleccionar_predio'
                if (this.predioCoords.length > 0)
                    this.barraIOpen = true
            }
        })
    }

    limpiarMapa(interaction) {
        this.girosSeleccionados = []
        this.nombresGirosSeleccionados = []

        if (this.municipioId > 0) {
            this.predioCoords = []
            this.capaMediciones.getSource().clear()
            this.aolmap.instance.getOverlays().getArray().slice(0).forEach((overlay, index) => {
                this.aolmap.instance.removeOverlay(overlay);
            })
            this.aolmap.instance.addOverlay(this.measureTooltip);
            this.aolmap.instance.addOverlay(this.helpTooltip);
        }

        this.capaAnalisisComercialURL = ''
        this.barraIOpen = false
        this.denueFilter = ''

        this.quitarPopupInfo()
        if (interaction) {
            this.aolInteraction = interaction
        }
    }


    mostrarDialogActividadesEconomicas() {
        let dialog = this.dialog.open(this.dialogActividades, {
            width: '800px',
        });

        dialog.afterClosed().subscribe(res => {
        })
    }

    giroSeleccionado(event: MatAutocompleteSelectedEvent): void {
        if (this.girosSeleccionados.indexOf(event.option.value.codigo) == -1) {
            if (this.girosSeleccionados.length < 5) {
                this.girosSeleccionados.push(event.option.value.codigo);
                this.nombresGirosSeleccionados.push(event.option.value.SCIAN);
                this.giroInput.nativeElement.value = '';
                this.girosCtrl.setValue(null);
            }
        } else {
            return
        }
    }

    agregarGiro(event: MatChipInputEvent): void {
        return
    }

    quitarGiro(giro: string): void {
        const index = this.girosSeleccionados.indexOf(giro);
        if (index >= 0) {
            this.girosSeleccionados.splice(index, 1);
            this.nombresGirosSeleccionados.splice(index, 1);
        }
    }

    dibujarAnalisis(tipo) {
        this.limpiarMapa(null)
        this.mostrarDialogActividadesEconomicas()
        this.aolInteraction = tipo;
    }

    createMeasureTooltip() {
        if (this.measureTooltipElement) {
            this.measureTooltipElement.parentNode.removeChild(this.measureTooltipElement);
        }
        this.measureTooltipElement = document.createElement('div');
        this.measureTooltipElement.className = 'ol-tooltip-medir ol-tooltip-medir-measure';
        this.measureTooltip = new ol.Overlay({
            element: this.measureTooltipElement,
            offset: [0, -15],
            positioning: 'bottom-center',
        });
        this.aolmap.instance.addOverlay(this.measureTooltip);
    }

    createHelpTooltip() {
        if (this.helpTooltipElement) {
            this.helpTooltipElement.parentNode.removeChild(this.helpTooltipElement);
        }
        this.helpTooltipElement = document.createElement('div');
        this.helpTooltipElement.className = 'ol-tooltip-medir hidden ';
        this.helpTooltip = new ol.Overlay({
            element: this.helpTooltipElement,
            offset: [15, 17],
            positioning: 'center-left',
        });
        this.aolmap.instance.addOverlay(this.helpTooltip);
    }

    formatArea(polygon) {
        let area = ol.Sphere.getArea(polygon, {projection: ol.proj.get('EPSG:4326')});
        let output;
        if (area > 1000) {
            output = Math.round((area / 1000) * 100) / 100 + ' ' + 'km<sup>2</sup>';
        } else {
            output = Math.round(area * 100) / 100 + ' ' + 'm<sup>2</sup>';
        }
        return output;
    };

    formatLength(line) {
        let length = ol.Sphere.getLength(line, {projection: ol.proj.get('EPSG:4326')});
        let output;
        if (length > 1000) {
            output = Math.round((length / 1000) * 100) / 100 + ' ' + 'km';
        } else {
            output = Math.round(length * 100) / 100 + ' ' + 'm';
        }
        return output;
    };

    alertaArchivoInvalido() {
        Swal.fire({
            icon: 'error',
            text: 'El archivo seleccionado no es válido'
        })
        this.helpMsg = 'Seleccionar predio'
        this.aolInteraction = 'seleccionar_predio'
    }

    guardarPoligonoArchivo(poligono): Observable<any> {
        const data = {
            giro: null,
            folio: 'ficha',
            geom: {"type": "Polygon", "coordinates": poligono},
            area: turf.area(turf.polygon(poligono))
        }
        return this.apivujService.guardarRegistroTramite(data)
    }

    seleccionarPoligonoArchivo(poligono) {

        this.guardarPoligonoArchivo(poligono).subscribe(response => {
            this.actividaddSeleccionada = '';
            this.datosDinamicosActual = {};
            this.barraIOpen = false;
            this.pasoT = 1;

            this.datosPredio.area_construccion = 0;
            this.datosPredio.nivel_impacto = 0;

            this.datosPredio.area_predio = turf.area(turf.polygon(poligono));
            this.predioCoords = poligono[0];

            const poligono_turf = turf.polygon(poligono)
            const centroide = turf.centroid(poligono_turf)


            const centroide32614 = proj4('EPSG:4326', 'EPSG:32614', centroide.geometry.coordinates);

            const wktstr = 'POLYGON (' +
                poligono.map((ring) => {
                    return '(' + ring.map((p) => {
                        return p[0] + ' ' + p[1];
                    }).join(', ') + ')';
                }).join(', ') + ')';


            this.getDatosEscuelas(wktstr)
            this.getDatosCentrosSalud(wktstr)
            this.getDatosEdificiosGobierno(wktstr)
            this.getDatosLocalidad(centroide32614)
            this.getDatosColonia(centroide32614)
            this.getDatosDenue(wktstr)
            this.getDatosEspacioPublico(wktstr)
            this.getDatosCuerposAgua(wktstr)
            this.getDomicilioGoogleMaps(centroide.geometry.coordinates)
            this.getDatosConstruccion(wktstr)
            this.getNivelImpacto(wktstr)
            // this.getDatosZoniciacion(wktstr)


            //poner como dibujar

            const bbox = turf.bbox(turf.buffer(turf.polygon(poligono), 10, {units: 'meters'}))
            
                                                                                                                                                                                                                                                         
            this.datosPredio.url_zip =    `${environment.SERVER_ORIGIN_GEO_SERVER}?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:construcciones,VUJ:planimetria_predio2,VUJ:manzanas&outputFormat=shape-zip&format_options=filename:extract.zip&cql_filter=BBOX(geom,${bbox[0]},${bbox[1]},${bbox[2]},${bbox[3]},%27EPSG:4326%27)`                                                                         //                          
            this.datosPredio.url_minimapa2 = `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WMS&version=1.3.0&request=GetMap&FORMAT=image/png8&layers=VUJ:mapa_ficha_informativa,VUJ:planimetria_predio2&exceptions=application/vnd.ogc.se_inimage&CRS=EPSG:4326&width=600&height=600&styles=,VUJ:Predio&cql_filter=INCLUDE;id=${response.id}&BBOX=${bbox[1]},${bbox[0]},${bbox[3]},${bbox[2]}`
            this.datosPredio.url_minimapa = `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WMS&version=1.3.0&request=GetMap&FORMAT=image/png8&layers=VUJ:MapaJaliscoMapaBase,VUJ:catastro,VUJ:registrotramite&exceptions=application/vnd.ogc.se_inimage&CRS=EPSG:4326&width=600&height=600&styles=,,VUJ:Predio&cql_filter=INCLUDE;INCLUDE;id=${response.id}&BBOX=${bbox[1]},${bbox[0]},${bbox[3]},${bbox[2]}`
            this.datosPredio.url_uso = `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WMS&version=1.3.0&request=GetMap&FORMAT=image/png8&layers=VUJ:planparcialdesarrollourbano32614,VUJ:MapaJaliscoMapaBase,VUJ:catastro,VUJ:registrotramite&exceptions=application/vnd.ogc.se_inimage&CRS=EPSG:4326&width=600&height=350&styles=,,,VUJ:Predio&cql_filter=INCLUDE;INCLUDE;INCLUDE;id=${response.id}&BBOX=${bbox[1]},${bbox[0]},${bbox[3]},${bbox[2]}`

            this.cargandoMunicipio = true;
            this.datosPredio.vectorial = poligono;
            this.barraIOpen = true

            this.apivujService.getRequisitosMunicipio(this.municipio.id).subscribe((response: any) => {
                response.data.forEach(element => {
                    this.datosDinamicosActual[element.name] = element;
                    if (element.requerido == 3) {
                        this.datosDinamicosActual[element.name].visible = false;
                    }
                });
                this.cargandoMunicipio = false;
                this.camposDiamicosMunicipio = response.data;
            }, error => {
                this.cargandoMunicipio = false;
            });


            setTimeout(() => {
                this.aolview.instance.fit(
                    turf.bbox(turf.buffer(turf.polygon(poligono), 10, {units: 'meters'})), {
                        duration: 500,
                        padding: [0, 0, 0, 300]
                    });
                this.aolInteraction = 'seleccionar_predio'
                this.helpMsg = 'Click sobre algún predio para seleccionarlo'
            }, 200)
        })
    }

    limpiarButton(){

        this.limpiarMapa('limpiar_mapa')
        this.aolInteraction = 'seleccionar_predio';
    }

    setMapaInteraccion(event) {
        this.aolmap.instance.removeInteraction(this.drawMedicion)
        if (event === 'limpiar_mapa') {
            this.limpiarMapa(event)
        } else {
            this.aolInteraction = event
            if (this.aolInteraction === 'seleccionar_predio') {
                this.helpMsg = 'Click sobre un predio para seleccionarlo';
            }

            if (this.aolInteraction === 'obtener_info') {
                this.helpMsg = 'Click sobre algún objeto para obtener su información';
                if (this.mapZoom < 18) {
                    this.aolview.instance.animate({zoom: 18, duration: 500})
                }
            }

            if (this.aolInteraction === 'dibujar_predio') {
                this.helpMsg = 'Click para empezar a dibujar'
            }


            if (this.aolInteraction === 'subir_archivo') {
                this.helpMsg = 'subir archivo'
                this.helpTooltipElement.classList.add('hidden');

                Swal.fire({
                    icon: 'info',
                    html: `<div class="info-impacto">
                    <p>La función de subir permite subir archivos con datos en formato vectorial.</p>
                    <p>Los datos a subir deben estar en formato <a href="https://geojson.org" target="_blank">GeoJSON</a>, 
                    <a href="https://www.geopackage.org" target="_blank">GeoPackage</a>, 
                    <a href="https://www.ogc.org/standards/kml" target="_blank">KML/KMZ</a> o
                    <a href="https://enterprise.arcgis.com/es/portal/latest/use/shapefiles.htm" target="_blank">Shapefile</a> 
                        (este último, al ser un formato multiarchivo, deberá estar empaquetado en un solo archivo .zip con todas las partes dentro, sin utilizar carpetas).</p>
                    <p>El archivo de datos vectoriales debe contar con el sistema de coordenadas de referencia (CRS) <a href="https://epsg.io/4326" target="_blank">EPSG:4326</a>. 
                        Y debe ser de geometría de tipo Polígono, ningún otro tipo de geometría está permitido.</p>
                    </div>`,
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Continuar',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        //accept: 'application/geojson,application/json,application/vnd.google-earth.kml+xml,application/vnd.google-earth.kmz,application/geopackage+vnd.sqlite3,application/zip,application/gpkg,application/kml,application/kmz',

                        Swal.fire({
                            showCancelButton: true,
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Subir archivo',
                            allowOutsideClick: false,
                            title: 'Subir Capa GeoJSON',
                            html: 'Seleccione el archivo a subir.',
                            input: 'file',
                            inputAttributes: {
                                name: "upload[]",
                                id: "fileToUpload",
                            },

                            preConfirm: (file) => {
                                const vector_extensions = ['kml', 'kmz', 'zip', 'shz', 'json', 'geojson', 'gpkg']
                                const vector_text_extensions = ['kml', 'kmz', 'json', 'geojson']
                                if (file) {
                                    const reader = new FileReader;

                                    ///////
                                    reader.onloadend = (e) => {
                                        // console.log("e.target.result", e.target.result)
                                        try {
                                            const extension = file.name.split(".")[file.name.split(".").length - 1]

                                            if (vector_extensions.includes(extension)) {
                                                //console.log("extension valida", extension)

                                                if (vector_text_extensions.includes(extension)) {
                                                    const result = e.target.result

                                                    if (extension === 'geojson' || extension === 'json') {
                                                        let geojson = JSON.parse(JSON.parse(JSON.stringify(result)))
                                                        if (geojson.type && geojson.type.toLowerCase() === 'featurecollection') {
                                                            console.log("si", geojson.type)
                                                            if (geojson.features && geojson.features.length > 0 && geojson.features[0]
                                                                && geojson.features[0].geometry
                                                                && geojson.features[0].geometry.type === 'Polygon') {
                                                                const poligono = geojson.features[0].geometry.coordinates
                                                                console.log("si hay un poligono", poligono)
                                                                this.seleccionarPoligonoArchivo(poligono)
                                                            } else {
                                                                this.alertaArchivoInvalido()
                                                            }
                                                        } else {
                                                            this.alertaArchivoInvalido()
                                                        }
                                                    }

                                                    if (extension === 'kml' || extension === 'kmz') {
                                                        console.log("es K")
                                                        let kml
                                                        if (extension === 'kmz') {
                                                            JSZip.loadAsync(result).then(zip => {
                                                                if (zip.files) {
                                                                    Object.keys(zip.files).forEach((filename, index) => {
                                                                        console.log("filename", filename)
                                                                        if (filename.split(".")[filename.split(".").length - 1] === 'kml') {
                                                                            zip.files[filename].async('string').then(function (fileData) {
                                                                                kml = fileData
                                                                                //console.log("juju XML kml", fileData) // These are your file contents
                                                                            })
                                                                        }
                                                                    })
                                                                } else {
                                                                    console.log("no hay archivos")
                                                                    this.alertaArchivoInvalido()
                                                                }
                                                            })
                                                        } else {
                                                            kml = result
                                                        }
                                                        setTimeout(() => {
                                                            if (kml) {
                                                                let geom = []
                                                                let parser = new DOMParser();
                                                                const xmlDoc = parser.parseFromString(kml, "text/xml");
                                                                // @ts-ignore
                                                                let kmljson = this.xml2json(xmlDoc)
                                                                console.log("kmljson", kmljson)
                                                                // @ts-ignore
                                                                if (kmljson.kml
                                                                    // @ts-ignore
                                                                    && kmljson.kml.Document
                                                                    // @ts-ignore
                                                                    && kmljson.kml.Document.Folder
                                                                    // @ts-ignore
                                                                    && kmljson.kml.Document.Folder.Placemark
                                                                    // @ts-ignore
                                                                    && kmljson.kml.Document.Folder.Placemark.Polygon
                                                                    // @ts-ignore
                                                                    && kmljson.kml.Document.Folder.Placemark.Polygon.outerBoundaryIs.LinearRing.coordinates) {
                                                                    // @ts-ignore
                                                                    let geoms = kmljson.kml.Document.Folder.Placemark.Polygon.outerBoundaryIs.LinearRing.coordinates.split(" ")

                                                                    geoms.forEach(c => {
                                                                        const a = c.split(",")
                                                                        if (!Number.isNaN(parseFloat(a[0])) && !Number.isNaN(parseFloat(a[1]))) {
                                                                            const b = [parseFloat(a[0]), parseFloat(a[1])]
                                                                            geom.push(b)
                                                                        }
                                                                    })

                                                                    geom = [geom]
                                                                    this.seleccionarPoligonoArchivo(geom)
                                                                    // @ts-ignore
                                                                } else if (kmljson.kml
                                                                    // @ts-ignore
                                                                    && kmljson.kml.Document
                                                                    // @ts-ignore
                                                                    && kmljson.kml.Document.Placemark
                                                                    // @ts-ignore
                                                                    && kmljson.kml.Document.Placemark.Polygon
                                                                    // @ts-ignore
                                                                    && kmljson.kml.Document.Placemark.Polygon.outerBoundaryIs.LinearRing.coordinates) {
                                                                    // @ts-ignore
                                                                    let geoms = kmljson.kml.Document.Placemark.Polygon.outerBoundaryIs.LinearRing.coordinates.replace("\t", '').replace("\n", '').split(" ")

                                                                    geoms.forEach(c => {
                                                                        const a = c.split(",")
                                                                        if (!Number.isNaN(parseFloat(a[0])) && !Number.isNaN(parseFloat(a[1]))) {
                                                                            const b = [parseFloat(a[0]), parseFloat(a[1])]
                                                                            geom.push(b)
                                                                        }
                                                                    })
                                                                    console.log("geom", geom)
                                                                    geom = [geom]
                                                                    this.seleccionarPoligonoArchivo(geom)
                                                                } else {
                                                                    this.alertaArchivoInvalido()
                                                                }
                                                            } else {
                                                                this.alertaArchivoInvalido()
                                                            }
                                                        }, 700)
                                                    }
                                                } else {

                                                    ////// -> aqui debe continuar la subida del archivo para convertir en geojson
                                                    console.log("subir archivo")

                                                }

                                            } else {
                                                this.alertaArchivoInvalido()
                                            }
                                        } catch (error) {
                                            Swal.fire({
                                                icon: 'error',
                                                text: `Error: "q" ${error}`
                                            })
                                        }
                                    }
                                    reader.onerror = (e) => {
                                        Swal.fire({
                                            icon: 'error',
                                            text: `$Error: {e}`,
                                        })
                                    };
                                    reader.readAsBinaryString(file);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        text: 'No se seleccionó ningún archivo'
                                    })
                                }
                            },
                        })
                    } else {
                        this.helpMsg = 'Click sobre un predio para seleccionarlo';
                        this.aolInteraction = 'seleccionar_predio'
                    }
                })
            }

            if (this.aolInteraction === 'medir_distancia' || this.aolInteraction === 'medir_area') {
                this.helpMsg = this.startDrawMsg
                let tipo
                if (this.aolInteraction === 'medir_distancia')
                    tipo = 'LineString'
                if (this.aolInteraction === 'medir_area')
                    tipo = 'Polygon'

                this.drawMedicion = new ol.interaction.Draw({
                    source: this.sourceMediciones,
                    type: tipo,
                    style: new ol.style.Style({
                        fill: new ol.style.Fill({
                            color: 'rgba(255, 255, 255, 0.2)',
                        }),
                        stroke: new ol.style.Stroke({
                            color: 'rgba(0, 0, 0, 0.5)',
                            lineDash: [10, 10],
                            width: 2,
                        }),
                        image: new ol.style.Circle({
                            radius: 5,
                            stroke: new ol.style.Stroke({
                                color: 'rgba(0, 0, 0, 0.7)',
                            }),
                            fill: new ol.style.Fill({
                                color: 'rgba(255, 255, 255, 0.2)',
                            }),
                        }),
                    }),
                });
                this.aolmap.instance.addInteraction(this.drawMedicion);


                let listener;
                this.drawMedicion.on('drawstart', (e) => {
                    this.sketch = e.feature;
                    this.measureTooltip.setPosition(e.coordinate);

                    /** @type {import("../src/ol/coordinate.js").Coordinate|undefined} */
                    let tooltipCoord = e.coordinate;

                    listener = this.sketch.getGeometry().on('change', (evt) => {
                        let geom = evt.target;
                        let output;
                        if (this.aolInteraction === 'medir_area') {
                            output = this.formatArea(geom);
                            try {
                                tooltipCoord = geom.getInteriorPoint().getCoordinates();
                            } catch {
                                tooltipCoord = geom.getLastCoordinate();
                            }
                        }
                        if (this.aolInteraction === 'medir_distancia') {
                            output = this.formatLength(geom);
                            tooltipCoord = geom.getLastCoordinate();
                        }
                        this.measureTooltipElement.innerHTML = output;
                        this.measureTooltip.setPosition(tooltipCoord);
                    });
                });

                this.drawMedicion.on('drawend', () => {
                    if (this.sketch) {
                        this.measureTooltipElement.className = 'ol-tooltip-medir ol-tooltip-medir-static';
                        this.measureTooltip.setOffset([0, -7]);
                        // unset sketch
                        this.sketch = null;
                        // unset tooltip so that a new one can be created
                        this.measureTooltipElement = null;
                        this.createMeasureTooltip();
                        ol.Observable.unByKey(listener);
                    }
                })
            }
        }
    }

    dibujoCaja = ol.interaction.Draw.createBox()


    municipioUnselected(): void {
        this.direccionInput = false;
        this.buscar_domicilio = "";
        this.busquedaDomicilioCoords = [];
        this.router.navigate([`/mapa/`]);
        this.municipioId = 0;
        this.municipio = {id: 0, nombre: '', tiene_zonificacion: false};
        this.buscar_domicilio = "";
        this.municipioCoords = [];
        this.capasMunicipio = undefined;
        this.pasoT = 1;
        this.limpiarMapa(null)
    }

    changeee(evt) {
        console.log("changeee", evt)
    }

    municipioSelected(pan): void {
        this.municipioId = this.municipio.id
        if (!pan) {
            this.limpiarMapa(null)
        }
        if (this.municipioId > 0) {
            this.mapaService.getGirosMunicipioAll(this.municipioId).subscribe(r => {
                this.girosDisponibles = r;
            });
        }

        this.filteredGiros = this.girosCtrl.valueChanges.pipe(startWith(null), map(value => this._filterGiro(value)));
        this.direccionInput = false;
        this.buscar_domicilio = "";
        this.busquedaDomicilioCoords = [];
        this.pasoT = 1;
        this.router.navigate([`/mapa/${this.municipio.nombre.replace(/ /g, "-")}`]);
        this.mapaService.getGirosMunicipio(this.municipio.id).subscribe((response: any) => {
            this.cargandoMunicipio = true;
            this.actividadesEconomicas = response;
            this.filteredActividades = response;
        }, error => {
        });
        console.log(this.municipio.id);
        this.mapaService.getGeomMunicipio(this.municipio.id).subscribe(
            
            response => {
                this.municipioCoords = [];
                console.log(response.geometry);
                response.geometry.coordinates[0].forEach(val => {
                  
                    this.municipioCoords.push(proj4('EPSG:32614', 'EPSG:4326', val));
                });
                if (pan) {
                    setTimeout(() => {
                        const extent = this.aolfeatureMunicipio.instance.O.geometry.getExtent();
                        this.aolview.instance.fit(extent, {duration: 500});
                    })
                }
            },
            error => {

            }
        );
        this.apivujService.getCapasMunicipio(this.municipio.id).subscribe(
            response => {
                this.capasMunicipio = response;
            }, error => {
            }
        );
    }

    barraProcesar(event) {
        this.barraOpen = event;
    }

    barraCapasToggle() {
        this.panelHerramientas = !this.panelHerramientas
    }

    iniciarTramiteButton(event): void {
        setTimeout(() => { // this will make the execution after the above boolean has changed
            this.searchElement.nativeElement.focus();
            this.toolTipT.show();
        }, 0);
    }

    ubicar_domicilio(domicilio) {
        if (domicilio.length > 6) {
          this.apivujService.getGeocode(domicilio, this.municipio.nombre).subscribe(res_domicilio => {
            if (res_domicilio) {
              const viewport = res_domicilio.geometry.viewport;
              this.busquedaDomicilioCoords = [res_domicilio.geometry.location.lng, res_domicilio.geometry.location.lat]
              const punto = turf.point(this.busquedaDomicilioCoords)
              const municipio = turf.polygon([this.municipioCoords])
              if (turf.booleanWithin(punto, municipio)) {
                const extent = [viewport.southwest.lng, viewport.southwest.lat, viewport.northeast.lng, viewport.northeast.lat]
                this.aolview.instance.fit(extent, {duration: 1000})
              } else {
                this.busquedaDomicilioCoords = [];
              }
            }
          });
        }
      }
      

    ubicar_domicilio2(domicilio) {
        if (domicilio.length > 6) {
            this.apivujService.getGoogleMapsGeo(
                `https://maps.googleapis.com/maps/api/geocode/json?address=${domicilio}, ${this.municipio.nombre}, Cuernavaca&key=.i.`
            ).subscribe(res_domicilio => {
                if (res_domicilio.results.length > 0) {
                    const viewport = res_domicilio.results[0].geometry.viewport;
                    this.busquedaDomicilioCoords = [res_domicilio.results[0].geometry.location.lng, res_domicilio.results[0].geometry.location.lat]
                    const punto = turf.point(this.busquedaDomicilioCoords)
                    const municipio = turf.polygon([this.municipioCoords])
                    if (turf.booleanWithin(punto, municipio)) {
                        const extent = [viewport.southwest.lng, viewport.southwest.lat, viewport.northeast.lng, viewport.northeast.lat]
                        this.aolview.instance.fit(extent, {duration: 1000})
                    } else {
                        this.busquedaDomicilioCoords = [];
                    }
                }
            });
        }
    }

    onClickOverMap(event: any): void {
        const clickCoord4326 = event.coordinate;
        this.clickCoord4326 = event.coordinate;
        const clickCoord32614 = proj4('EPSG:4326', 'EPSG:32614', clickCoord4326);
        this.clickCoord32614 = clickCoord32614
        const resolution = this.aolmap.instance.getView().getResolution()

        if (this.mapZoom >= 11) {
            if (this.municipio.id > 0 && this.aolInteraction === 'obtener_info' && this.mapZoom >= 18) {
                this.WMSInfo = {mostrar: false, contenido: []};
                this.WMSInfo.mostrar = true
                let urlsInfo = []
                this.popUp = new ol.Overlay({
                    element: this.popUps.nativeElement,
                    position: event.coordinate,
                    autoPan: true,
                    autoPanAnimation: {
                        source: event.coordinate
                    }
                });
                let d = this.popUp.setPosition(clickCoord4326);
                this.aolmap.instance.addOverlay(this.popUp);
                let idt = ol.Map;
                if (this.SourceWMS.length) {
                    this.SourceWMS.forEach(source => {
                        const layersName = source.instance.getParams().LAYERS.split(":")
                        let nombreCapa
                        layersName.length === 2 ? nombreCapa = layersName[1] : nombreCapa = layersName[0]
                        const urlInfo = source.instance.getGetFeatureInfoUrl(this.clickCoord4326, resolution, 'EPSG:4326',
                            {'INFO_FORMAT': 'application/json', FEATURE_COUNT: 10})
                        urlsInfo.push([nombreCapa, urlInfo])
                    })
                    if (urlsInfo.length) {
                        urlsInfo.forEach(url => {
                            if (url[0] == 'licencias') {
                                url[1] += `&cql_filter=giro IS NOT NULL`;
                            }
                            this.apivujService.getWFSFeature(url[1]).subscribe(response => {
                                if (response.numberReturned > 0) {
                                    // filtrar solo vectores
                                    this.WMSInfo.contenido.push({nombre: url[0], datos: response})
                                }
                            })
                        })
                    }
                }
            }
            if (this.municipio.id > 0 && this.aolInteraction === 'limpiar_medicion' && this.mapZoom >= 18) {
                this.aolInteraction = 'seleccionar_predio'
            }


            if (this.municipio.id > 0 && this.aolInteraction === 'seleccionar_predio' && this.mapZoom >= 18) {
                this.actividaddSeleccionada = '';
                this.datosDinamicosActual = {};
                this.barraIOpen = false;
                this.pasoT = 1;
                console.log(`${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:planimetria_predio2&count=1&outputFormat=application/json&cql_filter=CONTAINS(geom, POINT (${clickCoord32614[0]} ${clickCoord32614[1]}))`);
                this.apivujService.getWFSFeature(
                    `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:planimetria_predio2&count=1&outputFormat=application/json&cql_filter=CONTAINS(geom, POINT (${clickCoord32614[0]} ${clickCoord32614[1]}))`
                ).subscribe(
                    response => {
                        if (response.numberMatched > 0) {
                            console.log(response);
                            this.limpiarMapa('seleccionar_predio')
                            if (response.features[0].properties.municipio_id === this.municipio.id) {
                                this.datosPredio.area_construccion = 0;
                                this.datosPredio.nivel_impacto = 0;
                                const predio = [];
                                response.features[0].geometry.coordinates[0].forEach(vert => {
                                    predio.push(proj4('EPSG:32614', 'EPSG:4326', vert));
                                });
                                this.datosPredio.area_predio = turf.area(turf.polygon([predio]));
                                console.log( turf.area(turf.polygon([predio])));
                                this.predioCoords = predio;
                                const wktstr = 'POLYGON (' +
                                    response.features[0].geometry.coordinates.map((ring) => {
                                        return '(' + ring.map((p) => {
                                            return p[0] + ' ' + p[1];
                                        }).join(', ') + ')';
                                    }).join(', ') + ')';
                                const bboxMinimap = turf.bbox(turf.buffer(turf.polygon([predio]), 50, {units: 'meters'}));
                                console.log(bboxMinimap);
                                setTimeout(() => {
                                    this.aolview.instance.fit(
                                        turf.bbox(turf.buffer(turf.polygon([predio]), 10, {units: 'meters'})), {
                                            duration: 500,
                                            padding: [0, 0, 0, 300]
                                        });

                                    this.getDatosEscuelas(wktstr)
                                    this.getDatosCentrosSalud(wktstr)
                                    this.getDatosEdificiosGobierno(wktstr)
                                    this.getDatosLocalidad(clickCoord32614)
                                    this.getDatosColonia(clickCoord32614)
                                    this.getDatosDenue(wktstr)
                                    this.getDatosEspacioPublico(wktstr)
                                    this.getDatosCuerposAgua(wktstr)
                                    this.getDomicilioGoogleMaps(clickCoord4326)
                                    this.getDatosConstruccion(wktstr)
                                    this.getNivelImpacto(wktstr)
                                    // this.getDatosZoniciacion(wktstr)

                            
                                    this.datosPredio.url_zip =    `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:construcciones,VUJ:planimetria_predio2,VUJ:manzanas&outputFormat=shape-zip&format_options=filename:extract.zip&cql_filter=BBOX(geom,${bboxMinimap[0]},${bboxMinimap[1]},${bboxMinimap[2]},${bboxMinimap[3]},%27EPSG:4326%27)`
                                    this.datosPredio.url_minimapa2 = `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WMS&version=1.3.0&request=GetMap&FORMAT=image/png8&layers=VUJ:mapa_ficha_informativa,VUJ:planimetria_predio2&exceptions=application/vnd.ogc.se_inimage&CRS=EPSG:4326&width=600&height=600&styles=,VUJ:Predio&cql_filter=INCLUDE;id=${response.features[0].properties.id}&BBOX=${bboxMinimap[1]},${bboxMinimap[0]},${bboxMinimap[3]},${bboxMinimap[2]}`
                                    this.datosPredio.url_minimapa = `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WMS&version=1.3.0&request=GetMap&FORMAT=image/png8&layers=VUJ:MapaJaliscoMapaBase,VUJ:catastro,VUJ:planimetria_predio2&exceptions=application/vnd.ogc.se_inimage&CRS=EPSG:4326&width=600&height=600&styles=,,VUJ:Predio&cql_filter=INCLUDE;INCLUDE;id=${response.features[0].properties.id}&BBOX=${bboxMinimap[1]},${bboxMinimap[0]},${bboxMinimap[3]},${bboxMinimap[2]}`
                                    this.datosPredio.url_uso = `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WMS&version=1.3.0&request=GetMap&FORMAT=image/png8&layers=VUJ:planparcialdesarrollourbano32614,VUJ:MapaJaliscoMapaBase,VUJ:catastro_ficha,VUJ:planimetria_predio2&exceptions=application/vnd.ogc.se_inimage&CRS=EPSG:4326&width=600&height=350&styles=,,,VUJ:Predio&cql_filter=INCLUDE;INCLUDE;INCLUDE;id=${response.features[0].properties.id}&BBOX=${bboxMinimap[1]},${bboxMinimap[0]},${bboxMinimap[3]},${bboxMinimap[2]}`
                                    this.cargandoMunicipio = true;
                                    this.datosPredio.vectorial = response.features[0];
                                    this.barraIOpen = true

                                    this.apivujService.getRequisitosMunicipio(this.municipio.id).subscribe((response: any) => {
                                        response.data.forEach(element => {
                                            this.datosDinamicosActual[element.name] = element;
                                            if (element.requerido == 3) {
                                                this.datosDinamicosActual[element.name].visible = false;
                                            }
                                        });
                                        this.cargandoMunicipio = false;
                                        this.camposDiamicosMunicipio = response.data;
                                    }, error => {
                                        this.cargandoMunicipio = false;
                                    });
                                }, 100);
                            } else {
                                this.limpiarMapa('seleccionar_predio')
                                //this.aolInteraction = 'seleccionar_predio'
                            }
                            this.aolInteraction = 'seleccionar_predio'
                        } else {
                            this.openBottomSheet()
                        }
                    }
                );
            }
        } else {
            this.aolInteraction === ''
            this.apivujService.getWFSFeature(
                `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:municipio&count=1&outputFormat=application/json&cql_filter=CONTAINS(geom, POINT (${clickCoord32614[0]} ${clickCoord32614[1]}))`
            ).subscribe(response => {
                if (response.numberMatched === 1) {
                    let idActualIngresado = response.features[0].properties.id.toString().replace(/-/g, " ")
                    let i = FuseUtils.filterArrayByString(this.municipios, idActualIngresado);
                    this.municipio = i[0];
                    this.municipioId = response.features[0].properties.id
                    this.municipioSelected(true)
                }
            });
        }
    }

    addActive() {
        this.barraOpen = true;
        // this._renderer.addClass(this.barraLateral.nativeElement, 'active');
    }

    removeActive() {
        this.barraOpen = false;
        //  this._renderer.removeClass(this.barraLateral.nativeElement, 'active');
    }

    getDatosEscuelas(coord): void {
        this.apivujService.getWFSFeature(
            `${environment.SERVER_ORIGIN_MAPA_JALISCO}/geos/vector/geoserver/ows?service=WFS&request=GetFeature&version=2.0.0&typename=vjal:r101&outputFormat=application/json&cql_filter=WITHIN(geom, ${coord})`
        ).subscribe(response => {
            response.numberMatched > 0 ? this.datosPredio.escuelas = response : this.datosPredio.escuelas = []
        }, error => {
            this.datosPredio.escuelas = []
        });
    }

    getDatosCentrosSalud(coord): void {
        this.apivujService.getWFSFeature(
            `${environment.SERVER_ORIGIN_MAPA_JALISCO}/geos/vector/geoserver/ows?service=WFS&request=GetFeature&version=2.0.0&typename=vjal:r102&outputFormat=application/json&cql_filter=WITHIN(geom, ${coord})`
        ).subscribe(response => {
            response.numberMatched > 0 ? this.datosPredio.centros_salud = response : this.datosPredio.centros_salud = []
        }, error => {
            this.datosPredio.centros_salud = []
        });
    }

    getDatosEdificiosGobierno(coord): void {
        this.apivujService.getWFSFeature(
            `${environment.SERVER_ORIGIN_MAPA_JALISCO}/geos/vector/geoserver/ows?service=WFS&request=GetFeature&version=2.0.0&typename=vjal:r104&outputFormat=application/json&cql_filter=WITHIN(geom, ${coord})`
        ).subscribe(response => {
            response.numberMatched > 0 ? this.datosPredio.edificios_gobierno = response : this.datosPredio.edificios_gobierno = []
        }, error => {
            this.datosPredio.edificios_gobierno = []
        });
    }

    getDatosLocalidad(centroide): void {
        this.apivujService.getWFSFeature(
            `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:localidades&outputFormat=application/json&cql_filter=CONTAINS(geom, POINT (${centroide[0]} ${centroide[1]}))`
        ).subscribe(response => {
            response.numberMatched > 0 ? this.datosPredio.localidad = response.features[0].properties : this.datosPredio.localidad = {}
        }, error => {
            this.datosPredio.denue = []
        });
    }

    getDatosColonia(centroide): void {
        this.apivujService.getWFSFeature(
            `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:colonias&outputFormat=application/json&cql_filter=CONTAINS(geom, POINT (${centroide[0]} ${centroide[1]}))`
        ).subscribe(response => {
            response.numberMatched > 0 ? this.datosPredio.colonia = response.features[0].properties : this.datosPredio.colonia = {}
        }, error => {
            this.datosPredio.denue = []
        });
    }

    getDatosDenue(coord): void {
        this.apivujService.getWFSFeature(
            `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:denue&outputFormat=application/json&cql_filter=WITHIN(geom, ${coord})`
        ).subscribe(response => {
            response.numberMatched > 0 ? this.datosPredio.denue = response : this.datosPredio.denue = []
        }, error => {
            this.datosPredio.denue = []
        });
    }

    getNivelImpacto(coord): void {
        this.apivujService.getWFSFeature(
            `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typeNames=VUJ:nivel_impacto_32614&outputFormat=application/json&cql_filter=INTERSECTS(geom, ${coord})`
        ).subscribe(response => {
            if (response.numberMatched > 0) {
                response.features.forEach(feat => {
                    if (feat.properties.nivel_impacto > this.datosPredio.nivel_impacto) {
                        this.datosPredio.nivel_impacto = feat.properties.nivel_impacto
                    }
                })
            }
        }, error => {
            this.datosPredio.nivel_impacto = 0
        });
    }

    getDatosEspacioPublico(coord): void {
        this.apivujService.getWFSFeature(
            `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:espaciopublico&outputFormat=application/json&cql_filter=WITHIN(geom, ${coord})`
        ).subscribe(response => {
            response.numberMatched > 0 ? this.datosPredio.espacio_publico = response.features : this.datosPredio.espacio_publico = []
        }, error => {
            this.datosPredio.espacio_publico = []
        });
    }

    getDatosZoniciacion(coord): void {
        this.apivujService.getWFSFeature(
            `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:planparcialdesarrollourbano32614&outputFormat=application/json&cql_filter=INTERSECTS(geom, ${coord})`
        ).subscribe(response => {
            let features
            if (response.numberReturned > 0) {
                features = response.features
                features.forEach((feat, idx) => {
                    this.apivujService.getWFSFeature(
                        `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/uso-suelo/?municipio=${feat.properties.municipio_id}&clave=${feat.properties.clave_de_zonificacion_primaria}&distrito=${feat.properties.distrito}`
                    ).subscribe(usoSueloResponse => {
                        if (usoSueloResponse.length > 0) {
                            features[idx].properties.detalles_uso_suelo = usoSueloResponse[0].filter(([k, v]) => v != null)
                        }
                    })
                })
                this.datosPredio.zonificacion = features
            }
        }, error => {
            this.datosPredio.zonificacion = []
        });
    }


    getDatosCuerposAgua(coord): void {
        this.apivujService.getWFSFeature(
            `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:cuerposagua&outputFormat=application/json&cql_filter=DWITHIN(geom, ${coord}, 25, meters)`
        ).subscribe(response => {
            response.numberMatched > 0 ? this.datosPredio.cuerpos_agua = response : this.datosPredio.cuerpos_agua = {}
        }, error => {
            this.datosPredio.cuerpos_agua = {}
        });
    }

    getDatosConstruccion(coord): void {
        this.apivujService.getWFSFeature(
            `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:construccion2&outputFormat=application/json&cql_filter=WITHIN(geom, ${coord})`
        ).subscribe(response => {
            this.construccionCoords = [];
            if (response.numberMatched > 0) {
                let turffeatpolydissolve = turf.dissolve(turf.dissolve(response))
                turffeatpolydissolve['numberMatched'] = turffeatpolydissolve.features.length
                turffeatpolydissolve['numberReturned'] = turffeatpolydissolve.features.length
                turffeatpolydissolve['totalFeatures'] = turffeatpolydissolve.features.length
                const bloques = [];
                turffeatpolydissolve.features.forEach(feat => {
                    const featpoly = [];
                    feat.geometry.coordinates[0].forEach(coord => {
                        featpoly.push(proj4('EPSG:32614', 'EPSG:4326', coord));
                    });
                    this.datosPredio.area_construccion += turf.area(turf.polygon([featpoly]));
                    bloques.push(featpoly);
                });
                this.construccionCoords = bloques;
            }
            //this.tramiteList.nativeElement.style.cssText = 'display:inline';
        }, error => {
            this.datosPredio.area_construccion = 0
        });
    }

    getDomicilioGoogleMaps(centroide): void {
        this.apivujService.getReverseGeocode(centroide[1], centroide[0]).subscribe(domicilio => {
          this.datosPredio.domicilio = {}
          this.datosPredio.calle = ""
          if (domicilio) {
            const addr = domicilio;
            const formaddr = addr.formatted_address.split(",")[0];
            this.datosPredio.domicilio = domicilio;
            this.datosPredio.calle = formaddr;
          }
        });
      }

    getDomicilioGoogleMaps2(centroide): void {
        this.apivujService.getGoogleMapsGeo(
            `https://maps.googleapis.com/maps/api/geocode/json?latlng=${centroide[1]},${centroide[0]}&key=00101001010`
        ).subscribe(domicilio => {
            this.datosPredio.domicilio = {}
            this.datosPredio.calle = ""
            if (domicilio.results.length > 0) {
                const addr = domicilio.results[0]
                const formaddr = addr.formatted_address.split(",")[0];
                this.datosPredio.domicilio = domicilio.results[0];
                this.datosPredio.calle = formaddr;
            }
        });
    }


    comercialPoligonoTerminado(event): void {
        let geojsonDenue = []
        let filtroActividades;
        this.girosSeleccionados.length > 0 ? filtroActividades = `AND actividad_economica_id IN (${this.girosSeleccionados})` : filtroActividades = ""
        let cqlFilter = "";
        const denuebaseurl = `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:denue&outputFormat=application/json&srsName=EPSG:4326&cql_filter=`
        const municipiosbaseurl = `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:municipio&outputFormat=application/json&cql_filter=`

        if (this.aolInteraction === 'dibujar_analisis_caja' || this.aolInteraction === 'dibujar_analisis_poligono') {
            const dibujoCoordenadas = event.feature.getGeometry().getCoordinates()
            const extent = event.feature.getGeometry().getExtent()
            const area = (event.feature.getGeometry().transform('EPSG:4326', 'EPSG:3857').getArea() / 1000).toFixed(2) + ' km';
            const dibujoCoordenadas32614 = [];
            dibujoCoordenadas[0].forEach(val => {
                dibujoCoordenadas32614.push(proj4('EPSG:4326', 'EPSG:32614', val));
            });

            const poligono32614 = 'POLYGON (' +
                [dibujoCoordenadas32614].map((ring) => {
                    return '(' + ring.map((p) => {
                        return p[0] + ' ' + p[1];
                    }).join(', ') + ')';
                }).join(', ') + ')';
            cqlFilter = `INTERSECTS(geom, ${poligono32614}) ${filtroActividades}`
            this.denueFilter = cqlFilter
            let wfsurl = `${denuebaseurl}${cqlFilter}`
            this.apivujService.getWFSFeature(wfsurl).subscribe(response => {
                geojsonDenue = response
                this.capaAnalisisComercial = response
                this.capaAnalisisComercialURL = wfsurl
                this.apivujService.getWFSFeature(municipiosbaseurl + `INTERSECTS(geom, ${poligono32614})`).subscribe(munres => {
                    let municipios = []
                    munres.features.forEach(mun => municipios.push(mun.properties.nombre))
                    let municipios64 = btoa(municipios.join("|"))
                    setTimeout(() => {
                        this.aolInteraction = 'seleccionar_predio';
                        const minimapaurl = `${environment.SERVER_ORIGIN_GEO_SERVER}/wms?service=WMS&version=1.3.0&request=GetMap&FORMAT=image/png8&layers=VUJ:MapaJaliscoMapaBase,VUJ:catastro,VUJ:denue&exceptions=application/vnd.ogc.se_inimage&CRS=EPSG:4326&width=600&height=600&styles&cql_filter=INCLUDE;INCLUDE;${cqlFilter}&BBOX=${extent[1]},${extent[0]},${extent[3]},${extent[2]}`
                        this.router.navigate([]).then(result => {
                            window.open(`${environment.SERVER_ORIGIN}comercial/${btoa(wfsurl)}/${btoa(minimapaurl)}/${municipios64}/${btoa(area)}/${btoa(this.nombresGirosSeleccionados.join("|"))}`, '_blank');
                        });
                    }, 300);
                })
            }, error => {
                // nada
            });
        } else {
            const circulo = event.feature.getGeometry()
            const extent = circulo.getExtent()
            let poligonocirculo = ol.geom.Polygon.fromCircle(circulo, 20)
            const centro4326 = proj4('EPSG:4326', 'EPSG:32614', circulo.getCenter())
            circulo.transform('EPSG:4326', 'EPSG:3857');
            const radio = circulo.getRadius()
            const area = ((3.1416 * (radio) * (radio)) / 1000).toFixed(2) + ' km';
            let poligonocirculo32614 = []
            poligonocirculo.getCoordinates()[0].forEach(val => {
                poligonocirculo32614.push(proj4('EPSG:4326', 'EPSG:32614', val))
            })
            const wktpoligonocirculo32614 = 'POLYGON (' +
                [poligonocirculo32614].map((ring) => {
                    return '(' + ring.map((p) => {
                        return p[0] + ' ' + p[1];
                    }).join(', ') + ')';
                }).join(', ') + ')';
            circulo.transform('EPSG:3857', 'EPSG:4326');
            const centrowkt32614 = `POINT (${centro4326[0]} ${centro4326[1]})`
            cqlFilter = `DWITHIN(geom, ${centrowkt32614}, ${radio - 20}, meters) ${filtroActividades}`
            this.denueFilter = cqlFilter
            let wfsurl = `${denuebaseurl}${cqlFilter}`
            this.apivujService.getWFSFeature(wfsurl).subscribe(response => {
                geojsonDenue = response
                this.capaAnalisisComercial = response
                this.capaAnalisisComercialURL = wfsurl
                this.apivujService.getWFSFeature(municipiosbaseurl + `INTERSECTS(geom, ${wktpoligonocirculo32614})`).subscribe(munres => {
                    let municipios = []
                    munres.features.forEach(mun => municipios.push(mun.properties.nombre))
                    let municipios64 = btoa(municipios.join("|"))
                    setTimeout(() => {
                        this.aolInteraction = 'seleccionar_predio'; // para terminar de dibujar, dejar de hacer interactivo
                        const minimapaurl = `${environment.SERVER_ORIGIN_GEO_SERVER}/wms?service=WMS&version=1.3.0&request=GetMap&FORMAT=image/png8&layers=VUJ:MapaJaliscoMapaBase,VUJ:catastro,VUJ:denue&exceptions=application/vnd.ogc.se_inimage&CRS=EPSG:4326&width=600&height=600&styles&cql_filter=INCLUDE;INCLUDE;${cqlFilter}&BBOX=${extent[1]},${extent[0]},${extent[3]},${extent[2]}`
                        window.open(`${environment.SERVER_ORIGIN}comercial/${btoa(wfsurl)}/${btoa(minimapaurl)}/${municipios64}/${btoa(area)}/${btoa(this.nombresGirosSeleccionados.join("|"))}`);
                    }, 300);
                })
            }, error => {
                // nada
            });
        }
    }

    dibujoTerminado(event): void {
        this.datosPredio.area_construccion = 0;
        this.datosPredio.nivel_impacto = 0;
        this.barraIOpen = false;
        this.pasoT = 1;
        const polygon = turf.polygon(event.feature.getGeometry().getCoordinates())
        const centroide = turf.centroid(polygon);
        this.datosPredio.area_predio = turf.area(polygon);

        this.predioCoords = event.feature.getGeometry().getCoordinates()[0]
        const centroide32614 = proj4('EPSG:4326', 'EPSG:32614', centroide.geometry.coordinates);
        const poly32614 = []
        event.feature.getGeometry().getCoordinates()[0].forEach(val => {
            poly32614.push(proj4('EPSG:4326', 'EPSG:32614', val))
        })

        const polyPredioWKT32614 = 'POLYGON (' +
            [poly32614].map((ring) => {
                return '(' + ring.map((p) => {
                    return p[0] + ' ' + p[1];
                }).join(', ') + ')';
            }).join(', ') + ')';

        this.getDatosEscuelas(polyPredioWKT32614)
        this.getDatosCentrosSalud(polyPredioWKT32614)
        this.getDatosEdificiosGobierno(polyPredioWKT32614)
        this.getDatosLocalidad(centroide32614)
        this.getDatosColonia(centroide32614)
        this.getDatosDenue(polyPredioWKT32614)
        this.getDatosEspacioPublico(polyPredioWKT32614)
        this.getDatosCuerposAgua(polyPredioWKT32614)
        this.getDomicilioGoogleMaps(centroide.geometry.coordinates)
        this.getDatosConstruccion(polyPredioWKT32614)
        this.getNivelImpacto(polyPredioWKT32614)
        // this.getDatosZoniciacion(polyPredioWKT32614)

        this.cargandoMunicipio = true;
        this.apivujService.getRequisitosMunicipio(this.municipio.id).subscribe((response: any) => {
            response.data.forEach(element => {
                this.datosDinamicosActual[element.name] = element;
                if (element.requerido == 3) {
                    this.datosDinamicosActual[element.name].visible = false;
                }
            });
            this.cargandoMunicipio = false;
            this.camposDiamicosMunicipio = response.data;
        }, error => {
            this.cargandoMunicipio = false;
        });

        setTimeout(() => {
            const bbox = turf.bbox(turf.buffer(polygon, 50, {units: 'meters'}))
            this.aolview.instance.fit(bbox, {
                duration: 500,
                padding: [0, 0, 0, 300]
            });
            this.datosPredio.url_minimapa = `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WMS&version=1.3.0&request=GetMap&FORMAT=image/png8&layers=VUJ:MapaJaliscoMapaBase,VUJ:catastro,VUJ:registrotramite&exceptions=application/vnd.ogc.se_inimage&CRS=EPSG:4326&width=600&height=600&styles=,,VUJ:Predio&cql_filter=INCLUDE;INCLUDE;id=DIBUJOID&BBOX=${bbox[1]},${bbox[0]},${bbox[3]},${bbox[2]}`
            this.datosPredio.url_uso = `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WMS&version=1.3.0&request=GetMap&FORMAT=image/png8&layers=VUJ:planparcialdesarrollourbano32614,VUJ:MapaJaliscoMapaBase,VUJ:catastro,VUJ:registrotramite&exceptions=application/vnd.ogc.se_inimage&CRS=EPSG:4326&width=600&height=350&styles=,,,VUJ:Predio&cql_filter=INCLUDE;INCLUDE;INCLUDE;id=DIBUJOID&BBOX=${bbox[1]},${bbox[0]},${bbox[3]},${bbox[2]}`
            this.datosPredio.url_zip =    `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:construcciones,VUJ:planimetria_predio2,VUJ:manzanas&outputFormat=shape-zip&format_options=filename:extract.zip&cql_filter=BBOX(geom,${bbox[0]},${bbox[1]},${bbox[2]},${bbox[3]},%27EPSG:4326%27)`
                                   
            console.log(this.datosPredio.url_zip );
            this.dibujoPredio.instance.clear()
            this.barraIOpen = true
            this.iniciarTramite();
        }, 500);
    }

    private _filter(nombre: string): Municipio[] {
        const filterValue = this.clearF(nombre);
        return this.municipios.filter(option => this.clearF(option.nombre).includes(filterValue));
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


