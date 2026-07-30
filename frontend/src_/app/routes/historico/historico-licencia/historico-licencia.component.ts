import { Component, OnInit, TemplateRef, ViewChild } from '@angular/core';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { PageEvent } from '@angular/material/paginator';
import { Router } from '@angular/router';
import { MtxGridColumn } from '@ng-matero/extensions';
import { HistoricoDialogComponent } from 'app/routes/historico/historico-dialog/historico-dialog.component';
import { RolesService } from 'app/services/administrador/roles/roles.service';
import { UserroleService } from 'app/services/administrador/usuarios/userrole.service';
import { HistoricoLicenciaService } from 'app/services/historico/historico-licencia.service';
import Swal from 'sweetalert2';
import { HistoricoLicenciaDialogComponent } from '../historico-licencia-dialog/historico-licencia-dialog.component';
import { environment } from '../../../../environments/environment';
import { fuseAnimations } from '@fuse/animations';
@Component({
  selector: 'app-historico-licencia',
  templateUrl: './historico-licencia.component.html',
  styleUrls: ['./historico-licencia.component.scss'],
  animations: fuseAnimations
})
export class HistoricoLicenciaComponent implements OnInit {
  columns: MtxGridColumn[] = [];
  columnsRole: MtxGridColumn[] = [];
  filter = new FormControl('');
  @ViewChild('statusTpl', { static: true }) statusTpl: TemplateRef<any>;
  updateUser = false;
  list = [];
  total = 0;
  isLoading = true;
  page = 0;
  editId = 0;
  query = {
    order: 'desc',
    page: 0,
  };
  roleForm: FormGroup;
  dialogRef;
  listRole = [];
  totalRole = 0;
  isLoadingRole = true;
  pageRole = 0;
  queryRole = {
    order: 'desc',
    page: 0,
  };

  constructor(private _matDialog: MatDialog,
    private _roles: RolesService,
    private _formBuilder: FormBuilder,
    private _userRole: UserroleService,
    private _historicoLicencia: HistoricoLicenciaService,
    private router: Router,
  ) {
  }
  ngOnInit(): void {
    this.columns = [
      {
        header: 'Folio Licencia',
        field: 'folio_licencia',
      },
      {
        header: 'Giro',
        field: 'giro'
      },
      {
        header: 'Descripción',
        field: 'descripcion_detallada'
      },
      {
        header: 'Calle',
        field: 'calle',

      },
      {
        header: 'Número Int',
        field: 'numero_int',

      },
      {
        header: 'Código Giro',
        field: 'codigo_giro'
      },
      {
        header: 'Superficie  Giro',
        field: 'superficie_giro'
      },
      {
        header: 'Acciones',
        field: 'acciones',
        type: 'button',
        buttons: [
          {
            type: 'icon', tooltip: 'Editar', color: 'primary', text: 'Editar', icon: 'create',
            click: (data) => this.edtiHistorial(data)
          },
          {
            type: 'icon', tooltip: 'Dar de baja', color: 'warn', text: 'Baja', icon: 'delete',
            click: (data) => this.delHistorico(data)
          }
        ],
      }
    ];
    this.getData();
  }
  buscar() {
    console.log(this.filter.value);
    if (this.filter.value != '') {
      this.columns = [
        {
          header: 'Folio Licencia',
          field: 'folio_licencia',
        },
        {
          header: 'Giro',
          field: 'giro'
        },
        {
          header: 'Descripción',
          field: 'descripcion_detallada'
        },
        {
          header: 'Codigo Giro',
          field: 'codigo_giro'
        },
        {
          header: 'Superficie  Giro',
          field: 'superficie_giro'
        },
        {
          header: 'Acciones',
          field: 'acciones',
          type: 'button',
          buttons: [
            {
              type: 'icon', tooltip: 'Editar', color: 'primary', text: 'Editar', icon: 'create',
              click: (data) => this.edtiHistorial(data)
            },
            {
              type: 'icon', tooltip: 'Dar de baja', color: 'warn', text: 'Baja', icon: 'delete',
              click: (data) => this.delHistorico(data)
            }
          ],
        }
      ];
      this.getData();
    }
  }
  
  downloadExcel(data) {
    this.dialogRef = this._matDialog.open(HistoricoDialogComponent, {
      data: {
        data,
        action: 'edit'
      }
    });
    this.dialogRef.afterClosed().subscribe(e => {
      this.getData()
    })
  }
  delHistorico(data) {
    Swal.fire({
      title: '<strong>¿Estás seguro que deseas eliminar el histórico?</strong>',
      icon: 'info',
      showCancelButton: true,
      focusConfirm: false,
      confirmButtonText:
        'Confirmar',
      cancelButtonText:
        'Cancelar',
    }).then((result) => {
      if (result['isConfirmed']) {
        this._historicoLicencia.deleteHistorico(data.id).subscribe(
          (res: any) => {
            Swal.fire({
              title: '¡Éxito!',
              text: 'Eliminado Correctamente!',
              icon: 'warning',
              confirmButtonText: 'Ok'
            });
            this.getData();
          },
          error => {
            this.isLoading = false;
            console.log(error);
          }
        );
      }
    })
  }

  edtiHistorial(user) {
    this.dialogRef = this._matDialog.open(HistoricoLicenciaDialogComponent, {
      panelClass: 'contact-form-dialog',
      data: {
        user,
        action: 'edit'
      }
    });

    this.dialogRef.afterClosed()
      .subscribe(response => {
        if (!response) {
          return;
        }
        const actionType: string = response[0];
        const formData: FormGroup = response[1];
        switch (actionType) {
          /**
           * Save
           */
          case 'save':
            //   this._contactsService.updateContact(formData.getRawValue());
            break;
          /**
           * Delete
           */
        }
      });
  }
  deleteAll(event) {
    Swal.fire({
      title: '<strong>¿Estás seguro que deseas eliminar el histórico?"</strong>',
      icon: 'info',
      showCancelButton: true,
      focusConfirm: false,
      confirmButtonText:
        'Confirmar',
      cancelButtonText:
        'Cancelar',
    }).then((result) => {
      if (result['isConfirmed']) {
        this._historicoLicencia.deleteAllHistorico().subscribe(
          (res: any) => {
            Swal.fire({
              title: '¡Éxito!',
              text: 'Eliminado Correctamente!',
              icon: 'warning',
              confirmButtonText: 'Ok'
            });
            this.getData();
          },
          error => {
            this.isLoading = false;
            console.log(error);
          }
        );
      }
    })
  }

  editarCapa(data) {
    this.dialogRef = this._matDialog.open(HistoricoDialogComponent, {
      data: {
        data,
        action: 'edit'
      }
    });
    this.dialogRef.afterClosed().subscribe(e => {
      this.getData();
    })
  }
  descargarDemo(event) {
    const url = this.router.serializeUrl(
      this.router.createUrlTree([`/custompage`])
    );
   
    window.open(environment.SERVER_ORIGIN + '' + 'formato-visor-historico.csv', '_blank');
  }
  getData() {
    this.isLoading = true;
    this._historicoLicencia.getHistorico(this.page, this.filter.value).subscribe(
      (res: any) => {

        this.total = res.total;
        this.list = res.data['data'];
        console.log(this.list);
        this.isLoading = false;
      },
      error => {
        this.isLoading = false;
        console.log(error);
      }
    );
  }
  getNextPage(e: PageEvent) {
    this.page = e.pageIndex + 1;
    this.query.page = e.pageIndex;
    this.getData();
  }
}