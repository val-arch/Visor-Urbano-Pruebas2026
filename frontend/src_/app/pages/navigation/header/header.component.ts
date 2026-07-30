import { Component, OnInit,ViewEncapsulation,Input, ViewChild } from '@angular/core';
import { FuseConfigService } from '@fuse/services/config.service';
import { MatDialog } from '@angular/material/dialog';
import { Subscription } from 'rxjs';
import { MediaChange, MediaObserver } from '@angular/flex-layout';
import { TranslateService } from '@ngx-translate/core';
import { MatBottomSheet, MatBottomSheetRef } from '@angular/material/bottom-sheet';
import { LocalStorageService } from '@shared/services/storage.service';
import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';
import {locale as  english} from '../../i18n/en'
import {locale as esp } from '../../i18n/esp'
import {locale as pg } from '../../i18n/pg'

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss',"../../landing/landing.component.scss",],
  encapsulation: ViewEncapsulation.None,

})
export class HeaderComponent implements OnInit {
    private mediaSub: Subscription;
  deviceXs: boolean;
  private section: string;
  constructor(
    private _fuseConfigService: FuseConfigService ,
    public dialog: MatDialog,
    public mediaObserver: MediaObserver,
    private _translateService: TranslateService,
    private _bottomSheet: MatBottomSheet,
    private store: LocalStorageService,
    private _fuseTranslationLoaderService: FuseTranslationLoaderService
) {
  this._fuseTranslationLoaderService.loadTranslations(esp, english,pg);
    // Configure the layout
    this._fuseConfigService.config = {
        layout: {
            navbar: {
                hidden: true
            },
            toolbar: {
                hidden: true
            },
            footer: {
                hidden: true
            },
            sidepanel: {
                hidden: true
            }
        }
    };
}
sheetIdioma(){
  this._bottomSheet.open(BottomSheetIdiomaComponent);
}

idioma(lang){
  this.store.set('lang',lang)
  this._translateService.use(lang);
}

  ngOnInit(): void {

    const lang = this.store.get('lang')
    this._translateService.use(lang);
    this.mediaSub = this.mediaObserver.media$.subscribe((res: MediaChange) => {
        this.deviceXs = res.mqAlias === "xs" ? true : false;
      })
      
  }

  sectionView(section){
    try {
        document.querySelector(`#${section}`).scrollIntoView();
      } catch (e) {
       // console.log(e);
      }
  }

}


@Component({
  selector: 'bottom-sheet-idioma',
  templateUrl: './bottom-sheet-idioma.component.html',
  styleUrls: ['./header.component.scss',"../../landing/landing.component.scss",],
  encapsulation: ViewEncapsulation.None,

})
export class BottomSheetIdiomaComponent{
  constructor(private store: LocalStorageService,private _translateService: TranslateService,private _bottomSheetRef: MatBottomSheetRef<BottomSheetIdiomaComponent>) {}

  idioma(lang){
    this.store.set('lang',lang)
    this._translateService.use(lang);
    this._bottomSheetRef.dismiss();
    event.preventDefault();
  }
}