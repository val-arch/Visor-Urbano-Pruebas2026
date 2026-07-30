import {Component, ElementRef, OnInit, QueryList, ViewChild, ViewChildren} from '@angular/core';
import {CapaMapa} from '../../../models/mapa/CapaMapa';
import {environment} from "@env/environment";
import {fuseAnimations} from '@fuse/animations';
// @ts-ignore
import proj4 from 'proj4';
import * as ol from "openlayers";
import {LocalStorageService} from "@shared/services/storage.service";
import {MapaService} from "../../../services/mapa/mapa.service";
import {MatDialog} from "@angular/material/dialog";
import {Observable} from "rxjs";
import Swal from 'sweetalert2';

@Component({
    selector: 'app-impacto',
    templateUrl: './impacto.component.html',
    styleUrls: ['./impacto.component.scss'],
    animations: fuseAnimations
})
export class ImpactoComponent implements OnInit {
    mapZoom: number = 8;
    WMSInfo = {mostrar: false, contenido: []};
    capasMunicipio: Observable<CapaMapa[]>;
    aolInteraction: string = '';
    municipioCoords = [];
    coord;
    isLoading: boolean = false;
    panelHerramientas = false;
    herramientaActiva: string = 'capas'
    popUp: ol.Overlay;
    impactoWFSURL: string;
    vectorSourceNivelImpacto: any;
    vectorLayerNivelImpacto: any;
    deleteSelect: ol.interaction.Select = new ol.interaction.Select();
    modifySelect: ol.interaction.Select = new ol.interaction.Select();
    nuevoPoligono: any;
    editarPoligono: any;
    snap: any;

    refreshkey: number = 0;

    nivelImpacto1 = new ol.style.Style({
        //fill: new ol.style.Fill({color: '#1a964144'}),
        stroke: new ol.style.Stroke({color: '#fff', width: 3, lineDash: [4, 8]}),
    })
    nivelImpacto2 = new ol.style.Style({
        //fill: new ol.style.Fill({color: '#8acc6244'}),
        stroke: new ol.style.Stroke({color: '#fff', width: 3, lineDash: [4, 8]}),
    })
    nivelImpacto3 = new ol.style.Style({
        //fill: new ol.style.Fill({color: '#ffdf9a44'}),
        stroke: new ol.style.Stroke({color: '#fff', width: 3, lineDash: [4, 8]}),
    })
    nivelImpacto4 = new ol.style.Style({
        //fill: new ol.style.Fill({color: '#f6905344'}),
        stroke: new ol.style.Stroke({color: '#fff', width: 3, lineDash: [4, 8]}),
    })
    nivelImpacto5 = new ol.style.Style({
        //fill: new ol.style.Fill({color: '#d7191c44'}),
        stroke: new ol.style.Stroke({color: '#fff', width: 3, lineDash: [4, 8]}),
    })
    nivelImpacto0 = new ol.style.Style({
        //fill: new ol.style.Fill({color: '#00000044'}),
        stroke: new ol.style.Stroke({color: '#70ce68', width: 3, lineDash: [4, 8]}),
    })

    @ViewChild('impactoFeaturesMunicipio') impactoFeaturesMunicipio: any;
    @ViewChild('impactoSourceVector') impactoSourceVector: any;
    @ViewChild('impactoMap') impactoMap: any;
    @ViewChild('impactoView') impactoView: any;
    @ViewChild('impactoInfoPopUp') impactoInfoPopUp: ElementRef;
    @ViewChild('impactoGeoJSON') impactoGeoJSON: ElementRef;
    @ViewChildren('SourceWMS') SourceWMS: QueryList<any>;
    geoserver_url = environment.SERVER_ORIGIN_GEO_SERVER

    constructor(
        private _store: LocalStorageService,
        private _mapaService: MapaService,
        private _matDialog: MatDialog,
    ) {
    }

    refreshLayers() {
        let layers = this.impactoMap.instance.getLayers()
        layers.forEach(layer => {
            try {
                let params = layer.getSource().getParams()
                if (params.LAYERS === 'VUJ:nivel_impacto') {
                    this.refreshkey += 1;
                    params.updated = this.refreshkey
                    layer.getSource().updateParams(params);
                    layer.getSource().refresh();
                }
            } catch (error) {
            }
        })
        this.vectorSourceNivelImpacto.clear()
    }

    capasMunicipioChange(): void {
        setTimeout(() => {
            this.refreshLayers()
        }, 300)
    }

    ngOnInit(): void {
        let user = this._store.get('usr')
        //this.impactoWFSURL = `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:nivel_impacto&outputFormat=application/json&cql_filter=municipio_id=${user.id_municipio}`
        this.impactoWFSURL = `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/nivel-impacto-geom/?municipio=${user.id_municipio}`

        proj4.defs('EPSG:32614', '+proj=utm +zone=13 +ellps=WGS84 +datum=WGS84 +units=m +no_defs');
        proj4('EPSG:32614');

        this.getData()

        this.vectorSourceNivelImpacto = new ol.source.Vector({
            format: new ol.format.GeoJSON(),
            url: this.impactoWFSURL,
        });
        this.vectorLayerNivelImpacto = new ol.layer.Vector({
            source: this.vectorSourceNivelImpacto,
            zIndex: 2000,
            style: (feature) => {
                const nivel_impacto = feature.get('nivel_impacto')
                return nivel_impacto === 1 ? this.nivelImpacto1 : nivel_impacto === 2 ? this.nivelImpacto1
                    : nivel_impacto === 3 ? this.nivelImpacto3 : nivel_impacto === 4 ? this.nivelImpacto4
                        : nivel_impacto === 5 ? this.nivelImpacto5 : this.nivelImpacto0
            },
        });
        this.nuevoPoligono = new ol.interaction.Draw({
            type: 'Polygon',
            source: this.vectorLayerNivelImpacto.getSource()
        });
        this.editarPoligono = new ol.interaction.Modify({
            features: this.modifySelect.getFeatures()
        });
        this.snap = new ol.interaction.Snap({source: this.vectorLayerNivelImpacto.getSource()});
        setTimeout(() => {
            this.impactoMap.instance.addLayer(this.vectorLayerNivelImpacto)
        }, 200)

        this.nuevoPoligono.on('drawend', (evt) => {
            const coord = evt.feature.getGeometry().getCoordinates()
            const nuevoPoligono = {
                "nivel_impacto": 0,
                "municipio": user.id_municipio,
                "geom": {
                    "type": "Polygon",
                    "coordinates": coord
                }
            }
            Swal.fire({
                title: 'Introduzca el nivel de impacto',
                input: 'radio',
                inputOptions: {1: "Mínimo", 2: "Bajo", 3: "Medio", 4: "Alto", 5: "Máximo"},
                inputAttributes: {autocapitalize: 'off'},
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Agregar',
                showLoaderOnConfirm: true,
                preConfirm: (val) => {
                    const valorImpacto = parseInt(val)
                    nuevoPoligono.nivel_impacto = valorImpacto
                    return this._mapaService.nuevoNivelImpacto(nuevoPoligono).subscribe(
                        response => {
                        }, error => {
                            Swal.showValidationMessage(
                                `No se guardó el poligono`
                            )
                        }
                    );
                },
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    this.refreshLayers()
                    Swal.fire({
                        title: `Polígono guardado con éxito`,
                    })
                } else {
                    this.vectorSourceNivelImpacto.clear()
                    Swal.fire({
                        icon: 'error',
                        // @ts-ignore
                        title: `No se guardó el poligono`,
                        confirmButtonText: 'Cerrar'
                    })
                }
            })
        })

        this.deleteSelect.on('select', (select) => {
            const id = select.selected[0].c
            if (id) {
                Swal.fire({
                    title: '¿Está seguro que quiere eliminar este polígono?',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: `Sí, eliminar`,
                    confirmButtonColor: '#d14529',
                    denyButtonText: 'Cancelar',
                    denyButtonColor: '#2778c4',
                    preConfirm: () => {
                        let eliminarPoligono = new Promise((resolve) => {
                            resolve(this._mapaService.eliminarNivelimpacto(id).subscribe())
                        })
                        eliminarPoligono.then(() => {
                            Swal.fire('Polígono eliminado!', '', 'success').then(() => {
                                this.deleteSelect.getFeatures().clear()
                                this.refreshLayers()
                            })
                        }).catch(error => {
                        })
                    },
                }).then((result) => {
                    if (result.isDenied) {
                        Swal.fire('Polígono sin cambios', '', 'info').then(() => {
                            this.deleteSelect.getFeatures().clear()
                        })
                    }
                })
            } else {
                this.deleteSelect.getFeatures().clear()
            }
        })

        this.editarPoligono.on('modifyend', (evt) => {
            // console.log('editarPoligono modifyend', evt)
        })

        this.modifySelect.on('select', (select) => {
            if (select.selected.length > 0) {
                if (!select.selected[0].c || select.deselected.length > 0) {
                    this.modifySelect.getFeatures().clear()
                }
            }

            if (select.deselected.length > 0) {
                select.deselected.forEach(feat => {
                    //console.log("deselected feat", feat)
                    const coord = feat.getGeometry().getCoordinates()
                    const id = feat.c
                    const objFeature = {
                        "id": id,
                        "nivel_impacto": feat.O.nivel_impacto,
                        "municipio": feat.O.municipio,
                        "geom": {
                            "type": "Polygon",
                            "coordinates": coord
                        }
                    }

                    Swal.fire({
                        title: 'Seleccione nivel de impacto',
                        input: 'radio',
                        inputValue: feat.O.nivel_impacto,
                        inputOptions: {1: "Mínimo", 2: "Bajo", 3: "Medio", 4: "Alto", 5: "Máximo"},
                        inputAttributes: {autocapitalize: 'off'},
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        confirmButtonText: 'Guardar',
                        showLoaderOnConfirm: true,
                        preConfirm: (val) => {
                            const valorImpacto = parseInt(val)
                            objFeature.nivel_impacto = valorImpacto
                            return this._mapaService.actualizarNivelImpacto(objFeature).subscribe(
                                response => {
                                    // console.log("response actualizarNivelImpacto", response)
                                }, error => {
                                    Swal.showValidationMessage(
                                        `No se guardó el poligono`
                                    )
                                }
                            );
                        },
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let layers = this.impactoMap.instance.getLayers()
                            layers.forEach(layer => {
                                try {
                                    let params = layer.getSource().getParams()
                                    if (params.LAYERS === 'VUJ:nivel_impacto') {
                                        this.refreshkey += 1;
                                        params.updated = this.refreshkey
                                        console.log("VUJ:nivel_impacto")
                                        console.log("layer params", params)
                                        console.log(layer.getSource())
                                        layer.getSource().updateParams(params);
                                        layer.getSource().refresh();
                                    }
                                } catch (error) {
                                }
                            })
                            this.vectorSourceNivelImpacto.clear()
                            Swal.fire({
                                title: `Polígono guardado con éxito`,
                            })
                        } else {
                            this.vectorSourceNivelImpacto.clear()
                            Swal.fire({
                                icon: 'error',
                                // @ts-ignore
                                title: `No se guardó el poligono`,
                                confirmButtonText: 'Cerrar'
                            })
                        }
                    })


                })
            }

        })
    }

    onClickOverMap(event: any): void {
        const clickCoord4326 = event.coordinate;
        const clickCoord32614 = proj4('EPSG:4326', 'EPSG:32614', clickCoord4326);
        const resolution = this.impactoView.instance.getResolution()

        console.log("onClickOverMap", this.aolInteraction)

        if (this.aolInteraction === 'info') {
            this.WMSInfo = {mostrar: false, contenido: []};
            this.WMSInfo.mostrar = true
            let urlsInfo = []
            this.popUp = new ol.Overlay({
                element: this.impactoInfoPopUp.nativeElement,
                position: event.coordinate,
                autoPan: true,
                autoPanAnimation: {
                    source: event.coordinate
                }
            });
            this.impactoMap.instance.addOverlay(this.popUp);
            if (this.SourceWMS.length) {
                this.SourceWMS.forEach(source => {
                    const layersName = source.instance.getParams().LAYERS.split(":")
                    let nombreCapa
                    layersName.length === 2 ? nombreCapa = layersName[1] : nombreCapa = layersName[0]
                    const urlInfo = source.instance.getGetFeatureInfoUrl(clickCoord4326, resolution, 'EPSG:4326',
                        {'INFO_FORMAT': 'application/json', FEATURE_COUNT: 10})
                    urlsInfo.push([nombreCapa, urlInfo])
                })
                if (urlsInfo.length) {
                    urlsInfo.forEach(url => {
                        this._mapaService.getWFSFeature(url[1]).subscribe(response => {
                            if (response.numberReturned > 0 && this.WMSInfo.mostrar) {
                                // filtrar solo vectores

                                this.WMSInfo.contenido.push({nombre: url[0], datos: response})
                            }
                        })
                    })
                }
            }
        }
    }

    setHerramientaActiva(event) {
        this.herramientaActiva = event
        console.log("setHerramientaActiva", event)
    }

    setInteraction(event) {
        const user = this._store.get('usr')
        this.aolInteraction = event
        this.nuevoPoligono.setActive(false)
        this.editarPoligono.setActive(false)

        this.impactoMap.instance.removeInteraction(this.deleteSelect)
        this.impactoMap.instance.removeInteraction(this.nuevoPoligono)
        this.impactoMap.instance.removeInteraction(this.editarPoligono)
        this.impactoMap.instance.removeInteraction(this.snap)

        if (event === 'file_upload') {
            this.aolInteraction = ''
            Swal.fire({
                icon: 'info',
                html: `<div class="info-impacto">
                    <p>La función de subir permite subir datos vectoriales de los niveles de impacto.</p>
                    <p>Los datos a subir deben estar en formato <a href="https://geojson.org" target="_blank">GeoJSON</a> con el sistema de coordenadas de referencia (CRS) <a href="https://epsg.io/4326" target="_blank">EPSG:4326</a>.</p>
                    <p>La capa requiere ser de geometría de tipo Polígono, ningún otro tipo de geometría está permitido.</p>
                    <p>Es recomendable que la capa tenga un campo en sus atributos con el nombre de campo \'nivel_impacto\' con valores númericos en el rango [1, 5], los cuales cooresponden a los valores:</p>
                    <ol>
                    <li>Mínimo</li>
                    <li>Bajo</li>
                    <li>Medio</li>
                    <li>Alto</li>
                    <li>Máximo</li>
                    </ol>
                    <p>Cualquier otro valor será ignorado, y cualquier otra columna también.</p>
                    <p>Si su capa no cuenta con los valores o no están de acuerdo al criterio descrito arriba, puede aún actualizar los valores de los poligonos con la herramienta editar, pero debe hacerlo de uno a la vez.</p>
                    </div>`,
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Continuar',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
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
                            accept: 'application/geojson, application/json',
                        },

                        preConfirm: (file) => {
                            if (file) {
                                const reader = new FileReader;
                                reader.onloadend = (e) => {
                                    try {
                                        const fileJSON = JSON.parse(<string>e.target.result)
                                        if (fileJSON.type && fileJSON.type === 'FeatureCollection') {

                                            if (fileJSON.crs && fileJSON.features && fileJSON.name && fileJSON.type &&
                                                fileJSON.features.length > 0 && fileJSON.type === 'FeatureCollection' &&
                                                fileJSON.crs.properties.name === 'urn:ogc:def:crs:OGC:1.3:CRS84') {
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    text: 'El archivo seleccionado no es válido'
                                                })
                                            }

                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                text: 'El archivo seleccionado no es válido'
                                            })
                                        }
                                        let correctos = []
                                        let errores = []
                                        let c = 0
                                        fileJSON.features.forEach(feat => {
                                            console.log("feat", feat)
                                            c++
                                            if (feat.geometry.type === 'Polygon') {
                                                const nuevoPoligono = {
                                                    "nivel_impacto": feat.properties.nivel_impacto || 1,
                                                    "municipio": user.id_municipio,
                                                    "geom": {
                                                        "type": "Polygon",
                                                        "coordinates": feat.geometry.coordinates
                                                    }
                                                }
                                                this._mapaService.nuevoNivelImpacto(nuevoPoligono).subscribe(
                                                    response => {
                                                        correctos.push(response)
                                                        if (fileJSON.features.length === c) {
                                                            if (correctos.length > 0) {
                                                                Swal.fire({
                                                                    icon: 'success',
                                                                    text: `${correctos.length} elementos agregados correctamente.`,
                                                                }).then(() => {
                                                                    if (errores.length > 0) {
                                                                        Swal.fire({
                                                                            icon: 'error',
                                                                            text: `${correctos.length} elementos con errores no pudieron ser agregados.`,
                                                                        })
                                                                    }
                                                                }).then(() => {
                                                                    this.refreshLayers()

                                                                })
                                                            } else {
                                                                if (errores.length > 0) {
                                                                    Swal.fire({
                                                                        icon: 'error',
                                                                        text: `${correctos.length} elementos con errores no pudieron ser agregados.`,
                                                                    })
                                                                }
                                                            }
                                                        }
                                                    }, error => {
                                                        errores.push(error)
                                                    }
                                                )
                                            } else {
                                                errores.push('Geometría no es de tipo polígono')
                                            }
                                        })
                                    } catch (error) {
                                        Swal.fire({
                                            icon: 'error',
                                            text: `Error: ${error}`
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
                }
            })
        }

        if (event === 'refresh') {
            this.refreshLayers()
            this.aolInteraction = ''
        }

        if (event === 'eliminar') {
            this.impactoMap.instance.addInteraction(this.deleteSelect)
        }

        if (event === 'agregar') {
            this.editarPoligono.setActive(false)
            this.nuevoPoligono.setActive(true)
            this.impactoMap.instance.addInteraction(this.nuevoPoligono)
            this.impactoMap.instance.addInteraction(this.snap)
        }

        if (event === 'editar') {
            this.nuevoPoligono.setActive(false)
            this.editarPoligono.setActive(true)
            this.impactoMap.instance.addInteraction(this.modifySelect)
            this.impactoMap.instance.addInteraction(this.editarPoligono)
            this.impactoMap.instance.addInteraction(this.snap)
        }
    }

    dataTableKeyValue(data) {
        return Object.entries(data).map(val => {
            return {clave: val[0], valor: String(val[1])}
        })
    }

    quitarPopupInfo(): void {
        this.WMSInfo = {mostrar: false, contenido: []};
    }

    showNombreCapa(data) {
        return data.split(".")[0]
    }

    setImpactoLayer() {
        const user = this._store.get('usr')
        return {
            active: true,
            attribution: '',
            cql_filter: `municipio_id=${user.id_municipio}`,
            format: "image/png8",
            label: "Niveles de impacto",
            layers: "VUJ:nivel_impacto",
            opacity: 1,
            projection: "EPSG:4326",
            type: "wms",
            url: this.geoserver_url + "/wms",
            value: "nivel_impacto",
            version: "1.3.0",
            visible: true
        }
    }

    getCapasMunicipio() {
        this.capasMunicipio = undefined
        const user = this._store.get('usr')
        this._mapaService.getCapasMunicipio(user.id_municipio).subscribe(
            response => {
                const capas = response;
                capas.push(this.setImpactoLayer())
                this.capasMunicipio = capas
            }, error => {
                // console.log(error)
            }
        );
    }

    getData() {
        this.isLoading = true;
        let user = this._store.get('usr')
        this._mapaService.getGeomMunicipio(user.id_municipio).subscribe(
            municipio => {
                this.municipioCoords = [];
                this.isLoading = false;
                municipio.geometry.coordinates[0].forEach(val => {
                    this.municipioCoords.push(proj4('EPSG:32614', 'EPSG:4326', val));
                });
                const extent = this.impactoFeaturesMunicipio.instance.O.geometry.getExtent();
                setTimeout(() => {
                    this.impactoView.instance.fit(extent, {duration: 200});
                }, 150);
            },
            (error) => {
                this.isLoading = false;
            }
        )
        this.getCapasMunicipio();
    }

}
