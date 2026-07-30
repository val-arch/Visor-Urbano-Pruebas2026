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
import { BlogService } from './blog.service';
import { ActivatedRoute } from '@angular/router';

@Component({
    templateUrl: "./blog.component.html",
    styleUrls: [
        "./blog.component.scss",
        "../../landing/landing.component.scss",
    ],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations,
})
export class BlogComponent implements OnInit, OnDestroy {
    private mediaSub: Subscription;
    deviceXs: boolean;
    id=0;
    blog;
    loading=true;
    constructor(
        private _fuseConfigService: FuseConfigService,
        public mediaObserver: MediaObserver,
        public dialog: MatDialog,
        public _blogService: BlogService,
        private activatedRoute: ActivatedRoute
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
        this.activatedRoute.params
        .subscribe(params => {
          this.id = params['id'];
          console.log(this.id); // Print the parameter to the console. 
      });
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
        this._blogService.get(this.id).subscribe((r:any)=>{
          this.blog = r.data;
          this.loading = false;
        })

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
}
