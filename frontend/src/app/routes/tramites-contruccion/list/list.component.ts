import { Component, OnInit, TemplateRef, ViewChild } from "@angular/core";
import { FormBuilder, FormGroup } from "@angular/forms";
import { MatDialog } from "@angular/material/dialog";
import { MtxGridColumn } from "@ng-matero/extensions";
import { PageEvent } from "@angular/material/paginator";
import { MisTramitesService } from "../mis-tramites.service";
import { TokenService } from '@core/authentication/token.service';
import { EmitirLicenciaComponent } from '../emitir-licencia/emitir-licencia.component';
import { environment } from '@env/environment';
import { OrdenPagoComponent } from '../orden-pago/orden-pago.component';
import { ConditionPerson } from '../../../models/user2.service'
import { DialogResolutivoComponent } from './dialog-resolutivo/dialog-resolutivo.component';
import Swal from 'sweetalert2';

@Component({
    selector: "tramites-list",
    templateUrl: "./list.component.html",
    styleUrls: ["./list.component.scss"],
})
export class ListComponent implements OnInit {
    columns: MtxGridColumn[] = [];
    columnsGiros: MtxGridColumn[] = [];
    columnsPrev: MtxGridColumn[] = [];
    columnsVentanilla: MtxGridColumn[] = [];
    @ViewChild("statusTpl", { static: true }) statusTpl: TemplateRef<any>;
    @ViewChild("statusTplGiros", { static: true }) statusTplGiros: TemplateRef<any>;
    @ViewChild("statusTplPrev", { static: true }) statusTplPrev: TemplateRef<any>;
    role;
    tabs = 1;
    filtro = '';
    list = [];
    listGiros = [];
    total = 0;
    totalGiros = 0;
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
        this.getDataGiros();
    }
    btoaf(f) {
        return btoa(f);
    }

    resolutivo(id_tramite, id_municipio, tramite_relacionado, folio_interno){
        this.dialogRef = this._matDialog.open(DialogResolutivoComponent, {
            panelClass: 'emitir-form-dialog',
            width: '100%',
            disableClose: true,
            data: {
                id_tramite: tramite_relacionado,
                id_tramite_construccion : id_tramite,
                id_municipio : id_municipio,
                folio_interno : folio_interno
            }
        });
        this.dialogRef.afterClosed().subscribe((response: FormGroup) => {
            this.makeTable();
            this.getData();
        });
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
            this.getDataRev();
            return
        } else if ((this.role == 2 && tab.index + 1 == 2) || (this.role == 4 && tab.index + 1 == 3)) {
            //console.log('if 2')
            this.getDataSolv();
            return
        } else if ((this.role == 4 && tab.index + 1 == 4) || (this.role == 3 && tab.index + 1 == 2)) {
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
        else if ((this.role == 4 && this.tabs == 4) || (this.role == 3 && this.tabs == 2)) {
            this.getDataVentanilla();
            return
        }
        else {
            this.getData();
        }
        // this.getData();
    }

    getStatus(item) {
        if ((this.role == 2) || (this.role == 4 && this.tabs == 4)) {
            if (item.aprobado_directo == 1) {
                return '<li class="text-success">Aprobado</li>'
            } else if (item.enviado_revisores == 1) {
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
        if(!row.resolutivo){
            Swal.fire({
              title: '¿Estás seguro que deseas continuar?',
              text: 'Este trámite aún no cuenta con la información del resolutivo, se recomienda llenar dicha información.',
              showCancelButton: true,
              confirmButtonText: 'Sí, continuar',
              cancelButtonText: `Cancelar`,
              confirmButtonColor: "#003E76",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.dialogRef = this._matDialog.open(EmitirLicenciaComponent, {
                        panelClass: 'emitir-form-dialog',
                        width: '65%',
                        height: '74%',
                        disableClose: true,
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
            });
        }else{
            this.dialogRef = this._matDialog.open(EmitirLicenciaComponent, {
                panelClass: 'emitir-form-dialog',
                width: '65%',
                height: '74%',
                disableClose: true,
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
        if (event == '' || event.length > 2) {
            this.isLoading = true;
            this.total = 0;
            this._listado.getData(this.page, this.filtro).subscribe(
                (res: any) => {
                    this.total = res.data.total;
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

    getDataGiros(event = '') {
        this.filtro = event;
        if (event == '' || event.length > 3) {
            this.isLoading = true;
            this.total = 0;
            this._listado.getDataGiros(this.page, this.filtro).subscribe(
                (res: any) => {
                    this.totalGiros = res.data.total;
                    this.listGiros = res.data.data;
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
        ///Poner Persona condicion

        let userInfo = new ConditionPerson();
        if (userInfo.permiso_dir == 1 && userInfo.type_user == 1) {
            this.columns = [
                {
                    header: "Folio Ingreso",
                    field: "folio_interno",
                    width: '100px'
                },
                {
                            header: "Domicilio",
                            field: "direccion_tramite",
                        },
                        {
                            header: "Interesado",
                            field: "nombre_solicitante_oficial",
                        },
                {
                    header: "Ingreso",
                    field: "fecha_inicio_tramite",
                    formatter: (item) => {
                        const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                        return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                    }
                },
                {
                    header: "Última resolución",
                    field: "fecha_ultima_resolucion",
                    
                },
                {
                    header: "Sentido últ. Resolución",
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
                    header: "Folio Ingreso",
                    field: "folio_interno",
                    width: '100px'
                },
                {
                    header: "Domicilio",
                    field: "direccion_tramite",
                },
                {
                    header: "Interesado",
                    field: "nombre_solicitante_oficial",
                },
                {
                    header: "Ingreso",
                    field: "fecha_inicio_tramite",
                    formatter: (item) => {
                        const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                        return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                    }
                },
                {
                    header: "Última resolución",
                    field: "fecha_ultima_resolucion",
                    formatter: (item) => {
                        const f = new Date(this.role > 2 ? item.fecha_actualizacion : item.updated_at);
                        return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                    }
                },
                {
                    header: "Sentido últ. Resolución",
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
                    header: "Folio Ingreso",
                    field: "folio_interno",
                    width: '100px'
                },
                {
                    header: "Domicilio",
                    field: "direccion_tramite",
                },
                {
                    header: "Interesado",
                    field: "nombre_solicitante_oficial",
                },
                {
                    header: "Ingreso",
                    field: "fecha_inicio_tramite",
                    formatter: (item) => {
                        const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                        return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                    }
                },
                {
                    header: "Última resolución",
                    field: "fecha_ultima_resolucion",
                    formatter: (item) => {
                        const f = new Date(this.role > 2 ? item.fecha_actualizacion : item.updated_at);
                        return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                    }
                },
                {
                    header: "Sentido últ. Resolución",
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
        } else {
            switch (this.role) {
                case 1:
                    this.columns = [
                        {
                            header: "Folio Ingreso",
                            field: "folio_interno",
                            width: '100px'
                        },
                        {
                            header: "Domicilio",
                            field: "direccion_tramite",
                        },
                        {
                            header: "Interesado",
                            field: "nombre_solicitante_oficial",
                        },
                        {
                            header: "Ingreso",
                            field: "fecha_inicio_tramite",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Última resolución",
                            field: "fecha_ultima_resolucion",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_actualizacion : item.updated_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Sentido últ. Resolución",
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
                    this.columnsGiros = [
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
                        cellTemplate: this.statusTplGiros,
                    },
                ];

                    break;
                case 2:
                case 3:
                    this.columns = [
                        {
                            header: "Folio Ingreso",
                            field: "folio_interno",
                            width: '100px'
                        },
                        {
                            header: "Domicilio",
                            field: "direccion_tramite",
                        },
                        {
                            header: "Interesado",
                            field: "nombre_solicitante_oficial",
                        },
                        {
                            header: "Ingreso",
                            field: "fecha_inicio_tramite",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Última resolución",
                            field: "fecha_ultima_resolucion",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_actualizacion : item.updated_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Sentido últ. Resolución",
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
                            header: "Folio Ingreso",
                            field: "folio_interno",
                            width: '100px'
                        },
                        {
                            header: "Domicilio",
                            field: "direccion_tramite",
                        },
                        {
                            header: "Interesado",
                            field: "nombre_solicitante_oficial",
                        },
                        {
                            header: "Ingreso",
                            field: "fecha_inicio_tramite",
                            formatter: (item) => {

                                const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                                
                                
                            }
                        },
                        {
                            header: "Última resolución",
                            field: "fecha_ultima_resolucion",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_actualizacion : item.updated_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Sentido últ. Resolución",
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
                            header: "Folio Ingreso",
                            field: "folio_interno",
                            width: '100px'
                        },
                        {
                            header: "Domicilio",
                            field: "direccion_tramite",
                        },
                        {
                            header: "Interesado",
                            field: "nombre_solicitante_oficial",
                        },
                        {
                            header: "Ingreso",
                            field: "fecha_inicio_tramite",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Última resolución",
                            field: "fecha_ultima_resolucion",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_actualizacion : item.updated_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Sentido últ. Resolución",
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
                            header: "Folio Ingreso",
                            field: "folio_interno",
                            width: '100px'
                        },
                        {
                            header: "Domicilio",
                            field: "direccion_tramite",
                        },
                        {
                            header: "Interesado",
                            field: "nombre_solicitante_oficial",
                        },
                        {
                            header: "Ingreso",
                            field: "fecha_inicio_tramite",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Tipo de trámite",
                            field: "tramite_relacionado",
                            formatter: (item) => {
                                return `${item.tramite}`;
                            },
                        },
                        {
                            header: "Última resolución",
                            field: "fecha_ultima_resolucion",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_ultima_resolucion : item.fecha_ultima_resolucion);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Sentido últ. Resolución",
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
                            header: "Folio Ingreso",
                            field: "folio_interno",
                            width: '100px'
                        },
                        {
                            header: "Domicilio",
                            field: "direccion_tramite",
                        },
                        {
                            header: "Interesado",
                            field: "nombre_solicitante_oficial",
                        },
                        {
                            header: "Ingreso",
                            field: "fecha_inicio_tramite",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Última resolución",
                            field: "fecha_ultima_resolucion",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_actualizacion : item.updated_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Sentido últ. Resolución",
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
                            field: "folio_interno",
                            width: '100px'
                        },
                        {
                            header: "Domicilio",
                            field: "direccion_tramite",
                        },
                        {
                            header: "Interesado",
                            field: "nombre_solicitante_oficial",
                        },
                        {
                            header: "Ingreso",
                            field: "fecha_inicio_tramite",
                            formatter: (item) => {
                                if(item.fecha_inicio_tramite == null){
                                    return '-';
                                }else{
                                    const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                                    return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                                }
                            }
                        },
                        {
                            header: "Última resolución",
                            field: "fecha_ultima_resolucion",
                            formatter: (item) => {
                                if(item.fecha_ultima_resolucion == null){
                                    return '-';
                                }else{
                                    const f = new Date(this.role > 2 ? item.fecha_ultima_resolucion : item.updated_at);
                                    return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                                }
                                
                            }
                        },
                        {
                            header: "Sentido últ. Resolución",
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
                    this.columnsPrev = [
                        {
                            header: "Folio Ingreso",
                            field: "folio_interno",
                            width: '100px'
                        },
                        {
                            header: "Domicilio",
                            field: "direccion_tramite",
                        },
                        {
                            header: "Interesado",
                            field: "nombre_solicitante_oficial",
                        },
                        {
                            header: "Ingreso",
                            field: "fecha_inicio_tramite",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Última resolución",
                            field: "fecha_ultima_resolucion",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_actualizacion : item.updated_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Sentido últ. Resolución",
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
                    this.columns = [
                        {
                            header: "Folio Ingreso",
                            field: "folio_interno",
                            width: '100px'
                        },
                        {
                            header: "Domicilio",
                            field: "direccion_tramite",
                        },
                        {
                            header: "Interesado",
                            field: "nombre_solicitante_oficial",
                        },
                        {
                            header: "Ingreso",
                            field: "fecha_inicio_tramite",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_inicio_tramite : item.created_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Última resolución",
                            field: "fecha_ultima_resolucion",
                            formatter: (item) => {
                                const f = new Date(this.role > 2 ? item.fecha_actualizacion : item.updated_at);
                                return `${f.getDate()} de ${MESES[f.getMonth()]} de ${f.getFullYear()}`
                            }
                        },
                        {
                            header: "Sentido últ. Resolución",
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
}
