import { Component, OnInit } from '@angular/core';
import { MediaObserver, MediaChange } from '@angular/flex-layout';
import { Subscription } from 'rxjs';

@Component({
  templateUrl: './noticia-pop.component.html',
  styleUrls: ['./noticia-pop.component.scss']
})
export class NoticiaPopComponent implements OnInit {

  private mediaSub: Subscription;
  deviceXs: boolean = false;
  constructor(public mediaObserver: MediaObserver) { }

  ngOnInit(): void {
    this.mediaSub = this.mediaObserver.media$.subscribe((res: MediaChange) => {
      console.log(res.mqAlias);
      this.deviceXs = res.mqAlias === "xs" ? true : false;
    })
  }

}
