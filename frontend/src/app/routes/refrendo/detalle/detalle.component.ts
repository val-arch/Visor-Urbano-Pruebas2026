import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { CamposTramiteServiceService } from 'app/services/tramite/iniciar-tramite/campos-tramite-service.service';
import { MatDialog } from '@angular/material/dialog';
import { TokenService } from '@core/authentication/token.service';

@Component({
  selector: 'app-detalle',
  templateUrl: './detalle.component.html',
  styleUrls: ['./detalle.component.scss']
})
export class DetalleComponent implements OnInit {

  folio = '';
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
    private route: ActivatedRoute,
    private _token: TokenService,
  ) {
    this.folio64 = this.route.snapshot.params.folio;
    this.folio = atob(this.route.snapshot.params.folio);
    this.role = this._token.get().role;

  }

  ngOnInit(): void {
  }
}
