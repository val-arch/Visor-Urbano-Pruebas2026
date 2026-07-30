import { AfterViewInit, Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { CamposTramiteServiceService } from 'app/services/tramite/iniciar-tramite/campos-tramite-service.service';
import { fuseAnimations } from '@fuse/animations/index';
import { MatDialog } from '@angular/material/dialog';
import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-detalle',
  templateUrl: './detalle.component.html',
  styleUrls: ['./detalle.component.scss']
})
export class DetalleComponent implements OnInit {
  folio = '';
  tipo = '';
  id_tramite;
  curp;
  loading = true;
  loadingCampos = true;
  consultaPDF = '';
  cadena = '';
  cadena_firmada = '';
  dialogRef;
  role;
  folio64;
  cartaResponsiva = '';
  cartaResponsivaUp = '';
  constructor(
    private _campoDinamico: CamposTramiteServiceService,
    private route: ActivatedRoute,
    private _matDialog: MatDialog,
    private _token: TokenService,
    private _route: Router
  ) {
    this.folio64 = this.route.snapshot.params.folio;
    this.folio = atob(this.route.snapshot.params.folio);
    this.tipo = this.route.snapshot.params.tipo;
    this.role = this._token.get().role;

  }

  ngOnInit(): void {
  }

}
