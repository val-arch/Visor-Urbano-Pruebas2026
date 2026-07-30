import { Component } from '@angular/core';

import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';

import { locale as english } from './i18n/en';
import { locale as turkish } from './i18n/tr';
import { locale as esp } from './i18n/esp';
import { LocalStorageService } from '@shared/services/storage.service';

@Component({
    selector   : 'sample',
    templateUrl: './sample.component.html',
    styleUrls  : ['./sample.component.scss']
})
export class SampleComponent
{
    /**
     * Constructor
     *
     * @param {FuseTranslationLoaderService} _fuseTranslationLoaderService
     */
    constructor(
        private _fuseTranslationLoaderService: FuseTranslationLoaderService,
        private _storage: LocalStorageService
    )
    {
        this._fuseTranslationLoaderService.loadTranslations(english, turkish,esp);
        this._storage.set('Prueba',12322);
    }
}
