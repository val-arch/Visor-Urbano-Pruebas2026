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
    columnsConstruccion: MtxGridColumn[] = [];
    columnsPrev: MtxGridColumn[] = [];
    columnsDes: MtxGridColumn[] = [];
    columnsVentanilla: MtxGridColumn[] = [];

    @ViewChild("statusTpl", { static: true }) statusTpl: TemplateRef<any>;
    @ViewChild("statusTplConstruccion", { static: true }) statusTplConstruccion: TemplateRef<any>;
    @ViewChild("statusTplPrev", { static: true }) statusTplPrev: TemplateRef<any>;
    role;
    tabs = 1;
    filtro = '';
    list = [];
    listConstruccion = [];
    total = 0;
    totalConstruccion = 0;
    listV = [];
    listDes = [];
    totalV = 0;
    totalDes = 0;
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
        this.getDataConstruccion();
    }
    btoaf(f) {
        return btoa(f);
    }

    onClick(tab) {
        console.log(this.role, tab.index + 1)
        // this.tabs = tab.index + 1;
        this.tabs = tab.index + 1;
        this.page = 1;
        this.query = {
            order: "desc",
            page: 0,
        };

        switch(this.role){
            case 4:{
                switch(tab.index+1){
                    case 1:{
                        this.getData();
                        break;
                    }
                    case 2:{
                        this.getDataRev();
                        break;
                    } 
                    case 3:{
                        this.getDataSolv();
                        break;
                    }
                    case 4:{
                        this.getDataDesechados();
                        break;
                    }
                    case 5:{
                        this.getDataVentanilla();
                        break;
                    }
                }
                
                break;
            }
            case 3:{
                switch(tab.index+1){
                    case 1:{
                        this.getData();
                        break;
                    }
                    case 2:{
                        this.getDataSolv();
                        break;
                    }
                    case 3:{
                        this.getDataDesechados();
                        break;
                    }
                    case 4:{
                        this.getDataVentanilla();
                        break;
                    }
                }
                break;
            } 
            case 2:{
                switch(tab.index+1){
                    case 1:{
                        this.getDataVentanilla2();
                        break;
                    }
                    case 2:{
                        this.getDataSolv();
                        break;
                    }
                    case 3:{
                        this.getDataDesechados();
                        break;
                    }
                }
                break;
            }
        }

        /*if (this.role == 4 && tab.index + 1 == 2) {
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
             if(this.role == 1 && tab.index == 0){
                this.getData();
            }else if(this.role == 1 && tab.index == 1){
                this.getDataConstruccion();
            }
            return
        }*/



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
        if(item.desechado){
            return '<li class="text-danger">Desechado</li>';
        }
        if ((this.role == 2) || (this.role == 4 && this.tabs == 5)) {
            if (item.aprobado_director == 1) {
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

    getDataConstruccion(event = '') {
        this.filtro = event;
        if (event == '' || event.length > 3) {
            this.isLoading = true;
            this.total = 0;
            this._listado.getDataConstruccion(this.page, this.filtro).subscribe(
                (res: any) => {
                    this.totalConstruccion = res.data.total;
                    this.listConstruccion = res.data.data;
                    this.isLoading = false;
                    console.log(this.listConstruccion);
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

    getDataVentanilla2(event = '') {
        this.filtro = event;
        if (event == '' || event.length > 3) {
            this.isLoading = true;
            this.total = 0;
            this._listado.getDataVentanilla(this.page, this.filtro).subscribe(
                (res: any) => {
                  console.log(res);
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

    getDataDesechados(event = '') {
        this.filtro = event;
        if (event == '' || event.length > 3) {
            this.isLoading = true;
            this.total = 0;
            this._listado.getDataDesechados(this.page, this.filtro).subscribe(
                (res: any) => {
                  console.log(res);
                    this.totalDes = res.data.total;
                    this.listDes = res.data.data;
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

                this.columnsConstruccion = [
                        {
                            header: "Folio",
                            field: "folio_interno",
                            width: '100px'
                        },
                        {
                            header: "Domicilio",
                            field: "calle",
                            formatter: (item) => {
                                return `${item.calle}, colonia ${item.colonia}`;
                            },
                        },
                        {
                            header: "Interesado",
                            field: "interesado",
                            formatter: (item) => {
                                return `${item.nombre_solicitante}`;
                            },
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
                            cellTemplate: this.statusTplConstruccion,
                        },
                    ];

                break;
            case 2:
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
                this.columnsDes = [
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
                this.columnsDes = [
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

                this.columnsDes = [
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
                    this.columnsDes = [
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
