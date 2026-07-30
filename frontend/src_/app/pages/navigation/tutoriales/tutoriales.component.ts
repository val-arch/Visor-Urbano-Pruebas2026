import { Component, OnInit,ViewEncapsulation,Input, ViewChild, OnDestroy } from '@angular/core';
import { FuseConfigService } from '@fuse/services/config.service';
import { MediaObserver, MediaChange } from '@angular/flex-layout';
import { Subscription } from 'rxjs';
import { fuseAnimations } from '@fuse/animations';

@Component({
  templateUrl: './tutoriales.component.html',
  styleUrls: ['./tutoriales.component.scss'],
  encapsulation: ViewEncapsulation.None,
  animations: fuseAnimations,
})
export class TutorialesComponent implements OnInit {

  private mediaSub: Subscription;
  @Input() deviceXs: boolean;
  topVal = 0;
  constructor() { }

  ngOnInit(): void {
  }
 
  onScroll(e) {
    let scrollXs = this.deviceXs ? 55 : 73;
    if (e.srcElement.scrollTop < scrollXs) {
      this.topVal = e.srcElement.scrollTop;
    } else {
      this.topVal = scrollXs;
    }
  }

}
