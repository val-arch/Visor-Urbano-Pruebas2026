import { Component, Input, OnInit, ViewEncapsulation } from "@angular/core";
import { MediaObserver, MediaChange } from "@angular/flex-layout";
import { MatDialog } from "@angular/material/dialog";
import { PageEvent } from "@angular/material/paginator";
import { fuseAnimations } from "@fuse/animations";
import { FuseConfigService } from "@fuse/services/config.service";
import { MtxGridColumn } from "@ng-matero/extensions";
import { DialogAvisosPrivacidadComponent } from "app/pages/dialogs/dialog-avisos-privacidad/dialog-avisos-privacidad.component";
import { DialogScianComponent } from "app/pages/dialogs/dialog-scian/dialog-scian.component";
import { Subscription } from "rxjs";
import { BoletinService } from "./boletin.service";

@Component({
    templateUrl: "./boletin.component.html",
    styleUrls: [
        "./boletin.component.scss",
        "../../landing/landing.component.scss",
    ],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations,
})
export class BoletinComponent implements OnInit {
    private mediaSub: Subscription;
    deviceXs: boolean;
    columns: MtxGridColumn[] = [];
    list = [];
    total = 0;
    isLoading = true;
    page = 0;
    query = {
      order: "desc",
      page: 0,
      filter: "",
  };

    constructor(
        private _fuseConfigService: FuseConfigService,
        public mediaObserver: MediaObserver,
        public dialog: MatDialog,
        private _boletin: BoletinService
    ) {
        this.mediaSub = this.mediaObserver.media$.subscribe(
            (res: MediaChange) => {
                // console.log(res.mqAlias);
                this.deviceXs = res.mqAlias === "xs" ? true : false;
            }
        );
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
      this.columns = [
        {
            header: "Folio",
            field: "folio",
            pinned: "left",
        },
        {
            header: "Actividad comercial",
            field: "actividad_comercial",
        },
        {
            header: "Código SCIAN",
            field: "codigo_scian",
        },
        {
            header: "Colonia",
            field: "colonia",
        },

        {
            header: "Municipio",
            field: "municipio",
        },
    ];
        this.getData();
    }
    ngOnDestroy() {
        this.mediaSub.unsubscribe();
    }
    openDialogAvisos() {
        this.dialog.open(DialogAvisosPrivacidadComponent);
    }
    openDialogScian() {
        this.dialog.open(DialogScianComponent);
    }

    getData(event = "") {
        this.isLoading = true;

        this._boletin
            .getData(this.page)
            .toPromise()
            .then((res: any) => {
              console.log(res);
              console.log(res.data.total);
                this.total = res.data.total;
                this.list = res.data.data;
                this.isLoading = false;
            });
    }
    getNextPage(e: PageEvent) {
        this.page = e.pageIndex + 1;
        this.query.page = e.pageIndex;
        this.getData();
    }
}
