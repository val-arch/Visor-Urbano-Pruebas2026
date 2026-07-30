import { Component, OnInit,ViewEncapsulation,Input, ViewChild, OnDestroy } from '@angular/core';
import { FuseConfigService } from '@fuse/services/config.service';
import { MediaObserver, MediaChange } from '@angular/flex-layout';
import { Subscription } from 'rxjs';
import { MatDialog } from '@angular/material/dialog';
import { fuseAnimations } from '@fuse/animations';
import { DialogAvisosPrivacidadComponent } from 'app/pages/dialogs/dialog-avisos-privacidad/dialog-avisos-privacidad.component';
import { DialogScianComponent } from 'app/pages/dialogs/dialog-scian/dialog-scian.component';


@Component({
  selector: 'vuj-utilidades-municipios',
  templateUrl: './utilidades-municipios.component.html',
  styleUrls: ['./utilidades-municipios.component.scss','../../landing/landing.component.scss'],
  encapsulation: ViewEncapsulation.None,
  animations: fuseAnimations


})
export class UtilidadesMunicipiosComponent implements OnInit, OnDestroy {
  private mediaSub: Subscription;
  @Input() deviceXs: boolean;


  constructor(
    private _fuseConfigService: FuseConfigService ,
    public mediaObserver: MediaObserver,
    public dialog: MatDialog,
  ) { 
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
    this.mediaSub = this.mediaObserver.media$.subscribe((res: MediaChange) => {
      //console.log(res.mqAlias);
      this.deviceXs = res.mqAlias === "xs" ? true : false;
    })
  }
  ngOnDestroy() {
    this.mediaSub.unsubscribe();
  }
  openDialogAvisos(){
    this.dialog.open(DialogAvisosPrivacidadComponent);
  }
  openDialogScian(){
    this.dialog.open(DialogScianComponent);
  }

}
