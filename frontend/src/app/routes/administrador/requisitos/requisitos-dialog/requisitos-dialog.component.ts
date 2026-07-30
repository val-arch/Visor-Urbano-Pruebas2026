import {
    Component,
    ElementRef,
    Inject,
    OnInit,
    ViewChild,
} from "@angular/core";
import {
    FormArray,
    FormBuilder,
    FormControl,
    FormGroup,
    Validators,
} from "@angular/forms";
import {
    MatDialog,
    MatDialogRef,
    MAT_DIALOG_DATA,
} from "@angular/material/dialog";
import { MtxGridColumn } from "@ng-matero/extensions";
import { RequisitosService } from "app/services/administrador/requisitos/requisitos.service";
import { Requisitos } from "../../../../models/administrador/requisito.models";
import Swal from "sweetalert2";
import { fuseAnimations } from "@fuse/animations";
import { RolesDialogComponent } from "../../roles/roles-dialog/roles-dialog.component";
import {
    MatAutocomplete,
    MatAutocompleteSelectedEvent,
} from "@angular/material/autocomplete";
import { startWith, map } from "rxjs/operators";
import { Observable } from "rxjs";
import { COMMA, ENTER } from "@angular/cdk/keycodes";
import { MatChipInputEvent } from "@angular/material/chips";
import { FuseUtils } from "@fuse/utils/index";
import { RolesService } from "../../../../services/administrador/roles/roles.service";
import { UtilsService } from "../../../../services/utils.service";
import { NgxCopilotService } from "ngx-copilot";

@Component({
    templateUrl: "./requisitos-dialog.component.html",
    styleUrls: ["./requisitos-dialog.component.scss"],
    animations: fuseAnimations,
})
export class RequisitosDialogComponent implements OnInit {
    public modelRequsito: Requisitos = new Requisitos();
    public requisitoForm: FormGroup;
    public action: string;
    public dialogTitle: string;
    errors = [];
    btnCss = {
        "background-color": "#003E76",
        color: "white",
    };
    btnCssCancel = {
        "background-color": "red",
        color: "white",
    };

    @ViewChild("errorsdiv") errorDiv: ElementRef;

    constructor(
        public matDialogRef: MatDialogRef<RequisitosDialogComponent>,
        @Inject(MAT_DIALOG_DATA) private _data: any,
        private fb: FormBuilder,
        private requisitoService: RequisitosService,
        private _matDialog: MatDialog,
        private _requisitos: RequisitosService,
        private _role: RolesService,
        private _utilService: UtilsService,
        private copilot: NgxCopilotService
    ) {
        this.action = _data.action;
        // console.log(_data);
        // this.filteredFruits = this.fruitCtrl.valueChanges.pipe(
        //   startWith(null),
        //     map((fruit: string | null) => fruit ? this._filter(fruit) : this.allFruits.slice()));
        this.filteredGiros = this.girosCtrl.valueChanges.pipe(
            startWith(null),
            map((value) => this._filterGiro(value))
        );
    }
    dialogRef;
    visible = true;
    selectable = true;
    removable = true;
    separatorKeysCodes: number[] = [ENTER, COMMA];
    girosCtrl = new FormControl();
    filteredGiros: Observable<any[]>;
    girosDisponibles: any;
    girosSeleccionados: string[] = [];
    dependenciaList: [] = [];
    propiedad_condicion;
    propiedad_metros;
    actividad_condicion;
    actividad_metros;
    caracter_solicitante_condicion = [];
    tipo_persona_condicion = [];
    todasCondiciones;
    dependenciaSelected;
    opcionesDisp = this.fb.array([]);
    @ViewChild("giroInput") giroInput: ElementRef<HTMLInputElement>;
    @ViewChild("auto") matAutocomplete: MatAutocomplete;

    ngOnInit(): void {
        this.getRoleMunicipio();
        if (this._data.action == "new") {
            // this.copilot.checkInit();
        } else {
            this.dependenciaSelected = this._data.requisito.condicion_dependencia;
            this.modelRequsito = this._data.requisito;

            this.modelRequsito.step = this.modelRequsito.step.toString();
            this.getCondiciones();

            if (
                this.modelRequsito.condicion_giro != null &&
                this.modelRequsito.condicion_giro.includes(",")
            ) {
                let d = this.modelRequsito.condicion_giro.split(",");
                for (let i = 0; i < d.length; i++) {
                    const element = d[i];
                    this.girosSeleccionados.push(element);
                }
            } else if (
                this.modelRequsito.condicion_giro != null &&
                this.modelRequsito.condicion_giro != ""
            ) {
                this.girosSeleccionados.push(this.modelRequsito.condicion_giro);
            }

            if(this.modelRequsito.condicion_visible.includes("check_alcohol")){
                var number:any = this.modelRequsito.condicion_visible.match(/\d/g);
                number = number.join("");
            }
        }

        var user = JSON.parse(localStorage.getItem('usr'));

        this._requisitos.getGirosAdmin2(user.id_municipio).subscribe((r) => {
            //  console.log(r);
            this.girosDisponibles = r.data;
        });

        console.log(this.actividad_condicion);


        this.requisitoForm = this.fb.group({
            id: [this.modelRequsito.id, Validators.required],
            name: [this.modelRequsito.name, Validators.required],
            opciones_desc: [
                this.modelRequsito.opciones_desc,
                Validators.required,
            ],
            type: [this.modelRequsito.type, Validators.required],
            fundamento: [this.modelRequsito.fundamento],
            opciones: this.opcionesDisp,
            //giros : this.fb.array([]),
            description: [this.modelRequsito.description],
            secuencia: [this.modelRequsito.secuencia, Validators.required],
            requerido: [
                this.modelRequsito.requerido
                    ? this.modelRequsito.requerido.toString()
                    : "",
                Validators.required,
            ],
            condicion_visible: [this.modelRequsito.condicion_visible],
            campo_afectado: [this.modelRequsito.campo_afectado],
            tipo_tramite: [this.modelRequsito.tipo_tramite],
            step: [this.modelRequsito.step, Validators.required],
            description_rec: [this.modelRequsito.description_rec],
            check_dependencia: [
                this.modelRequsito.condicion_dependencia == null ? false : true,
            ],
            check_alcohol: [number || 0],
            propiedad_condicion: [this.propiedad_condicion || "0"],
            propiedad_metros: [this.propiedad_metros],
            actividad_condicion: [this.actividad_condicion || "0"],
            actividad_metros: [this.actividad_metros],
            caracter_solicitante_condicion: [
                this.caracter_solicitante_condicion,
            ],
            tipo_persona_condicion: [this.tipo_persona_condicion],
            todasCondiciones: [this.todasCondiciones ?? "false"],
            dependencias: [Number(this.modelRequsito.condicion_dependencia)],
        });
    }
    getCondiciones() {
        const {
            condicion_visible,
            id_municipio,
            requerido,
            type,
            opciones,
        } = this._data.requisito;
        if (type == "select" || type == "radio") {
            let arrayOpc = opciones.split("|");
            for (let i = 0; i < arrayOpc.length; i++) {
                const element = arrayOpc[i];
                this.opcionesDisp.push(this.fb.control(element));
            }
        }
        if (condicion_visible && id_municipio != 0) {3
            const arrayCondicion = condicion_visible.match(/\(([^)]+)\)/g);
            const condicionOY = condicion_visible.includes(") || (");
            this.todasCondiciones = condicionOY ? "false" : "true";
            let textCondicion = `${
                condicionOY
                    ? "Cuando se actualice <b>cualquiera</b> de los siguientes supuestos"
                    : "Cuando se actualicen <b>todos</b> los supuestos"
            }`;
            if (arrayCondicion.length == 0) {
                return "Algo a ocurrido";
            } else {
                // console.log(arrayCondicion);

                for (let i = 0; i < arrayCondicion.length; i++) {
                    const element = arrayCondicion[i];
                    //  console.log("Element: ", element);
                    if (element.includes(">=") || element.includes("<=")) {
                        let condicion = element.split("=");
                        // console.log(condicion);
                        let condicionNombre = condicion[0]
                            .match(/\/(\w*)/g)[0]
                            .replace("/", "");
                        let valorCondicion = condicion[1]
                            .replace(")", "")
                            .replace(" ", "");
                        if (condicionNombre.includes("superficie_propiedad")) {
                            this.propiedad_condicion = element.includes(">=")
                                ? ">="
                                : "<=";
                            this.propiedad_metros = valorCondicion;
                        } else if(condicionNombre.includes("superficie_actividad")){
                            this.actividad_condicion = element.includes(">=")
                                ? ">="
                                : "<=";
                            this.actividad_metros = valorCondicion;
                        }
                    } else if (element.includes("==")) {
                        if (
                            element.includes("caracter") ||
                            element.includes("tipo_persona")
                        ) {
                            if (element.includes("||")) {
                                let arrayCaracter = element.split("||");
                                for (let i = 0; i < arrayCaracter.length; i++) {
                                    const elementCaracter = arrayCaracter[
                                        i
                                    ].split("==");
                                    let condicionNombre = elementCaracter[0]
                                        .match(/\/(\w*)/g)[0]
                                        .replace("/", "");
                                    let valorCondicion = elementCaracter[1]
                                        .replace(")", "")
                                        .replace(/ /gi, "")
                                        .replace(/'/gi, "");
                                    if (element.includes("caracter")) {
                                        this.caracter_solicitante_condicion.push(
                                            valorCondicion
                                        );
                                    } else if (
                                        element.includes("tipo_persona")
                                    ) {
                                        this.tipo_persona_condicion.push(
                                            valorCondicion
                                        );
                                    }
                                }
                                // console.log(this.caracter_solicitante_condicion);

                                // falta codigo aquí  es para cuando solo existe una opc
                            } else {
                                const elementCaracter = element.split("==");
                                let condicionNombre = elementCaracter[0]
                                    .match(/\/(\w*)/g)[0]
                                    .replace("/", "");
                                let valorCondicion = elementCaracter[1]
                                    .replace(")", "")
                                    .replace(/ /gi, "")
                                    .replace(/'/gi, "");
                                valorCondicion =
                                    valorCondicion == "CartaPoder"
                                        ? "Carta Poder"
                                        : valorCondicion;
                                if (element.includes("caracter")) {
                                    this.caracter_solicitante_condicion.push(
                                        valorCondicion
                                    );
                                } else if (element.includes("tipo_persona")) {
                                    this.tipo_persona_condicion.push(
                                        valorCondicion
                                    );
                                }
                            }
                        } /*else if(element.includes('tipo_persona')){
              if(element.includes('||')){

              }else{
                
              }
            }*/
                    }
                }
            }
            return textCondicion;
        } else if (condicion_visible) {
            if (requerido == 3) {
                return "Condicion pre establecida";
            }
            if (condicion_visible.includes("||")) {
                let arrayCondicion = condicion_visible.split("||");
                let textCondicion = "Cuando ";
                for (let i = 0; i < arrayCondicion.length; i++) {
                    const element = arrayCondicion[i];
                    let arrayElement = element.split("==");
                    if (arrayElement[0].includes("propietario_rad")) {
                        textCondicion += `Pro pietario es ${
                            arrayElement[1].includes("propietario_i")
                                ? "Física"
                                : "Moral"
                        } ${i + 1 == arrayCondicion.length ? "" : "o "}`;
                    }
                    if (arrayElement[0].includes("arrendatario_rad")) {
                        textCondicion += `${
                            arrayElement[1].includes("representante_arr")
                                ? "Moral"
                                : "Física"
                        } ${i + 1 == arrayCondicion.length ? "" : " o "}`;
                    }
                    if (arrayElement[0].includes("carta_poder_rad")) {
                        textCondicion += `${
                            arrayElement[1].includes("persona_fisica")
                                ? "Física"
                                : "Moral"
                        } ${i + 1 == arrayCondicion.length ? "" : " o "}`;
                    }
                }
                return textCondicion;
            } else {
                let textCondicion = "Cuando ";
                let arrayElement = condicion_visible.split("==");
                if (arrayElement[0].includes("propietario_rad")) {
                    textCondicion += `Propietario es ${
                        arrayElement[1].includes("propietario_i")
                            ? "Física"
                            : "Moral"
                    }`;
                }
                if (arrayElement[0].includes("arrendatario_rad")) {
                    textCondicion += `Arrendatario es ${
                        arrayElement[1].includes("representante_arr")
                            ? "Moral"
                            : "Física"
                    } `;
                }
                if (arrayElement[0].includes("carta_poder_rad")) {
                    textCondicion += `Carta poder es ${
                        arrayElement[1].includes("persona_fisica")
                            ? "Física"
                            : "Moral"
                    }`;
                }
                return textCondicion;
            }
        } else {
            return "-";
        }
    }

    guardar() {
        // console.log('re_____')
        // console.log(this.requisitoForm);
        let form = this.requisitoForm.value;
        this.errors = [];
        // console.log(form);
        if (!form.description)
            this.errors.push(
                "Se debe agregar el título del requisito o información a requerir"
            );
        if (!form.type)
            this.errors.push(
                "Se debe seleccionar el documento o información a requerir"
            );
        if (!form.step)
            this.errors.push("Se debe seleccionar el requisito relacionado");
        if (!form.tipo_tramite)
            this.errors.push(
                "Se debe seleccionar requisito o información en dónde se solicitará"
            );
        if (!form.requerido)
            this.errors.push("Se debe seleccionar Cuándo se requiere");

        if (this.errors.length == 0) {
            //let idn = typeof this._data.requisito.id == 'undefined'  ?  0:this._data.requisito.id;
            let data = {
                name: "",
                type: "",
                description: "",
                description_rec: "",
                fundamento: "",
                opciones: "",
                opciones_desc: "",
                step: 1,
                secuencia: 1,
                requerido: 1,
                condicion_visible: "",
                campo_afectado: "",
                tipo_tramite: "",
                condicion_dependencia: "",
                condicion_giro: "",
            };
            if (this._data.action == "edit") {
                data["id"] = this._data.requisito.id;
            }

            // if(!form.description_rec)this.errors.push( "Se debe agregar la descripción del requisito");
            if (form.type == "select" || form.type == "radio") {
                if (form.opciones.length > 2) {
                    let elementOpc = "",
                        elementLeng = "";
                    for (let i = 0; i < form.opciones.length; i++) {
                        elementOpc +=
                            i + 1 == form.opciones.length
                                ? `${form.opciones[i]}`
                                : `${form.opciones[i]}|`;
                        elementLeng +=
                            i + 1 == form.opciones.length
                                ? `${i + 1}`
                                : `${i + 1}|`;
                        if (form.opciones[i] == "") {
                            this.errors.push(
                                `No puedes dejar el campo número ${
                                    i + 1
                                } sin texto (Opciones)`
                            );
                        }
                    }
                    data.opciones = elementOpc;
                    data.opciones_desc = elementLeng;
                } else {
                    this.errors.push(
                        "Se tiene que agregar por lo menos 3 opciones"
                    );
                }
            }
            if (this.errors.length > 0) {
                setTimeout(
                    () =>
                        document
                            .querySelector(`.error`)
                            .scrollIntoView({ behavior: "auto" }),
                    //  () =>
                    50
                );
                return;
            }

            if (form.description_rec == null) {
                form.description_rec = "";
            }

            data.name =
                FuseUtils.getCleanedString(form.description).replace(
                    / /gi,
                    ""
                ) +
                "visor" +
                `${new Date().getHours()}`;
            data.type = form.type;
            data.description = form.description;
            data.description_rec = form.description_rec;
            data.fundamento = form.fundamento;
            data.step = parseInt(form.step);
            data.requerido = parseInt(form.requerido);
            data.tipo_tramite = form.tipo_tramite;
            switch (form.requerido) {
                case "1":
                    // console.group("Switch case siempre");
                    data.condicion_visible = "";
                    // console.log(data);
                    console.groupEnd();
                    // break;
                case "2":
                    // console.group("Switch case personalizados");
                    data.condicion_visible = "";
                    let condicionPersonalizados = "&&";
                    let condicionText = "";
                    let contadorCondicion = 0;
                    if (form.todasCondiciones == "false") {
                        condicionPersonalizados = "||";
                    }
                    if (form.propiedad_condicion != "0") {
                        if (form.propiedad_metros < 1) {
                            this.errors.push(
                                "Agregar metros a la condición de la propiedad"
                            );
                        } else {
                            condicionText += `(/superficie_propiedad ${form.propiedad_condicion} ${form.propiedad_metros}) ${condicionPersonalizados} `;
                            contadorCondicion++;
                        }
                    }
                    if (form.actividad_condicion != "0") {
                        if (form.actividad_metros < 1) {
                            this.errors.push(
                                "Agregar metros a la condición de la actividad"
                            );
                        } else {
                            condicionText += `(/superficie_actividad ${form.actividad_condicion} ${form.actividad_metros}) ${condicionPersonalizados} `;
                            contadorCondicion++;
                        }
                    }
                    if (form.caracter_solicitante_condicion != "0") {
                        let caracterText = "( ",
                            leng = form.caracter_solicitante_condicion.length;
                        if (leng == 1) {
                            condicionText += `(/caracter_solicitante == '${form.caracter_solicitante_condicion}' ) ${condicionPersonalizados} `;
                            contadorCondicion++;
                        } else if (leng > 1) {
                            for (let i = 0; i < leng; i++) {
                                caracterText +=
                                    leng == i + 1
                                        ? `/caracter_solicitante == '${form.caracter_solicitante_condicion[i]}' ) ${condicionPersonalizados} `
                                        : ` /caracter_solicitante == '${form.caracter_solicitante_condicion[i]}' ||`;
                                contadorCondicion++;
                            }
                            condicionText += caracterText;
                        }
                    }
                    if (form.tipo_persona_condicion != "0") {
                        let tipoPersonaText = "( ",
                            leng = form.tipo_persona_condicion.length;
                        if (leng == 1) {
                            condicionText += `(/tipo_persona == '${form.tipo_persona_condicion}' ) `;
                            contadorCondicion++;
                        } else if (leng > 1) {
                            for (let i = 0; i < leng; i++) {
                                tipoPersonaText +=
                                    leng == i + 1
                                        ? ` /tipo_persona == '${form.tipo_persona_condicion[i]}' ) `
                                        : ` /tipo_persona == '${form.caracter_solicitante_condicion[i]}' ||`;
                                contadorCondicion++;
                            }
                            condicionText += tipoPersonaText;
                        }
                    }
                    if(form.check_alcohol != "0"){
                        let checkAlcoholText = "( ",
                            leng = form.check_alcohol.length;
                        if (leng == 1) {
                            if(form.check_alcohol == 5){
                                condicionText += `(/check_alcohol <= '${form.check_alcohol}' ) `;
                            }else{
                                condicionText += `(/check_alcohol == '${form.check_alcohol}' ) `;
                            }
                            
                            contadorCondicion++;
                        }
                    }

                    if (contadorCondicion == 1) {
                        condicionText = condicionText.replace(
                            condicionPersonalizados,
                            ""
                        );
                    }
                    if (
                        condicionText.substr(-3) == "&& " ||
                        condicionText.substr(-3) == "|| "
                    ) {
                        condicionText = condicionText.substring(
                            0,
                            condicionText.length - 3
                        );
                    }

                    data.condicion_visible = window.btoa(
                        unescape(encodeURIComponent(condicionText))
                    );
                    if (this.girosSeleccionados.length) {
                        data.condicion_giro = this.girosSeleccionados.toString();
                    }
                    // console.log(form.dependencias);
                    // if(form.check_dependencia){
                    data.condicion_dependencia = form.check_dependencia
                        ? form.dependencias.toString()
                        : "";
                    //  }
                    // console.log(data);
                    // console.groupEnd();

                    break;
                case "3":
                    break;
                default:
                    this.errors.push("Debes seleccionar cuando se requiere");
                    break;
            }
            if (this.errors.length > 0) {
                setTimeout(
                    () => document.querySelector(`.error`).scrollIntoView(),
                    50
                );
            } else {
                // console.log(data);
                this.saveRequisito(data);
            }
        }
    }

    get opciones() {
        return this.requisitoForm.get("opciones") as FormArray;
    }

    addOpcion() {
        this.opciones.push(this.fb.control(""));
    }
    removeOpcion(i) {
        this.opciones.removeAt(i);
    }
    tipoRequisitoClear() {
        //console.log("Change");
        this.opciones.clear();
    }

    add(event: MatChipInputEvent): void {
        // console.log(event);
        return;
    }

    selected(event: MatAutocompleteSelectedEvent): void {
        //  console.log(this.girosSeleccionados.indexOf(event.option.value.codigo));
        if (this.girosSeleccionados.indexOf(event.option.value.codigo) == -1) {
            this.girosSeleccionados.push(event.option.value.codigo);
            this.giroInput.nativeElement.value = "";
            this.girosCtrl.setValue(null);
        } else {
            return;
        }
    }

    remove(fruit: string): void {
        const index = this.girosSeleccionados.indexOf(fruit);

        if (index >= 0) {
            this.girosSeleccionados.splice(index, 1);
        }
    }

    private _filterGiro(value: any) {
        if (!value) {
            return;
        }
        let filterValue = value.SCIAN
            ? value.SCIAN.toLowerCase()
            : value.toLowerCase();
        return this.girosDisponibles.filter(
            (option) =>
                FuseUtils.getCleanedString(option.SCIAN.toLowerCase()).includes(
                    filterValue
                ) || option.SCIAN.toLowerCase().includes(filterValue)
        );
    }

    saveRequisito(form) {
        if (this.action == "edit") {
            this.requisitoService.storeRequisitos(form).subscribe((resp) => {
                Swal.fire({
                    title: "¡Éxito!",
                    text: "Guardado Correctamente!",
                    icon: "success",
                    confirmButtonText: "Ok",
                });
            });
        } else {
            this.requisitoService.storeRequisitos(form).subscribe((resp) => {
                Swal.fire({
                    title: "¡Éxito!",
                    text: "Guardado correctamente!",
                    icon: "success",
                    confirmButtonText: "Ok",
                });
            });
        }
        this.matDialogRef.close();
    }
    getRoleMunicipio() {
        this._role.getRoleMunicipaly().subscribe(
            (r: any) => {
                // console.log(r);
                if (r.data.length) {
                    this.dependenciaList = r.data;
                }
            },
            (e) => console.error(e)
        );
    }

    initPosition = (stepNumber: any) => this.copilot.checkInit(stepNumber);

    /*Next Step*/
    nextStep = (stepNumber: any) => this.copilot.next(stepNumber);

    /*Finish*/
    done = () => this.copilot.removeWrapper();

    newRole(): void {
        /*
this.giroService.apagarGiro(id_giro,id_municipio).subscribe((res) => {
});
 */

        this.dialogRef = this._matDialog.open(RolesDialogComponent, {
            panelClass: "role-form-dialog",
            data: {
                action: "new",
                dialogTitle: "Agregar Rol",
            },
        });
        this.dialogRef.afterClosed().subscribe((response: FormGroup) => {
            this.getRoleMunicipio();
        });
    }
}
