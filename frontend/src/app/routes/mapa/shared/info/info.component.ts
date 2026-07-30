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
import { MatSelectionListChange } from '@angular/material/list';
import { RolesService } from 'app/services/administrador/roles/roles.service';
import { FormFichasComponent } from '../form-fichas/form-fichas.component';
import { MunicipioService } from 'app/services/administrador/municipios/municipio.service';
import { LocalStorageService } from '@shared/services/storage.service';

@Component({
    selector: 'app-map-info',
    templateUrl: './info.component.html',
    styleUrls: ['./info.component.scss'],
    animations: fuseAnimations
})
export class InfoComponent implements OnInit {
    typesOfShoes: string[] = ['Habitacional','Comercial y/o servicios', 'Industrial', 'Alojamiento temporal (Turístico)','Equipamiento', 'Espacios verdes, abiertos y recreativos', 'Otro (especificar)'];
    @Input('activeMedia') activeMedia: any;
    @Input('barraIOpen') barraIOpen: any;
    @Input('municipio') municipio: any;
    @Input('DatosPredio') datosPredio: DatosPredio;
    @Input('predioCoords') predioCoords: any;
    @Input('datosDinamicosActual') datosDinamicosActual: any = {};
    @Input('datosDinamicosActualConstruccion') datosDinamicosActualConstruccion: any = {};
    @Input('pasoT') pasoT: number = 1;
    @Input('construccion') construccion: number = 1;

    @Input('camposDiamicosMunicipio') camposDiamicosMunicipio: any;
    @Input('camposDiamicosMunicipioConstruccion') camposDiamicosMunicipioConstruccion: any;
    
    @Input('actividadesEconomicas') actividadesEconomicas: any;
    @Output() barra = new EventEmitter<boolean>();
    @Output() nuevoPoligonoID = new EventEmitter<number>();

    //

    tiposTramite:any = [];

    mostrar_default = false;

    mostrarDemolicion = false;
    mostrarSotano = false;
    cargandoMunicipio = false;
    actividaddSeleccionada: any;
    filteredActividades: Observable<any[]>;

    actividadSelected;
    controlActividades = new FormControl();


    infoTramite = 'https://api-visorurbano.jalisco.gob.mx/geoserver/ows?service=WMS&version=1.3.0&request=GetMap&FORMAT=image/png8&layers=VUJ:mapa_ficha_informativa&exceptions=application/vnd.ogc.se_inimage&CRS=EPSG:4326&width=600&height=600&BBOX=20.77867088903078,-102.90900628593921,20.779570209394507,-102.9080443997053';
    errorFormulario = false;
    errorFormulario2 = false;
    errorFormularioMessage = [];
    errorActividadSelected = false;
    mostrarConcepto = false;

    //datosDinamicosActual = {};
    //camposDiamicosMunicipio: any;
    dataEnvioPredio: any = {};

    roleId = 0;

    // Constructor
    constructor(private apivujService: MapaService,
        private _formBuilder: FormBuilder,
        private dialog: MatDialog,
        private route: Router,
        private _splash: FuseSplashScreenService,
        private municipioService:MunicipioService,
        private _store : LocalStorageService,
        private roleService: RolesService) {
    }

    ngOnInit(): void {
        this.roleService.getUserRoleId().subscribe(resp => {
            this.roleId = resp.data[0].id;
        });
        this.getTipoTramites();
        this.pasoT = 1;
        this.construccion = 0;
        this.filteredActividades = this.controlActividades.valueChanges.pipe(startWith(''), map(value => this._filterActividades(value)));

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

    }

    cancelarTramite() {
        this.barraIOpen = false;
        //this.tramiteList.nativeElement.style.cssText = 'display:none';
        this.pasoT = 1;
    }


    predioForm: FormGroup;
    predioFormConstruccion: FormGroup;
    predioFormConstruccion2: FormGroup;
    predioForm2: FormGroup;
    new_pol;

    iniciarTramite(): void {
        this.pasoT = 2;
        this.guardarPoligono().subscribe(response => {
            console.log("guardarPoligono response", response)
            console.log("guardarPoligono response id", response.id)
            let id = btoa(response.id)
            this.new_pol = response.id;
            console.log("id---", id)
            this.nuevoPoligonoID.emit(response.id)
        })
        this.createFromPredio();

      
    }

    iniciarTramiteConstruccion(): void {
        this.pasoT = 2;
        this.construccion = 1;
        this.guardarPoligono().subscribe(response => {
            let id = btoa(response.id)
            this.new_pol = response.id;
            this.nuevoPoligonoID.emit(response.id)
            let c = [];
            
            let co = turf.simplify(this.datosPredio.vectorial, { tolerance: 2, highQuality: false });

            co.geometry.coordinates[0].forEach(val => {
                c.push(proj4('EPSG:4326', 'EPSG:32614', val));
            });

            let coords = btoa(JSON.stringify(c))
            this.predioFormConstruccion.controls['coords'].setValue(coords);
        })
        this.createFromPredioConstruccion();
        this.ficha_tecnica2();

    }

    openSuccessDialog(folio, emite = 0, consulta = '', role=0, giro = true): void {
        let dialogSuccess = this.dialog.open(DialogSuccessComponent, {
            width: '505px',
            height: '479px',
            id: 'successDialog',
            disableClose: true,
            panelClass: 'myDialogStyle',
            data: { emite, consulta, role, giro},
        });
        dialogSuccess.afterClosed().subscribe(r => {
            if (r == 1) {
                this.route.navigate(['tramite', 'iniciar-tramite', btoa(folio)])
            }
            // this.route.navigate(['tramite', 'iniciar-tramite', btoa(folio)])
        })
    }

    openSuccessDialogConstruccion(folio, emite = 0, consulta = '', role= 0, giro = false): void {
        let dialogSuccess = this.dialog.open(DialogSuccessComponent, {
            width: '505px',
            height: '479px',
            id: 'successDialog',
            disableClose: true,
            panelClass: 'myDialogStyle',
            data: { emite, consulta, role, giro},
        });
        dialogSuccess.afterClosed().subscribe(r => {
            if (r == 1) {
                this.route.navigate(['tramite', 'iniciar-tramite', btoa(folio)])
            }
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

    radioDemolicion($event:any){
        if($event.value == 'no'){
            this.predioFormConstruccion.controls['mdemolicion'].setValue(0);
            this.mostrarDemolicion = false;
        }else{
            this.predioFormConstruccion.controls['mdemolicion'].setValue('');
            this.mostrarDemolicion = true;
        }
    }

    radioSotanos($event:any){
        if($event.value == 'no'){
            this.predioFormConstruccion.controls['sotano'].setValue(0);
            this.mostrarSotano = false;
        }else{
            this.predioFormConstruccion.controls['sotano'].setValue('');
            this.mostrarSotano = true;
        }
    }

    getRequisitosMunicipioConstruccion(id_tipoTramite){

        this.apivujService.getRequisitosMunicipioConstruccion2(this.municipio.id, id_tipoTramite).subscribe((response: any) => {
            response.data.forEach(element => {
                this.datosDinamicosActualConstruccion[element.name] = element;
                if (element.requerido == 3) {
                    this.datosDinamicosActualConstruccion[element.name].visible = false;
                }
            });
            this.cargandoMunicipio = false;
            this.camposDiamicosMunicipioConstruccion = response.data;
            console.log(this.camposDiamicosMunicipioConstruccion);
        }, error => {
            this.cargandoMunicipio = false;
        });
    }

    changeTipoTramite(id_n:any){

        this.getRequisitosMunicipioConstruccion(id_n);
        var localArray = ['numero_viviendas', 'niveles_nuevos_construir', 'sotano', 'mdemolicion'];
        const result = this.tiposTramite.find(({ id }) => id == id_n);
        if(result.default_preguntas){
            for(let x of localArray){
                this.predioFormConstruccion.controls[x].enable();
            }
            this.mostrar_default = true;
        }else{
            for(let x of localArray){
                this.predioFormConstruccion.controls[x].disable();
            }
            this.mostrar_default = false;
        }
    }

    getTipoTramites(){
        this.municipioService.getTipoTramites(this.municipio.id).subscribe(resp=>{
          this.tiposTramite = resp;
        });
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
    createFromPredioConstruccion() {
        this.predioFormConstruccion = this._formBuilder.group({
            tramite_relacionado: ['', Validators.required],
            mdemolicion: ['', Validators.required],
            sotano: ['', Validators.required],
            nombreC: ['', Validators.required],
            calleC: [this.datosPredio.calle, Validators.required],
            localidadC: [this.datosPredio.localidad.nombre || '', Validators.required],
            coloniaC: [this.datosPredio.localidad.nombre || '', Validators.required],
            niveles_nuevos_construir: ['', Validators.required],
            numero_viviendas: ['', Validators.required],
            coords: [''],
            uuid: [''],
        });

        this.predioFormConstruccion2 = this._formBuilder.group({
            Habitacional: ['', Validators.required],
            'Comercial y/o servicios': ['', Validators.required],
            'Industrial': ['', Validators.required],
            'Alojamiento temporal (Turístico)': ['', Validators.required],
            Equipamiento: ['', Validators.required],
            'Espacios verdes, abiertos y recreativos': ['', Validators.required],
            'Otro (especificar)': ['', Validators.required],
            Concepto_otro: ['', Validators.required],
        });

        this.predioFormConstruccion2.disable();

    }
    campoDinamicoFunctionConstruccion(e) {
        if (e.campo_afectado != '' && e.campo_afectado != null) {
            if (e.campo_afectado.includes(',')) {
                let arrayAfectado = e.campo_afectado.split(',');
                for (let index = 0; index < arrayAfectado.length; index++) {
                    const element = arrayAfectado[index];
                    if (e.value == this.datosDinamicosActualConstruccion[element].condicion_visible) {
                        this.datosDinamicosActualConstruccion[element].visible = true;
                    } else {
                        this.datosDinamicosActualConstruccion[element].visible = false;
                        this.datosDinamicosActualConstruccion[element].value = null;
                    }
                }
            } else {
                if (e.value == this.datosDinamicosActualConstruccion[e.campo_afectado].condicion_visible) {

                    this.datosDinamicosActualConstruccion[e.campo_afectado].visible = true;
                } else {
                    this.datosDinamicosActualConstruccion[e.campo_afectado].visible = false;
                    this.datosDinamicosActualConstruccion[e.campo_afectado].value = null;

                }
            }

        }
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
    ficha_tecnica2() {
     
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
                municipio_id: this.municipio.id
            }
          
      
            this.apivujService.registrarFicha(data).subscribe((r: any) => {
               
                if (r.data.uuid) {
                    this.predioFormConstruccion.controls['uuid'].setValue(r.data.uuid);
                    
                }  
            }, err => {
                this._splash.hide();
            })
            // 
        })

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
        let camposDinamicosConstruccion = {};
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
            this.openSuccessDialog(x.data.folio, x.data.emitir_licencia, x.data.url, this.roleId);
            window.open(`${environment.SERVER_ORIGIN}${x.data.url}`, "_blank");
            this.guardarPoligono(x.data.folio, this.actividaddSeleccionada, this.new_pol).subscribe(r => {
                console.log(r)
            });


        });
        //  console.log(JSON.stringify(this.dataEnvioPredio));

    }
    consultarRequisitosConstruccion() {
   
        //console.log(this.predioForm,typeof this.actividadSelected);

        if (this.predioFormConstruccion.valid) {
            this.errorFormulario = false;
            this.dataEnvioPredio = this.predioFormConstruccion.value;

        } else {
            this.errorFormulario = true;
        }
        //console.log(this.predioFormConstruccion);
        //console.log(this.predioFormConstruccion2);

        if(this.mostrar_default == true){
            if(this.predioFormConstruccion2.enabled){
                if(this.predioFormConstruccion2.invalid){
                    console.log(this.predioFormConstruccion2);
                    this.errorFormulario2 = true;
                }else{
                    this.errorFormulario2 = false;
                }
            }else{
                this.errorFormulario2 = true;
            }
        }

        


        this.errorFormularioMessage = [];
        let camposDinamicos = {};
         let camposDinamicosConstruccion = {};
        this.dataEnvioPredio['id_municipio'] = this.municipio.id;
        this.dataEnvioPredio['municipio'] = this.municipio.nombre;
        this.dataEnvioPredio['superficie_propiedad'] = this.datosPredio.area_predio;
        this.dataEnvioPredio['url_minimapa'] = this.datosPredio.url_minimapa;
        for(let shoe of this.typesOfShoes){
            if(this.predioFormConstruccion2.controls[shoe].value == ''){
                this.dataEnvioPredio[this.search(shoe)] = 0;
            }else{
                this.dataEnvioPredio[this.search(shoe)] = this.predioFormConstruccion2.controls[shoe].value;
            }
        }
        this.dataEnvioPredio[this.search('Concepto_otro')] = this.predioFormConstruccion2.controls['Concepto_otro'].value;
        this.dataEnvioPredio['restricciones'] = new Object();
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
        console.log(this.camposDiamicosMunicipioConstruccion);
        this.camposDiamicosMunicipioConstruccion.forEach(element => {
            if(element.tipo_tramite== 'consulta_requisitos'){
                camposDinamicosConstruccion[element.name] = element.value;
            }
          
            /*if (element.requerido == 1) {
                if (element.value) {
                    console.log(element.value);
                    camposDinamicosConstruccion[element.name] = element.value;
                } else {
                    console.log(element);
                    this.errorFormularioMessage.push(element.description);
                    this.errorFormulario = true;
                }
            }
            if (element.requerido == 3 && this.datosDinamicosActual[element.name].visible) {
                if (element.value) {
                    camposDinamicosConstruccion[element.name] = element.value;
                } else {
                    console.log("aqui 2");
                    this.errorFormularioMessage.push(element.description);
                    this.errorFormulario = true;
                }
            }*/

        });
       
        this.dataEnvioPredio['camposDinamicos'] = camposDinamicosConstruccion;
        console.log("aqui " + this.errorFormulario);
        console.log("aqui " + this.errorFormulario2);
        if (this.errorFormulario == true || this.errorFormulario2 == true) {
            return;
        }

        this.apivujService.consultaRequisitosConstruccion(this.dataEnvioPredio).subscribe((x: any) => {
            this.cancelarTramite();
            this.openSuccessDialogConstruccion(x.data.folio, x.data.emitir_licencia, x.data.url, this.roleId);
            window.open(`${environment.SERVER_ORIGIN}${x.data.url}`, "_blank");
            this.guardarPoligono(x.data.folio, this.actividaddSeleccionada, this.new_pol).subscribe(r => {

            });
        });

        
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

    checkbox($event: MatSelectionListChange){
        if($event.option.selected){
            this.predioFormConstruccion2.controls[$event.option.value].enable();
            if($event.option.value == 'Otro (especificar)'){
                this.predioFormConstruccion2.controls['Concepto_otro'].enable();
                this.mostrarConcepto = true;
            }
        }else{
            this.predioFormConstruccion2.controls[$event.option.value].disable();
            if($event.option.value == 'Otro (especificar)'){
                this.predioFormConstruccion2.controls['Concepto_otro'].disable();
                this.mostrarConcepto = false;
            }
        }
    }

    search(value){
        switch(value){
            case 'Habitacional':{
                return 'superficie_habitacional';
            }
            case 'Comercial y/o servicios':{
                return 'superficie_comercial_servicios';
            }
            case 'Industrial':{
                return 'superficie_industrial';
            }
            case 'Alojamiento temporal (Turístico)':{
                return 'superficie_turistico';
            }
            case 'Equipamiento':{
                return 'superficie_equipamiento';
            }
            case 'Espacios verdes, abiertos y recreativos':{
                return 'superficie_espacios_verdes';
            }
            case 'Otro (especificar)':{
                return 'superficie_otro';
            }
            case 'Concepto_otro':{
                return 'concepto_otro';
            }

        }
    }

}
