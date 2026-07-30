import {Component, Inject, OnInit, Output, EventEmitter, ViewChild} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import {RolesService} from 'app/services/administrador/roles/roles.service';
import {CapaMapa} from '../../../../models/administrador/capaMapa.model';
import Swal from 'sweetalert2';
import {LocalStorageService} from "@shared/services/storage.service";
import {MapaService} from "../../../../services/mapa/mapa.service";
import * as xml2js from 'xml2js';
import {Observable} from 'rxjs';
import {map, startWith} from 'rxjs/operators';

@Component({
    templateUrl: './capas-municipio-dialog.component.html',
    styleUrls: ['./capas-municipio-dialog.component.scss']
})
export class CapasMunicipioDialogComponent implements OnInit {

    public capaMapa: CapaMapa;
    public capaForm: FormGroup;
    public action: string;
    public dialogTitle: string;

    formTipoWMS: boolean = false;
    wmsURLOK = false;
    wmsLayers: any[] = [];
    wmsFormats: string[] = [];
    wmsLayerProjections: string[] = [];
    selectedLayer: object = {};

    layersFilteredOptions: Observable<string[]>;
    formatsFilteredOptions: Observable<string[]>;

    constructor(public matDialogRef: MatDialogRef<CapasMunicipioDialogComponent>,
                @Inject(MAT_DIALOG_DATA) private _data: any,
                private _formBuilder: FormBuilder,
                private roleService: RolesService,
                private _store: LocalStorageService,
                private _mapaService: MapaService) {
        this.action = _data.action;

        if (this.action === 'edit') {
            this.dialogTitle = 'Editar capa';
            this.capaMapa = _data.data;
        } else {
            this.dialogTitle = 'Agregar capa';
            this.capaMapa = new CapaMapa({});
        }

        this.capaForm = this.createCapaForm('xyz');
    }

    wmsImageFormats() {
        const formatosSoportados = ['image/png', 'image/gif', 'image/gif;subtype=animated', 'image/png8',
            'image/png; mode=8bit', 'image/svg', 'image/svg xml', 'image/svg+xml', 'image/vnd.jpeg-png',
            'image/vnd.jpeg-png8']
        return this.wmsFormats.filter(val => formatosSoportados.includes(val))
    }

    layerChanged(idx) {
        if (this.capaForm.value.type === 'wms') {
            console.log("this.wmsLayers[idx]", this.wmsLayers[idx])
            if (this.capaForm.value.version === '1.1.1') {
                this.wmsLayerProjections = this.wmsLayers[idx].SRS
            }
            if (this.capaForm.value.version === '1.3.0') {
                this.wmsLayerProjections = this.wmsLayers[idx].CRS
            }
        } else {
            this.wmsLayerProjections = []
        }
    }

    wmsVersionChanged() {
        this.capaForm.patchValue({
            url: undefined,
            layers: undefined,
            format: undefined,
            projection: undefined,
        })
    }

    urlChanged(e) {
        if (this.capaForm.value.url) {
            let url = this.capaForm.value.url

            if (this.capaForm.value.type === 'wms') {

                const regex = new RegExp('https?:\\/\\/(www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b([-a-zA-Z0-9()@:%_\\+.~#?&//=]*)');

                // if (this.capaForm.value.url.match(regex) && (url.endsWith('wms61') || url.endsWith('wms61?') || url.endsWith('wms') || url.endsWith('ows') || url.endsWith('wms?') || url.endsWith('ows?'))) {
                if (this.capaForm.value.url.match(regex) && this.capaForm.value.url.length > 20) {
                    if (!url.endsWith('?') && !url.endsWith('/')) {
                        url = url + '?'
                    }
                    let jsonRoot;
                    this.capaForm.value.version === '1.3.0' ? jsonRoot = 'WMS_Capabilities' : jsonRoot = 'WMT_MS_Capabilities'
                    const wmsURl = url + `service=wms&version=${this.capaForm.value.version}&request=GetCapabilities`
                    this._mapaService.getWMSCapabilities(wmsURl).subscribe(response => {
                        // console.log("response", response)
                        const xml2jsparser = new xml2js.Parser();
                        xml2jsparser.parseStringPromise(response).then(result => {
                            if (result[jsonRoot].Service && result[jsonRoot].Service.length > 0) {
                                if (result[jsonRoot].Service[0].Name[0].toUpperCase().includes('WMS')) {
                                    console.log("WMS OK")
                                    this.wmsURLOK = true

                                } else {
                                    console.log("WMS MAL")
                                    this.wmsURLOK = false
                                }
                            }
                            this.wmsLayers = result[jsonRoot].Capability[0].Layer[0].Layer
                            this.wmsFormats = result[jsonRoot].Capability[0].Request[0].GetMap[0].Format
                            console.log("this.wmsFormats", this.wmsFormats)
                            console.log("result", result)
                        }).catch(function (error) {
                            // Failed err
                            this.wmsLayers = []
                            this.wmsFormats = []
                            this.wmsProjections = []
                            this.selectedLayer = {}
                            //console.log("error", error)
                        });
                    }, error => {
                    })
                } else {
                    this.wmsLayers = []
                    this.wmsFormats = []
                    this.wmsLayerProjections = []
                    this.selectedLayer = {}
                }
            }
        }
    }

    createCapaForm(type): FormGroup {
        if (!this.capaMapa.type.length) {
            this.capaMapa.type = type
        }

        return this._formBuilder.group({
            id: [this.capaMapa.id],
            value: [this.capaMapa.value, [Validators.required, Validators.pattern('^[a-zA-Z]+$')]],
            label: [this.capaMapa.label, Validators.required],
            type: [this.capaMapa.type, Validators.required],
            url: [this.capaMapa.url, [Validators.required, Validators.pattern('https?:\\/\\/(www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b([-a-zA-Z0-9()@:%_\\+\{\}.~#?&//=]*)')]],
            attribution: [this.capaMapa.attribution, Validators.required],
            order: [this.capaMapa.order, Validators.required],
            opacity: [this.capaMapa.opacity],
            version: [this.capaMapa.version],
            visible: [this.capaMapa.visible],
            layers: [this.capaMapa.layers],
            projection: [this.capaMapa.projection],
            format: [this.capaMapa.format],
        });
    }

    ngOnInit() {
        this.dialogTitle = this._data.dialogTitle || '';
        if (this._data.action == 'new') {
            // algo
        } else {
            //this.modelCapa = this._data.role;
        }
    }

    agregarCapa(): void {
        let datosCapa = this.capaForm.value
        let user = this._store.get('usr')
        datosCapa.owner = 1
        datosCapa.municipality = [user.id_municipio]

        if (datosCapa.type === 'xyz') {
            datosCapa.layers = "xyz"
            datosCapa.projection = "xyz"
            datosCapa.format = "xyz"
            datosCapa.version = "1.0"
        }
        this.matDialogRef.close(this.capaForm)

        this._mapaService.agregarCapaMunicipio(datosCapa).subscribe(response => {
            setTimeout(() => {
                Swal.fire({
                    title: 'Exito!',
                    text: 'Guardado Correctamente!',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                });
            }, 1000)
        })
    }

    actualizarCapa(data): void {
        let datosCapa = this.capaForm.value
        let user = this._store.get('usr')
        datosCapa.owner = 1

        datosCapa.municipality = [user.id_municipio]

        if (datosCapa.type === 'xyz') {
            datosCapa.layers = "xyz"
            datosCapa.projection = "xyz"
            datosCapa.format = "xyz"
            datosCapa.version = "1.0"
        }

        this.matDialogRef.close(this.capaForm)

        this._mapaService.actualizarCapaMunicipio(datosCapa).subscribe(response => {
            Swal.fire({
                title: 'Exito!',
                text: 'Guardado Correctamente!',
                icon: 'success',
                confirmButtonText: 'Ok'
            });
        })
    }

    typeLayerChanged(event) {
        this.capaForm.patchValue({
            url: undefined,
            layers: undefined,
            format: undefined,
            projection: undefined,
        })
    }

    saveRole() {
        if (this.action == 'edit') {
            this.roleService.storeRoles(this.capaForm.value)
                .subscribe(resp => {
                    Swal.fire({
                        title: 'Exito!',
                        text: 'Guardado Correctamente!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
                })
        } else {

            this.roleService.storeRoles(this.capaForm.value)
                .subscribe(resp => {
                    Swal.fire({
                        title: 'Exito!',
                        text: 'Guardado Correctamente!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
                })
        }
    }
}
