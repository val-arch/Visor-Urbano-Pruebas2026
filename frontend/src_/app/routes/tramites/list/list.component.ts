import { Component, OnInit, TemplateRef, ViewChild } from "@angular/core";
import { FormBuilder, FormGroup } from "@angular/forms";
import { MatDialog } from "@angular/material/dialog";
import { MtxGridColumn } from "@ng-matero/extensions";
import { RolesService } from "app/services/administrador/roles/roles.service";
import { PageEvent } from "@angular/material/paginator";
import { MisTramitesService } from "../mis-tramites.service";
import { TokenService } from '@core/authentication/token.service';
import { EmitirLicenciaComponent } from '../emitir-licencia/emitir-licencia.component';
import { environment } from '@env/environment';
import { OrdenPagoComponent } from '../orden-pago/orden-pago.component';
@Component({
    selector: "tramites-list",
    templateUrl: "./list.component.html",
    styleUrls: ["./list.component.scss"],
})
export class ListComponent implements OnInit {
    columns: MtxGridColumn[] = [];
    columnsPrev: MtxGridColumn[] = [];
    columnsVentanilla: MtxGridColumn[] = [];

    @ViewChild("statusTpl", { static: true }) statusTpl: TemplateRef<any>;
    @ViewChild("statusTplPrev", { static: true }) statusTplPrev: TemplateRef<any>;
    role;
    tabs = 1;
    filtro = '';
    list = [];
    total = 0;
    listV = [];
    totalV = 0;
    listRev = [];
    totalRev = 0;
    isLoading = true;
    page = 1;
    editId = 0;
    listPrev = [];
    totalPrev = 0;
    isLoadingPrev = true;;
    query = {
        order: "desc",
        page: 0,
    };
    queryPrev = {
        order: "desc",
        page: 0,
    };
    dialogRef;

    constructor(
        private _matDialog: MatDialog,
        private _listado: MisTramitesService,
        private _formBuilder: FormBuilder,
        private _token: TokenService,
    ) {
        this.role = this._token.get().role;
    }

    ngOnInit(): void {

        this.makeTable();

        this.getData();
    }
    btoaf(f) {
        return btoa(f);
    }

    onClick(tab) {
        ////console.log(this.role, tab.index + 1)
        // this.tabs = tab.index + 1;
        this.tabs = tab.index + 1;
        this.page = 1;
        this.query = {
            order: "desc",
            page: 0,
        };
        if (this.role == 4 && tab.index + 1 == 2) {
            //console.log('if 1 ')
            this.getDataRev();
            return
        } else if ((this.role == 2 && tab.index + 1 == 2) || (this.role == 4 && tab.index + 1 == 3)) {
            //console.log('if 2')
            this.getDataSolv();
            return
        } else if((this.role == 4 && tab.index + 1 == 4) || (this.role == 3 && tab.index + 1 == 2)){
            this.getDataVentanilla();
            return
        }
         else {
            //console.log('if 3 ')
            this.getData();
            return
        }



    }
    
    getNextPage(e: PageEvent) {
        this.page = e.pageIndex + 1;
        this.query.page = e.pageIndex;
        if ((this.role == 4 && this.tabs == 2)) {
            this.getDataRev();
        } else if ((this.role == 2 && this.tabs == 2) || (this.role == 4 && this.tabs == 3)) {
            this.getDataSolv();
        }
        else if((this.role == 4 && this.tabs == 4) || (this.role == 3 && this.tabs == 2)){
            this.getDataVentanilla();
            return
        }
        else {
            this.getData();
        }
        // this.getData();
    }

    getStatus(item) {
        ////console.log(item)
        if ((this.role == 2) || (this.role == 4 && this.tabs == 4)) {
            if (item.aprobado_directo == 1) {
                return '<li class="text-success">Aprobado</li>'
            }else if (item.enviado_revisores == 1) {
                return '<li class="text-warning">En revision</li>';
            } else {
                return '<li class="text-default">Nuevo</li>';
            }
        } else {
            switch (item.status_actual) {
                case 1:
                    return '<li class="text-success">Aprobado</li>';
                    break;
                case 2:
                    return '<li class="text-danger">Desechado</li>';
                    break;
                case 3:
                    return '<li class="text-warning">Prevención</li>';
                    break;
                case 4:
                    return '<li class="text-success">Aprobado por director</li>';
                    break;
                case 7:
                    return '<li class="text-success">Licencia emitida en resumen por Ventanilla</li>';
                    break;
                case 8:
                    return '<li class="text-success">Licencia emitida en resumen por Revisor</li>';
                    break;
                case 9:
                    return '<li class="text-success">Licencia emitida en resumen por Director</li>';
                    break;
                case 9:
                    return '<li class="text-success">Licencia emitida en resumen</li>';
                    break;
                default:
                    return '<li class="text-default">Nuevo</li>';
                    break;
            }
        }
    }

    verLicencia(row) {
        window.open(`${environment.SERVER_ORIGIN}${row.pdf_licencia}`, '_blank')
    }
    verOrden(row) {
        window.open(`${environment.SERVER_ORIGIN}${row.orden_pago}`, '_blank')
    }

    emitirDialog(row) {
        this.dialogRef = this._matDialog.open(EmitirLicenciaComponent, {
            panelClass: 'emitir-form-dialog',
            width:'65%',
            height:'74%',
            disableClose:true,
            data: {
                action: 'new',
                data: row
            }
        });
        this.dialogRef.afterClosed()
            .subscribe((response: FormGroup) => {
                this.getData();
            });
    }
    ordenDialog(row) {
        this.dialogRef = this._matDialog.open(OrdenPagoComponent, {
            panelClass: 'orden-form-dialog',
            data: {
                action: 'new',
                data: row
            }
        });
        this.dialogRef.afterClosed()
            .subscribe((response: FormGroup) => {
                this.getData();
            });
    }


    getData(event = '') {
        this.filtro = event;
        if (event == '' || event.length > 3) {
            this.isLoading = true;
            this.total = 0;
            this._listado.getData(this.page, this.filtro).subscribe(
                (res: any) => {
                  console.log(res);
                  console.log(res.data.total);
                    this.total = res.data.total;
                    console.log(this.total);
                    this.list = res.data.data;
                    this.isLoading = false;
                },
                (error) => {
                    this.isLoading = false;
                    //console.log(error);
                }
            );
        }
    }
    getDataVentanilla(event = '') {
        this.filtro = event;
        if (event == '' || event.length > 3) {
            this.isLoading = true;
            this.total = 0;
            this._listado.getDataVentanilla(this.page, this.filtro).subscribe(
                (res: any) => {
                  console.log(res);
                    this.totalV = res.data.total;
                    this.listV = res.data.data;
                    this.isLoading = false;
                },
                (error) => {
                    this.isLoading = false;
                    //console.log(error);
                }
            );
        }
    }
    getDataRev(event = '') {
        this.filtro = event;
        if (event == '' || event.length > 3) {
            this.isLoading = true;

            this._listado.getDataDir(this.page, this.filtro).subscribe(
                (res: any) => {
                    console.log(res);
                    this.totalRev = res.data.total;
                    this.listRev = res.data.data;
                    this.isLoading = false;
                },
                (error) => {
                    this.isLoading = false;
                    //console.log(error);
                }
            );
        }
    }
    getDataSolv(event = '') {
        this.filtro = event;
        if (event == '' || event.length > 3) {
            this.isLoadingPrev = true;
            this.totalPrev = 0;
            this._listado.getDataSolv(this.page, this.filtro).subscribe(
                (res: any) => {
                    console.log(res);
                    this.totalPrev = res.data.total;
                    this.listPrev = res.data.data;
                    this.isLoadingPrev = false;
                },
                (error) => {
                    this.isLoadingPrev = false;
                    //console.log(error);
                }
            );
        }
    }

    makeTable() {
        const MESES = [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre",
        ];
        switch (this.role) {
            case 1:
                this.columns = [
                    {
                        header: "Folio",
                        field: "folio",
                        width: '100px'
                    },
                    {
                        header: "Dirección",
                        field: "calle",
                        formatter: (item) => {
                            return `${item.calle}, colonia ${item.colonia}`;
                        },
                    },
                    {
                        header: "Municipio",
                        field: "municipio",
                        // formatter: item => this.getStatus(item.status_actual)
                    },
                    {
                        header: "SCIAN",
                        field: "nombre_scian",
                    },
                    {
                        header: "Fecha de ingreso",
                        field: "fecha_inicio_tramite",
                        formatter: (item) => {
                            const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                            return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                        }
                    },

                    {
                        header: "Acciones",
                        pinned: "right",
                        field: "Acciones",
                        cellTemplate: this.statusTpl,
                    },
                ];

                break;
            case 2:
            case 3:
                this.columnsPrev = [
                    {
                        header: "Folio",
                        field: "folio",
                        width: '100px'
                    },
                    {
                        header: "Dirección",
                        field: "calle",
                        formatter: (item) => {
                            return `${item.calle}, colonia ${item.colonia}`;
                        },
                    },
                    {
                        header: "SCIAN",
                        field: "nombre_scian",
                    },
                    {
                        header: "Fecha de ingreso",
                        field: "fecha_inicio_tramite",
                        formatter: (item) => {
                            const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                            return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                        }
                    },
                    {
                        header: "Acciones",
                        pinned: "right",
                        field: "Acciones",
                        cellTemplate: this.statusTplPrev,
                    },
                ];
                this.columns = [
                    {
                        header: "Folio",
                        field: "folio",
                        width: '100px'
                    },
                    {
                        header: "Dirección",
                        field: "calle",
                        formatter: (item) => {
                            return `${item.calle}, colonia ${item.colonia}`;
                        },
                    },
                    {
                        header: "SCIAN",
                        field: "nombre_scian",
                    },
                    {
                        header: "Fecha de ingreso",
                        field: "fecha_inicio_tramite",
                        formatter: (item) => {
                            const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                            return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                        }
                    },
                    {
                        header: "Estatus",
                        field: "status",
                        formatter: item => this.getStatus(item)
                    },
                    {
                        header: "Acciones",
                        pinned: "right",
                        field: "Acciones",
                        cellTemplate: this.statusTpl,
                    },
                ];
                this.columnsVentanilla = [
                    {
                        header: "Folio",
                        field: "folio",
                        width: '100px'
                    },
                    {
                        header: "Dirección",
                        field: "calle",
                        formatter: (item) => {
                            return `${item.calle}, colonia ${item.colonia}`;
                        },
                    },
                    {
                        header: "Municipio",
                        field: "municipio",
                        // formatter: item => this.getStatus(item.status_actual)
                    },
                    {
                        header: "SCIAN",
                        field: "nombre_scian",
                    },
                    {
                        header: "Fecha de ingreso",
                        field: "fecha_inicio_tramite",
                        formatter: (item) => {
                            const f = new Date(item.created_at);
                            return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                        }
                    },
                    {
                        header: "Estatus",
                        field: "status",
                        formatter: item => this.getStatus(item)
                    },

                    {
                        header: "Acciones",
                        pinned: "right",
                        field: "Acciones",
                        cellTemplate: this.statusTpl,
                    },
                ];
                break
            case 4:

                this.columns = [
                    {
                        header: "Folio",
                        field: "folio",
                        width: '100px'
                    },
                    {
                        header: "Dirección",
                        field: "calle",
                        formatter: (item) => {
                            return `${item.calle}, colonia ${item.colonia}`;
                        },
                    },
                    {
                        header: "SCIAN",
                        field: "nombre_scian",
                    },
                    {
                        header: "Fecha de ingreso",
                        field: "fecha_inicio_tramite",
                        formatter: (item) => {
                            const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                            return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                        }
                    },
                    {
                        header: "Estatus",
                        field: "status",
                        formatter: item => this.getStatus(item)
                    },
                    {
                        header: "Acciones",
                        pinned: "right",
                        field: "Acciones",
                        cellTemplate: this.statusTpl,
                    },
                ];

                this.columnsPrev = [
                    {
                        header: "Folio",
                        field: "folio",
                        width: '100px'
                    },
                    {
                        header: "Dirección",
                        field: "calle",
                        formatter: (item) => {
                            return `${item.calle}, colonia ${item.colonia}`;
                        },
                    },
                    {
                        header: "SCIAN",
                        field: "nombre_scian",
                    },
                    {
                        header: "Fecha de ingreso",
                        field: "fecha_inicio_tramite",
                        formatter: (item) => {
                            const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                            return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                        }
                    },
                    {
                        header: "Acciones",
                        pinned: "right",
                        field: "Acciones",
                        cellTemplate: this.statusTplPrev,
                    },
                ];

                this.columnsVentanilla = [
                    {
                        header: "Folio",
                        field: "folio",
                        width: '100px'
                    },
                    {
                        header: "Dirección",
                        field: "calle",
                        formatter: (item) => {
                            return `${item.calle}, colonia ${item.colonia}`;
                        },
                    },
                    {
                        header: "Municipio",
                        field: "municipio",
                        // formatter: item => this.getStatus(item.status_actual)
                    },
                    {
                        header: "SCIAN",
                        field: "nombre_scian",
                    },
                    
                    {
                        header: "Fecha de ingreso",
                        field: "fecha_inicio_tramite",
                        formatter: (item) => {
                            const f = new Date(item.created_at);
                            return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                        }
                    },
                    {
                        header: "Estatus",
                        field: "status",
                        formatter: item => this.getStatus(item)
                    },

                    {
                        header: "Acciones",
                        pinned: "right",
                        field: "Acciones",
                        cellTemplate: this.statusTpl,
                    },
                ];

                break
            default:
                case 3:
                    this.columnsPrev = [
                        {
                            header: "Folio",
                            field: "folio",
                            width: '100px'
                        },
                        {
                            header: "Dirección",
                            field: "calle",
                            formatter: (item) => {
                                return `${item.calle}, colonia ${item.colonia}`;
                            },
                        },
                        {
                            header: "SCIAN",
                            field: "nombre_scian",
                        },
                        {
                            header: "Fecha de ingreso",
                            field: "fecha_inicio_tramite",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Acciones",
                            pinned: "right",
                            field: "Acciones",
                            cellTemplate: this.statusTplPrev,
                        },
                    ];
                    this.columns = [
                        {
                            header: "Folio",
                            field: "folio",
                            width: '100px'
                        },
                        {
                            header: "Dirección",
                            field: "calle",
                            formatter: (item) => {
                                return `${item.calle}, colonia ${item.colonia}`;
                            },
                        },
                        {
                            header: "SCIAN",
                            field: "nombre_scian",
                        },
                        {
                            header: "Fecha de ingreso",
                            field: "fecha_inicio_tramite",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Estatus",
                            field: "status",
                            formatter: item => this.getStatus(item)
                        },
                        {
                            header: "Acciones",
                            pinned: "right",
                            field: "Acciones",
                            cellTemplate: this.statusTpl,
                        },
                    ];
                    break;
        }

    }
}
