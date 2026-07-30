import { Component, Inject, OnDestroy, OnInit } from '@angular/core';
import { DOCUMENT } from '@angular/common';
import { Platform } from '@angular/cdk/platform';
import { TranslateService } from '@ngx-translate/core';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';

import { FuseConfigService } from '@fuse/services/config.service';
import { FuseNavigationService } from '@fuse/components/navigation/navigation.service';
import { FuseSidebarService } from '@fuse/components/sidebar/sidebar.service';
import { FuseSplashScreenService } from '@fuse/services/splash-screen.service';
import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';

import { navigation } from 'app/navigation/navigation';
import { locale as navigationEs } from 'app/navigation/i18n/esp';
import { locale as navigationEnglish } from 'app/navigation/i18n/en';
import { locale as navigationTurkish } from 'app/navigation/i18n/tr';
import { navigationRevisor } from 'app/navigation/navigationRevisor';
import { navigationAdmin } from 'app/navigation/navigationAdmin';
import { navigationVentanilla } from 'app/navigation/navigationVentanilla';
import { navigationUser } from 'app/navigation/navigationUser';
import { navigationTec } from 'app/navigation/navigationTec';
import { TokenService } from './core/authentication/token.service';
import { Router, NavigationEnd } from '@angular/router'; 

// declare let ga: Function;

@Component({
    selector   : 'app',
    templateUrl: './app.component.html',
    styleUrls  : ['./app.component.scss']
})
export class AppComponent implements OnInit, OnDestroy
{
    fuseConfig: any;
    navigation: any;

    // Private
    private _unsubscribeAll: Subject<any>;

    /**
     * Constructor
     *
     * @param {DOCUMENT} document
     * @param {FuseConfigService} _fuseConfigService
     * @param {FuseNavigationService} _fuseNavigationService
     * @param {FuseSidebarService} _fuseSidebarService
     * @param {FuseSplashScreenService} _fuseSplashScreenService
     * @param {FuseTranslationLoaderService} _fuseTranslationLoaderService
     * @param {Platform} _platform
     * @param {TranslateService} _translateService
     */
    constructor(
        @Inject(DOCUMENT) private document: any,
        private _fuseConfigService: FuseConfigService,
        private _fuseNavigationService: FuseNavigationService,
        private _fuseSidebarService: FuseSidebarService,
        private _fuseSplashScreenService: FuseSplashScreenService,
        private _fuseTranslationLoaderService: FuseTranslationLoaderService,
        private _translateService: TranslateService,
        private _platform: Platform,
        private _token: TokenService,
        public router: Router
    )
    {
        // this.router.events.subscribe(event => {
        //     if (event instanceof NavigationEnd) {
        //       ga('set', 'page_title', event.urlAfterRedirects);
        //       ga('send', 'pageview');
        //     }
        //   });
        // Get default navigation
       // this.navigation = navigationUser;
        switch (this._token.get<any>().role) {
            case 0:
            case 1:
                    this._fuseNavigationService.register('user-nav',navigationUser);
                    this._fuseNavigationService.setCurrentNavigation('user-nav');
                
                break;
            case 2:

                this._fuseNavigationService.register('ventanilla-nav',navigationVentanilla);
                this._fuseNavigationService.setCurrentNavigation('ventanilla-nav');
                
                break;
            case 3:

                this._fuseNavigationService.register('revisor-nav',navigationRevisor);
                this._fuseNavigationService.setCurrentNavigation('revisor-nav');
                
                break;
            case 4:
            //console.log(navigation);
                this._fuseNavigationService.register('director-nav',navigation);
                this._fuseNavigationService.setCurrentNavigation('director-nav');
                break;
            case 5:
                this._fuseNavigationService.register('admin-nav',navigationAdmin);
                this._fuseNavigationService.setCurrentNavigation('admin-nav');
                
                break;
            case 6:
                this._fuseNavigationService.register('tec-nav',navigationTec);
                this._fuseNavigationService.setCurrentNavigation('tec-nav');
                
                break;
            default:

                this._fuseNavigationService.register('revisor-nav',navigationRevisor);
                this._fuseNavigationService.setCurrentNavigation('revisor-nav');

                break;
        
        }
        // Register the navigation to the service
        //this._fuseNavigationService.register('main', this.navigation);

        // Set the main navigation as our current navigation
        // this._fuseNavigationService.setCurrentNavigation('main');

        // Add languages
        this._translateService.addLangs(['en', 'pg','esp','fr']);

        // Set the default language
        this._translateService.setDefaultLang('esp');

        // Set the navigation translations
        // this._fuseTranslationLoaderService.loadTranslations(navigationEs,navigationEnglish, navigationTurkish);

        // Use a language
        let nLeg = window.navigator.language;
        if(nLeg.includes('es')){
            this._translateService.use('esp');
        }else if(nLeg.includes('en')){
            this._translateService.use('en');
        }else if(nLeg.includes('pt')){
            this._translateService.use('pg');
        }else if(nLeg.includes('fr')){
            this._translateService.use('fr');
        }else{
            this._translateService.use('esp');
        }
        


        /**
         * ----------------------------------------------------------------------------------------------------
         * ngxTranslate Fix Start
         * ----------------------------------------------------------------------------------------------------
         */

        /**
         * If you are using a language other than the default one, i.e. Turkish in this case,
         * you may encounter an issue where some of the components are not actually being
         * translated when your app first initialized.
         *
         * This is related to ngxTranslate module and below there is a temporary fix while we
         * are moving the multi language implementation over to the Angular's core language
         * service.
         */

        // Set the default language to 'en' and then back to 'tr'.
        // '.use' cannot be used here as ngxTranslate won't switch to a language that's already
        // been selected and there is no way to force it, so we overcome the issue by switching
        // the default language back and forth.
        /**
         * setTimeout(() => {
         * this._translateService.setDefaultLang('en');
         * this._translateService.setDefaultLang('tr');
         * });
         */

        /**
         * ----------------------------------------------------------------------------------------------------
         * ngxTranslate Fix End
         * ----------------------------------------------------------------------------------------------------
         */

        // Add is-mobile class to the body if the platform is mobile
        if ( this._platform.ANDROID || this._platform.IOS )
        {
            this.document.body.classList.add('is-mobile');
        }

        // Set the private defaults
        this._unsubscribeAll = new Subject();
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Lifecycle hooks
    // -----------------------------------------------------------------------------------------------------

    /**
     * On init
     */
    ngOnInit(): void
    {
        // Subscribe to config changes
        this._fuseConfigService.config
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe((config) => {

                this.fuseConfig = config;

                // Boxed
                if ( this.fuseConfig.layout.width === 'boxed' )
                {
                    this.document.body.classList.add('boxed');
                }
                else
                {
                    this.document.body.classList.remove('boxed');
                }

                // Color theme - Use normal for loop for IE11 compatibility
                for ( let i = 0; i < this.document.body.classList.length; i++ )
                {
                    const className = this.document.body.classList[i];

                    if ( className.startsWith('theme-') )
                    {
                        this.document.body.classList.remove(className);
                    }
                }

                this.document.body.classList.add(this.fuseConfig.colorTheme);
            });
    }

    /**
     * On destroy
     */
    ngOnDestroy(): void
    {
        // Unsubscribe from all subscriptions
        this._unsubscribeAll.next();
        this._unsubscribeAll.complete();
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Public methods
    // -----------------------------------------------------------------------------------------------------

    /**
     * Toggle sidebar open
     *
     * @param key
     */
    toggleSidebarOpen(key): void
    {
        this._fuseSidebarService.getSidebar(key).toggleOpen();
    }
}
