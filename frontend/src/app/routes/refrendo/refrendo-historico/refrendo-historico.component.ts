import { ChangeDetectorRef, Component, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { ActivatedRoute, Router } from '@angular/router';
import { TokenService } from '@core/authentication/token.service';
import { FuseSplashScreenService } from '@fuse/services/splash-screen.service';
import { RevisionService } from 'app/routes/tramites/revision/services/revision.service';
import { CamposTramiteServiceService } from 'app/services/tramite/iniciar-tramite/campos-tramite-service.service';
import { ResumenService } from 'app/services/tramite/resumen.service';
import { LicenciaStatusService } from "app/services/licencia/licencia-status.service";
import Swal from 'sweetalert2';
import { EmitirRefrendoHistoricoComponent } from '../emitir-refrendo-historico/emitir-refrendo-historico.component';
import { Observable } from 'rxjs';
import { map, startWith } from 'rxjs/operators';
import { MapaService } from '../../../services/mapa/mapa.service';
import { FuseUtils } from '@fuse/utils';
import { MatChipInputEvent } from '@angular/material/chips';
import { COMMA, ENTER } from '@angular/cdk/keycodes';
import { environment } from '@env/environment';
import * as turf from "@turf/turf";
import proj4 from 'proj4';
import * as ol from "openlayers";

export interface User {
    SCIAN: string;
    codigo: string;
}

@Component({
    selector: 'app-refrendo-historico',
    templateUrl: './refrendo-historico.component.html',
    styleUrls: ['./refrendo-historico.component.scss']
})

export class RefrendoHistoricoComponent implements OnInit {
    myControl = new FormControl();
    code = new FormControl();
    options: string[] = ['One', 'Two', 'Three'];
    filteredOptions: Observable<any[]>;
    step1_visible = false;
    step2_visible = false;
    step3_visible = false;
    step4_visible = false;
    step1_completed = false;
    step2_completed = false;
    step3_completed = false;
    step4_completed = false;
    coordenadas_x = -99.23076;
    coordenadas_y = 18.9261;
    coordenadas_x_old;
    coordenadas_y_old;
    loading = true;
    mensaje = '';
    archivos: any;
    giro_desc = new FormControl();
    imageContainer = [];
    isExpanded1 = true;
    separatorKeysCodes: number[] = [ENTER, COMMA];
    isExpanded2 = false;
    isExpanded3 = false;
    submitted = false;
    formCompleted = false;
    id = null;
    step_actual = null;
    isExpanded4 = false;
    filterForm: FormGroup;
    filterForm_step2: FormGroup;
    filterForm_step3: FormGroup;
    filterForm_step4: FormGroup;
    camposLabel = [];
    camposLabel2 = [];
    camposLabel3 = [];
    camposLabel4 = [];
    fileList: File[] = [];
    descriptiones: any[] = [];
    listOfFiles: any[] = [];
    desValida: boolean = true;
    filesForm: FormGroup;
    description: string;
    folio = '';
    datos_form: any;
    municipio: any;
    municipio_geom: any;
    tipoEdit = '';
    tipoRefrendo;
    tituloFilesRefrendo;
    dialogRef;
    d: any;
    id2;
    superficie;
    hora_c;
    hora_a;

    idMapaRef;
    @ViewChild('img') imagen;
    @ViewChild('attachments') attachment: any;

    @ViewChild('aolview') aolview: any;
    @ViewChild('aolmap') aolmap: any;

    constructor(private fb: FormBuilder,
        private fb4: FormBuilder,
        private _campoDinamico: CamposTramiteServiceService,
        private activatedRoute: ActivatedRoute,
        private splash: FuseSplashScreenService,
        private _token: TokenService,
        private _matDialog: MatDialog,
        public dialog: MatDialog,
        private _revision: RevisionService,
        private fbDinamic: FormBuilder,
        private _LicenciaSevice: LicenciaStatusService,
        private router: Router,
        private mapaService: MapaService,
        private _mapaService: MapaService,
        private _resumenService: ResumenService) {
        this.filesForm = this.fbDinamic.group({
            name: '',
            quantities: this.fb.array([]),
        });
        this.filterForm_step2 = this.fb.group({
            nombre_propietario: [null],
            apellido_m_propietario: [null],
            apellido_p_propietario: [null],
            domicilio_propietario: [null],
            curp_propietario: [null, Validators.min(18)],
            rfc_propietario: [null, Validators.min(12)],
            correo_propietario: [null, Validators.email],
            telefono_propietario: [null],
            colonia_propietario: [null],
            num_ext_propietario: [null],
            num_int_propietario: [null],
            cp_propietario: [null],
            id: [null],
        });
        this.filterForm_step3 = this.fb.group({
            calle_predio: [null, Validators.required],
            colonia_predio: [null, Validators.required],
            num_int_predio: [null],
            num_ext_predio: [null, Validators.required],
            cp_predio: [null],
            id: [null],
            clave_cas: [null],
            coordenadas_x: [null],
            coordenadas_y: [null]

        });
        this.filterForm_step4 = this.fb4.group({
            tipo_inmueble: [null, Validators.required],
            nombre_negocio: [null],
            hora_a: [null],
            hora_c: [null],
            anuncio: [null],
            inversion: [null],
            numero_empleado: [null],
            numero_cajones: [null],
            id: [null],
            giro: [null, Validators.required],
            giro_desc: [null, Validators.required],
            giro_codigo: [null],
            superficie: [null],
        });
        this.filterForm = this.fb.group({
            nombre: [null],
            apellido_m: [null],
            apellido_p: [null],
            razon_social: [null],
            domicilio: [null],
            curp: [null, [Validators.required, Validators.min(18)]],
            rfc: [null, [Validators.min(12)]],
            correo: [null, [Validators.required, Validators.email]],
            telefono: [null, Validators.required],
            colonia: [null],
            num_ext: [null],
            num_int: [null],
            cp: [null],
            folio_actual: [null],
            id_municipio: [null],
            id: [null],
        });
    }
    uploadFiles() {
        const formData = new FormData();
        console.log(this.fileList);
        for (var i = 0; i < this.fileList.length; i++) {
            formData.append("file[]", this.fileList[i]);
            formData.append("description[]", this.descriptiones[i]);
        }
        this.splash.show();
        this._revision.uploadFileHist(formData, this.id).subscribe(r => {
            Swal.fire({
                title: 'Archivos Guardados Correctamente',
                icon: 'success',
                confirmButtonText: 'Continuar'
            }).then(e => {
                this.fileList = [];
                this.descriptiones = [];
                this.splash.hide();
            })
            this.splash.hide();
        }, e => {
            Swal.fire({
                title: '¡Algo a ocurrido!',
                text: 'Intentar más tarde o contactar al soporte',
                icon: 'error',
                confirmButtonText: 'Continuar'
            })
            this.splash.hide();
        });
        return false;
    }
    giroSeleccionado(e) {
    }
    agregarGiro(event: MatChipInputEvent): void {
        return
    }
    displayFn(user: User): string {
        return user && user.SCIAN ? user.SCIAN : '';
    }
    private _filter(name: string): User[] {
        const filterValue = name.toLowerCase();
        return this.d.filter(option => option.SCIAN.toLowerCase().includes(filterValue));
    }
    ngOnInit(): void {
        var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
        var tipo = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.tipo;
        this.mapaService.getGirosMunicipios().subscribe(r => {
            this.d = r;
        });
        if (!this.d) {
            this.d = []  /// quitar pq solo es para que no salga el error en la consola
        }
        this.filteredOptions = this.filterForm_step4.controls.giro.valueChanges.pipe(
            startWith(''),
            // map(value => (typeof value === 'string' ? value : value.SCIAN)),
            map(SCIAN => (SCIAN ? this._filter(SCIAN) : this.d.slice())),
        );

        this.tipoEdit = atob(tipo);
        if (atob(tipo) == 'edicion') {
            this.id = atob(folio);
        }
        this._LicenciaSevice.copyTramiteHis(folio).subscribe(
            (res: any) => {

                this.coordenadas_x_old = res.data[0].coordonadas_x;
                this.coordenadas_y_old = res.data[0].coordonadas_y;
                this.datos_form = res.data[0];
                console.log(this.coordenadas_x_old);

                this.id2 = this.datos_form.id;
                if (atob(tipo) == 'edicion') {
                    this.step1_completed = true;
                    this.step2_completed = true;
                    this.step3_completed = true;
                    this.step4_completed = true;
                    this.tipoRefrendo = 'Edición de licencia ';
                    this.tituloFilesRefrendo = 'Archivos adicionales para edición';
                } else {
                    this.step1_completed = false;
                    this.step2_completed = false;
                    this.step3_completed = false;
                    this.step4_completed = false;
                    this.tipoRefrendo = 'Nuevo ';
                    this.tituloFilesRefrendo = 'Archivos adicionales para refrendo';
                }
                this.filterForm.patchValue({
                    nombre: this.datos_form.nombre_titular,
                    apellido_m: this.datos_form.apellido_m,
                    apellido_p: this.datos_form.apellido_p,
                    razon_social: this.datos_form.razon_social,
                    domicilio: this.datos_form.calle_titular,
                    curp: this.datos_form.curp,
                    rfc: this.datos_form.rfc,
                    correo: this.datos_form.email,
                    telefono: this.datos_form.telefono,
                    colonia: this.datos_form.colonia_titular,
                    num_ext: this.datos_form.numero_ext_titular,
                    num_int: this.datos_form.numero_int_titular,
                    cp: this.datos_form.cp_titular,
                    folio_actual: this.datos_form.folio_licencia,
                    id_municipio: this.datos_form.id_municipio,
                    id: this.id,
                });
                this.filterForm_step2.patchValue({
                    nombre_propietario: this.datos_form.nombre_solicitante,
                    curp_propietario: this.datos_form.curp_solicitante,
                    apellido_m_propietario: this.datos_form.apellido_solicitante_m,
                    apellido_p_propietario: this.datos_form.apellido_solicitante_p,
                    domicilio_propietario: this.datos_form.calle_solicitante,
                    rfc_propietario: this.datos_form.rfc_solicitante,
                    correo_propietario: this.datos_form.email_solicitante,
                    telefono_propietario: this.datos_form.telefono_solicitante,
                    colonia_propietario: this.datos_form.colonia,
                    num_ext_propietario: this.datos_form.numero_ext,
                    num_int_propietario: this.datos_form.numero_int,
                    cp_propietario: this.datos_form.cp_solicitante,
                    id: this.id,
                    /* ... */
                });
                this.filterForm_step3.patchValue({
                    calle_predio: this.datos_form.calle_predio,
                    colonia_predio: this.datos_form.colonia_predio,
                    num_ext_predio: this.datos_form.num_ext_predio,
                    cp_predio: this.datos_form.cp_predio,
                    num_int_predio: this.datos_form.num_int_predio,
                    clave_cas: this.datos_form.clave_catastral,
                    coordenadas_x: this.datos_form.coordenadas_x,
                    coordenadas_y: this.datos_form.coordenadas_y
                });


                let scian = this.datos_form.SCIAN === null ? this.datos_form.giro : this.datos_form.SCIAN;
                this.filterForm_step4.patchValue({
                    giro: scian,
                    giro_desc: this.datos_form.descripcion_detallada,
                    giro_codigo: this.datos_form.codigo_giro,
                    inversion: this.datos_form.inversion,
                    numero_empleado: this.datos_form.numero_empleado,
                    nombre_negocio: this.datos_form.nombre_negocio,
                    numero_cajones: this.datos_form.numero_cajones,
                    tipo_inmueble: this.datos_form.tipo_inmueble,
                    hora_a: this.datos_form.hora_a,
                    hora_c: this.datos_form.hora_c,
                    anuncio: this.datos_form.anuncio,
                    superficie: this.datos_form.superficie_giro
                });
                proj4.defs('EPSG:32614', '+proj=utm +zone=13 +ellps=WGS84 +datum=WGS84 +units=m +no_defs');
                proj4('EPSG:32614');
                /* **** START **** */
                this._mapaService.getWFSFeature(
                    `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:base_municipio&count=1&outputFormat=application/json&cql_filter=id=${this.datos_form.id_municipio}`
                ).subscribe(municipio_wfs => {
                    if (municipio_wfs.features && municipio_wfs.features.length > 0) {
                        this.municipio = municipio_wfs.features[0].properties
                        let geom4326 = []
                        municipio_wfs.features[0].geometry.coordinates[0].forEach((val, i) => {
                            val.forEach((val2, j) => {
                                const coordenadaTransformada = proj4('EPSG:32614', 'EPSG:4326', val2);
                                geom4326.push(coordenadaTransformada);

                            });

                        });

                        this.municipio_geom = geom4326;

                        // this.aolview.instance.fit(turf.bbox(turf.polygon([geom4326])));
                        this.aolview.instance.setZoom(12)

                        this._LicenciaSevice.getInfoLicenciaDatos(this.id2).subscribe(datos => {
                            // @ts-ignore
                            const data = datos.data
                            const centroide = turf.centroid(turf.polygon([geom4326])).geometry.coordinates
                            if (data.coordonadas_x && data.coordonadas_y) {
                                const turf_point = turf.point([parseFloat(data.coordonadas_x), parseFloat(data.coordonadas_y)]);
                                const turf_municipio = turf.polygon([this.municipio_geom]);
                                if (turf.booleanPointInPolygon(turf_point, turf_municipio)) {

                                    this.datos_form.coordonadas_x = data.coordonadas_x
                                    this.datos_form.coordonadas_y = data.coordonadas_y

                                    this.aolview.instance.setCenter([parseFloat(data.coordonadas_x), parseFloat(data.coordonadas_y)])
                                } else {
                                    //this.aolview.instance.setCenter(centroide);
                                    this.datos_form.coordonadas_x = this.coordenadas_x;
                                    this.datos_form.coordonadas_y = this.coordenadas_y;

                                }
                            } else if ((!data.coordonadas_x || !data.coordonadas_y) &&
                                (this.datos_form.calle_predio && this.datos_form.colonia_predio &&
                                    this.datos_form.num_ext_predio)) {
                                this.datos_form.coordonadas_x = this.coordenadas_x;
                                this.datos_form.coordonadas_y = this.coordenadas_y;
                                this.buscarDir();
                            } else {
                                //this.aolview.instance.setCenter(centroide);
                                this.datos_form.coordonadas_x = this.coordenadas_x;
                                this.datos_form.coordonadas_y = this.coordenadas_y;
                            }
                        });
                        /* -------------------------------- */
                    }
                });
                /* **** END **** */

            },
            error => {
                console.log(error)
            }
        );
        this._resumenService.getFiles(atob(folio)).subscribe(
            (r: any) => {
                this.archivos = r.data;
                console.log(this.archivos);
            }, e => {
                console.error(e);
            }
        );
    }


    buscarDir() {
        let val = this.filterForm_step3.value;
        //const centroide = turf.centroid(turf.polygon([this.municipio_geom])).geometry.coordinates

        const domicilio = `${val.calle_predio} ${val.num_ext_predio}, ${val.colonia_predio}`;
        //const gres: any = this.getData();
        this._mapaService.getGeocode(domicilio, this.municipio.nombre).subscribe(gres => {

            if (gres && gres.geometry && gres.geometry.location) {
                const loc = gres.geometry.location
                const turf_loc = turf.point([loc.lng, loc.lat]);
                const turf_municipio = turf.multiPolygon([this.municipio_geom]);
                const direccion =  gres.formatted_address.toUpperCase();

                //if (turf.booleanPointInPolygon(turf_loc, turf_municipio)) {
                if (direccion.includes("CUERNAVACA")) {
                    this.aolview.instance.setCenter([loc.lng, loc.lat])
                    this.datos_form.coordonadas_x = loc.lng
                    this.datos_form.coordonadas_y = loc.lat
                    this.coordenadas_x = loc.lng;
                    this.coordenadas_y = loc.lat;
                    this.aolview.instance.setZoom(16)
                } else {
                    this.datos_form.coordonadas_x = this.coordenadas_x;
                    this.datos_form.coordonadas_y = this.coordenadas_y;
                }
            } else {
                //this.aolview.instance.setCenter(centroide);
                this.datos_form.coordonadas_x = this.coordenadas_x;
                this.datos_form.coordonadas_y = this.coordenadas_y;
               /* this.coordenadas_x = centroide[0];
                this.coordenadas_y = centroide[1];*/
            }
        })
    }


    buscarDir2() {
        let val = this.filterForm_step3.value;
        const centroide = turf.centroid(turf.polygon([this.municipio_geom])).geometry.coordinates

        const domicilio = `${val.calle_predio} ${val.num_ext_predio}, ${val.colonia_predio}`
        this._mapaService.getGoogleMapsGeo(
            `https://maps.googleapis.com/maps/api/geocode/json?address=${domicilio}, ${this.municipio.nombre}, Jalisco&key=AIzaSyCt8iQcvCc8xq50xvR-NXRA2oHWToaiKmo`
        ).subscribe(gres => {
            if (gres.results && gres.results.length > 0) {
                const loc = gres.results[0].geometry.location
                const turf_loc = turf.point([loc.lng, loc.lat]);
                const turf_municipio = turf.polygon([this.municipio_geom]);
                if (turf.booleanPointInPolygon(turf_loc, turf_municipio)) {
                    this.aolview.instance.setCenter([loc.lng, loc.lat])
                    this.datos_form.coordonadas_x = loc.lng
                    this.datos_form.coordonadas_y = loc.lat
                    this.coordenadas_x = loc.lng;
                    this.coordenadas_y = loc.lat;
                } else {
                    this.aolview.instance.setCenter(centroide);
                    this.datos_form.coordonadas_x = centroide[0]
                    this.datos_form.coordonadas_y = centroide[1]
                    this.coordenadas_x = centroide[0];
                    this.coordenadas_y = centroide[1];
                }
            } else {
                this.aolview.instance.setCenter(centroide);
                this.datos_form.coordonadas_x = centroide[0]
                this.datos_form.coordonadas_y = centroide[1]
                this.coordenadas_x = centroide[0];
                this.coordenadas_y = centroide[1];
            }
        })
    }

    onClick(event) {
        console.log(event)
        this.datos_form.coordonadas_x = event.mapBrowserEvent.coordinate[0]
        this.datos_form.coordonadas_y = event.mapBrowserEvent.coordinate[1]
    }


    setCode(code) {
        this.filterForm_step4.patchValue({
            giro_codigo: code
        });

    }

    onModifyEnd(event) {
        console.log(event)
        this.datos_form.coordonadas_x = event.mapBrowserEvent.coordinate[0]
        this.datos_form.coordonadas_y = event.mapBrowserEvent.coordinate[1]
        this.coordenadas_x = this.datos_form.coordonadas_x;
        this.coordenadas_y = this.datos_form.coordonadas_y;
    }

    setModifyStyle() {
        const style = [
            new ol.style.Style({
                image: new ol.style.Circle({
                    snapToPixel: false,
                    radius: 15,
                    stroke: new ol.style.Stroke({
                        color: 'rgb(66,134,61)',
                        width: 2,
                    }),
                    fill: new ol.style.Fill({
                        color: 'rgb(126,221,118,0.2)',
                    }),
                }),
            }),
            new ol.style.Style({
                image: new ol.style.Icon({
                    src: './assets/icons/visor/location_icon.png',
                    anchor: [0.496, 0.18],
                    anchorXUnits: 'fraction',
                    anchorYUnits: 'fraction',
                    scale: 1.3,
                    anchorOrigin: 'bottom-left'
                })
            })
        ]
        return style
    }

    onFileChanged(event: any) {
        if (this.description != '') {
            this.desValida = true;
            for (var i = 0; i <= event.target.files.length - 1; i++) {
                var size = event.target.files[i].size;
                if (size <= 15368312) {
                    var selectedFile = event.target.files[i];
                    this.fileList.push(selectedFile);
                    this.descriptiones.push(this.description);
                    this.listOfFiles.push({ 'name': selectedFile.name, 'description': this.description })
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'No se puedo subir un archivo mayor a 15 mb',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            }
        } else {
            this.desValida = false;
        }

        this.description = '';
        this.attachment.nativeElement.value = '';
    }

    removeSelectedFile(index) {
        // Delete the item from fileNames list
        this.listOfFiles.splice(index, 1);
        // delete file from FileList
        this.fileList.splice(index, 1);
    }

    download(row) {
        window.open(
            `${environment.SERVER_ORIGIN}${row.archivo}`,
            "_blank"
        );
    }

    getFormData(object) {
        const formData = new FormData();
        Object.keys(object).forEach(key =>
            formData.append(key, object[key])
        );
        var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
        formData.append("folio", folio);
        formData.append("step_actual", this.step_actual);

        return formData;
    }

    getFormData2 = object => Object.keys(object).reduce((formData, key) => {
        if (object[key] != null) {
            formData.append(key, object[key]);
        }
        return formData;
    }, new FormData());

    validador(formulario) {
        const propOwn = Object.getOwnPropertyNames(formulario.value);
        let valido = formulario.valid;
        let contadorInvalidos = 0;
        if (!valido) {
            for (var prop in formulario.value) {
                if (formulario.value[prop] == '' && formulario.value[prop] == 'VALID') {
                } else {
                    if (formulario.controls[prop].status != 'VALID') {
                        contadorInvalidos++;
                    }
                }
            }
        }
        if (contadorInvalidos == 0) {
            valido = true;
        } else {
            valido = false;
        }
        return valido;
    }

    extandex(step) {
        step = step + 1;
        if (step == 5) {
            step = 4;
        }
        var max1 = (step === 1);
        var max2 = (step === 2);
        var max3 = (step === 3);
        var max4 = (step === 4);

        this.isExpanded1 = max1;
        this.isExpanded2 = max2;
        this.isExpanded3 = max3;
        this.isExpanded4 = max4;
    }

    guardarPoligonoTramiteHistorico(data): Observable<any> {
        return this._mapaService.guardarRegistroTramiteHistorico(data)
    }

    Submit(step, event): void {
        this.splash.show();
        event.preventDefault();
        var filter_form = null;
        var valido = false
        if (step == 1) {
            filter_form = this.filterForm.value;
            valido = this.filterForm.valid;
            this.getErrosForm(this.filterForm.controls, this.camposLabel);
            valido = this.validador(this.filterForm);
            console.log(this.filterForm);
            this.step_actual = 1;
            this.step1_completed = true;

        }
        if (step == 2) {
            this.filterForm_step2.patchValue({ id: this.id });
            filter_form = this.filterForm_step2.value;
            this.getErrosForm(this.filterForm_step2.controls, this.camposLabel2);
            valido = this.validador(this.filterForm_step2);
            this.step_actual = 2;
            this.step2_completed = true;

        }
        if (step == 3) {
            this.filterForm_step3.patchValue({
                id: this.id,
                coordenadas_x: this.coordenadas_x,
                coordenadas_y: this.coordenadas_y
            });
            filter_form = this.filterForm_step3.value;
            let contadorInvalidos = 0;
            this.getErrosForm(this.filterForm_step3.controls, this.camposLabel3);
            valido = this.validador(this.filterForm_step3);
            this.step_actual = 3;
            this.step3_completed = true;
            // actualizar geometria, pedir geom tramite por id; si existe actualizar los datos
            // y si no existe crearlo, con los datos
            //


            // validar datos de este objeto:

            if (this.coordenadas_x_old === this.coordenadas_x && this.coordenadas_y_old === this.coordenadas_y) {

            } else {
                const Bbox = turf.bbox(turf.buffer(turf.point([this.coordenadas_x, this.coordenadas_y]), 50, { units: 'meters' }));
                const bbox = `${Bbox[1]},${Bbox[0]},${Bbox[3]},${Bbox[2]}`;
                const geom = turf.buffer(turf.point([this.coordenadas_x, this.coordenadas_y]), 8, { units: 'meters' });
                let datos = {
                    id_historico: this.id,
                    folio: this.datos_form.folio_licencia,
                    area: 0,
                    tipo_tramite: 'refrendo',
                    origen_tramite: 'importado',
                    municipio: this.municipio.id,
                    geom: geom.geometry.coordinates,
                    bbox: bbox
                }

                const url = `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:licencias&outputFormat=application/json&cql_filter=id=${this.id}`
                this._mapaService.getWFSFeature(url).subscribe(response => {
                    if (response.numberReturned > 0) {
                        datos['id'] = response.features[0].properties.id
                        this.idMapaRef = response.features[0].properties.id
                    }
                    this._mapaService.guardarRegistroTramiteHistorico(datos).subscribe(hist => {
                        console.log("hist", hist)
                    })
                })
            }

        }

        if (step == 4) {
            this.filterForm_step4.patchValue({ id: this.id });
            filter_form = this.filterForm_step4.value;
            this.getErrosForm(this.filterForm_step4.controls, this.camposLabel4);
            valido = this.validador(this.filterForm_step4);
            this.step_actual = 4;
            this.step4_completed = true;
        }
        this.submitted = true;
        if (valido) {
            this.validateAll(filter_form).then((arrayValidated) => {
                var json_arr = JSON.stringify(arrayValidated);
                const formData = new FormData();
                this._campoDinamico.uploadDataRefrendoHistorico(this.getFormData(arrayValidated)).pipe()
                    .subscribe(
                        result => {
                            this.id = result;
                            this.splash.hide();
                            // this.isExpanded2 = true;
                            this.extandex(this.step_actual);
                            Swal.fire({
                                title: 'Sección completada',
                                text: 'La información se guardó correctamente',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }).then(
                                () => {
                                    //   this.validarIngreso();
                                }
                            );
                        },
                        error => {
                            Swal.fire({
                                title: 'Error!',
                                text: error.error.error,
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        }
                    );
                if (step == 4) {
                    let datos = {
                        id: this.idMapaRef,
                        id_historico: this.id,
                        folio: this.datos_form.folio_licencia,
                        giro: this.filterForm_step4.value.giro
                    }
                    this._mapaService.guardarGiroTramiteHistorico(datos).subscribe(res => {
                        console.log("giro", res)
                    })
                }


            });
        } else {
            this.splash.hide();
            Swal.fire({
                title: 'Ups!',
                html: '<ul style="font-size: 11px;color: indianred;font-family: monospace;margin: 0;">Valida los campos Obligatorios</ul>',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });

        }
    }

    getErrosForm(form, camposLabel) {
    }

    validarIngreso() {
        const formData = new FormData();
        var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
        formData.append("folio", folio);
        this._campoDinamico.validarIngresoRefrendo(formData).subscribe(
            (res: any) => {
                this.step1_completed = (res['step_uno'] == 1) ? true : false;
                this.step2_completed = (res['step_dos'] == 1) ? true : false;
                this.step3_completed = (res['step_tres'] == 1) ? true : false;
                this.step4_completed = (res['step_cuatro'] == 1) ? true : false;
                if (res['step_uno'] == 1 && res['step_dos'] == 1 && res['step_tres'] == 1 && res['step_cuatro'] == 1) {
                    this.formCompleted = true;
                }
                this.extandex(res['step_actual']);
            },
            error => {
            }
        );
    }

    validateAll(filter_form) {
        return new Promise((resolve, reject) => {
            let allValues = filter_form
            Object.keys(filter_form).forEach((key, index) => {
                if (this.imageContainer[key]) {
                    allValues[key] = null;
                    delete allValues[key];
                }
                if (Object.keys(allValues).length > 0) {
                    resolve(allValues)
                } else {
                    resolve(true)
                }
            });
        })
    }

    copyData() {


        this.filterForm_step2.patchValue(
            {
                nombre_propietario: this.filterForm.controls['nombre'].value,
                apellido_m_propietario: this.filterForm.controls['apellido_m'].value,
                apellido_p_propietario: this.filterForm.controls['apellido_p'].value,
                domicilio_propietario: this.filterForm.controls['domicilio'].value,
                num_ext_propietario: this.filterForm.controls['num_ext'].value,
                num_int_propietario: this.filterForm.controls['num_int'].value,
                rfc_propietario: this.filterForm.controls['rfc'].value,
                curp_propietario: this.filterForm.controls['curp'].value,
                colonia_propietario: this.filterForm.controls['colonia'].value,
                cp_propietario: this.filterForm.controls['cp'].value,
                correo_propietario: this.filterForm.controls['correo'].value,
                telefono_propietario: this.filterForm.controls['telefono'].value
            }
        );

    }

    emitirDialog(row) {


        this.dialogRef = this._matDialog.open(EmitirRefrendoHistoricoComponent, {
            panelClass: 'emitir-form-dialog',
            width: '50%',
            disableClose: true,
            data: {
                action: 'new',
                data: this.id,
                num_licencia: this.id,
                superficie: this.superficie,
                hora_c: this.hora_c,
                hora_a: this.hora_a,

            }
        });
        this.dialogRef.afterClosed()
            .subscribe((response: FormGroup) => {

            });
    }

    guardarCambios() {
        Swal.fire({
            title: 'Datos Guardados',
            text: 'La información se guardó correctamente',
            icon: 'success',
            confirmButtonText: 'Ok'
        }).then(
            () => {
                this.router.navigate(['/licencias-emitidas']);
            }
        );

    }

    getData () {
        return {
            "address_components": [
                {
                    "long_name": "25",
                    "short_name": "25",
                    "types": [
                        "street_number"
                    ]
                },
                {
                    "long_name": "Río Tamazula",
                    "short_name": "Río Tamazula",
                    "types": [
                        "route"
                    ]
                },
                {
                    "long_name": "Vista Hermosa",
                    "short_name": "Vista Hermosa",
                    "types": [
                        "political",
                        "sublocality",
                        "sublocality_level_1"
                    ]
                },
                {
                    "long_name": "Cuernavaca",
                    "short_name": "Cuernavaca",
                    "types": [
                        "locality",
                        "political"
                    ]
                },
                {
                    "long_name": "Morelos",
                    "short_name": "Mor.",
                    "types": [
                        "administrative_area_level_1",
                        "political"
                    ]
                },
                {
                    "long_name": "Mexico",
                    "short_name": "MX",
                    "types": [
                        "country",
                        "political"
                    ]
                },
                {
                    "long_name": "62290",
                    "short_name": "62290",
                    "types": [
                        "postal_code"
                    ]
                }
            ],
            "formatted_address": "Río Tamazula 25, Vista Hermosa, 62290 Cuernavaca, Mor., Mexico",
            "geometry": {
                "bounds": {
                    "northeast": {
                        "lat": 18.9317725,
                        "lng": -99.2152256
                    },
                    "southwest": {
                        "lat": 18.9315788,
                        "lng": -99.2153175
                    }
                },
                "location": {
                    "lat": 18.9316756,
                    "lng": -99.21527160000001
                },
                "location_type": "ROOFTOP",
                "viewport": {
                    "northeast": {
                        "lat": 18.9331504302915,
                        "lng": -99.2139225697085
                    },
                    "southwest": {
                        "lat": 18.9304524697085,
                        "lng": -99.21662053029151
                    }
                }
            },
            "partial_match": true,
            "place_id": "ChIJTYt0Ar_fzYUROxLt7I0wGIA",
            "types": [
                "premise"
            ]
        }
    }

}
