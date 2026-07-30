import { Component, Input, OnInit, TemplateRef, ViewChild } from "@angular/core";
import { FormControl, FormGroup, FormBuilder } from "@angular/forms";
import { MatDialog } from "@angular/material/dialog";
import { Router } from "@angular/router";
import { MtxGridColumn } from "@ng-matero/extensions";
import { HistoricoLicenciaService } from "app/services/historico/historico-licencia.service";
import { PageEvent } from "@angular/material/paginator";
import { environment } from "@env/environment";
import Swal from "sweetalert2";
import { HistoricoDialogComponent } from "app/routes/historico/historico-dialog/historico-dialog.component";
import { HistoricoLicenciaDialogComponent } from "app/routes/historico/historico-licencia-dialog/historico-licencia-dialog.component";
import { LicenciaStatusDialogComponent } from "app/routes/licencias-emitidas-giro/licencia-status-dialog/licencia-status-dialog.component";
import { LicenciaStatusService } from "app/services/licencia/licencia-status.service";
import { HistoricoStatusDialogComponent } from "app/routes/historico/historico-status-dialog/historico-status-dialog.component";
import { OrdenPagoComponent } from "app/routes/tramites/orden-pago/orden-pago.component";
import { FuseSplashScreenService } from "@fuse/services/splash-screen.service";

@Component({
  templateUrl: "./licencias-emitidas-list.component.html",
  styleUrls: ["./licencias-emitidas-list.component.scss"],
})
export class LicenciasEmitidasListComponent implements OnInit {
  columns: MtxGridColumn[] = [];
  columns2: MtxGridColumn[] = [];

  displayedColumns: string[] = ['folio', 'folio_consulta', 'actividad', 'solicitantes', 'direccion', 'tipo_l', 'status_l', 'codigo', 'fecha_e', 'date_emi', 'status', 'acciones'];
  columnsHistorico: MtxGridColumn[] = [];

  filter = new FormControl("");
  @ViewChild("statusTpl", { static: true }) statusTpl: TemplateRef<any>;
  @ViewChild("statusTplPago", { static: true }) statusTplPago: TemplateRef<any>;
  @ViewChild("statusTplPagoHis", { static: true }) statusTplPagoHis: TemplateRef<any>;
  @ViewChild("statusBaja", { static: true }) statusBaja: TemplateRef<any>;
  @ViewChild('statusTplHis', { static: true }) statusTplHis: TemplateRef<any>;
  @ViewChild('statusTplStatus', { static: true }) statusTplStatus: TemplateRef<any>;

  //Variables Licencias Visor Urbano
  updateUser = false;
  list = [];
  repeditos = [];
  list2 = [];
  listHistorico2 = [];
  total = 0;
  id_tramite_refrendo;
  isLoading = true;
  page = 0;
  queryHis = {

    page: 1,
    per_page: 500,
  };

  editId = 0;
  query = {
    order: "desc",
    page: 0,
  };

  //Historico licencias Variables  
  dialogRef;
  listHistorico = [];
  listRefrendo = [];
  totalHistorico = 0;
  isLoadingHistorico = true;
  pageHistorico = 0;

  server = environment.SERVER_ORIGIN;


  constructor(
    private _matDialog: MatDialog,
    private _historicoLicencia: HistoricoLicenciaService,
    private router: Router,
    private _LicenciaSevice: LicenciaStatusService,
    private _splash: FuseSplashScreenService) { }
  ngOnInit(): void {
    // ${environment.SERVER_ORIGIN}
    this.columns = [
      {
        header: "Folio licencia",
        field: "numero_lic",
        showExpand: true,


        formatter: (data) => {
          let d = data.numero_lic.toString();
          return d.padStart(5, "0");
        }
      },
      {
        header: "Folio lista requisitos",
        field: "folio",


      },
      { header: "Actividad comercial", field: "actividad_comercial" },
      {
        header: "Solicitante/Titular",
        field: "dueno",
        formatter: (data) => {
          return data.dueno + ' ' + (data.apellido_p ?? '') + ' ' + (data.apellido_m ?? '');
        }
      },
      {
        header: "Domicilio", field: "calle",
        formatter: (data) => {
          return data.calle + ' ' + (data.colonia ?? '');
        }
      },
      { header: "Tipo licencia", field: "tipo_licencia" },
      { header: "Estatus licencia", field: "status_licencia" },
      {
        header: "Fecha de emisión",
        field: "fecha_emision",
        formatter: (data) => {
          let f = new Date(data.fecha_emision);
          return `${f.getDate()}/${f.getMonth() + 1}/${f.getFullYear()}`;
        },
      },

      {
        header: "Estatus pago",
        field: "status_pago",
        cellTemplate: this.statusTplPago,
      },

      {
        header: "Acciones",
        field: "municipio",
        cellTemplate: this.statusTpl,
      },
    ];
    this.columns2 = [
      {
        field: "numero_lic",
        showExpand: true,
        formatter: (data) => {
          let d = data.numero_lic.toString();
          return d.padStart(5, "0");
        }
      },
      {
        field: "folio",
      },
      { field: "actividad_comercial" },
      {
        field: "dueno",
        formatter: (data) => {
          let d = data.dueno + ' ' + (data.apellido_p ?? '') + ' ' + (data.apellido_m ?? '');

          return d;
        }
      },
      { field: "calle" },
      { field: "tipo_licencia" },
      { field: "status_licencia" },
      { field: "codigo_scian" },
      {

        field: "fecha_emision",
        formatter: (data) => {
          let f = new Date(data.fecha_emision);
          return `${f.getDate()}/${f.getMonth() + 1}/${f.getFullYear()}`;
        },
      },
      { field: "anio_licencia" },
      {

        field: "status_pago",
        cellTemplate: this.statusTplPago,
      },
      { field: "status_baja", cellTemplate: this.statusBaja },
      {

        field: "municipio",
        cellTemplate: this.statusTpl,
      },
    ];
    this.getData();
    this.columnsHistorico = [
      {
        header: 'Folio licencia',
        field: 'folio_licencia',
        showExpand: true,

      },
      {
        header: 'Actividad comercial',
        field: 'giro',

      },
      {
        header: 'Solicitante/Titular',
        field: "nombre_titular",
        formatter: (data) => {
          return data.razon_social ? data.razon_social : data.nombre_titular + ' ' + (data.apellido_p ?? '') + ' ' + (data.apellido_m ?? '');
        }

      },
      {
        header: 'Domicilio',
        field: 'calle_predio',
        formatter: (data) => {
          return data.calle_predio + ' no. ' + (data.num_ext_predio ?? '') + ' ' + (data.num_int_predio ?? '') + ' ' + (data.colonia_predio ?? '');
        }
      },
      {
        header: 'Tipo licencia',
        field: 'tipo_licencia',

      },
      {
        header: 'Fecha emisión',
        field: 'fecha_emision'
      },
      {
        header: 'Estatus licencia',
        field: 'status_licencia',
      },


      {
        header: "Estatus pago",
        field: "status_pago",
        cellTemplate: this.statusTplPagoHis,
      },

      {
        header: "Acciones",
        field: "municipio",
        cellTemplate: this.statusTplHis,
      }
    ];
    this.getDataHistorico();

  }

  verDetalle(d) {

  }

  async seguro($event, row) {

    if (row.status_pago == 1) return;
    $event.returnValue = false;
    await Swal.fire({
      title: "Confirmar pago",
      text: 'Actualizar el estatus de la licencia a "Pagada"',
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#70CE68",
      cancelButtonColor: "#757575",
      confirmButtonText: "Confirmar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $event.returnValue = true;
        //row.status_pago=1;
        this.pagada(row);
        this.ordenDialog(row)
        this.getData();
        //Cambio
        return true;
      } else {
        return false;
      }
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

  async seguroHis($event, row) {

    if (row.status_pago == 1) return;
    $event.returnValue = false;
    await Swal.fire({
      title: "Confirmar pago",
      text: 'Actualizar el estatus de la licencia a "Pagada"',
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#70CE68",
      cancelButtonColor: "#757575",
      confirmButtonText: "Confirmar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $event.returnValue = true;
        //row.status_pago=1;
        this.pagadaHis(row);
        return true;
      } else {
        return false;
      }
    });
  }
  async subirPdfEscaneado(row) {
    const { value: file } = await Swal.fire({
      title: 'Subir licencia escaneada',
      text: 'En este apartado se sube la licencia con las firmas',
      icon: 'info',
      input: 'file',
      inputAttributes: {
        'aria-label': 'Upload your profile picture'
      },
      inputValidator: (value) => {
        if (!value) {
          return 'Subir archivo'
        }
      },
    })
    if (file) {
      Swal.showLoading();

      const formData = new FormData();
      formData.append("licencia", file);
      this._historicoLicencia.licenciaEscaneada(row.id, formData).subscribe(
        (r: any) => {
          row.pdf_escaneado = r.data;
          window.open(r.data, '_blank')
          Swal.hideLoading();
        },
        e => {
          Swal.hideLoading();
        }
      )
    }
  }
  descargarDemo(event) {
    const url = this.router.serializeUrl(
      this.router.createUrlTree([`/custompage`])
    );

    window.open(environment.SERVER_ORIGIN + '' + 'formato-visor-historico.csv', '_blank');
  }
  downloadExcel(data) {
    this.dialogRef = this._matDialog.open(HistoricoDialogComponent, {
      data: {
        data,
        action: 'edit'
      }
    });
    this.dialogRef.afterClosed().subscribe(e => {
      this.getDataHistorico()
    })
  }
  log(e: any) {
    console.log(e);
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
      title: '<strong>¿Estás seguro que deseas eliminar el histórico?',
      icon: 'info',
      text: 'IMPORTANTE: Una vez borrado el histórico, no se podrán recuperar los archivos digitales de ningún expediente electrónico.',
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
            this.getDataHistorico();
          },
          error => {
            this.isLoading = false;
            console.log(error);
          }
        );
      }
    })
  }
  async subirPdfEscaneadoHis(row) {
    const { value: file } = await Swal.fire({
      title: 'Subir licencia escaneada',
      text: 'En este apartado se sube la licencia con las firmas',
      icon: 'info',
      input: 'file',
      inputAttributes: {
        'aria-label': 'Upload your profile picture'
      },
      inputValidator: (value) => {
        if (!value) {
          return 'Subir archivo'
        }
      },
    })
    if (file) {
      Swal.showLoading();
      // console.log(file.target.files);
      const formData = new FormData();
      formData.append("licencia", file);
      this._historicoLicencia.licenciaEscaneadaHist(row.id, formData).subscribe(
        (r: any) => {
          row.pdf_escaneado = r.data;
          window.open(r.data, '_blank')
          Swal.hideLoading();
        },
        e => {
          Swal.hideLoading();
        }
      )
    }
  }
  pagada(row) {
    this.isLoading = true;

    if (row.tipo_licencia == "Refrendo" && typeof row.folio != 'string') {
      this.pagadaHis(row);
    } else {
      this._historicoLicencia.licenciaPagada(row.id, 1).subscribe(
        (r: any) => {
          row.status_pago = 1;
          this.isLoading = false;
        },
        (e) => {
          this.isLoading = false;
        }
      );
    }

  }
  pagadaHis(row) {
    this.isLoading = true;
    this._historicoLicencia.licenciaPagadaHis(row.id, 1).subscribe(
      (r: any) => {
        row.status_pago = 1;
        this.isLoading = false;
      },
      (e) => {
        this.isLoading = false;
      }
    );
  }
  motivoBaja(row) {
    Swal.fire(row.motivo_baja)
  }
  t(word) {
    let d = word.toString();
    return d.padStart(5, "0")
  }
  date(date, i) {

    var f = new Date(date.replace(/-/g, '/'));
    return `${f.getDate()}/${f.getMonth() + 1}/${f.getFullYear()}`;
  }
  solicitante(a, b, c) {
    return a + ' ' + (b ?? '') + ' ' + (c ?? '');
  }
  async bajaLicencia(row) {
    this.isLoading = true;
    let text = '';
    this._historicoLicencia.bajaLicencia(row.id, { motivo: text }).subscribe(
      (r: any) => {

        this.isLoading = false;
      }, e => {
        console.error(e)
        this.isLoading = false;
      });
    row.status_baja = 1;
    row.motivo_baja = text;
    Swal.fire(text)
  }
  redireccionar(folio) {
  }
  refrendar(folio, municipio_id, row) {
    this.prepRefendo(folio, municipio_id, row['numero_lic']);
    folio = this.id_tramite_refrendo;
  }
  refrendarHis(id, municipio_id, row) {
    this.router.navigate(['refrendo/historico-refrendo/' + btoa(row.id) + '/' + btoa('refrendo')]);
  }
  async UpdateStatusLicencia(row) {
    this.dialogRef = this._matDialog.open(LicenciaStatusDialogComponent, {
      width: '30%',
      data: {
        id: row,
        action: 'edit'
      }
    });
    this.dialogRef.afterClosed().subscribe(e => {
      this.getData()
    })
  }

  async UpdateStatusLicenciaHis(row) {

    this.dialogRef = this._matDialog.open(HistoricoStatusDialogComponent, {
      width: '30%',
      data: {
        id: row,
        action: 'edit'
      }
    });
    this.dialogRef.afterClosed().subscribe(e => {
      this.getDataHistorico()
    })
  }
  editarLicencia(folio, municipio_id, data) {
    this.router.navigate(['/tramite/nuevo-tramites/' + folio + '/' + data.ids]);
  }
  editarLicenciaHist(folio, id, row) {
    this.router.navigate(['/refrendo/historico-refrendo/' + btoa(row.id) + '/' + btoa('edicion')]);
  }
  verAperturaProvicional(folio, id, row) {
    window.open(`${environment.SERVER_ORIGIN}`+'/aperturaProvisional/'+folio);
  }
  verOrden(row) {
    window.open(`${environment.SERVER_ORIGIN}${row.archivo_compobante_pago}`, '_blank')
  }
  getData() {
    this.isLoading = true;
    this._historicoLicencia
      .getLicenciasEmitidas(this.page, this.filter.value)
      .subscribe(
        (res: any) => {
          const repeditos3 = res.data["data"];
          this.total = res.data.total;
          this.list = res.data["data"];


          var indice = 0;
          const repeditos2 = [];
          const array2 = new Array();;
          const array3 = [];

          this.list.forEach(function (valor, indice, array) {
            const isInArray = repeditos2.includes(valor.folio);
            if (!isInArray) {
              array2.push(repeditos3[indice]);
              repeditos2.push(repeditos3[indice].folio);
            } else {
              array3.push(repeditos3[indice]);
            }
          });

          this.list = array3;
          this.list2 = array2;
          this.isLoading = false;
        },
        (error) => {
          this.isLoading = false;
          //console.log(error);
        }
      );
  }
  getNextPage(e: PageEvent) {
    this.page = e.pageIndex + 1;
    this.query.page = e.pageIndex;
    this.getData();
  }
  getNextPageHis(e: PageEvent) {
    this.queryHis.page = e.pageIndex;
    this.queryHis.per_page = e.pageSize;
    this.getDataHistorico();
  }
  btoaf(f) {
    return btoa(f);
  }
  editarCapa(data) {
    this.dialogRef = this._matDialog.open(HistoricoDialogComponent, {
      data: {
        data,
      }
    });
    this.dialogRef.afterClosed().subscribe(e => {
      this.getData();
    })
  }
  exportExcel() {
    this._splash.show();

    let filename = "respaldo historico licencias.xlsx";
    this._historicoLicencia.downloadReport(filename)
      .subscribe(
        (res: any) => {
          const url = window.URL.createObjectURL(res);
          const anchor = document.createElement("a");
          anchor.download = filename;
          anchor.href = url;
          anchor.click();
          this._splash.hide();

        },
        error => {

          console.log(error);
        }
      );
  }
  exportExcelVisor() {
    this._splash.show();
    let filename = "respaldo licencias visor.xlsx";
    this._historicoLicencia.downloadReportVisor(filename)
      .subscribe(
        (res: any) => {
          const url = window.URL.createObjectURL(res);
          const anchor = document.createElement("a");
          anchor.download = filename;
          anchor.href = url;
          anchor.click();
          this._splash.hide();
        },
        error => {
          console.log(error);
        }
      );


  }
  getDataHistorico() {
    this.isLoadingHistorico = true;
    this._historicoLicencia.getHistorico(this.queryHis.page, this.filter.value).subscribe(
      (res: any) => {
        const repeditos3 = res.data["data"];
        this.totalHistorico = res.data['total'];

        this.listHistorico = res.data['data'];
        const repeditos2 = [];
        const array2 = new Array();;
        const array3 = [];
        this.listHistorico.forEach(function (valor, indice, array) {
          const isInArray = repeditos2.includes(valor.folio_licencia);
          if (!isInArray) {
            array2.push(repeditos3[indice]);
            repeditos2.push(repeditos3[indice].folio_licencia);
          } else {
            array3.push(repeditos3[indice]);
          }
        });
        this.listHistorico2 = array3;
        this.listHistorico = array2;
        // this.totalHistorico = res.total;
        this.isLoadingHistorico = false;
      },
      error => {
        this.isLoadingHistorico = false;

      }
    );
  }
  getNextPageHistorico(e: PageEvent) {
    this.pageHistorico = e.pageIndex + 1;
    this.queryHis.page = e.pageIndex;
    this.getDataHistorico();
  }
  delHistorico(data) {

    Swal.fire({
      title: '<strong>¿Estas seguro que deseas eliminar el historico?</strong>',
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
  prepRefendo(folio, municipio_id, n) {
    this._LicenciaSevice.copyTramite(folio, municipio_id, n).subscribe(
      (res: any) => {
        var id = res.data;
        this.router.navigate(['/refrendo/' + btoa(id)]);
      },
      error => {

      }
    );
  }
}