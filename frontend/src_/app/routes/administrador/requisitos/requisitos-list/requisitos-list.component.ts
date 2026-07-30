import {
    Component,
    Input,
    OnInit,
    TemplateRef,
    ViewChild,
} from "@angular/core";
import { FormControl, FormGroup } from "@angular/forms";
import { MatDialog } from "@angular/material/dialog";
import { PageEvent } from "@angular/material/paginator";
import { MtxGridColumn } from "@ng-matero/extensions";
import { RequisitosService } from "../../../../services/administrador/requisitos/requisitos.service";
import { RequisitosDialogComponent } from "../requisitos-dialog/requisitos-dialog.component";
import { SettingsService } from "@core/settings.service";
@Component({
    selector: "app-requisitos-list",
    templateUrl: "./requisitos-list.component.html",
    styleUrls: ["./requisitos-list.component.scss"],
})
export class RequisitosListComponent implements OnInit {
    @ViewChild("statusRow", { static: true }) statusRow: TemplateRef<any>;
    columns: MtxGridColumn[] = [];
    filter = new FormControl("");

    myUser;
    list = [];
    total = 0;
    isLoading = true;
    disabledCheck = false;
    page = 0;
    editId = 0;
    query = {
        order: "desc",
        page: 0,
    };
    dialogRef;
    constructor(
        private _matDialog: MatDialog,
        private _requisitos: RequisitosService,
        private _sett: SettingsService
    ) {
        this.myUser = this._sett.user;
    }

    ngOnInit(): void {
        // console.log(this.statusRow);
        this.columns = [
            {
                header: "Título del requisito",
                field: "description",
                description: "Título del requisito o información a requerir",
            },
            ///{ header: 'Fundamento', field: 'fundamento', },
            {
                header: "Tipo campo",
                field: "type",
                formatter: (data) => this.getType(data.type),
            },
            {
                header: "Opciones",
                field: "opciones",
                formatter: (data) => this.getOpciones(data),
                width: "250px",
            },
            {
                header: "Cuándo se requiere",
                field: "requerido",
                formatter: (data) => this.getRequeridoF(data.requerido),
            },
            {
                header: "Condición(es) para requerir",
                field: "condicion_visible",
                formatter: (data) => this.getCondicion(data),
                width: "320px",
            },
            {
                header: "Relacionado a",
                field: "step",
                formatter: (data) => this.getStepF(data.step),
            },
            {
                header: "Estatus",
                field: "existe",
                cellTemplate: this.statusRow,
            },
            {
                header: "Acciones",
                field: "acciones",
                type: "button",
                buttons: [
                    {
                        type: "icon",
                        tooltip: "Editar",
                        color: "primary",
                        text: "Editar",
                        iif: (data) => data.id_municipio,
                        icon: "create",
                        click: (data) => this.editRequisito(data),
                    },
                    //  { type: 'icon',tooltip:'Dar de baja', color: 'warn', text: 'Baja', icon: 'delete' }
                ],
            },
        ];
        this.getData();
    }

    buscar() {
        // console.log(this.filter.value)
        this.getData();
    }
    onStatus(row, $event) {
        var id_campo = row.id;
        var id_requisito = row.id_requi;

        //return;

        this.disabledCheck = true;
        this._requisitos
            .postRequisitoChange(id_campo, id_requisito || 0)
            .subscribe((rest) => {
                //console.log(rest);
                this.disabledCheck = false;
                this.getData();
            });
    }

    getRequeridoF(data) {
        switch (data) {
            case 1:
                return "Siempre";
                break;
            case 2:
                return "Personalizado";
                break;
            case 3:
                return "Otro";
                break;

            default:
                break;
        }
    }

    getStepF(data) {
        switch (data) {
            case 1:
                return "Solicitante";
                break;
            case 3:
                return "Predio";
                break;
            case 4:
                return "Establecimiento";
                break;
            case 2:
                return "Propietario";
                break;

            default:
                break;
        }
    }

    getDisabled(data): boolean {
        if (data == 0) {
            return true;
        }
        return false;
    }

    getType(data) {
        switch (data) {
            case "file":
                return "Archivo";
                break;
            case "multifile":
                return "Multi archivo";
                break;
            case "radio":
                return "Opciones";
                break;
            case "select":
                return "Selector";
                break;
            case "input":
                return "Campo de texto";
                break;
            case "boolean":
                return "Responder sí o no";
                break;

            default:
                break;
        }
    }

    getOpciones(data) {
        if (
            data.type == "input" ||
            data.type == "file" ||
            data.type == "boolean" ||
            data.type == "multifile"
        ) {
            return "";
        }
        if (data.opciones == "" || data.opciones == null) {
            return "";
        }
        data = data.opciones;
        const arrayDeCadenas = data.split("|");
        if (arrayDeCadenas.length > 1) {
            let li = "";
            for (var i = 0; i < arrayDeCadenas.length; i++) {
                li += `<li>${arrayDeCadenas[i]}</li>`;
            }
            return li;
        } else {
            return "";
        }
    }
    getCondicion(data) {
        // console.log(data);
        const {
            condicion_visible,
            id_municipio,
            requerido,
            condicion_giro,
        } = data;
        if (condicion_visible && id_municipio != 0) {
            const arrayCondicion = condicion_visible.match(/\(([^)]+)\)/g);
            const condicionOY = condicion_visible.includes(") || (");
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
                        if(element.includes("check_alcohol")){
                            textCondicion += `<li>Venta de alcohol sea: Cualquiera</li> `;
                        }else{
                            textCondicion += `<li>${condicionNombre.includes("superficie_propiedad")? "Superficie de la propiedad": "Superficie de la actividad" }${element.includes(">=")? "Mayor a ": " Menor a "} ${valorCondicion}</li> `;
                        }
                        if(condicion_giro!=null){
                            textCondicion += `<li>Condicionado a un giro: ` + condicion_giro+`</li> `;
                        }
                    } else if (element.includes("==")) {
                        if (
                            element.includes("caracter") ||
                            element.includes("tipo_persona") || element.includes("check_alcohol")
                        ) {
                            if (element.includes("||")) {
                                let arrayCaracter = element.split("||");
                                //console.log(arrayCaracter);
                                let condicionTextCaracter = "(";
                                for (let i = 0; i < arrayCaracter.length; i++) {
                                    const elementCaracter = arrayCaracter[
                                        i
                                    ].split("==");
                                    let condicionNombre = elementCaracter[0]
                                        .match(/\/(\w*)/g)[0]
                                        .replace("/", "");
                                    let valorCondicion = elementCaracter[1]
                                        .replace(")", "")
                                        .replace(" ", "")
                                        .replace(/'/gi, "");
                                    condicionTextCaracter += `'${valorCondicion}' `;
                                }
                                condicionTextCaracter += ")";
                                textCondicion += `<li>
                                    Cuando el ${
                                        element.includes("tipo_persona")
                                            ? "Tipo de persona"
                                            : "caracter del solicitante "
                                    } sea  ${condicionTextCaracter}
                              </li>`;
                            } else {
                                let condicionTextCaracter;
                                const elementCaracter = element.split("==");
                                let condicionNombre = elementCaracter[0]
                                    .match(/\/(\w*)/g)[0]
                                    .replace("/", "");
                                let valorCondicion = elementCaracter[1]
                                    .replace(")", "")
                                    .replace(" ", "")
                                    .replace(/'/gi, "");
                                if(element.includes("check_alcohol")){
                                    switch(valorCondicion.trim()){
                                        case "1":{
                                            valorCondicion = 'Bebidas de baja graduación cerrada';
                                            break;
                                        }
                                        case "2":{
                                            valorCondicion = 'Bebidas de baja graduación abierta';
                                            break;
                                        }
                                        case "3":{
                                            valorCondicion = 'Bebidas de alta graduación cerrada';
                                            break;
                                        }
                                        case "4":{
                                            valorCondicion = 'Bebidas de alta graduación abierta';
                                            break;
                                        }
                                    }
                                    textCondicion += `<li>Venta de alcohol sea: ${valorCondicion}</li>`;
                                }else{
                                    textCondicion += `<li>Cuando el ${element.includes("tipo_persona")? "Tipo de persona": "caracter del solicitante "} sea  ${valorCondicion}</li>`;
                                }

                                if(condicion_giro!=null){
                                    console.log("Condicionado a un giro: "+ condicion_giro);
                                }

                                return textCondicion;
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
        } else if (requerido == 2 && condicion_giro != null) {
            console.log("aqui");
            return "Condicionado a un giro";
        } else {
            return "-";
        }
    }

    editRequisito(requisito): void {
        this.dialogRef = this._matDialog.open(RequisitosDialogComponent, {
            panelClass: "requisito-form-dialog",
            data: {
                requisito,
                action: "edit",
            },
        });
        this.dialogRef.afterClosed().subscribe((response: FormGroup) => {
            this.getData();
        });
    }

    getNextPage(e: PageEvent) {
        //console.log(e);
        this.page = e.pageIndex + 1;
        this.query.page = e.pageIndex;
        this.getData();
    }

    getData() {
        window.scroll(0, 0);
        this.isLoading = true;
        document.querySelector(`.header`).scrollIntoView();
        this._requisitos.getRequisitos(this.page, this.filter.value).subscribe(
            (res: any) => {
                this.total = res.total;
                this.list = res.data;
                this.isLoading = false;
            },
            (error) => {
                this.isLoading = false;
                //  console.log(error);
            }
        );
    }
}
