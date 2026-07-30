import { Component, OnInit, Input } from "@angular/core";
import { MatDialog } from "@angular/material/dialog";
import { fuseAnimations } from "@fuse/animations";
import { ContentService } from "./content.service";


import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';

import {locale as  english} from '../../i18n/en'
import {locale as esp } from '../../i18n/esp'
import {locale as pg } from '../../i18n/pg'
import {locale as fr } from '../../i18n/fr'

@Component({
    selector: "app-content",
    templateUrl: "./content.component.html",
    styleUrls: ["./content.component.scss"],
    animations: fuseAnimations,
})

export class ContentComponent {
    constructor(public dialog: MatDialog, public _content: ContentService,
        private _fuseTranslationLoaderService: FuseTranslationLoaderService) {
            this._fuseTranslationLoaderService.loadTranslations(esp, english,pg,fr);
        }
    noti: { data };
    isLoading = true;
    ngOnInit(): void {
        this._content.get().subscribe(
            (r: any) => {
                this.isLoading = false;
                this.noti = r.data;
            },
            (err) => {
                this.isLoading = false;
            }
        );
    }

    @Input() deviceXs: boolean;
    topVal = 0;
    dataModel: any;
    onScroll(e) {
        let scrollXs = this.deviceXs ? 55 : 73;
        if (e.srcElement.scrollTop < scrollXs) {
            this.topVal = e.srcElement.scrollTop;
        } else {
            this.topVal = scrollXs;
        }
    }
    sideBarScroll() {
        let e = this.deviceXs ? 160 : 130;
        return e - this.topVal;
    }

    prueba() {
        console.log(this.dataModel);
    }
}
