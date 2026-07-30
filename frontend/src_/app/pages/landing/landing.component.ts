import { Component, OnInit,ViewEncapsulation,Input, OnDestroy } from '@angular/core';
import { FuseConfigService } from '@fuse/services/config.service';
import { MediaObserver, MediaChange } from '@angular/flex-layout';
import { Subscription } from 'rxjs';
import { MatDialog } from '@angular/material/dialog';
import { NoticiaPopComponent } from '../shared/noticia-pop/noticia-pop.component';

@Component({
  selector: 'landing',
  templateUrl: './landing.component.html',
  styleUrls: ['./landing.component.scss'],
  encapsulation: ViewEncapsulation.None,

})
export class LandingComponent implements OnInit, OnDestroy
 {
     private mediaSub: Subscription;
     deviceXs: boolean;


      constructor(
    
        private _fuseConfigService: FuseConfigService, public mediaObserver: MediaObserver,
        public dialog: MatDialog
    ) {
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

   


    ngOnInit() {
        this.mediaSub = this.mediaObserver.media$.subscribe((res: MediaChange) => {
          //console.log(res.mqAlias);
          this.deviceXs = res.mqAlias === "xs" ? true : false;
          
        })
        if(!this.deviceXs){
          this.openDialog()
        }
      }

  ngOnDestroy() {
    this.mediaSub.unsubscribe();
  }

  openDialog() {
  //  this.dialog.open(NoticiaPopComponent);
  }

}
