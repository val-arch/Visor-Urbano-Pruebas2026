import { AfterViewInit, Component, OnInit } from "@angular/core";
import { ActivatedRoute, Router } from "@angular/router";
import { CamposTramiteServiceService } from "app/services/tramite/iniciar-tramite/campos-tramite-service.service";
import { ResumenService } from "../../../../services/tramite/resumen.service";
import { fuseAnimations } from "@fuse/animations/index";
import { MatDialog } from "@angular/material/dialog";
import { FirmarComponent } from "../shared/firmar/firmar.component";
import { TokenService } from "@core/authentication/token.service";
import { environment } from "@env/environment";
import Swal from "sweetalert2";
import { EmitirLicenciaComponent } from "../../../tramites/emitir-licencia/emitir-licencia.component";

@Component({
    selector: "app-resumen",
    templateUrl: "./resumen.component.html",
    styleUrls: ["./resumen.component.scss", "../../formulario-vu.scss"],
    animations: fuseAnimations,
})
export class ResumenComponent implements OnInit {
    id_tramite;
    curp;
    dialogRef;
    role;
    folio64;
    questions2: [];
    folio = "";
    cartaResponsiva = "";
    cartaResponsivaUp = "";
    cadena = "";
    cadena_firmada = "";
    loading = true;
    loadingCampos = true;
    consultaPDF = "";
    lic_v = 0 ;
    constructor(
        private _campoDinamico: CamposTramiteServiceService,
        private _resumenService: ResumenService,
        private route: ActivatedRoute,
        private _matDialog: MatDialog,
        private _token: TokenService,
        private _route: Router
    ) {
        this.folio64 = this.route.snapshot.params.folio;
        this.folio = atob(this.route.snapshot.params.folio);
        this.role = this._token.get().role;
    }

    ngOnInit(): void {
        // this.generateCadena();
    }

    carta() {
        window.open(
            `${environment.SERVER_ORIGIN}cartaResponsiva/${btoa(this.folio)}`,
            "_blank"
        );
    }
    cartaUpload() {
        console.log(this.cartaResponsivaUp);
        window.open(
            `${environment.SERVER_ORIGIN}/${this.cartaResponsivaUp}`,
            "_blank"
        );
    }

    subirCarta(event) {
        const formData = new FormData();
        formData.append("file", event.target.files[0]);
        formData.append("carta", "true");
        this._resumenService.uploadCarta(formData, this.folio).subscribe(
            (r: any) => {
                console.log(r);
                this.cartaResponsivaUp = r.data;
            },
            (e) => {
                console.error(e);
            }
        );
    }

    openFirma() {
        this.dialogRef = this._matDialog.open(FirmarComponent, {
            width: "350px",
            panelClass: "firmar-form-dialog",
            data: {
                action: "firmar",
                cadena: this.cadena,
                curp: this.curp,
                id_tramite: this.id_tramite,
                parte_tramite: "firmar_licencia_tramite",
            },
        });
        this.dialogRef.afterClosed().subscribe((response: any) => {
            if (response.status) {
                this.cadena_firmada = response.cadena;
            }
        });
    }

    cadenaAfirmar(event) {
        this.curp = event.curp;
        this.cadena = event.cadena;
    }

    noTengoFirma() {
        this._resumenService.noFirmaElectronica(this.folio).subscribe(
            (r: any) => {
                console.log(r);
                let acuse = "Continuar";
                if (r.data.acuse_no_firma != false) {
                    window.open(
                        `${environment.SERVER_ORIGIN}${r.data.acuse_no_firma}`,
                        "_blank"
                    );
                    acuse = `Para descargar tu acuse da clic <a target="_blank" href="${environment.SERVER_ORIGIN}${r.data.acuse_no_firma}">AQUÍ.</a>`;
                }
                Swal.fire({
                    title: "¡Muy bien!",
                    html: acuse,
                    icon: "success",
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    confirmButtonText: "Ok!",
                }).then((result) => {
                    // Read more about isConfirmed, isDenied below
                    if (result.isConfirmed) {
                        this._route.navigate(["/tramites"]);
                    }
                });
            },
            (e) => {
                console.error(e);
            }
        );
    }

    swalFirma() {
        Swal.fire({
            title:
                '<strong>Seleccionaste la opción de "no tengo firma electrónica"</strong>',
            icon: "info",
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: "Confirmar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result["isConfirmed"]) {
                this.noTengoFirma();
            }
        });
    }

    continuar() {
        this._resumenService.tramiteContinuar(this.folio).subscribe(
            (r: any) => {
                let text,
                    apertura = "",
                    acuse = "";

                if(r.data == "Tu solicitud se ha enviado a la ventanilla del municipio para su correcto ingreso"){
                    Swal.fire({
                    title: "Solicitud concluida",
                    html: r.data,
                    width: "40%",
                    heightAuto: true,
                    icon: "success",
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#003E76",
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        this._route.navigate(["/tramites"]);
                    }
                });
                    return false;
                }

                if (r.data.apertura_provisional_url != false) {
                    window.open(
                        `${environment.SERVER_ORIGIN}${r.data.apertura_provisional_url}`,
                        "_blank"
                    );
                    apertura = `${environment.SERVER_ORIGIN}${r.data.apertura_provisional_url}`;
                }
                if (r.data.acuse_firma_electronica_url) {
                    text = `<div style="font-weight: 400; font-size: 14px;"><b>IMPORTANTE</b>: El trámite fue turnado para revisión. Cualquier avance será notificado por Visor Urbano y al correo electrónico señalado para recibir notificaciones. 
                    ${apertura != "" ? 'Para descargar tu Cédula de Apertura Provisional da clic <a target="_blank" href="' + apertura +'">AQUÍ.</a>': ""} Para descargar el acuse de recibo da clic <a target="_blank" href="${
                        environment.SERVER_ORIGIN}${r.data.acuse_firma_electronica_url}">AQUÍ.</a></div>`;
                    window.open(
                        `${environment.SERVER_ORIGIN}${r.data.acuse_firma_electronica_url}`,
                        "_blank"
                    );
                }
                if (this.role > 1 || r.data.acuse_Ventanilla) {
                    text = `<div style="font-weight: 400; font-size: 14px;"><b>IMPORTANTE</b>: El trámite fue turnado para revisión. Cualquier avance será notificado por Visor Urbano y al correo electrónico señalado para recibir notificaciones. 
        ${
            apertura != ""
                ? 'Para descargar tu Cédula de Apertura Provisional da clic <a target="_blank" href="' +
                  apertura +
                  '">AQUÍ.</a>'
                : ""
        } Para descargar el acuse de recibo da clic <a target="_blank" href="${
                        environment.SERVER_ORIGIN
                    }${r.data.acuse_Ventanilla}">AQUÍ.</a></div>`;
                    window.open(
                        `${environment.SERVER_ORIGIN}${r.data.acuse_Ventanilla}`,
                        "_blank"
                    );
                }
                Swal.fire({
                    title: "Solicitud concluida",
                    html: text,
                    width: "40%",
                    heightAuto: true,
                    icon: "success",
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#003E76",
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        this._route.navigate(["/tramites"]);
                    }
                });
            },
            (e) => {
                console.error(e);
            }
        );
    }

    continuarGenerar() {
        this.dialogRef = this._matDialog.open(EmitirLicenciaComponent, {
            panelClass: "emitir-form-dialog",
            width: "50%",
            height: "60%",
            disableClose: true,
            data: {
                action: "new",
                data: { folio: this.folio },
                type_lic: 2,
            },
        });
        this.dialogRef.afterClosed().subscribe((response) => {
            //console.log(response);
            if (response.status) {
                this._route.navigate(["/tramites"]);
            }
            //
        });
    }

    loadingOff(event) {
        console.log(event);
        this.id_tramite = event.id_tramite;
        this.loading = event.loading;
        this.lic_v = event.lic_v;
        if (event.cadenaFirmada != 0) {
            this.cadena_firmada = event.cadenaFirmada;
        }
        if (event.cartaResponsiva != 0) {
            this.cartaResponsivaUp = event.cartaResponsiva;
        }
    }
}
