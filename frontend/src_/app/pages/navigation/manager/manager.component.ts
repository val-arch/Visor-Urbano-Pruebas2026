import {
    Component,
    OnInit,
    ViewEncapsulation,
    Input,
    ViewChild,
    OnDestroy,
} from "@angular/core";
import { FuseConfigService } from "@fuse/services/config.service";
import { MediaObserver, MediaChange } from "@angular/flex-layout";
import { Subscription } from "rxjs";
import { MatDialog } from "@angular/material/dialog";
import { fuseAnimations } from "@fuse/animations";
import { DialogAvisosPrivacidadComponent } from "app/pages/dialogs/dialog-avisos-privacidad/dialog-avisos-privacidad.component";
import { DialogScianComponent } from "app/pages/dialogs/dialog-scian/dialog-scian.component";
import { ManagerService } from "./manager.service";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";

import Swal from "sweetalert2";
@Component({
    templateUrl: "./manager.component.html",
    styleUrls: [
        "./manager.component.scss",
        "../../landing/landing.component.scss",
    ],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations,
})
export class ManagerComponent implements OnInit, OnDestroy {
    private mediaSub: Subscription;
    deviceXs: boolean;
    ingreso: boolean = false;
    dataModel: string = "";
    key: string = "";
    dataGet: any;
    agregar: Boolean = false;
    form: {
        id?;
        titulo;
        resumen;
        img;
        link;
        fecha_noticia;
        type?;
        body?;
        publicado?;
        password?;
    };
    loading;
    constructor(
        private _fuseConfigService: FuseConfigService,
        public mediaObserver: MediaObserver,
        public dialog: MatDialog,
        public _managerService: ManagerService,
        public _formBuilder: FormBuilder
    ) {
        this._fuseConfigService.config = {
            layout: {
                navbar: {
                    hidden: true,
                },
                toolbar: {
                    hidden: true,
                },
                footer: {
                    hidden: true,
                },
                sidepanel: {
                    hidden: true,
                },
            },
        };
    }
    topVal = 0;
    onScroll(e) {
        let scrollXs = this.deviceXs ? 55 : 73;
        if (e.srcElement.scrollTop < scrollXs) {
            this.topVal = e.srcElement.scrollTop;
        } else {
            this.topVal = scrollXs;
        }
    }
    ngOnInit(): void {
        this.mediaSub = this.mediaObserver.media$.subscribe(
            (res: MediaChange) => {
                this.deviceXs = res.mqAlias === "xs" ? true : false;
            }
        );
        console.log();
    }
    ngOnDestroy() {
        this.mediaSub.unsubscribe();
    }

    regresar() {
        this.getData();
        this.agregar = false;
    }

    getData() {
        // Swal.showLoading();
        this._managerService.getAll(this.key).subscribe(
            (r: any) => {
                // Swal.close();
                this.ingreso = true;
                this.dataGet = r.data;
            },
            (e) => {
                console.error(e);
            }
        );
    }

    add() {
        console.log(this.form);
        this.form.password = this.key;
        this._managerService.send(this.form).subscribe(
            (r: any) => {
                Swal.fire("Registrado con exito", "", "success");
                this.regresar();
            },
            (err) =>
                Swal.fire(
                    "Upss",
                    "Algo salio mal. intenta más tarde",
                    "warning"
                )
        );
    }

    publicar(item, publicar) {
        Swal.fire({
            title: "Estas a punto de cambiar el estatus",
            showCancelButton: true,
            confirmButtonText: `Guardar`,
        }).then((result) => {
            if (result.isConfirmed) {
                item.publicado = publicar;
                item.password = this.key;
                this._managerService.send(item).subscribe(
                    (r: any) => {
                        Swal.fire("Actualizado con exito", "", "success");
                        this.regresar();
                    },
                    (err) =>
                        Swal.fire(
                            "Upss",
                            "Algo salio mal. intenta más tarde",
                            "warning"
                        )
                );
            }
        });
    }

    eliminar(item) {
        Swal.fire({
            title: "Estas a punto de eliminar la publicación",
            showCancelButton: true,
            confirmButtonText: `Guardar`,
        }).then((result) => {
            if (result.isConfirmed) {
                item.password = this.key;
                this._managerService.delete(item).subscribe(
                    (r: any) => {
                        Swal.fire("Eliminado con exito", "", "success");
                        this.regresar();
                    },
                    (err) =>
                        Swal.fire(
                            "Upss",
                            "Algo salio mal. intenta más tarde",
                            "warning"
                        )
                );
            }
        });
    }

    createForm(data?) {
        if (data) {
            this.form = data;
        } else {
            this.form = {
                titulo: "",
                resumen: "",
                img: "",
                link: "",
                fecha_noticia: null,
                type: 1,
                publicado: 0,
                body: "",
            };
        }
        console.log(this.form);
        this.agregar = true;
    }
}
