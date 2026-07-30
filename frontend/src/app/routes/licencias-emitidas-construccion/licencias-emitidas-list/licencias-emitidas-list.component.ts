import { Component, OnInit, TemplateRef, ViewChild } from "@angular/core";
import { FormControl, FormGroup, FormBuilder } from "@angular/forms";
import { MatDialog } from "@angular/material/dialog";
import { Router } from "@angular/router";
import { MtxGridColumn } from "@ng-matero/extensions";
import { HistoricoLicenciaService } from "app/services/historico/historico-licencia.service";
import { PageEvent } from "@angular/material/paginator";
import { environment } from "@env/environment";
import Swal from "sweetalert2";
import { LicenciasEmitidasDialogComponent } from "app/routes/licencias-emitidas-construccion/licencias-emitidas-dialog/licencias-emitidas-dialog.component";
import { LicenciaStatusService } from "app/services/licenciaConstruccion/licencia-status.service";
import { OrdenPagoComponent } from "app/routes/tramites-contruccion/orden-pago/orden-pago.component";
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
        header: "Folio ingreso",
        field: "folio_interno",
        showExpand: false,


        /*formatter: (data) => {
          let d = data.numero_lic.toString();
          return d.padStart(5, "0");
        }*/
      },
      {
        header: "Folio único resolutivo",
        field: "consecutivo",


      },
      {
        header: "Tipo de trámite",
        field: "tramite_relacionado_nombre",


      },
      {
        header: "Titular del trámite",
        field: "nombre_solicitante",
      },
      {
        header: "Domicilio", 
        field: "domicilio_tramite",
      },
      { header: "Estatus", field: "status_licencia" },
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
      confirmButtonColor: "#003E76",
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
      confirmButtonColor: "#003E76",
      cancelButtonColor: "#757575",
      confirmButtonText: "Confirmar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $event.returnValue = true;
        //row.status_pago=1;

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
      console.log(row.id);
      this._historicoLicencia.licenciaEscaneada2(row.id, formData).subscribe(
        (r: any) => {
          row.pdf_escaneado = r.data;
          window.open(r.data, '_blank')
          Swal.hideLoading();
        },
        e => {
          Swal.hideLoading();
          console.log(e);
        }
      )
    }
  }
  log(e: any) {
    console.log(e);
  }
  pagada(row) {
    this.isLoading = true;
    this._historicoLicencia.licenciaPagada2(row.id, 1).subscribe(
      (r: any) => {
        row.status_pago = 1;
        this.isLoading = false;
      },
      (e) => {
        this.isLoading = false;
        console.log(e);
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
  async UpdateStatusLicencia(row) {
    this.dialogRef = this._matDialog.open(LicenciasEmitidasDialogComponent, {
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
  editarLicencia(folio, municipio_id, data) {
    this.router.navigate(['/tramite/nuevo-tramites/' + folio + '/' + data.ids]);
  }
  editarLicenciaProrroga(folio, municipio_id, data, tipo) {
    this.router.navigate(['/tramite/prorroga-tramites/' + folio + '/' + data.ids + '/' + tipo]);
  }
  verDetalles(folio) {
    folio = this.btoaf(folio)
    this.router.navigate(['/tramites/detalle/' + folio]);
  }

  verOrden(row) {
    window.open(`${environment.SERVER_ORIGIN}${row.archivo_compobante_pago}`, '_blank')
  }
  getData() {
    this.isLoading = true;
    this._LicenciaSevice
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
  btoaf(f) {
    return btoa(f);
  }

  enviarLicenciaInteresados(folio:any){
    this._LicenciaSevice.enviarLicenciaInteresados(this.btoaf(folio)).subscribe((res: any) => {
      Swal.fire({
        title: "Correcto!",
        text: "Licencia enviada correctamente",
        icon: "success"
      });
      this.isLoading = false;
    },(error) => {
      this.isLoading = false;
      Swal.fire({
        title: "Error!",
        text: "Ha ocurrido un error",
        icon: "error"
      });
      //console.log(error);
    });
  }

}