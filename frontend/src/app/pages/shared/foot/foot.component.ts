import { Component, Input, OnInit, ViewEncapsulation } from '@angular/core';
import { MediaObserver, MediaChange } from '@angular/flex-layout';
import { MatDialog } from '@angular/material/dialog';
import { fuseAnimations } from '@fuse/animations';
import { DialogAvisosPrivacidadComponent } from "app/pages/dialogs/dialog-avisos-privacidad/dialog-avisos-privacidad.component";
import { DialogScianComponent } from "app/pages/dialogs/dialog-scian/dialog-scian.component";
import { Subscription } from 'rxjs';

@Component({
  selector: 'landing-footer',
  templateUrl: './foot.component.html',
  styleUrls: ['./foot.component.scss'],
  encapsulation: ViewEncapsulation.None,
  animations: fuseAnimations
})
export class FootComponent implements OnInit {

  private mediaSub: Subscription;
  deviceXs: boolean = false;
  constructor(public dialog: MatDialog, public mediaObserver: MediaObserver) { }

  ngOnInit(): void {
    this.mediaSub = this.mediaObserver.media$.subscribe((res: MediaChange) => {
      console.log(res.mqAlias);
      this.deviceXs = res.mqAlias === "xs" ? true : false;
    })
  }

  openDialogAvisos() {
    this.dialog.open(DialogAvisosPrivacidadComponent);
}
openDialogScian() {
    this.dialog.open(DialogScianComponent);
}

}
