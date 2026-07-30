import {Component, OnInit, TemplateRef, ViewChild} from '@angular/core';
import {MtxGridColumn} from "@ng-matero/extensions";
import {MapaService} from '../../../../services/mapa/mapa.service'
import {LocalStorageService} from "@shared/services/storage.service";
import {CapasMunicipioDialogComponent} from "../capas-municipio-dialog/capas-municipio-dialog.component";
import {MatDialog} from "@angular/material/dialog";

@Component({
    selector: 'app-capas-municipio-list',
    templateUrl: './capas-municipio-list.component.html',
    styleUrls: ['./capas-municipio-list.component.scss']
})
export class CapasMunicipioListComponent implements OnInit {
    @ViewChild('accionesTpl', {static: true}) accionesTpl: TemplateRef<any>;

    public action: string;

    columns: MtxGridColumn[] = [];
    list = [];
    total = 0;
    isLoading = true;
    disabledCheck = false;
    page = 0;
    editId = 0;
    query = {
        order: 'desc',
        page: 0,
    };
    dialogRef;

    constructor(private _mapaService: MapaService,
                private _matDialog: MatDialog,
                private _store: LocalStorageService,
    ) {
    }

    ngOnInit(): void {
        this.columns = [
            {
                header: 'Nombre de capa',
                field: 'label',
                description: 'Nombre de la capa'
            },
            {
                header: 'Tipo', field: 'type',
                description: 'Tipo de servicio de capa'
                //formatter: (data) => this.getType(data.type)
            },
            {
                header: 'Visible',
                field: 'visible',
                description: 'Indica si la capa es visible de forma predeterminada'
            },
            {
                header: 'Activa',
                field: 'active',
                description: 'Indica si la capa está activa para mostrar al público'
            },
            {
                header: 'Opacidad',
                field: 'opacity',
                description: 'Opacidad inicial de la capa en el mapa'
            },
            {
                header: 'Formato',
                field: 'format',
                description: 'Formato de imagen (solo aplica en WMS)'
            },
            {
                header: 'Orden',
                field: 'order',
            },
            {
                header: 'Acciones',
                field: 'id',
                type: 'button',
                cellTemplate: this.accionesTpl
            }
        ];
        this.getData();
    }

    subirCapa(data) {
        data.order = data.order - 1
        this.actualizarCapa(data)
    }

    bajarCapa(data) {
        data.order = data.order + 1
        this.actualizarCapa(data)
    }

    editarCapa(data) {
        this.dialogRef = this._matDialog.open(CapasMunicipioDialogComponent, {
            data: {
                data,
                action: 'edit'
            }
        });

        this.dialogRef.afterClosed().subscribe(e => {
            this.getData()
        })

    }

    actualizarCapa(data) {
        this._mapaService.actualizarCapaMunicipio(data).subscribe()
        setTimeout(() => {
            this.getData()
        }, 2000)
    }

    eliminarCapa(id) {
        if (id) {
            this._mapaService.eliminarCapaMunicipio(id).subscribe()
            setTimeout(() => {
                this.getData()
            }, 1000)
        } else {
            this.getData()
        }
    }

    isUpDisabled(data): boolean {
        return (data.order <= 0 || data.order >= 1001)
    }

    getData() {
        let user = this._store.get('usr')
        this._mapaService.getCapasMunicipio(user.id_municipio).subscribe(
            (capas: any) => {
                this.list = capas
                this.total = capas.length
                this.isLoading = false;
            },
            (error) => {
                this.isLoading = false;
                // console.log(error);
            }
        )
    }

}
